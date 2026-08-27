<div class="flex rounded-xl border border-gray-200 bg-white p-1 shadow-sm w-fit">
    <button @click="tab='1on1'" :class="tab==='1on1' ? 'bg-[#0A52C4] text-white shadow-md' : 'text-gray-500 hover:text-gray-800'"
        class="flex items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-bold transition-all duration-200">
        Kelola Slot 1-on-1
    </button>
    <button @click="tab='live'" :class="tab==='live' ? 'bg-[#0A52C4] text-white shadow-md' : 'text-gray-500 hover:text-gray-800'"
        class="flex items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-bold transition-all duration-200">
        Live Class Saya
    </button>
</div>
