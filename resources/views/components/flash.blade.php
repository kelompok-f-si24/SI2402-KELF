@php
    $messages = array_filter([
        'success' => session('success'),
        'error' => session('error'),
    ]);
@endphp

@if ($messages || $errors->any())
    <div class="space-y-3">
        @foreach ($messages as $type => $message)
            <div data-alert data-autohide role="alert"
                class="flex items-start gap-3 rounded-xl border px-4 py-3 text-sm {{ $type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-800' }}">
                <x-icon :name="$type === 'success' ? 'check-circle' : 'alert'" class="mt-0.5 size-5 shrink-0" />
                <p class="flex-1">{{ $message }}</p>
                <button type="button" data-dismiss class="opacity-60 hover:opacity-100" aria-label="Tutup">
                    <x-icon name="x" class="size-4" />
                </button>
            </div>
        @endforeach

        @if ($errors->any() && ! $messages)
            <div data-alert role="alert" class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <x-icon name="alert" class="mt-0.5 size-5 shrink-0" />
                <p class="flex-1">Gagal menyimpan. Periksa kembali isian yang ditandai merah.</p>
                <button type="button" data-dismiss class="opacity-60 hover:opacity-100" aria-label="Tutup">
                    <x-icon name="x" class="size-4" />
                </button>
            </div>
        @endif
    </div>
@endif
