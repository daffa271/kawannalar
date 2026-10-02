{{-- Pop-up konfirmasi Setujui / Tolak. Cukup satu per halaman; dibuka oleh <x-moderation-button>. --}}
@if($errors->moderation->has('reason'))
<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-800 flex items-center gap-2">
    <x-icon name="warning" class="h-5 w-5" /> {{ $errors->moderation->first('reason') }}
</div>
@endif

<div x-data="{ open: false, mode: 'approve', action: '', title: '', subject: '', note: '', audience: '', reason: '' }"
    @moderation.window="Object.assign($data, $event.detail, { reason: '', open: true })"
    @keydown.escape.window="open = false"
    x-show="open" x-transition.opacity
    class="fixed inset-0 z-[70] flex items-end justify-center bg-gray-900/60 p-4 backdrop-blur-sm sm:items-center"
    style="display:none;" x-cloak>

    <form method="POST" :action="action" @click.outside="open = false"
        role="dialog" aria-modal="true"
        class="w-full max-w-md rounded-2xl bg-white p-5 shadow-2xl sm:p-6">
        @csrf
        @method('PATCH')

        <div class="flex items-start gap-3">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full"
                :class="mode === 'reject' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700'">
                <x-icon name="warning" class="h-6 w-6" x-show="mode === 'reject'" />
                <x-icon name="check_circle" class="h-6 w-6" x-show="mode !== 'reject'" />
            </span>
            <div class="min-w-0 flex-1">
                <h3 class="text-base font-extrabold text-gray-900" x-text="title"></h3>
                <p class="mt-1 break-words text-sm font-bold text-gray-800" x-text="subject"></p>
                <p class="mt-1 text-xs leading-relaxed text-gray-500" x-show="note" x-text="note"></p>
            </div>
        </div>

        <div x-show="mode === 'reject'" class="mt-4">
            <label for="moderation-reason" class="mb-1 block text-sm font-bold text-gray-700">
                Alasan penolakan <span class="text-red-500">*</span>
            </label>
            <textarea id="moderation-reason" name="reason" rows="3" maxlength="500"
                x-model="reason" :required="mode === 'reject'" :disabled="mode !== 'reject'"
                placeholder="Tulis alasan yang jelas agar bisa diperbaiki..."
                class="w-full resize-none rounded-xl border border-gray-200 px-3 py-2.5 text-sm outline-none focus:border-red-400 focus:ring-1 focus:ring-red-400"></textarea>
            <p class="mt-1 flex justify-between gap-3 text-[11px] text-gray-400">
                <span>Alasan ini ditampilkan kepada <span x-text="audience"></span>.</span>
                <span class="shrink-0" x-text="reason.length + '/500'"></span>
            </p>
        </div>

        <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row">
            <button type="button" @click="open = false"
                class="flex-1 rounded-xl border border-gray-200 bg-white py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" :disabled="mode === 'reject' && !reason.trim()"
                :class="mode === 'reject' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
                class="flex-1 rounded-xl py-2.5 text-sm font-bold text-white shadow transition disabled:cursor-not-allowed disabled:opacity-50"
                x-text="mode === 'reject' ? 'Ya, Tolak' : 'Ya, Setujui'"></button>
        </div>
    </form>
</div>
