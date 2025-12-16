<!DOCTYPE html>
<html lang="nl-BE" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'TOB Calculator - Belgische Beurstaks voor Revolut' }}</title>
    <meta name="description" content="{{ $description ?? 'Bereken gratis je Belgische beurstaks (TOB) voor Revolut transacties. Automatische herkenning van 400+ tickers, correcte tarieven en plafonds volgens FOD Financiën.' }}">
    <meta name="keywords" content="TOB, beurstaks, Revolut, België, taks op beursverrichtingen, ETF, aandelen, belasting, calculator, IWDA, VWCE">
    <meta name="author" content="Dante Schrauwen">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta name="geo.region" content="BE">
    <meta name="geo.placename" content="België">
    <meta name="language" content="Dutch">
    <meta name="content-language" content="nl-BE">

    <meta property="og:site_name" content="TOB Calculator">
    <meta property="og:title" content="{{ $title ?? 'TOB Calculator - Belgische Beurstaks voor Revolut' }}">
    <meta property="og:description" content="{{ $description ?? 'Bereken gratis je Belgische beurstaks (TOB) voor Revolut transacties. Automatische herkenning van 400+ tickers.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="nl_BE">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'TOB Calculator - Belgische Beurstaks' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Bereken gratis je Belgische beurstaks voor Revolut. 400+ tickers automatisch herkend.' }}">

    <meta name="theme-color" content="#2563eb">
    <meta http-equiv="x-dns-prefetch-control" content="on">

    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "WebSite",
                "@@id": "{{ url('/') }}/#website",
                "name": "TOB Calculator",
                "url": "{{ url('/') }}",
                "description": "Gratis Belgische beurstaks calculator voor Revolut en andere buitenlandse brokers",
                "inLanguage": "nl-BE",
                "publisher": {
                    "@@type": "Organization",
                    "name": "TOB Calculator",
                    "url": "{{ url('/') }}"
                }
            },
            {
                "@@type": "{{ $schemaType ?? 'WebPage' }}",
                "@@id": "{{ url()->current() }}/#webpage",
                "name": "{{ $title ?? 'TOB Calculator - Belgische Beurstaks voor Revolut' }}",
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
            ['href' => route('calculator'), 'label' => 'Calculator', 'title' => 'TOB Calculator - Bereken je beurstaks'],
            ['href' => route('tickers'), 'label' => 'Tickers', 'title' => 'Ticker Database - Zoek ETFs en aandelen'],
            ['href' => route('page.show', 'rates-and-caps'), 'label' => 'Tarieven', 'title' => 'TOB Tarieven en Plafonds 2025'],
            ['href' => route('page.show', 'how-to-declare'), 'label' => 'Aangifte', 'title' => 'Hoe TOB aangeven bij FOD Financiën'],
        ];

        $footerLinks = [
            ['href' => route('page.show', 'revolut-beurstaks'), 'label' => 'Revolut & Beurstaks'],
            ['href' => route('page.show', 'rates-and-caps'), 'label' => 'Tarieven en Plafonds'],
            ['href' => route('page.show', 'how-to-declare'), 'label' => 'Hoe aangeven?'],
            ['href' => route('tickers'), 'label' => 'Ticker Database'],
            ['href' => route('calculator'), 'label' => 'Calculator'],
        ];
    @endphp

    <nav class="fixed top-0 left-0 right-0 z-50" role="navigation" aria-label="Hoofdnavigatie">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3" wire:navigate title="TOB Calculator - Home">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/25">
                        <span class="text-white font-bold text-sm" aria-hidden="true">T</span>
                    </div>
                    <span class="text-lg font-semibold text-gray-950">TOB Calculator</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    @foreach($navLinks as $link)
                        <a href="{{ $link['href'] }}" title="{{ $link['title'] }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-950 rounded-full hover:bg-black/5 transition" wire:navigate>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                    <a href="{{ route('calculator') }}" title="Start de TOB Calculator" class="ml-2 px-5 py-2 text-sm font-semibold text-white bg-gray-950 rounded-full hover:bg-gray-800 transition shadow-lg shadow-gray-950/10" wire:navigate>
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
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-white font-bold text-sm" aria-hidden="true">T</span>
                        </div>
                        <span class="text-lg font-semibold">TOB Calculator</span>
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
                <p>&copy; {{ date('Y') }} TOB Calculator. Alle rechten voorbehouden.</p>
                <p>Gemaakt door <a href="https://danteschrauwen.be" target="_blank" rel="noopener" class="text-gray-300 hover:text-white font-medium">Dante Schrauwen</a></p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
