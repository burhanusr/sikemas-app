@props([
    'id' => 'input-date-' . uniqid(),
    'name' => null,
    'label' => null,
    'placeholder' => null,
    'color' => 'gray',
    'value' => null,
    'size' => 'medium',
    'required' => false, // ✅ Tambahan prop required
])

@php
    $inputPadding = $size === 'small' ? 'px-4 py-2' : 'px-4 py-3';
@endphp

<div class="flex w-full flex-col space-y-1">
    {{-- Label opsional dengan tanda * jika required --}}
    @if ($label)
        <label for="{{ $id }}" class="text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Input tanggal --}}
    <input type="date" id="{{ $id }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        class="{{ $inputPadding }} border-{{ $color }}-200 focus:ring-{{ $color }}-500 w-full cursor-pointer rounded-lg border focus:outline-none focus:ring-2"
        {{ $required ? 'required' : '' }} oninvalid="this.setCustomValidity('Silakan pilih {{ $label ?? $name }}')"
        oninput="this.setCustomValidity('')">
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("{{ $id }}");
        if (!input) return;

        // Saat diklik, langsung buka date picker
        input.addEventListener("click", function() {
            if (typeof this.showPicker === "function") {
                this.showPicker();
            } else {
                this.focus();
            }
        });
    });
</script>
