{{--
    Footer publik lengkap untuk halaman landing, login, register.
    Dark navy (#0F1F3D) bg, links, sosial media, dan logo KawanNalar.
--}}
<footer class="bg-[#0F1F3D] text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Main Footer Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">

            {{-- Brand Column --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white rounded-xl overflow-hidden flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logokawannalar.jpeg') }}" alt="Logo KawanNalar" class="w-full h-full object-cover">
                    </div>
                    <div class="leading-tight">
                        <p class="font-bold text-lg text-white">Kawan<span class="text-[#F28C28]">Nalar</span></p>
                        <p class="text-[10px] text-white/50">Belajar Bersama, Raih Impian</p>
                    </div>
                </div>
                <p class="text-sm text-white/60 leading-relaxed mb-5">
                    Platform pendampingan pendidikan gratis untuk siswa SMA/MA/SMK Magetan menuju PTN favorit.
                </p>

                {{-- Social Media --}}
                <p class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-3">Ikuti Kami</p>
                <div class="flex items-center gap-2">
                    {{-- Instagram --}}
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-[#F28C28] transition-colors" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    {{-- Telegram --}}
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-[#F28C28] transition-colors" aria-label="Telegram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                        </svg>
                    </a>
                    {{-- YouTube --}}
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-[#F28C28] transition-colors" aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </a>
                    {{-- TikTok --}}
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center hover:bg-[#F28C28] transition-colors" aria-label="TikTok">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Platform Links --}}
            <div>
                <p class="text-sm font-bold text-white mb-4">Platform</p>
                <ul class="space-y-2.5">
                    <li><a href="#fitur" class="text-sm text-white/60 hover:text-white transition-colors">Fitur</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Jejak Nalar</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Kabar Nalar</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Uji Nalar</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">NalarBot AI</a></li>
                </ul>
            </div>

            {{-- Company Links --}}
            <div>
                <p class="text-sm font-bold text-white mb-4">Perusahaan</p>
                <ul class="space-y-2.5">
                    <li><a href="#tentang" class="text-sm text-white/60 hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Untuk Sekolah</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Jadi Mentor</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Karir</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Kontak</a></li>
                </ul>
            </div>

            {{-- Help Links --}}
            <div>
                <p class="text-sm font-bold text-white mb-4">Bantuan</p>
                <ul class="space-y-2.5">
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Pusat Bantuan</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Panduan Pengguna</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="w-full pt-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <p class="min-w-0 text-xs text-white/40">
                &copy; {{ date('Y') }} KawanNalar. Seluruh hak dilindungi.
            </p>
            <p class="min-w-0 text-xs text-white/30 sm:text-right">
                Dari Mahasiswa Magetan untuk Magetan
            </p>
        </div>
    </div>
</footer>