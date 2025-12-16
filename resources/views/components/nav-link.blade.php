@props(['href', 'active' => false])

@php
$classes = $active
    ? 'px-4 py-2 text-sm font-medium text-slate-900 bg-slate-100 rounded-lg'
    : 'px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
