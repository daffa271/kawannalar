<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-red-600">Hapus Akun</h2>
        <p class="mt-1 text-sm text-gray-500">
            Setelah akun dihapus, semua data dan resource-nya akan dihapus secara permanen. Pastikan kamu sudah mengunduh data yang ingin disimpan sebelum melanjutkan.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        Hapus Akun Saya
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" maxWidth="md">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-gray-900 mb-2">
                Yakin ingin menghapus akun?
            </h2>
            <p class="text-sm text-gray-500 mb-6">
                Semua data, modul, progres, dan history belajar kamu akan dihapus secara permanen. Masukkan kata sandi untuk konfirmasi.
            </p>

            {{-- Password Confirm --}}
            <div class="mb-6">
                <x-input-label for="password" value="Kata Sandi" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="Masukkan kata sandi untuk konfirmasi"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>
                <x-danger-button>
                    Ya, Hapus Akun Permanen
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
