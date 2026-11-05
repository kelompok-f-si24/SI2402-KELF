@props(['name', 'label' => null, 'options' => [], 'value' => null, 'placeholder' => 'Pilih salah satu'])

@php
    $id = $attributes->get('id', $name);
    $selected = (string) old($name, $value instanceof \BackedEnum ? $value->value : $value);
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
    <select id="{{ $id }}" name="{{ $name }}" {{ $attributes->except('id')->class(['form-input', 'is-invalid' => $errors->has($name)]) }}>
        @if ($placeholder !== false)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($selected === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
