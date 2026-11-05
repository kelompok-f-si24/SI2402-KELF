@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'hint' => null])

@php
    $id = $attributes->get('id', $name);
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if ($attributes->has('required'))
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
        @if ($type !== 'password' && $type !== 'file') value="{{ old($errorKey, $value) }}" @endif
        {{ $attributes->except('id')->class(['form-input', 'is-invalid' => $errors->has($errorKey)]) }}>
    @if ($hint)
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif
    @error($errorKey)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
