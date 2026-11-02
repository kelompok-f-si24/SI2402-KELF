@props(['light' => false, 'class' => 'size-8'])

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 40 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    {{-- ribbon forming the "L" --}}
    <rect x="12" y="7" width="6" height="21" rx="3" class="{{ $light ? 'fill-sky-300' : 'fill-sky-500' }}" />
    <rect x="12" y="22" width="17" height="6" rx="3" class="{{ $light ? 'fill-sky-300' : 'fill-sky-500' }}" />

    {{-- briefcase --}}
    <rect x="16" y="17" width="17" height="12" rx="3.5" class="{{ $light ? 'fill-white' : 'fill-navy-800' }}" />
    <rect x="21.5" y="13.5" width="6" height="4.5" rx="1.5" fill="none" stroke-width="1.6" class="{{ $light ? 'stroke-white' : 'stroke-navy-800' }}" />

    {{-- graduation cap --}}
    <polygon points="19,3 32,9 19,15 6,9" class="{{ $light ? 'fill-white' : 'fill-navy-800' }}" />
    <rect x="15" y="10" width="8" height="5.5" rx="2" class="{{ $light ? 'fill-white' : 'fill-navy-800' }}" />
    <line x1="6" y1="9" x2="6" y2="16" stroke-width="1.6" stroke-linecap="round" class="{{ $light ? 'stroke-white' : 'stroke-navy-800' }}" />
    <circle cx="6" cy="17.3" r="1.4" class="{{ $light ? 'fill-white' : 'fill-navy-800' }}" />

    {{-- sparkle accent --}}
    <g stroke-width="2.2" stroke-linecap="round" class="stroke-amber-400">
        <line x1="31" y1="6" x2="33.5" y2="3.5" />
        <line x1="34.5" y1="9.5" x2="37.5" y2="9" />
        <line x1="32.5" y1="13" x2="34.5" y2="15.5" />
    </g>
</svg>
