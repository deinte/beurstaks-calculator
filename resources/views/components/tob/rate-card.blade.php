@props(['rate'])

@php
$config = match($rate) {
    'low' => [
        'percentage' => '0,12%',
        'color' => 'bg-emerald-500',
        'bgGradient' => 'from-emerald-50 to-emerald-100/50',
        'title' => 'Laag tarief',
        'description' => 'Accumulerende ETFs (EER)',
        'cap' => '1.300',
    ],
    'high' => [
        'percentage' => '1,32%',
        'color' => 'bg-orange-500',
        'bgGradient' => 'from-orange-50 to-orange-100/50',
        'title' => 'Hoog tarief',
        'description' => 'Niet-EER fondsen',
        'cap' => '4.000',
    ],
    default => [
        'percentage' => '0,35%',
        'color' => 'bg-blue-500',
        'bgGradient' => 'from-blue-50 to-blue-100/50',
        'title' => 'Medium tarief',
        'description' => 'Aandelen, distribuerende ETFs',
        'cap' => '1.600',
    ],
};
@endphp

<div {{ $attributes->merge(['class' => 'rounded-3xl bg-gradient-to-br ' . $config['bgGradient'] . ' p-6']) }}>
    <div class="inline-flex items-center justify-center px-3 py-1.5 rounded-full {{ $config['color'] }} text-white text-sm font-bold mb-4 shadow-lg">
        {{ $config['percentage'] }}
    </div>
    <h3 class="text-lg font-semibold text-gray-950 mb-1">{{ $config['title'] }}</h3>
    <p class="text-sm text-gray-600 mb-3">{{ $config['description'] }}</p>
    <p class="text-xs text-gray-500">Plafond: €{{ $config['cap'] }}</p>
</div>
