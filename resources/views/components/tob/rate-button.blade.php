@props(['rate', 'type' => 'button'])

@php
$config = match($rate) {
    'low' => ['label' => '0,12%', 'class' => 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'],
    'high' => ['label' => '1,32%', 'class' => 'bg-orange-100 text-orange-700 hover:bg-orange-200'],
    default => ['label' => '0,35%', 'class' => 'bg-blue-100 text-blue-700 hover:bg-blue-200'],
};
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'rounded-full px-3 py-1 text-xs font-medium transition ' . $config['class']]) }}
>
    {{ $slot->isEmpty() ? 'Alles ' . $config['label'] : $slot }}
</button>
