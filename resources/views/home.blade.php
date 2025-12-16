<x-layouts.app title="TOB Calculator - Belgische Beurstaks voor Revolut">
    {{-- Hero with gradient --}}
    <section class="relative min-h-screen flex items-center overflow-hidden">
        {{-- Gradient background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-sky-100 via-blue-100 to-indigo-200"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.8),transparent_50%)]"></div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-32 lg:py-40">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur ring-1 ring-black/5 text-sm font-medium text-gray-700 mb-8">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Gratis & Open Source
                </div>

                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-gray-950 tracking-tight">
                    Bereken je Belgische
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Beurstaks</span>
                </h1>

                <p class="mt-8 text-xl text-gray-600 leading-relaxed max-w-xl">
                    Revolut houdt de TOB niet voor je in. Upload je transacties en bereken automatisch je verschuldigde taks.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('calculator') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gray-950 rounded-full hover:bg-gray-800 transition shadow-xl shadow-gray-950/20" wire:navigate>
                        Start Calculator
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ route('page.show', 'rates-and-caps') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-gray-700 bg-white/80 backdrop-blur rounded-full ring-1 ring-black/10 hover:bg-white hover:ring-black/20 transition" wire:navigate>
                        Bekijk tarieven
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-24 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-3xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 ring-1 ring-emerald-200/50">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-950 mb-2">Veilig verwerkt</h3>
                    <p class="text-gray-600">Je bestand wordt tijdelijk verwerkt en direct na de berekening verwijderd.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-gradient-to-br from-violet-50 to-violet-100/50 ring-1 ring-violet-200/50">
                    <div class="w-12 h-12 rounded-2xl bg-violet-500 flex items-center justify-center mb-6 shadow-lg shadow-violet-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-950 mb-2">400+ tickers</h3>
                    <p class="text-gray-600">Populaire ETFs en aandelen worden automatisch herkend met het juiste tarief.</p>
                </div>

                <div class="group p-8 rounded-3xl bg-gradient-to-br from-amber-50 to-amber-100/50 ring-1 ring-amber-200/50">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center mb-6 shadow-lg shadow-amber-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-950 mb-2">Officiele bronnen</h3>
                    <p class="text-gray-600">Tarieven gebaseerd op actuele documentatie van de FOD Financien.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-24 lg:py-32 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-950">Hoe werkt het?</h2>
                <p class="mt-4 text-lg text-gray-600">Drie simpele stappen</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                @foreach ([
                    ['num' => '1', 'title' => 'Upload', 'desc' => 'Exporteer je transacties uit Revolut en upload het bestand.'],
                    ['num' => '2', 'title' => 'Controleer', 'desc' => 'Verifieer de automatisch voorgestelde tarieven.'],
                    ['num' => '3', 'title' => 'Download', 'desc' => 'Bekijk het resultaat per aangifteperiode.'],
                ] as $step)
                    <div class="text-center">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center mx-auto mb-6 text-xl font-bold shadow-lg shadow-blue-500/30">
                            {{ $step['num'] }}
                        </div>
                        <h3 class="text-lg font-semibold text-gray-950 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Rates --}}
    <section class="py-24 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-950">TOB Tarieven 2025</h2>
                <p class="mt-4 text-lg text-gray-600">De drie tarieven die van toepassing zijn</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <x-tob.rate-card rate="low" class="ring-1 ring-black/5 hover:ring-emerald-300 hover:shadow-xl hover:shadow-emerald-500/10 transition-all" />
                <x-tob.rate-card rate="medium" class="ring-1 ring-black/5 hover:ring-blue-300 hover:shadow-xl hover:shadow-blue-500/10 transition-all" />
                <x-tob.rate-card rate="high" class="ring-1 ring-black/5 hover:ring-orange-300 hover:shadow-xl hover:shadow-orange-500/10 transition-all" />
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('page.show', 'rates-and-caps') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700" wire:navigate>
                    Meer over tarieven
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Ticker Search --}}
    <section class="py-24 lg:py-32 bg-gray-50">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-950">Zoek een ticker</h2>
                <p class="mt-4 text-lg text-gray-600">Zoek in onze database van 400+ effecten</p>
            </div>

            <div class="bg-white rounded-3xl ring-1 ring-black/5 p-8">
                <livewire:tob.ticker-search />

                <div class="text-center mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('tickers') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700" wire:navigate>
                        Bekijk volledige ticker database
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="py-24 lg:py-32 bg-white">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-950">Veelgestelde vragen</h2>
            </div>

            <div class="space-y-4" x-data="{ open: null }">
                @foreach ([
                    ['q' => 'Wat is de TOB?', 'a' => 'De TOB (Taks op Beursverrichtingen) is een Belgische belasting op elke aan- en verkoop van effecten. Het tarief varieert van 0,12% tot 1,32%. Belgische banken houden dit automatisch in, maar buitenlandse brokers niet.'],
                    ['q' => 'Waarom moet ik dit zelf doen?', 'a' => 'Buitenlandse brokers zijn niet verplicht de Belgische beurstaks in te houden. Als Belgische inwoner ben je zelf verantwoordelijk voor aangifte en betaling.'],
                    ['q' => 'Wanneer moet ik betalen?', 'a' => 'De deadline is de laatste werkdag van de tweede maand na je transactie(s). Voor transacties in januari moet je uiterlijk eind maart betalen. Je mag meerdere maanden bundelen.'],
                ] as $index => $faq)
                    <div class="rounded-2xl ring-1 ring-black/5 overflow-hidden">
                        <button x-on:click="open = open === {{ $index }} ? null : {{ $index }}" class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-gray-50 transition">
                            <span class="font-semibold text-gray-950">{{ $faq['q'] }}</span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open === {{ $index }} }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open === {{ $index }}" x-collapse class="px-6 pb-5 text-gray-600">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach

                {{-- Rate FAQ with component --}}
                <div class="rounded-2xl ring-1 ring-black/5 overflow-hidden">
                    <button x-on:click="open = open === 3 ? null : 3" class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-950">Hoe weet ik welk tarief?</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open === 3 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open === 3" x-collapse class="px-6 pb-5">
                        <x-tob.rate-legend layout="vertical" class="mb-3" />
                        <p class="text-sm text-gray-500">Deze calculator herkent 400+ tickers automatisch.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[2.5rem] bg-gray-950 px-8 py-20 lg:px-20">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-indigo-600/20 to-transparent"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full blur-3xl opacity-20"></div>

                <div class="relative text-center max-w-2xl mx-auto">
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Klaar om te beginnen?</h2>
                    <p class="text-lg text-gray-400 mb-10">
                        Bereken je TOB binnen enkele seconden.
                    </p>
                    <a href="{{ route('calculator') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-gray-950 text-base font-semibold rounded-full hover:bg-gray-100 transition shadow-xl" wire:navigate>
                        Start Calculator
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
