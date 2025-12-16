<!DOCTYPE html>
<html lang="nl-BE" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'beurstaks.be - Belgische Beurstaks Calculator' }}</title>
    <meta name="description" content="{{ $description ?? 'Bereken gratis je Belgische beurstaks (TOB) voor Revolut transacties. Automatische herkenning van 400+ tickers, correcte tarieven en plafonds volgens FOD Financiën.' }}">
    <meta name="keywords" content="TOB, beurstaks, Revolut, België, taks op beursverrichtingen, ETF, aandelen, belasting, calculator, IWDA, VWCE">
    <meta name="author" content="Dante Schrauwen">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta name="geo.region" content="BE">
    <meta name="geo.placename" content="België">
    <meta name="language" content="Dutch">
    <meta name="content-language" content="nl-BE">

    <meta property="og:site_name" content="beurstaks.be">
    <meta property="og:title" content="{{ $title ?? 'beurstaks.be - Belgische Beurstaks Calculator' }}">
    <meta property="og:description" content="{{ $description ?? 'Bereken gratis je Belgische beurstaks (TOB) voor Revolut transacties. Automatische herkenning van 400+ tickers.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="nl_BE">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'beurstaks.be - Belgische Beurstaks Calculator' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Bereken gratis je Belgische beurstaks voor Revolut. 400+ tickers automatisch herkend.' }}">

    <meta name="theme-color" content="#2563eb">
    <meta http-equiv="x-dns-prefetch-control" content="on">

    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.svg">

    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script defer src="https://analytics.deinte.be/script.js" data-website-id="a2e318f0-5b1c-437e-8560-19bed7657944"></script>

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "WebSite",
                "@@id": "{{ url('/') }}/#website",
                "name": "beurstaks.be",
                "url": "{{ url('/') }}",
                "description": "Gratis Belgische beurstaks calculator voor Revolut en andere buitenlandse brokers",
                "inLanguage": "nl-BE",
                "publisher": {
                    "@@type": "Organization",
                    "name": "beurstaks.be",
                    "url": "{{ url('/') }}"
                }
            },
            {
                "@@type": "{{ $schemaType ?? 'WebPage' }}",
                "@@id": "{{ url()->current() }}/#webpage",
                "name": "{{ $title ?? 'beurstaks.be - Belgische Beurstaks Calculator' }}",
                "url": "{{ url()->current() }}",
                "isPartOf": { "@@id": "{{ url('/') }}/#website" },
                "inLanguage": "nl-BE",
                "about": {
                    "@@type": "FinancialProduct",
                    "name": "Taks op Beursverrichtingen (TOB)",
                    "description": "Belgische belasting op aan- en verkoop van effecten"
                }
            },
            {
                "@@type": "BreadcrumbList",
                "@@id": "{{ url()->current() }}/#breadcrumb",
                "itemListElement": [
                    {
                        "@@type": "ListItem",
                        "position": 1,
                        "name": "Home",
                        "item": "{{ url('/') }}"
                    }
                    @if(request()->path() !== '/')
                    ,{
                        "@@type": "ListItem",
                        "position": 2,
                        "name": "{{ $title ?? 'Pagina' }}",
                        "item": "{{ url()->current() }}"
                    }
                    @endif
                ]
            }
        ]
    }
    </script>
