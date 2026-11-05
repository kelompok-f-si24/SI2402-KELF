@props(['src' => null, 'initials' => '?', 'size' => 'size-10', 'rounded' => 'rounded-full'])

@if ($src)
    <img src="{{ $src }}" alt="" {{ $attributes->merge(['class' => "$size $rounded shrink-0 object-cover ring-1 ring-slate-200"]) }}>
@else
    <div {{ $attributes->merge(['class' => "$size $rounded flex shrink-0 items-center justify-center bg-navy-100 text-sm font-semibold text-navy-800"]) }}>
        {{ $initials }}
    </div>
@endif
