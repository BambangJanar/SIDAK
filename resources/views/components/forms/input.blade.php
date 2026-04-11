@props(['disabled' => false, 'name', 'type' => 'text'])

@php
    $errorClass = $errors->has($name) ? 'border-red-600 focus:border-red-400 focus:shadow-outline-red' : 'border-gray-300 focus:border-purple-400 focus:shadow-outline-purple';
@endphp

<input 
    {{ $disabled ? 'disabled' : '' }} 
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    {!! $attributes->merge([
        'class' => "block w-full mt-1 text-sm dark:text-gray-300 dark:bg-gray-700 focus:outline-none form-input rounded-md shadow-sm $errorClass"
    ]) !!}
>

@error($name)
    <span class="text-xs text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
@enderror