</head>
<body class="h-full bg-white font-sans antialiased text-gray-950">
    @php
        $navLinks = [
            ['href' => route('calculator'), 'label' => 'Calculator', 'title' => 'Beurstaks Calculator - Bereken je TOB'],
            ['href' => route('tickers'), 'label' => 'Tickers', 'title' => 'Ticker Database - Zoek ETFs en aandelen'],
            ['href' => route('page.show', 'rates-and-caps'), 'label' => 'Tarieven', 'title' => 'TOB Tarieven en Plafonds 2025'],
            ['href' => route('page.show', 'how-to-declare'), 'label' => 'Aangifte', 'title' => 'Hoe TOB aangeven bij FOD Financiën'],
            ['href' => route('page.show', 'revolut-vergelijken'), 'label' => 'Vergelijken', 'title' => 'Revolut vergelijken met andere brokers'],
        ];

        $footerLinks = [
            ['href' => route('page.show', 'revolut-beurstaks'), 'label' => 'Revolut & Beurstaks'],
            ['href' => route('page.show', 'rates-and-caps'), 'label' => 'Tarieven en Plafonds'],
            ['href' => route('page.show', 'how-to-declare'), 'label' => 'Hoe aangeven?'],
            ['href' => route('page.show', 'revolut-vergelijken'), 'label' => 'Revolut Vergelijken'],
            ['href' => route('tickers'), 'label' => 'Ticker Database'],
            ['href' => route('sources'), 'label' => 'Bronnen'],
            ['href' => route('calculator'), 'label' => 'Calculator'],
        ];
    @endphp

    <nav class="fixed top-0 left-0 right-0 z-50" role="navigation" aria-label="Hoofdnavigatie">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate title="beurstaks.be - Home">
                    {{-- Logo icon --}}
                    <svg class="h-8 w-8" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="navLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#2563eb"/>
                                <stop offset="100%" style="stop-color:#4f46e5"/>
                            </linearGradient>
                        </defs>
                        <rect x="4" y="10" width="6" height="14" rx="1.5" fill="url(#navLogoGradient)"/>
                        <rect x="13" y="14" width="6" height="10" rx="1.5" fill="url(#navLogoGradient)" opacity="0.7"/>
                        <rect x="22" y="6" width="6" height="18" rx="1.5" fill="url(#navLogoGradient)"/>
                    </svg>
                    {{-- Logo text --}}
                    <span class="text-lg font-bold text-gray-950">beurstaks<span class="text-blue-600">.be</span></span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    @foreach($navLinks as $link)
                        <a href="{{ $link['href'] }}" title="{{ $link['title'] }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-950 rounded-full hover:bg-black/5 transition" wire:navigate>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                    <a href="https://github.com/deinte/beurstaks-calculator" target="_blank" rel="noopener noreferrer" title="Bekijk op GitHub" class="p-2 text-gray-500 hover:text-gray-950 rounded-full hover:bg-black/5 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="{{ route('calculator') }}" title="Start de Beurstaks Calculator" class="ml-1 px-5 py-2 text-sm font-semibold text-white bg-gray-950 rounded-full hover:bg-gray-800 transition shadow-lg shadow-gray-950/10" wire:navigate>
                        Start Calculator
                    </a>
                </div>

                <button type="button"
                        x-data
                        x-on:click="$dispatch('toggle-mobile-menu')"
                        class="md:hidden p-2 text-gray-600 hover:text-gray-950 hover:bg-black/5 rounded-full"
                        aria-label="Menu openen"
                        aria-expanded="false">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-data="{ open: false }"
             x-on:toggle-mobile-menu.window="open = !open"
             x-show="open"
             x-cloak
             class="md:hidden bg-white/95 backdrop-blur-xl border-b border-black/5"
             role="menu">
            <div class="px-6 py-4 space-y-1">
                @foreach($navLinks as $link)
                    <a href="{{ $link['href'] }}" title="{{ $link['title'] }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-950 hover:bg-black/5 rounded-xl" wire:navigate role="menuitem">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <main role="main">
        {{ $slot }}
    </main>

    <footer class="bg-gray-950 text-white" role="contentinfo">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-20">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        {{-- Footer logo icon --}}
                        <svg class="h-8 w-8" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="10" width="6" height="14" rx="1.5" fill="#3b82f6"/>
                            <rect x="13" y="14" width="6" height="10" rx="1.5" fill="#3b82f6" opacity="0.7"/>
                            <rect x="22" y="6" width="6" height="18" rx="1.5" fill="#3b82f6"/>
                        </svg>
                        <span class="text-lg font-bold">beurstaks<span class="text-blue-400">.be</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                        Gratis tool om je Belgische beurstaks te berekenen voor buitenlandse brokers zoals Revolut. Automatische herkenning van 400+ ETFs en aandelen.
                    </p>
                </div>

                <nav aria-label="Footer navigatie">
                    <h3 class="font-semibold text-sm uppercase tracking-wider text-gray-400 mb-4">Informatie</h3>
                    <ul class="space-y-3 text-sm">
                        @foreach($footerLinks as $link)
                            <li>
                                <a href="{{ $link['href'] }}" class="text-gray-300 hover:text-white transition" wire:navigate>
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <div>
                    <h3 class="font-semibold text-sm uppercase tracking-wider text-gray-400 mb-4">Disclaimer</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Louter informatief, geen juridisch of fiscaal advies. Raadpleeg bij twijfel de <a href="https://financien.belgium.be/nl/particulieren/belastingaangifte/tarieven-702" target="_blank" rel="noopener noreferrer" class="underline hover:text-white">FOD Financiën</a>.
                    </p>
                    <p class="text-sm text-gray-400 leading-relaxed mt-3">
                        Fout gevonden of suggestie? <a href="https://github.com/deinte/beurstaks-calculator/issues" target="_blank" rel="noopener noreferrer" class="underline hover:text-white">Meld het op GitHub</a> of <a href="https://github.com/deinte/beurstaks-calculator" target="_blank" rel="noopener noreferrer" class="underline hover:text-white">draag bij</a>.
                    </p>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} beurstaks.be. Alle rechten voorbehouden.</p>
                <div class="flex items-center gap-4">
                    <a href="https://github.com/deinte/beurstaks-calculator" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-gray-400 hover:text-white transition" title="Bekijk broncode op GitHub">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">Open Source</span>
                    </a>
                    <span class="text-gray-600">·</span>
                    <p>Gemaakt door <a href="https://danteschrauwen.be?utm_source=beurstaks.be&utm_medium=referral&utm_campaign=footer" target="_blank" rel="noopener" class="text-gray-300 hover:text-white font-medium">Dante Schrauwen</a></p>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
