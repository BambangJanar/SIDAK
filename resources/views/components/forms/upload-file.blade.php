@props(['name', 'accept' => '*/*'])

@php
    $errorClass = $errors->has($name) ? 'border-red-600 focus:border-red-400 focus:shadow-outline-red' : 'border-gray-300 focus:border-purple-400 focus:shadow-outline-purple';
@endphp

<input 
    type="file" 
    name="{{ $name }}" 
    id="{{ $name }}"
    accept="{{ $accept }}"
    {!! $attributes->merge([
        'class' => "block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:text-gray-300 dark:file:bg-gray-600 dark:file:text-gray-300 form-input border rounded-md shadow-sm $errorClass"
    ]) !!}
>

@error($name)
    <span class="text-xs text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
@enderror