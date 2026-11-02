@props(['light' => false])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <x-logo-mark :light="$light" class="size-9" />
    <span class="text-lg font-extrabold tracking-tight">
        <span class="{{ $light ? 'text-white' : 'text-navy-900' }}">Loker</span><span class="{{ $light ? 'text-sky-300' : 'text-sky-500' }}">in</span>
    </span>
</a>
