{{--
    Edit Profile — KawanNalar.
    Menggunakan layouts.app dengan sidebar dan topbar internal.
--}}
<x-layouts.app title="Profil Saya — KawanNalar">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-gray-500 mt-1">Kelola informasi akun dan preferensi kamu.</p>
    </div>

    <div class="max-w-3xl space-y-6">

        {{-- Update Profile Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Update Password --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Delete Account --}}
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>

    </div>

</x-layouts.app>