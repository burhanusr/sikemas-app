@props([
    'id' => 'autocomplete-' . uniqid(),
    'name' => null,
    'label' => null,
    'color' => 'gray',
    'placeholder' => 'Ketik untuk mencari...',
    'optionPlaceholder' => 'Pilih salah satu...',
    'size' => 'medium',
    'required' => false,
])

@php
    $inputPadding = $size === 'small' ? 'px-4 py-2' : 'px-4 py-3';
@endphp

<div class="flex w-full flex-col space-y-1">
    @if ($label)
        <label for="{{ $id }}" class="text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative w-full">
        <input type="text" id="{{ $id }}" name="{{ $name }}_display" placeholder="{{ $placeholder }}"
            class="{{ $inputPadding }} border-{{ $color }}-200 focus:ring-{{ $color }}-500 w-full rounded-lg border focus:outline-none focus:ring-2"
            autocomplete="off" {{ $required ? 'required' : '' }}>

        <input type="hidden" id="{{ $id }}-value" name="{{ $name }}">

        <ul id="{{ $id }}-list"
            class="border-{{ $color }}-200 absolute z-10 mt-1 hidden max-h-48 w-full overflow-auto rounded-lg border bg-white shadow">
            <li class="cursor-default select-none bg-gray-50 px-4 py-2 text-gray-500">
                {{ $optionPlaceholder }}
            </li>
            {{ $slot }}
        </ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("{{ $id }}");
        const list = document.getElementById("{{ $id }}-list");
        const hidden = document.getElementById("{{ $id }}-value");
        if (!input || !list) return;


        const slotOptions = list.querySelectorAll("option");
        const generated = [];

        slotOptions.forEach(opt => {
            const li = document.createElement("li");
            li.className = "cursor-pointer px-4 py-2 hover:bg-{{ $color }}-100";
            li.dataset.value = opt.value;
            li.textContent = opt.textContent.trim();
            list.appendChild(li);
            generated.push(li);
            opt.remove();
        });

        input.addEventListener("focus", () => list.classList.remove("hidden"));


        input.addEventListener("blur", () => setTimeout(() => list.classList.add("hidden"), 150));

        // Filter search
        input.addEventListener("input", () => {
            const search = input.value.toLowerCase();
            generated.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(search) ? "block" : "none";
            });
        });

        // Klik pilihan
        generated.forEach(item => {
            item.addEventListener("mousedown", e => e.preventDefault()); // cegah blur
            item.addEventListener("click", () => {
                input.value = item.textContent.trim();
                hidden.value = item.dataset.value;
                list.classList.add("hidden");
            });
        });
    });
</script>
