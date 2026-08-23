<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#F28C28] hover:bg-[#E07D1C] text-white font-semibold text-sm rounded-xl transition-all hover:shadow-md']) }}>
    {{ $slot }}
</button>
