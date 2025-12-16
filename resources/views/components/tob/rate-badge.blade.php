@props(['rate', 'size' => 'sm'])

@php
$config = match($rate) {
    'low' => ['label' => '0,12%', 'class' => 'bg-emerald-100 text-emerald-700'],
    'high' => ['label' => '1,32%', 'class' => 'bg-orange-100 text-orange-700'],
    default => ['label' => '0,35%', 'class' => 'bg-blue-100 text-blue-700'],
};

$sizeClass = match($size) {
    'xs' => 'px-1.5 py-0.5 text-xs',
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-sm',
    'lg' => 'px-3 py-1.5 text-base',
    default => 'px-2 py-0.5 text-xs',
};
@endphp

<span {{ $attributes->merge(['class' => 'inline-block rounded font-semibold ' . $sizeClass . ' ' . $config['class']]) }}>
    {{ $config['label'] }}
</span>
