@props(['disabled' => false, 'type' => 'text'])

<input
    @disabled($disabled)
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => implode(' ', array_filter([
            'w-full px-4 py-3 rounded-xl border text-sm text-gray-800 placeholder-gray-400',
            'bg-[#F4F7FA] border-gray-200',
            'focus:outline-none focus:ring-2 focus:ring-[#0A52C4]/20 focus:border-[#0A52C4]',
            'transition-all duration-200',
            $disabled ? 'opacity-50 cursor-not-allowed' : '',
        ])),
    ]) }}
>
