<x-layouts.app
    :title="$page->title . ' - beurstaks.be'"
    :description="$page->description ?? Str::limit(strip_tags($page->content), 155)"
    schemaType="Article"
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
                <span class="text-gray-950 font-medium">{{ $page->title }}</span>
            </nav>

            <div class="max-w-3xl">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-950 tracking-tight">
                    {{ $page->title }}
                </h1>
                @if ($page->description)
                    <p class="mt-6 text-xl text-gray-600 leading-relaxed">
                        {{ $page->description }}
                    </p>
                @endif
                @if ($page->lastUpdated)
                    <p class="mt-6 text-sm text-gray-500">
                        Laatst bijgewerkt: {{ $page->lastUpdated->format('d F Y') }}
                    </p>
                @endif
            </div>
        </div>
    </header>

    {{-- Content --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12">
                {{-- Main Content --}}
                <article class="lg:col-span-8">
                    <div class="prose prose-lg prose-gray max-w-none
                        prose-headings:font-bold prose-headings:text-gray-950
                        prose-h2:text-3xl prose-h2:mt-12 prose-h2:mb-6
                        prose-h3:text-2xl prose-h3:mt-10 prose-h3:mb-4
                        prose-p:text-gray-600 prose-p:leading-relaxed
                        prose-a:text-blue-600 prose-a:font-medium prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-gray-950 prose-strong:font-semibold
                        prose-ul:text-gray-600 prose-ol:text-gray-600
                        prose-li:marker:text-blue-500
                        prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:rounded-r-xl prose-blockquote:py-1
                        prose-code:text-blue-600 prose-code:bg-blue-50 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded-md prose-code:before:content-none prose-code:after:content-none">
                        {!! $page->content !!}
                    </div>
                </article>

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
                                    <a href="{{ route('tickers') }}" class="flex items-center gap-3 text-gray-600 hover:text-gray-950 transition group" wire:navigate>
                                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:shadow transition">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">Ticker Database</span>
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

                        {{-- Sources --}}
                        @if (!empty($page->sources))
                            <div class="bg-gray-50 rounded-2xl p-6">
                                <h3 class="font-bold text-gray-950 mb-4">Bronnen</h3>
                                <ul class="space-y-3">
                                    @foreach ($page->sources as $source)
                                        <li>
                                            <a
                                                href="{{ $source['url'] }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition group"
                                            >
                                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                </svg>
                                                <span>{{ $source['name'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- CTA Card --}}
                        <div class="bg-gray-950 rounded-2xl p-6 text-center">
                            <h3 class="font-bold text-white mb-2">Klaar om te berekenen?</h3>
                            <p class="text-gray-400 text-sm mb-4">Bereken je TOB in enkele seconden</p>
                            <a
                                href="{{ route('calculator') }}"
                                class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 bg-white text-gray-950 font-semibold rounded-full hover:bg-gray-100 transition"
                                wire:navigate
                            >
                                Start Calculator
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>
