<x-layouts.app
    title="Bronnen & Referenties - beurstaks.be"
    description="Overzicht van alle officiële bronnen en referenties gebruikt voor beurstaks.be. Inclusief FOD Financiën, Wikifin, Europese Commissie en wetgeving."
>
    {{-- Hero --}}
    <header class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-100 via-blue-100 to-indigo-100"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm mb-8">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-950 transition" wire:navigate>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </a>
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span class="text-gray-950 font-medium">Bronnen</span>
            </nav>

            <div class="max-w-3xl">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-950 tracking-tight">
                    Bronnen & Referenties
                </h1>
                <p class="mt-6 text-xl text-gray-600 leading-relaxed">
                    Alle informatie op deze website is gebaseerd op officiële bronnen. Hieronder vind je een overzicht van de gebruikte referenties.
                </p>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12">
                {{-- Main Content --}}
                <div class="lg:col-span-8 space-y-12">
                    {{-- Official Resources --}}
                    <div>
                        <h2 class="text-2xl font-bold text-gray-950 mb-8">Officiële bronnen</h2>

                        @foreach ($officialResources as $category => $resources)
                            <div class="mb-10">
                                <h3 class="text-lg font-semibold text-gray-950 mb-4 flex items-center gap-2">
                                    @if ($category === 'FOD Financiën')
                                        <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                            </svg>
                                        </span>
                                    @elseif ($category === 'Wikifin (FSMA)')
                                        <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                            </svg>
                                        </span>
                                    @elseif ($category === 'Europese Commissie')
                                        <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                            </svg>
                                        </span>
                                    @else
                                        <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                            </svg>
                                        </span>
                                    @endif
                                    {{ $category }}
                                </h3>

                                <div class="space-y-3">
                                    @foreach ($resources as $resource)
                                        <a
                                            href="{{ $resource['url'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="block p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition group"
                                        >
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="font-medium text-gray-950 group-hover:text-blue-600 transition">
                                                        {{ $resource['name'] }}
                                                        @if (str_contains($resource['url'], '.pdf'))
                                                            <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">PDF</span>
                                                        @endif
                                                    </p>
                                                    @if (isset($resource['description']))
                                                        <p class="mt-1 text-sm text-gray-500">{{ $resource['description'] }}</p>
                                                    @endif
                                                    <p class="mt-2 text-xs text-gray-400 truncate">{{ $resource['url'] }}</p>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Content Page Sources --}}
                    @if (count($contentSources) > 0)
                        <div class="border-t border-gray-200 pt-12">
                            <h2 class="text-2xl font-bold text-gray-950 mb-8">Bronnen per pagina</h2>
                            <p class="text-gray-600 mb-8">Hieronder vind je de specifieke bronnen die gebruikt zijn voor elke informatiepagina op deze website.</p>

                            @foreach ($contentSources as $pageTitle => $sources)
                                <div class="mb-8">
                                    <h3 class="text-lg font-semibold text-gray-950 mb-3">{{ $pageTitle }}</h3>
                                    <ul class="space-y-2">
                                        @foreach ($sources as $source)
                                            <li>
                                                <a
                                                    href="{{ $source['url'] }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                                >
                                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                    </svg>
                                                    {{ $source['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Disclaimer --}}
                    <div class="border-t border-gray-200 pt-12">
                        <div class="rounded-2xl bg-amber-50 ring-1 ring-amber-200 p-6">
                            <h3 class="font-semibold text-amber-900 mb-2">Disclaimer</h3>
                            <p class="text-sm text-amber-800">
                                Hoewel wij streven naar correcte en actuele informatie, kan deze website fouten bevatten.
                                De informatie op deze website is louter informatief en vormt geen juridisch of fiscaal advies.
                                Raadpleeg bij twijfel altijd de officiële bronnen of een fiscaal adviseur.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-4">
                    <div class="sticky top-24 space-y-8">
                        {{-- Quick Links --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6">
                            <h3 class="font-bold text-gray-950 mb-4">Snelle links</h3>
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ route('calculator') }}" class="flex items-center gap-3 text-gray-600 hover:text-gray-950 transition group" wire:navigate>
                                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:shadow transition">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">TOB Calculator</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('page.show', 'revolut-beurstaks') }}" class="flex items-center gap-3 text-gray-600 hover:text-gray-950 transition group" wire:navigate>
                                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:shadow transition">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">Revolut & Beurstaks</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('page.show', 'rates-and-caps') }}" class="flex items-center gap-3 text-gray-600 hover:text-gray-950 transition group" wire:navigate>
                                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:shadow transition">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">Tarieven & Plafonds</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- Official Badge --}}
                        <div class="bg-gray-50 rounded-2xl p-6 text-center">
                            <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-950 mb-2">Gebaseerd op officiële bronnen</h3>
                            <p class="text-sm text-gray-500">Alle informatie is afkomstig van de FOD Financiën, FSMA en Europese instanties.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>
