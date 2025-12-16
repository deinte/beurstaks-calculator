<div>
    {{-- Hero --}}
    <header class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-100 via-blue-100 to-indigo-100"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-950 tracking-tight">
                Ticker Database
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Doorzoek onze database van <strong>{{ number_format($this->totalCount, 0, ',', '.') }}</strong> ETFs en aandelen met automatische TOB-tarieven
            </p>
        </div>
    </header>

    {{-- Search Section --}}
    <section class="py-16 bg-white" aria-label="Zoek effecten">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            {{-- Uitleg over tickers en TOB --}}
            <article class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 mb-10 ring-1 ring-black/5">
                <div class="grid md:grid-cols-2 gap-6 text-sm text-gray-600">
                    <div>
                        <h2 class="font-semibold text-gray-950 mb-2">Wat is een ticker?</h2>
                        <p>Een <dfn>ticker</dfn> (of tickersymbool) is de unieke afkorting waarmee een effect wordt verhandeld op de beurs. Bijvoorbeeld: <code class="font-mono bg-white px-1.5 py-0.5 rounded-md ring-1 ring-black/10">AAPL</code> voor Apple, <code class="font-mono bg-white px-1.5 py-0.5 rounded-md ring-1 ring-black/10">IWDA</code> voor iShares MSCI World ETF.</p>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-950 mb-2">Waarom is het TOB-tarief belangrijk?</h2>
                        <p>De <dfn>Taks op Beursverrichtingen</dfn> (TOB) kent drie tarieven. Accumulerende ETFs gevestigd in de EER betalen slechts <strong>0,12%</strong>, terwijl gewone aandelen <strong>0,35%</strong> betalen. Niet-EER fondsen betalen zelfs <strong>1,32%</strong>.</p>
                    </div>
                </div>
            </article>

            {{-- Zoekbalk --}}
            <div class="relative max-w-xl mx-auto mb-10">
                <label for="ticker-search" class="sr-only">Zoek op ticker of naam</label>
                <input
                    id="ticker-search"
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Zoek op ticker of naam (bv. IWDA, Apple, VWCE, Microsoft...)"
                    class="w-full px-6 py-4 pl-14 text-lg bg-gray-50 rounded-2xl ring-1 ring-black/10 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                    autocomplete="off"
                    autofocus
                >
                <svg class="absolute left-5 top-5 w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            {{-- Rate Legend --}}
            <div class="mb-8">
                <x-tob.rate-legend class="justify-center" />
            </div>

            {{-- Results --}}
            @if (strlen($search) > 0)
                @if (count($this->results) > 0)
                    <div class="rounded-2xl ring-1 ring-black/5 overflow-hidden" role="region" aria-label="Zoekresultaten">
                        <table class="w-full" aria-describedby="results-description">
                            <caption class="sr-only" id="results-description">Gevonden effecten met bijbehorende TOB-tarieven</caption>
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Ticker</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Naam</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Type</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">TOB Tarief</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($this->results as $ticker => $data)
                                    @php
                                        $typeLabels = [
                                            'ETF Acc' => 'ETF (accumul.)',
                                            'ETF Dist' => 'ETF (dividend)',
                                            'Stock' => 'Aandeel',
                                        ];
                                        $typeLabel = $typeLabels[$data['type'] ?? ''] ?? ($data['type'] ?? '-');
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <code class="font-mono font-bold text-gray-950">{{ $ticker }}</code>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $data['name'] ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-500 hidden sm:table-cell">{{ $typeLabel }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <x-tob.rate-badge :rate="$data['rate'] ?? 'medium'" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if (count($this->results) >= 50)
                        <p class="text-center text-sm text-gray-500 mt-4" role="status">Meer dan 50 resultaten gevonden. Verfijn je zoekopdracht voor nauwkeurigere resultaten.</p>
                    @else
                        <p class="text-center text-sm text-gray-500 mt-4" role="status">{{ count($this->results) }} {{ count($this->results) === 1 ? 'resultaat' : 'resultaten' }} gevonden</p>
                    @endif
                @else
                    <div class="text-center py-16" role="status" aria-live="polite">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xl font-medium text-gray-950">Geen resultaten voor "{{ $search }}"</p>
                        <p class="text-gray-500 mt-2">Deze ticker staat niet in onze database.<br>Je kunt het TOB-tarief handmatig selecteren in de calculator.</p>
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-gray-950">{{ number_format($this->totalCount, 0, ',', '.') }}</p>
                    <p class="text-lg text-gray-500 mt-1">effecten in database</p>
                    <p class="text-sm text-gray-400 mt-4">Typ een ticker of naam om te zoeken</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Populaire tickers sectie --}}
    <section class="py-16 bg-gray-50" aria-labelledby="popular-heading">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <h2 id="popular-heading" class="text-2xl font-bold text-gray-950 mb-8 text-center">Populaire ETFs voor Belgische beleggers</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $popularTickers = [
                        ['ticker' => 'IWDA', 'name' => 'iShares MSCI World', 'rate' => 'low', 'type' => 'ETF Acc'],
                        ['ticker' => 'VWCE', 'name' => 'Vanguard FTSE All-World', 'rate' => 'low', 'type' => 'ETF Acc'],
                        ['ticker' => 'CSPX', 'name' => 'iShares Core S&P 500', 'rate' => 'low', 'type' => 'ETF Acc'],
                        ['ticker' => 'EMIM', 'name' => 'iShares EM IMI', 'rate' => 'low', 'type' => 'ETF Acc'],
                        ['ticker' => 'IEMA', 'name' => 'iShares EM SRI', 'rate' => 'low', 'type' => 'ETF Acc'],
                        ['ticker' => 'SXR8', 'name' => 'iShares Core S&P 500 DE', 'rate' => 'low', 'type' => 'ETF Acc'],
                    ];
                @endphp
                @foreach($popularTickers as $item)
                    <div class="bg-white rounded-xl p-4 ring-1 ring-black/5 flex items-center justify-between">
                        <div>
                            <code class="font-mono font-bold text-gray-950">{{ $item['ticker'] }}</code>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $item['name'] }}</p>
                        </div>
                        <x-tob.rate-badge :rate="$item['rate']" />
                    </div>
                @endforeach
            </div>
            <p class="text-center text-sm text-gray-500 mt-6">
                Deze accumulerende ETFs zijn gevestigd in de EER en betalen het lage TOB-tarief van 0,12%.
            </p>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-white" aria-labelledby="cta-heading">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <h2 id="cta-heading" class="text-2xl font-bold text-gray-950 mb-4">Klaar om je TOB te berekenen?</h2>
            <p class="text-gray-600 mb-8">Upload je Revolut transacties en bereken automatisch je verschuldigde beurstaks.</p>
            <a href="{{ route('calculator') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-950 text-white font-semibold rounded-full hover:bg-gray-800 transition shadow-xl shadow-gray-950/20" wire:navigate>
                Start Calculator
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </section>
</div>
