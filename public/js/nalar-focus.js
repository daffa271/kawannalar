/**
 * Nalar Focus — timer Pomodoro (25 menit fokus / 5 menit istirahat) + musik ambience belajar.
 *
 * Semua suara dibangkitkan langsung dengan Web Audio API: tanpa file audio dan tanpa lisensi
 * pihak ketiga. Musik berhenti saat sesi fokus habis (tanda istirahat) dan berputar lagi saat
 * pomodoro berikutnya dilanjutkan.
 *
 * Dimuat sebagai script biasa sebelum Alpine (CDN, defer) berjalan: <div x-data="nalarFocus()">.
 */
(function () {
    'use strict';

    const FOCUS_SECONDS = 25 * 60;
    const BREAK_SECONDS = 5 * 60;
    const STORAGE_KEY = 'kawannalar.nalar-focus.v1';
    const TRACKS = ['lofi', 'rain', 'deep'];

    const mtof = (midi) => 440 * Math.pow(2, (midi - 69) / 12);

    // ── Mesin suara ─────────────────────────────────────────────────────────

    const Sound = {
        ctx: null,
        master: null,
        out: null,
        current: null,
        buffers: {},

        supported() {
            return Boolean(window.AudioContext || window.webkitAudioContext);
        },

        /** Harus dipanggil dari aksi pengguna (klik) agar browser mengizinkan audio. */
        ensure() {
            if (!this.supported()) return false;

            if (!this.ctx) {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                this.ctx = new AudioCtx();
                this.out = this.ctx.createDynamicsCompressor();
                this.out.connect(this.ctx.destination);
                this.master = this.ctx.createGain();
                this.master.gain.value = 0.6;
                this.master.connect(this.out);

                // iOS: tetap berbunyi walau tombol senyap aktif.
                if (navigator.audioSession) {
                    try { navigator.audioSession.type = 'playback'; } catch (e) { /* abaikan */ }
                }
            }

            if (this.ctx.state === 'suspended') this.ctx.resume();

            return true;
        },

        setVolume(value) {
            if (this.master) this.master.gain.setTargetAtTime(value, this.ctx.currentTime, 0.05);
        },

        play(track) {
            if (!this.ensure()) return;
            this.stop();

            const now = this.ctx.currentTime;
            const bus = this.ctx.createGain();
            bus.gain.setValueAtTime(0.0001, now);
            bus.gain.exponentialRampToValueAtTime(1, now + 1.5);
            bus.connect(this.master);

            const build = Tracks[track] || Tracks.lofi;
            this.current = { bus, cleanup: build(this.ctx, bus, this) };
        },

        stop() {
            if (!this.current) return;

            const { bus, cleanup } = this.current;
            const now = this.ctx.currentTime;
            if (bus.gain.cancelAndHoldAtTime) bus.gain.cancelAndHoldAtTime(now);
            else bus.gain.cancelScheduledValues(now);
            bus.gain.setTargetAtTime(0, now, 0.25);
            this.current = null;

            setTimeout(() => { cleanup(); bus.disconnect(); }, 1500);
        },

        /** Bunyi lonceng lembut penanda sesi selesai. */
        chime() {
            if (!this.ensure()) return;

            const start = this.ctx.currentTime + 0.05;
            [76, 79, 84].forEach((midi, i) => {
                const t = start + i * 0.22;
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.frequency.value = mtof(midi);
                gain.gain.setValueAtTime(0.0001, t);
                gain.gain.exponentialRampToValueAtTime(0.3, t + 0.015);
                gain.gain.exponentialRampToValueAtTime(0.0001, t + 1.8);
                osc.connect(gain);
                gain.connect(this.out);
                osc.start(t);
                osc.stop(t + 1.9);
            });
        },

        /** Buffer noise 8 detik (pink/brown) yang bisa di-loop tanpa bunyi "klik". */
        noise(type) {
            if (this.buffers[type]) return this.buffers[type];

            const length = this.ctx.sampleRate * 8;
            const buffer = this.ctx.createBuffer(2, length, this.ctx.sampleRate);

            for (let channel = 0; channel < 2; channel++) {
                const data = buffer.getChannelData(channel);
                let last = 0, b0 = 0, b1 = 0, b2 = 0, b3 = 0, b4 = 0, b5 = 0, b6 = 0;

                for (let i = 0; i < length; i++) {
                    const white = Math.random() * 2 - 1;
                    if (type === 'brown') {
                        last = (last + 0.02 * white) / 1.02;
                        data[i] = last * 3.5;
                    } else {
                        b0 = 0.99886 * b0 + white * 0.0555179;
                        b1 = 0.99332 * b1 + white * 0.0750759;
                        b2 = 0.969 * b2 + white * 0.153852;
                        b3 = 0.8665 * b3 + white * 0.3104856;
                        b4 = 0.55 * b4 + white * 0.5329522;
                        b5 = -0.7616 * b5 - white * 0.016898;
                        data[i] = (b0 + b1 + b2 + b3 + b4 + b5 + b6 + white * 0.5362) * 0.11;
                        b6 = white * 0.115926;
                    }
                }

                // Samakan ujung dan awal buffer supaya sambungan loop mulus.
                const drift = data[length - 1] - data[0];
                for (let i = 0; i < length; i++) data[i] -= drift * (i / (length - 1));
            }

            return (this.buffers[type] = buffer);
        },
    };

    // ── Helper node ─────────────────────────────────────────────────────────

    function loopNoise(ctx, buffer) {
        const source = ctx.createBufferSource();
        source.buffer = buffer;
        source.loop = true;
        source.start();
        return source;
    }

    function filter(ctx, type, frequency, q) {
        const node = ctx.createBiquadFilter();
        node.type = type;
        node.frequency.value = frequency;
        if (q) node.Q.value = q;
        return node;
    }

    function gain(ctx, value) {
        const node = ctx.createGain();
        node.gain.value = value;
        return node;
    }

    function chain(...nodes) {
        for (let i = 0; i < nodes.length - 1; i++) nodes[i].connect(nodes[i + 1]);
        return nodes[nodes.length - 1];
    }

    /** Ruang gema sederhana dari impuls noise yang meluruh. */
    function reverb(ctx, seconds) {
        const length = ctx.sampleRate * seconds;
        const impulse = ctx.createBuffer(2, length, ctx.sampleRate);
        for (let channel = 0; channel < 2; channel++) {
            const data = impulse.getChannelData(channel);
            for (let i = 0; i < length; i++) data[i] = (Math.random() * 2 - 1) * Math.pow(1 - i / length, 3);
        }
        const node = ctx.createConvolver();
        node.buffer = impulse;
        return node;
    }

    /** Penjadwal dengan lookahead 2 detik agar tetap rapi walau tab di latar belakang. */
    function scheduler(ctx, stepSeconds, scheduleStep) {
        let next = ctx.currentTime + 0.1;
        let step = 0;
        const run = () => {
            while (next < ctx.currentTime + 2) {
                scheduleStep(step++, next);
                next += stepSeconds;
            }
        };
        run();
        const timer = setInterval(run, 300);
        return () => clearInterval(timer);
    }

    // ── Pilihan ambience ────────────────────────────────────────────────────

    const Tracks = {
        /** Lo-fi Study: progresi Fmaj7–Em7–Dm7–Cmaj7, ketukan pelan, dan desis piringan hitam. */
        lofi(ctx, out, sound) {
            const BEAT = 60 / 72;
            const CHORDS = [
                { bass: 41, notes: [53, 57, 60, 64] },
                { bass: 40, notes: [52, 55, 59, 62] },
                { bass: 38, notes: [50, 53, 57, 60] },
                { bass: 36, notes: [48, 52, 55, 59] },
            ];

            const room = reverb(ctx, 2.5);
            chain(room, gain(ctx, 0.35), out);

            const keys = gain(ctx, 1.1);
            const tone = filter(ctx, 'lowpass', 1600, 0.5);
            chain(keys, tone, out);
            tone.connect(room);

            const drums = gain(ctx, 0.35);
            chain(drums, filter(ctx, 'lowpass', 4200), out);

            // Desis piringan hitam.
            const hiss = loopNoise(ctx, sound.noise('pink'));
            chain(hiss, filter(ctx, 'highpass', 2500), gain(ctx, 0.015), out);

            const note = (midi, time, length, level, type) => {
                const osc = ctx.createOscillator();
                const env = ctx.createGain();
                osc.type = type;
                osc.frequency.value = mtof(midi);
                osc.detune.value = (Math.random() - 0.5) * 8;
                env.gain.setValueAtTime(0.0001, time);
                env.gain.exponentialRampToValueAtTime(level, time + 0.03);
                env.gain.exponentialRampToValueAtTime(level * 0.35, time + 1.2);
                env.gain.exponentialRampToValueAtTime(0.0001, time + length);
                osc.connect(env);
                env.connect(keys);
                osc.start(time);
                osc.stop(time + length + 0.05);
            };

            const drum = (time, kind) => {
                if (kind === 'kick') {
                    const osc = ctx.createOscillator();
                    const env = ctx.createGain();
                    osc.frequency.setValueAtTime(110, time);
                    osc.frequency.exponentialRampToValueAtTime(45, time + 0.12);
                    env.gain.setValueAtTime(0.8, time);
                    env.gain.exponentialRampToValueAtTime(0.0001, time + 0.35);
                    chain(osc, env, drums);
                    osc.start(time);
                    osc.stop(time + 0.4);
                    return;
                }

                const source = ctx.createBufferSource();
                const env = ctx.createGain();
                const snare = kind === 'snare';
                source.buffer = sound.noise('pink');
                env.gain.setValueAtTime(snare ? 0.5 : 0.12, time);
                env.gain.exponentialRampToValueAtTime(0.0001, time + (snare ? 0.18 : 0.05));
                chain(source, snare ? filter(ctx, 'bandpass', 1800, 0.8) : filter(ctx, 'highpass', 7000), env, drums);
                source.start(time, Math.random() * 7);
                source.stop(time + 0.25);
            };

            const stopBeat = scheduler(ctx, BEAT, (step, time) => {
                const beat = step % 4;
                const chord = CHORDS[Math.floor(step / 4) % CHORDS.length];

                if (beat === 0) {
                    chord.notes.forEach((midi, i) => note(midi, time + i * 0.025, BEAT * 4, 0.09, 'triangle'));
                    note(chord.bass, time, BEAT * 4, 0.22, 'sine');
                }
                if (Math.random() < 0.3) {
                    note(chord.notes[Math.floor(Math.random() * 4)] + 12, time + BEAT / 2, BEAT * 2, 0.05, 'sine');
                }

                drum(time, beat % 2 === 0 ? 'kick' : 'snare');
                if (beat === 2 && Math.random() < 0.5) drum(time + BEAT * 0.75, 'kick');
                drum(time, 'hat');
                drum(time + BEAT * 0.58, 'hat');
            });

            return () => {
                stopBeat();
                hiss.stop();
            };
        },

        /** Suara Hujan: desis hujan, gemuruh rendah, dan tetesan acak. */
        rain(ctx, out, sound) {
            const body = gain(ctx, 0.3);
            const hiss = loopNoise(ctx, sound.noise('pink'));
            chain(hiss, filter(ctx, 'highpass', 600), filter(ctx, 'lowpass', 6500), body, out);

            const rumble = loopNoise(ctx, sound.noise('brown'));
            chain(rumble, filter(ctx, 'lowpass', 350), gain(ctx, 0.3), out);

            // Intensitas hujan naik-turun perlahan.
            const swell = ctx.createOscillator();
            swell.frequency.value = 0.07;
            chain(swell, gain(ctx, 0.07)).connect(body.gain);
            swell.start();

            const drops = gain(ctx, 0.25);
            drops.connect(out);
            const stopDrops = scheduler(ctx, 0.25, (step, time) => {
                for (let i = 0; i < 3; i++) {
                    const t = time + Math.random() * 0.25;
                    const source = ctx.createBufferSource();
                    const env = ctx.createGain();
                    source.buffer = sound.noise('pink');
                    env.gain.setValueAtTime(0.0001, t);
                    env.gain.exponentialRampToValueAtTime(0.25 + Math.random() * 0.35, t + 0.004);
                    env.gain.exponentialRampToValueAtTime(0.0001, t + 0.05 + Math.random() * 0.05);
                    chain(source, filter(ctx, 'bandpass', 1800 + Math.random() * 3500, 6), env, drops);
                    source.start(t, Math.random() * 7);
                    source.stop(t + 0.12);
                }
            });

            return () => {
                stopDrops();
                [hiss, rumble, swell].forEach((node) => node.stop());
            };
        },

        /** Deep Focus: brown noise hangat untuk konsentrasi panjang. */
        deep(ctx, out, sound) {
            const source = loopNoise(ctx, sound.noise('brown'));
            chain(source, filter(ctx, 'lowpass', 1000), gain(ctx, 0.35), out);

            return () => source.stop();
        },
    };

    // ── Komponen Alpine ─────────────────────────────────────────────────────

    window.nalarFocus = function () {
        return {
            mode: 'focus',      // focus | break
            status: 'idle',     // idle | running | paused | done
            remaining: FOCUS_SECONDS,
            endAt: null,
            session: 1,
            track: 'lofi',
            volume: 60,
            musicOn: true,      // preferensi: musik ikut berputar saat fokus berjalan
            playing: false,     // musik benar-benar sedang berbunyi
            audioSupported: Sound.supported(),
            ticker: null,

            init() {
                this.restore();
                if (this.status === 'running') this.startTicker();
                ['mode', 'status', 'remaining', 'endAt', 'session', 'track', 'volume', 'musicOn']
                    .forEach((key) => this.$watch(key, () => this.save()));
            },

            get total() {
                return this.mode === 'focus' ? FOCUS_SECONDS : BREAK_SECONDS;
            },

            get clock() {
                const seconds = Math.max(0, this.remaining);
                return String(Math.floor(seconds / 60)).padStart(2, '0') + ':' + String(seconds % 60).padStart(2, '0');
            },

            get progress() {
                return Math.min(100, Math.round((1 - this.remaining / this.total) * 100));
            },

            get statusText() {
                if (this.status === 'done') {
                    return this.mode === 'focus' ? 'Sesi fokus selesai, waktunya istirahat 5 menit' : 'Istirahat selesai, siap lanjut fokus?';
                }
                if (this.mode === 'break') return this.status === 'paused' ? 'Istirahat dijeda' : 'Istirahat · 5 menit';

                return this.status === 'paused' ? 'Fokus dijeda' : 'Fokus belajar · 25 menit';
            },

            get primaryLabel() {
                if (this.status === 'running') return 'Jeda';
                if (this.status === 'paused') return 'Lanjutkan';
                if (this.status === 'done') return this.mode === 'focus' ? 'Mulai Istirahat' : 'Lanjut Fokus';

                return 'Mulai Fokus';
            },

            get primaryIcon() {
                if (this.status === 'running') return 'pause';
                if (this.status === 'done' && this.mode === 'focus') return 'coffee';

                return 'play';
            },

            /** Lewati hanya relevan saat istirahat berjalan atau baru saja selesai fokus. */
            get canSkip() {
                return (this.mode === 'break' && this.status !== 'done') || (this.mode === 'focus' && this.status === 'done');
            },

            primary() {
                if (this.status === 'running') return this.pause();
                if (this.status === 'done') return this.mode === 'focus' ? this.startBreak() : this.nextFocus();

                this.start();
            },

            secondary() {
                this.canSkip ? this.nextFocus() : this.reset();
            },

            start() {
                Sound.ensure();
                this.endAt = Date.now() + this.remaining * 1000;
                this.status = 'running';
                this.startTicker();
                if (this.mode === 'focus' && this.musicOn) this.playMusic();
            },

            pause() {
                this.remaining = this.secondsLeft();
                this.endAt = null;
                this.status = 'paused';
                this.stopTicker();
                this.stopMusic();
            },

            reset() {
                this.stopTicker();
                this.stopMusic();
                this.mode = 'focus';
                this.status = 'idle';
                this.remaining = FOCUS_SECONDS;
                this.endAt = null;
                this.session = 1;
            },

            startBreak() {
                this.mode = 'break';
                this.remaining = BREAK_SECONDS;
                this.start();
            },

            nextFocus() {
                this.stopTicker();
                this.session++;
                this.mode = 'focus';
                this.remaining = FOCUS_SECONDS;
                this.start();
            },

            /** Waktu habis: musik berhenti sebagai tanda istirahat, lonceng berbunyi. */
            finish() {
                this.stopTicker();
                this.remaining = 0;
                this.endAt = null;
                this.status = 'done';
                this.stopMusic();
                Sound.chime();
            },

            secondsLeft() {
                return Math.max(0, Math.ceil((this.endAt - Date.now()) / 1000));
            },

            startTicker() {
                this.stopTicker();
                const tick = () => {
                    this.remaining = this.secondsLeft();
                    if (this.remaining <= 0) this.finish();
                };
                tick();
                if (this.status === 'running') this.ticker = setInterval(tick, 250);
            },

            stopTicker() {
                clearInterval(this.ticker);
                this.ticker = null;
            },

            toggleMusic() {
                if (this.playing) {
                    this.musicOn = false;
                    this.stopMusic();
                } else {
                    this.musicOn = true;
                    this.playMusic();
                }
            },

            playMusic() {
                if (!Sound.ensure()) return;
                Sound.setVolume(this.volume / 100);
                Sound.play(this.track);
                this.playing = true;
            },

            stopMusic() {
                Sound.stop();
                this.playing = false;
            },

            changeTrack() {
                if (this.playing) Sound.play(this.track);
            },

            changeVolume() {
                Sound.setVolume(this.volume / 100);
            },

            save() {
                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify({
                        mode: this.mode,
                        status: this.status,
                        remaining: this.remaining,
                        endAt: this.endAt,
                        session: this.session,
                        track: this.track,
                        volume: this.volume,
                        musicOn: this.musicOn,
                    }));
                } catch (e) { /* penyimpanan tidak tersedia: timer tetap jalan */ }
            },

            /** Lanjutkan timer setelah pindah halaman. Musik menunggu klik (aturan autoplay browser). */
            restore() {
                let saved = null;
                try {
                    saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null');
                } catch (e) { /* abaikan */ }
                if (!saved || typeof saved !== 'object') return;

                if (TRACKS.includes(saved.track)) this.track = saved.track;
                if (Number.isFinite(saved.volume)) this.volume = Math.min(100, Math.max(0, saved.volume));
                if (typeof saved.musicOn === 'boolean') this.musicOn = saved.musicOn;

                const validMode = ['focus', 'break'].includes(saved.mode);
                const validStatus = ['idle', 'running', 'paused', 'done'].includes(saved.status);
                if (!validMode || !validStatus || !Number.isFinite(saved.remaining)) return;

                this.mode = saved.mode;
                this.status = saved.status;
                this.session = Number.isInteger(saved.session) && saved.session > 0 ? saved.session : 1;
                this.remaining = Math.min(this.total, Math.max(0, Math.round(saved.remaining)));

                if (this.status === 'running') {
                    if (!Number.isFinite(saved.endAt)) {
                        this.status = 'paused';
                        return;
                    }
                    this.endAt = saved.endAt;
                    this.remaining = this.secondsLeft();
                    if (this.remaining <= 0) {
                        this.remaining = 0;
                        this.endAt = null;
                        this.status = 'done';
                    }
                }
            },
        };
    };
})();
