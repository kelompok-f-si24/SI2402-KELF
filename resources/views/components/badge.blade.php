@props(['color' => 'gray'])

@php
    $colors = [
        'gray' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'green' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'red' => 'bg-red-50 text-red-700 ring-red-200',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'sky' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'indigo' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
        'navy' => 'bg-navy-50 text-navy-800 ring-navy-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset whitespace-nowrap '.($colors[$color] ?? $colors['gray'])]) }}>
    {{ $slot }}
</span>
