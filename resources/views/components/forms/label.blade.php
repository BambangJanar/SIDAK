@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block text-sm text-gray-700 dark:text-gray-400 font-medium mb-1']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>