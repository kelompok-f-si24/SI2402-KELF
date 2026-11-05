@props(['label', 'value', 'icon' => 'chart', 'tone' => 'navy', 'href' => null])

@php
    $tones = [
        'navy' => 'bg-navy-50 text-navy-700',
        'sky' => 'bg-sky-50 text-sky-700',
        'green' => 'bg-emerald-50 text-emerald-700',
        'amber' => 'bg-amber-50 text-amber-700',
        'red' => 'bg-red-50 text-red-700',
        'gray' => 'bg-slate-100 text-slate-600',
    ];
@endphp

<{{ $href ? 'a' : 'div' }} @if ($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => 'card flex items-center gap-4 p-5 transition '.($href ? 'hover:border-navy-200 hover:shadow-md' : '')]) }}>
    <div class="flex size-12 shrink-0 items-center justify-center rounded-xl {{ $tones[$tone] ?? $tones['navy'] }}">
        <x-icon :name="$icon" class="size-6" />
    </div>
    <div class="min-w-0">
        <p class="truncate text-sm text-slate-500">{{ $label }}</p>
        <p class="text-2xl font-bold text-slate-900">{{ $value }}</p>
    </div>
</{{ $href ? 'a' : 'div' }}>
