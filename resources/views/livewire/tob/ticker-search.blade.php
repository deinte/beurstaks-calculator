<div>
    {{-- Uitleg --}}
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 mb-8 text-sm text-gray-600 ring-1 ring-black/5">
        <p class="mb-2"><strong class="text-gray-950">Wat is een ticker?</strong> Een ticker is de afkorting waarmee een aandeel of ETF wordt verhandeld op de beurs. Bijvoorbeeld: <span class="font-mono bg-white px-1.5 py-0.5 rounded-md ring-1 ring-black/10">AAPL</span> voor Apple, <span class="font-mono bg-white px-1.5 py-0.5 rounded-md ring-1 ring-black/10">IWDA</span> voor iShares MSCI World ETF.</p>
        <p><strong class="text-gray-950">Waarom is dit belangrijk?</strong> Het type effect bepaalt welk TOB-tarief je betaalt. Accumulerende ETFs uit de EER betalen slechts 0,12%, terwijl gewone aandelen 0,35% betalen.</p>
    </div>

    {{-- Zoekbalk --}}
    <div class="relative max-w-md mx-auto mb-8">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Zoek op ticker of naam (bv. IWDA, Apple...)"
            class="w-full px-5 py-4 pl-12 bg-gray-50 rounded-2xl ring-1 ring-black/10 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
        >
        <svg class="absolute left-4 top-4.5 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>

    @if (strlen($search) > 0)
        @if (count($this->results) > 0)
            <div class="rounded-2xl ring-1 ring-black/5 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Ticker</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Volledige naam</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Soort</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">TOB Tarief</th>
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
                                <td class="px-5 py-3">
                                    <span class="font-mono font-semibold text-gray-950">{{ $ticker }}</span>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $data['name'] ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $typeLabel }}</td>
                                <td class="px-5 py-3 text-right">
                                    <x-tob.rate-badge :rate="$data['rate'] ?? 'medium'" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Legenda --}}
            <x-tob.rate-legend class="mt-4" />

            @if (count($this->results) >= 20)
                <p class="text-center text-sm text-gray-500 mt-4">Meer dan 20 resultaten. Verfijn je zoekopdracht.</p>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-950 font-medium">Geen resultaten voor "{{ $search }}"</p>
                <p class="text-sm text-gray-500 mt-2">Deze ticker zit niet in onze database.<br>Je kunt het tarief handmatig selecteren in de calculator.</p>
            </div>
        @endif
    @else
        <div class="text-center py-8">
            <p class="text-2xl font-bold text-gray-950">{{ $this->totalCount }}</p>
            <p class="text-gray-500">effecten in database</p>
        </div>
    @endif
</div>
