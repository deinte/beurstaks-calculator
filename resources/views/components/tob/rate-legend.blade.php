@props(['layout' => 'horizontal', 'size' => 'sm'])

@php
$rates = [
    ['rate' => '0,12%', 'color' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Accumulerende ETFs (EER)', 'cap' => '1.300'],
    ['rate' => '0,35%', 'color' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Aandelen & dividend ETFs', 'cap' => '1.600'],
    ['rate' => '1,32%', 'color' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Niet-EER fondsen', 'cap' => '4.000'],
];

$containerClass = $layout === 'vertical'
    ? 'flex flex-col gap-2'
    : 'flex flex-wrap justify-center gap-4';

$textClass = $size === 'sm' ? 'text-xs' : 'text-sm';
$dotClass = $size === 'sm' ? 'w-3 h-3' : 'w-4 h-4';
@endphp

<div {{ $attributes->merge(['class' => $containerClass . ' ' . $textClass . ' text-slate-500']) }}>
    @foreach ($rates as $rate)
        <div class="flex items-center gap-1.5">
            <span class="inline-block {{ $dotClass }} rounded {{ $rate['color'] }}"></span>
            <span>{{ $rate['rate'] }} = {{ $rate['label'] }}</span>
        </div>
    @endforeach
</div>
