{{-- Section: Hubungi Kami — id="hubungi-kami" --}}
<section id="hubungi-kami" class="bg-white py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center max-w-xl mx-auto mb-14">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/10 text-primary rounded-full text-xs sm:text-sm font-semibold mb-4">
                <x-icon name="mail" class="h-4 w-4" />
                Hubungi Kami
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4 leading-tight">
                Ada Pertanyaan? <span class="text-primary">Kami Siap</span> Membantu
            </h2>
            <p class="text-gray-500 text-sm sm:text-base leading-relaxed">
                Tim KawanNalar selalu siap menjawab setiap pertanyaan dan kebutuhan kamu.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-start">

            {{-- Left: Info Kontak (satu gaya; hijau hanya pada logo WhatsApp) --}}
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-7">Informasi Kontak Resmi</h3>

                <div class="space-y-4">
                    {{-- WhatsApp --}}
                    <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 transition-shadow duration-200 hover:shadow-md">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-surface">
                            <svg class="h-6 w-6 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-primary">WhatsApp Center</p>
                            <p class="text-lg font-bold text-gray-900">085904300285</p>
                            <a href="https://wa.me/6285904300285" target="_blank" rel="noopener noreferrer"
                                class="mt-2 inline-flex items-center gap-1 text-sm font-semibold text-primary transition-colors hover:text-primary-dark">
                                Mulai Chat Sekarang <x-icon name="arrow_forward" class="h-4 w-4" />
                            </a>
                        </div>
                    </div>

                    {{-- Telegram: grup pengumuman kelas Belajar Bersama & Private 1-on-1 --}}
                    <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 transition-shadow duration-200 hover:shadow-md">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                            <x-icon name="telegram" class="h-6 w-6" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-primary">Komunitas Telegram</p>
                            <p class="font-bold text-gray-900">Gabung Komunitas KawanNalar</p>
                            <p class="mt-1 text-sm text-gray-500">Info kelas Belajar Bersama, sesi Private 1-on-1, dan kesempatan pendidikan terbaru.</p>
                            <a href="{{ config('services.telegram.group_url') }}" target="_blank" rel="noopener noreferrer"
                                class="mt-2 inline-flex items-center gap-1 text-sm font-semibold text-primary transition-colors hover:text-primary-dark">
                                Gabung Telegram <x-icon name="arrow_forward" class="h-4 w-4" />
                            </a>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 transition-shadow duration-200 hover:shadow-md">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                            <x-icon name="mail" class="h-6 w-6" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-primary">Email Resmi</p>
                            <a href="mailto:kawannalar@gmail.com" class="break-all font-bold text-gray-900 hover:text-primary">kawannalar@gmail.com</a>
                            <p class="mt-1 text-xs text-gray-400">Respon dalam 1×24 jam kerja</p>
                        </div>
                    </div>

                    {{-- Lokasi --}}
                    <div class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 transition-shadow duration-200 hover:shadow-md">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                            <x-icon name="location_on" class="h-6 w-6" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-primary">Lokasi</p>
                            <p class="font-bold text-gray-900">Kabupaten Magetan</p>
                            <p class="text-sm text-gray-500">Jawa Timur, Indonesia</p>
                        </div>
                    </div>
                </div>

                {{-- Quick WhatsApp CTA --}}
                <a href="https://wa.me/6285904300285" target="_blank" rel="noopener noreferrer"
                    class="mt-7 flex w-full items-center justify-center gap-3 rounded-2xl bg-primary px-6 py-4 text-sm font-bold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-md lg:text-base">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat Langsung via WhatsApp
                </a>
            </div>

            {{-- Right: Form Kirim Pesan → dibuka di WhatsApp Center dengan isi terisi otomatis --}}
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-lg sm:p-7 lg:p-8">
                <h3 class="mb-2 text-xl font-bold text-gray-900">Kirim Pesan</h3>
                <p class="mb-7 text-sm text-gray-500">Isi formulir berikut, pesanmu akan dibuka di WhatsApp Center KawanNalar.</p>

                <form
                    x-data="{ name: '', email: '', message: '' }"
                    @submit.prevent="window.open('https://wa.me/6285904300285?text=' + encodeURIComponent('Halo KawanNalar, saya ' + name.trim() + ' (' + email.trim() + ').' + '\n\n' + message.trim()), '_blank', 'noopener')"
                    class="space-y-5">
                    {{-- Nama --}}
                    <div>
                        <label for="contact_name" class="mb-2 block text-sm font-semibold text-gray-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="contact_name" name="name" x-model="name" placeholder="Masukkan nama lengkap kamu" required maxlength="100"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-150 focus:border-primary focus:ring-2 focus:ring-primary/20">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="contact_email" class="mb-2 block text-sm font-semibold text-gray-700">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="contact_email" name="email" x-model="email" placeholder="email@kamu.com" required maxlength="150"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-150 focus:border-primary focus:ring-2 focus:ring-primary/20">
                    </div>

                    {{-- Pesan --}}
                    <div>
                        <label for="contact_message" class="mb-2 block text-sm font-semibold text-gray-700">
                            Pesan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="contact_message" name="message" x-model="message" rows="5" placeholder="Tulis pertanyaan atau pesanmu di sini..." required maxlength="1000"
                            class="w-full resize-none rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-150 focus:border-primary focus:ring-2 focus:ring-primary/20"></textarea>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-primary py-3.5 text-sm font-bold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-md">
                        <x-icon name="send" class="h-4 w-4" />
                        Kirim via WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
