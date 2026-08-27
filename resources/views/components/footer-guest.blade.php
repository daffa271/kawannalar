{{-- Footer KawanNalar — bg-slate-900, 3 kolom, bottom bar --}}
<footer class="bg-slate-900 text-white pt-14 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Main Grid: 3 kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-10 border-b border-white/10">

            {{-- Kolom 1: Brand + Deskripsi --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white rounded-xl overflow-hidden flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logokawannalar.jpeg') }}" alt="Logo KawanNalar" class="w-full h-full object-cover">
                    </div>
                    <div class="leading-tight">
                        <p class="font-bold text-lg text-white">Kawan<span class="text-blue-400">Nalar</span></p>
                        <p class="text-[10px] text-white/50">Belajar Bersama, Raih Impian</p>
                    </div>
                </div>
                <p class="text-sm text-white/60 leading-relaxed mb-6">
                    Platform edukasi digital kolaboratif berbasis peer mentoring yang menghubungkan siswa SMA/SMK dengan mahasiswa PTN asal Magetan untuk persiapan UTBK-SNBT secara gratis.
                </p>
                {{-- Social Media --}}
                <p class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-3">Ikuti Kami</p>
                <div class="flex items-center gap-2">
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-blue-600 transition-colors" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/6285904300285" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-green-500 transition-colors" aria-label="WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-red-500 transition-colors" aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Kolom 2: Navigasi Cepat --}}
            <div>
                <p class="text-sm font-bold text-white mb-5">Navigasi Cepat</p>
                <ul class="space-y-3">
                    <li>
                        <a href="#beranda" class="text-sm text-white/60 hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-1 h-1 bg-blue-400 rounded-full"></span> Beranda
                        </a>
                    </li>
                    <li>
                        <a href="#fitur" class="text-sm text-white/60 hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-1 h-1 bg-blue-400 rounded-full"></span> Fitur
                        </a>
                    </li>
                    <li>
                        <a href="#tentang-kami" class="text-sm text-white/60 hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-1 h-1 bg-blue-400 rounded-full"></span> Tentang Kami
                        </a>
                    </li>
                    <li>
                        <a href="#testimoni" class="text-sm text-white/60 hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-1 h-1 bg-blue-400 rounded-full"></span> Testimoni
                        </a>
                    </li>
                    <li>
                        <a href="#hubungi-kami" class="text-sm text-white/60 hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-1 h-1 bg-blue-400 rounded-full"></span> Hubungi Kami
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="text-sm text-white/60 hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-1 h-1 bg-blue-400 rounded-full"></span> Masuk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="text-sm text-white/60 hover:text-white transition-colors flex items-center gap-2">
                            <span class="w-1 h-1 bg-blue-400 rounded-full"></span> Daftar Gratis
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Kontak Resmi --}}
            <div>
                <p class="text-sm font-bold text-white mb-5">Kontak Resmi</p>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-white/40 mb-0.5">WhatsApp Center</p>
                            <a href="https://wa.me/6285904300285" target="_blank" rel="noopener noreferrer" class="text-sm text-white font-semibold hover:text-green-400 transition-colors">
                                085904300285
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-white/40 mb-0.5">Email</p>
                            <a href="mailto:support@kawannalar.my.id" class="text-sm text-white font-semibold hover:text-blue-400 transition-colors">
                                support@kawannalar.my.id
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-600/50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-white/40 mb-0.5">Lokasi</p>
                            <p class="text-sm text-white font-semibold">Kabupaten Magetan, Jawa Timur</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-7 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-white/40">
                &copy; 2026 KawanNalar. All rights reserved.
            </p>
            <p class="text-xs text-white/30">
                Dari Mahasiswa Magetan untuk Magetan 🎓
            </p>
        </div>
    </div>
</footer>