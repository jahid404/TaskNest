@props(['paginator'])

@php
    $perPageOptions = [
        ['value' => 10, 'label' => '10'],
        ['value' => 25, 'label' => '25'],
        ['value' => 50, 'label' => '50'],
        ['value' => 100, 'label' => '100'],
    ];

    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $startPage = max($currentPage - 2, 1);
    $endPage = min($startPage + 4, $lastPage);
    if ($endPage - $startPage < 4) {
        $startPage = max($endPage - 4, 1);
    }
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-between gap-6 px-2 md:flex-row']) }}>
    <!-- Results Info & Per Page -->
    <div
        class="flex flex-wrap items-center justify-center gap-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:justify-start">
        <div
            class="flex h-12 items-center gap-2 rounded-2xl border border-gray-100 bg-white px-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <span>Showing</span>
            <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->firstItem() ?? 0 }}</span>
            <span>-</span>
            <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->lastItem() ?? 0 }}</span>
            <span>of</span>
            <span class="font-bold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Items:</span>
            <div class="w-24">
                <x-form.select name="per_page_selector" :options="$perPageOptions" :selected="request('per_page', 10)" placeholder="Size"
                    onchange="const url = new URL(window.location.href); url.searchParams.set('per_page', this.value); url.searchParams.set('page', 1); window.location.href = url.toString();" />
            </div>
        </div>
    </div>

    <!-- Pagination Links -->
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span
                    class="flex h-12 w-12 cursor-not-allowed items-center justify-center rounded-2xl border border-gray-100 bg-gray-50 text-gray-300 dark:border-gray-800 dark:bg-white/5 dark:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-gray-100 bg-white text-gray-600 transition-all hover:border-brand-500/50 hover:bg-brand-500 hover:text-white hover:shadow-lg hover:shadow-brand-500/20 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-brand-500 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Numeric Links --}}
            <div class="hidden items-center gap-2 sm:flex">
                @if ($startPage > 1)
                    <a href="{{ $paginator->url(1) }}"
                        class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-2xl border border-gray-100 bg-white px-4 text-sm font-bold text-gray-600 transition-all hover:bg-brand-50 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/5">1</a>
                    @if ($startPage > 2)
                        <span class="flex h-12 w-8 items-center justify-center text-gray-400">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                            </svg>
                        </span>
                    @endif
                @endif

                @for ($i = $startPage; $i <= $endPage; $i++)
                    @if ($i == $currentPage)
                        <span
                            class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-xl bg-brand-500 px-4 text-sm font-black text-white shadow-md shadow-brand-500/20 ring-4 ring-brand-500/10">
                            {{ $i }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($i) }}"
                            class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-xl border border-gray-100 bg-white px-4 text-sm font-bold text-gray-600 transition-all hover:border-brand-500/30 hover:bg-brand-50 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/5">
                            {{ $i }}
                        </a>
                    @endif
                @endfor

                @if ($endPage < $lastPage)
                    @if ($endPage < $lastPage - 1)
                        <span class="flex h-12 w-8 items-center justify-center text-gray-400">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                            </svg>
                        </span>
                    @endif
                    <a href="{{ $paginator->url($lastPage) }}"
                        class="flex h-10 min-w-[2.5rem] items-center justify-center rounded-2xl border border-gray-100 bg-white px-4 text-sm font-bold text-gray-600 transition-all hover:bg-brand-50 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-white/5">{{ $lastPage }}</a>
                @endif
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-gray-100 bg-white text-gray-600 transition-all hover:border-brand-500/50 hover:bg-brand-500 hover:text-white hover:shadow-lg hover:shadow-brand-500/20 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400 dark:hover:bg-brand-500 dark:hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span
                    class="flex h-12 w-12 cursor-not-allowed items-center justify-center rounded-2xl border border-gray-100 bg-gray-50 text-gray-300 dark:border-gray-800 dark:bg-white/5 dark:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </nav>
    @endif
</div>
