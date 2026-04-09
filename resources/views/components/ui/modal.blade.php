@props([
    'isOpen' => false,
    'showCloseButton' => true,
])

<div x-data="{
    open: @js($isOpen),
    init() {
        this.$watch('open', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'unset';
            }
        });
    }
}" x-show="open" x-cloak @keydown.escape.window="open = false"
    class="modal fixed inset-0 z-99999 flex items-center justify-center overflow-y-auto p-5"
    {{ $attributes->except('class') }}>

    <!-- Backdrop -->
    <div @click="open = false" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal Content -->
    <div @click.stop
        class="relative w-full max-h-[calc(100vh-4rem)] overflow-hidden rounded-3xl bg-white dark:bg-gray-900 flex flex-col {{ $attributes->get('class') }}"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95">

        <!-- Modal Header (Slot) -->
        @if (!empty($header) || $showCloseButton)
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        {{ $header ?? '' }}
                    </div>

                    @if ($showCloseButton)
                        <button @click="open = false"
                            class="flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:h-11 sm:w-11">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fillRule="evenodd" clipRule="evenodd"
                                    d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                                    fill="currentColor" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <!-- Modal Body -->
        <div class="flex-1 overflow-y-auto no-scrollbar">
            {{ $slot }}
        </div>

        <!-- Modal Footer Slot -->
        @if (!empty($footer))
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800 bg-white dark:bg-gray-900">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

<style>
    [x-cloak] {
        display: none;
    }

    .no-scrollbar::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
