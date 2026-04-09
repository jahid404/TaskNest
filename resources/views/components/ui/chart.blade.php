@props([
    'id' => 'chart-' . uniqid(),
    'type' => 'area',
    'height' => 300,
    'series' => [],
    'options' => [],
])

@php
    $chartConfig = array_replace_recursive([
        'chart' => [
            'type' => $type,
            'height' => $height,
            'width' => '100%',
            'fontFamily' => 'Outfit, sans-serif',
            'toolbar' => ['show' => false],
        ],
        'series' => $series,
    ], $options);
@endphp

<div 
    id="{{ $id }}" 
    data-chart-options="{{ json_encode($chartConfig) }}"
    {{ $attributes->merge(['class' => 'w-full']) }}
></div>
