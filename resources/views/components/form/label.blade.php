@props([
    'for' => null,
    'required' => false,
])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400']) }} @if($for) for="{{ $for }}" @endif>
    {{ $slot }}
    @if($required)
        <span class="text-error-500">*</span>
    @endif
</label>
