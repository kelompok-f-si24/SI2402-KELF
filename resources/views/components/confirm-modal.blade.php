@props([
    'id',
    'action',
    'method' => 'POST',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmLabel' => 'Ya, lanjutkan',
    'variant' => 'danger',
])

<div id="{{ $id }}" data-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 text-left shadow-xl">
        <div class="flex items-start gap-4">
            <div class="flex size-11 shrink-0 items-center justify-center rounded-full {{ $variant === 'danger' ? 'bg-red-50 text-red-600' : 'bg-sky-50 text-navy-700' }}">
                <x-icon name="alert" class="size-6" />
            </div>
            <div>
                <h3 id="{{ $id }}-title" class="text-base font-semibold text-slate-900">{{ $title }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $message }}</p>
            </div>
        </div>

        <form method="POST" action="{{ $action }}" class="mt-6">
            @csrf
            @if (strtoupper($method) !== 'POST')
                @method($method)
            @endif
            {{ $slot }}
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" data-modal-close data-modal-focus class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn {{ $variant === 'danger' ? 'btn-danger' : 'btn-primary' }}">{{ $confirmLabel }}</button>
            </div>
        </form>
    </div>
</div>
