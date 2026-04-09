@props([
    'placeholder' => null,
    'name' => null,
    'id' => null,
    'value' => null,
    'rows' => 4,
    'error' => null,
    'disabled' => false,
])

@php
    $baseClasses = 'w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm transition focus:outline-hidden focus:ring-3';
    $normalClasses = 'text-gray-800 border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-white/30';
    $errorClasses = 'border-error-500 text-error-600 focus:border-error-500 focus:ring-error-500/10 dark:text-error-400';
    $disabledClasses = 'bg-gray-50 cursor-not-allowed dark:bg-white/[0.02]';
    
    $classes = $baseClasses . ' ' . ($error ? $errorClasses : $normalClasses) . ' ' . ($disabled ? $disabledClasses : '');
@endphp

<textarea 
    @if($name) name="{{ $name }}" @endif
    @if($id) id="{{ $id }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($rows) rows="{{ $rows }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => $classes]) }}
>{{ $slot->isEmpty() ? $value : $slot }}</textarea>

@if($error)
    <p class="mt-1.5 text-xs text-error-500">{{ $error }}</p>
@endif
