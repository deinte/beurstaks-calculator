<div>
    {{-- Hero --}}
    <header class="relative pt-32 pb-16 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-100 via-blue-100 to-indigo-100"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-950 tracking-tight">
                TOB Calculator
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Bereken je Belgische beurstaks voor buitenlandse brokers
            </p>

            {{-- Progress Steps --}}
            <nav class="mt-10" aria-label="Progress">
                <ol class="flex items-center justify-center gap-2 sm:gap-4">
                    @php
                        $steps = [
                            ['num' => 1, 'label' => 'Upload', 'done' => $fileProcessed, 'current' => !$fileProcessed],
                            ['num' => 2, 'label' => 'Tarieven', 'done' => $this->allTickersMapped && $fileProcessed, 'current' => $fileProcessed && !$calculated],
                            ['num' => 3, 'label' => 'Resultaat', 'done' => $calculated, 'current' => $calculated],
                        ];
                    @endphp

                    @foreach ($steps as $i => $step)
                        <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold transition-all
                                {{ $step['done'] ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : ($step['current'] ? 'bg-white ring-2 ring-blue-600 text-blue-600' : 'bg-white/80 ring-1 ring-black/10 text-gray-400') }}">
                                @if ($step['done'])
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    {{ $step['num'] }}
                                @endif
                            </span>
                            <span class="hidden sm:block text-sm font-medium {{ $step['done'] || $step['current'] ? 'text-gray-950' : 'text-gray-500' }}">
                                {{ $step['label'] }}
                            </span>
                        </li>
                        @if ($i < count($steps) - 1)
                            <div class="h-0.5 w-8 sm:w-12 rounded-full {{ $step['done'] ? 'bg-blue-600' : 'bg-black/10' }}"></div>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">

            {{-- Error Alert --}}
            @if ($processingError)
                <div class="mb-8 rounded-2xl bg-red-50 p-5 ring-1 ring-red-200">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-red-900">Er ging iets mis</h3>
                            <p class="mt-1 text-sm text-red-700">{{ $processingError }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Step 1: File Upload --}}
            @if (!$fileProcessed)
                <div class="bg-white rounded-3xl ring-1 ring-black/5 overflow-hidden">
                    {{-- Header --}}
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 px-8 py-6">
                        <h2 class="text-xl font-bold text-gray-950">Upload je transacties</h2>
                        <p class="mt-1 text-gray-600">Exporteer je transacties en upload het .xlsx of .csv bestand.</p>
                    </div>

                    <div class="p-8">
                        {{-- Info Cards --}}
                        <div class="grid sm:grid-cols-2 gap-4 mb-8">
                            <div class="flex gap-4 p-4 rounded-2xl bg-gray-50">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-950 text-sm">Veilig verwerkt</h3>
                                    <p class="text-sm text-gray-600 mt-0.5">Bestand wordt direct na berekening verwijderd</p>
                                </div>
                            </div>
                            <div class="flex gap-4 p-4 rounded-2xl bg-gray-50">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-950 text-sm">Automatische herkenning</h3>
                                    <p class="text-sm text-gray-600 mt-0.5">400+ tickers met voorgestelde tarieven</p>
                                </div>
                            </div>
                        </div>

                        {{-- Upload Zone --}}
                        <div
                            x-data="{ dragging: false }"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
                            :class="dragging ? 'border-blue-500 bg-blue-50 ring-4 ring-blue-500/10' : 'border-gray-200 hover:border-blue-300 hover:bg-blue-50/50'"
                            class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed p-12 transition-all cursor-pointer"
                            @click="$refs.fileInput.click()"
                        >
                            <input type="file" wire:model="file" x-ref="fileInput" accept=".xlsx,.csv" class="hidden">

                            <div wire:loading.remove wire:target="file">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                </div>
                                <p class="mt-6 text-base text-gray-600">
                                    <span class="font-semibold text-blue-600">Klik om te uploaden</span> of sleep je bestand hierheen
                                </p>
                                <p class="mt-2 text-sm text-gray-500">.xlsx of .csv, maximaal 10 MB</p>
                            </div>

                            <div wire:loading wire:target="file" class="text-center">
                                <svg class="h-10 w-10 animate-spin text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-gray-600">Bestand uploaden...</p>
                            </div>
                        </div>

                        @error('file')
                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- File Selected --}}
                        @if ($file && !$errors->has('file'))
                            <div class="mt-6 flex items-center justify-between rounded-2xl bg-blue-50 p-5 ring-1 ring-blue-200">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-sm">
                                        <svg class="h-6 w-6 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M3 3.5A1.5 1.5 0 014.5 2h6.879a1.5 1.5 0 011.06.44l4.122 4.12A1.5 1.5 0 0117 7.622V16.5a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 013 16.5v-13z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-950">{{ $file->getClientOriginalName() }}</p>
                                        <p class="text-sm text-gray-600">Klaar om te verwerken</p>
                                    </div>
                                </div>
                                <button
                                    wire:click="processFile"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 rounded-full bg-gray-950 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:bg-gray-800 disabled:opacity-50 transition"
                                >
                                    <span wire:loading.remove wire:target="processFile">Verwerken</span>
                                    <span wire:loading wire:target="processFile">Verwerken...</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Disclaimer --}}
                <div class="mt-8 rounded-2xl bg-amber-50 p-5 ring-1 ring-amber-200">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="text-sm text-amber-800">
                            <p class="font-semibold">Disclaimer</p>
                            <p class="mt-1">Resultaten kunnen fouten bevatten. Controleer altijd de berekeningen en raadpleeg bij twijfel de officiële bronnen van de FOD Financiën.</p>
                        </div>
                    </div>
                </div>

            {{-- Step 2: Rate Assignment --}}
            @elseif (!$calculated)
                <div class="bg-white rounded-3xl ring-1 ring-black/5 overflow-hidden">
                    {{-- Header --}}
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-950">Ken tarieven toe</h2>
                                <p class="mt-1 text-gray-600">Selecteer het juiste TOB-tarief voor elk effect</p>
                            </div>
                            <button wire:click="resetCalculator" class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Opnieuw
                            </button>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mt-6">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-600">{{ $this->mappedCount }} van {{ $this->totalTickerCount }} toegewezen</span>
                                <span class="font-semibold text-gray-950">{{ $this->progress }}%</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-white/50">
                                <div class="h-full rounded-full bg-blue-600 transition-all duration-300" style="width: {{ $this->progress }}%"></div>
                            </div>
                        </div>

                        {{-- Suggestions Notice --}}
                        @if ($this->hasSuggestions)
                            <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2">
                                <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm font-medium text-emerald-800">
                                    {{ $this->suggestedCount }}/{{ $this->totalTickerCount }} tickers automatisch herkend
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Rate Legend --}}
                    <div class="border-b border-gray-100 px-8 py-5 bg-gray-50/50">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Tarieven overzicht</p>
                        <x-tob.rate-legend layout="vertical" size="sm" />
                    </div>

                    {{-- Quick Actions --}}
                    <div class="border-b border-gray-100 px-8 py-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Snel toewijzen:</span>
                            <x-tob.rate-button rate="low" wire:click="setAllRates('low')" />
                            <x-tob.rate-button rate="medium" wire:click="setAllRates('medium')" />
                            <x-tob.rate-button rate="high" wire:click="setAllRates('high')" />
                            <button
                                wire:click="clearAllRates"
                                class="rounded-full bg-gray-100 px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-200 transition"
                            >
                                Reset alles
                            </button>
                        </div>
                    </div>

                    {{-- Tickers Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Ticker</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Info</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Aantal</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Bedrag</th>
                                    <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tarief</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($uniqueTickers as $ticker)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="whitespace-nowrap px-8 py-4">
                                            <code class="font-mono text-sm font-bold text-gray-950">{{ $ticker->ticker }}</code>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($ticker->hasSuggestion())
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm text-gray-600">{{ $ticker->suggestedName }}</span>
                                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-blue-200">
                                                        {{ $ticker->suggestedType }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 italic">Niet herkend</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-600">
                                            {{ $ticker->count }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-gray-900">
                                            &euro;{{ number_format($ticker->totalAmount, 2, ',', '.') }}
                                        </td>
                                        <td class="whitespace-nowrap px-8 py-4">
                                            <div class="flex items-center gap-2">
                                                <select
                                                    wire:model.change="tickerRates.{{ $ticker->ticker }}"
                                                    class="block w-full rounded-xl border-0 py-2 pl-4 pr-10 text-sm font-medium text-gray-900 ring-1 ring-inset transition focus:ring-2 focus:ring-blue-600
                                                        {{ $tickerRates[$ticker->ticker] === null ? 'ring-red-300 bg-red-50' : 'ring-gray-200 bg-white' }}
                                                        {{ $tickerRates[$ticker->ticker] === 'low' ? 'ring-emerald-300 bg-emerald-50' : '' }}
                                                        {{ $tickerRates[$ticker->ticker] === 'medium' ? 'ring-blue-300 bg-blue-50' : '' }}
                                                        {{ $tickerRates[$ticker->ticker] === 'high' ? 'ring-orange-300 bg-orange-50' : '' }}"
                                                >
                                                    <option value="">Selecteer...</option>
                                                    @foreach ($this->rateOptions as $option)
                                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($ticker->hasSuggestion() && $tickerRates[$ticker->ticker] === $ticker->suggestedRate)
                                                    <svg class="h-5 w-5 flex-shrink-0 text-emerald-500" viewBox="0 0 20 20" fill="currentColor" title="Automatisch herkend">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Calculate Button --}}
                    <div class="px-8 py-6 bg-gray-50">
                        <div class="flex justify-end">
                            <button
                                wire:click="calculate"
                                wire:loading.attr="disabled"
                                @disabled(!$this->allTickersMapped)
                                class="inline-flex items-center gap-2 rounded-full bg-gray-950 px-8 py-3 text-sm font-semibold text-white shadow-lg hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50 transition"
                            >
                                <span wire:loading.remove wire:target="calculate">Bereken TOB</span>
                                <span wire:loading wire:target="calculate">Berekenen...</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            {{-- Step 3: Results --}}
            @else
                <div class="space-y-8">
                    {{-- Grand Total --}}
                    <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-700 p-8 shadow-xl">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                            <div>
                                <p class="text-sm font-medium text-blue-100">Totaal verschuldigde TOB</p>
                                <p class="mt-2 text-5xl font-extrabold tracking-tight text-white">&euro;{{ number_format($grandTotal, 2, ',', '.') }}</p>
                            </div>
                            <div class="flex gap-3">
                                <button
                                    wire:click="export('xlsx')"
                                    class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm hover:bg-white/20 transition ring-1 ring-white/20"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Excel
                                </button>
                                <button
                                    wire:click="export('csv')"
                                    class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur-sm hover:bg-white/20 transition ring-1 ring-white/20"
                                >
                                    CSV
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Periods --}}
                    @foreach ($results as $period)
                        <div class="overflow-hidden rounded-3xl bg-white ring-1 ring-black/5">
                            <div class="px-8 py-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-950">{{ $period->periodLabel }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Deadline: <span class="font-medium">{{ $period->deadline }}</span>
                                            @if ($period->isOverdue)
                                                <span class="ml-2 inline-flex items-center rounded-full bg-red-100 px-3 py-0.5 text-xs font-semibold text-red-700">Verlopen</span>
                                            @else
                                                <span class="text-gray-400">(nog {{ $period->daysUntilDeadline }} dagen)</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-bold text-gray-950">&euro;{{ number_format($period->totalTax, 2, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500">{{ $period->transactionCount }} transacties</p>
                                    </div>
                                </div>
                            </div>

                            <details class="group">
                                <summary class="flex cursor-pointer items-center justify-between bg-gray-50 px-8 py-4 hover:bg-gray-100 transition">
                                    <span class="text-sm font-semibold text-gray-700">Transactie details</span>
                                    <svg class="h-5 w-5 text-gray-400 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </summary>
                                <div class="overflow-x-auto border-t border-gray-100">
                                    <table class="min-w-full">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-8 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Datum</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Ticker</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Bedrag</th>
                                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Tarief</th>
                                                <th class="px-8 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">TOB</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($period->transactions as $tx)
                                                <tr class="hover:bg-gray-50/50">
                                                    <td class="whitespace-nowrap px-8 py-3 text-sm text-gray-600">{{ $tx->date }}</td>
                                                    <td class="whitespace-nowrap px-6 py-3 text-sm font-mono font-semibold text-gray-950">{{ $tx->ticker }}</td>
                                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-600">{{ $tx->type }}</td>
                                                    <td class="whitespace-nowrap px-6 py-3 text-right text-sm text-gray-600">&euro;{{ number_format($tx->amount, 2, ',', '.') }}</td>
                                                    <td class="whitespace-nowrap px-6 py-3 text-right">
                                                        <x-tob.rate-badge :rate="$tx->rateValue" />
                                                    </td>
                                                    <td class="whitespace-nowrap px-8 py-3 text-right text-sm font-semibold text-gray-950">
                                                        &euro;{{ number_format($tx->tax, 2, ',', '.') }}
                                                        @if ($tx->capApplied)
                                                            <span class="text-xs text-amber-600" title="Plafond toegepast">*</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        </div>
                    @endforeach

                    {{-- New Calculation --}}
                    <div class="flex justify-center pt-4">
                        <button
                            wire:click="resetCalculator"
                            class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-8 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Nieuwe berekening
                        </button>
                    </div>

                    {{-- Feedback Notice --}}
                    <div class="mt-8 rounded-2xl bg-amber-50 ring-1 ring-amber-200 p-4 text-center">
                        <p class="text-sm text-amber-800">
                            <svg class="inline-block w-4 h-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            Controleer altijd de berekeningen voordat je aangifte doet.
                            Fout gevonden? <a href="https://github.com/deinte/beurstaks-calculator/issues" target="_blank" rel="noopener" class="font-medium underline hover:text-amber-900">Meld het op GitHub</a>.
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </section>
</div>
