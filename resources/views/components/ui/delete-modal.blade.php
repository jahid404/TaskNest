@props([
    'id' => 'global-delete-modal',
    'title' => 'Delete Confirmation',
    'message' => 'Are you sure you want to delete this item? This action cannot be undone.',
    'buttonText' => 'Delete Now',
])

<div x-data="{
        url: '',
        name: '',
        title: '{{ $title }}',
        message: '{{ $message }}',
        init() {
            this.title = '{{ $title }}';
            this.message = '{{ $message }}';
        }
    }"
    x-on:open-delete-modal.window="
        if ($event.detail.id && $event.detail.id !== '{{ $id }}') return;
        url = $event.detail.url;
        name = $event.detail.name || '';
        title = $event.detail.title || '{{ $title }}';
        message = $event.detail.message || '{{ $message }}';
        $dispatch('open-modal-internal', { id: '{{ $id }}' });
    ">
    <x-ui.modal {{ $attributes->merge(['class' => 'max-w-md']) }} :show-close-button="false" x-on:open-modal-internal.window="if ($event.detail.id === '{{ $id }}') open = true">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-500/10">
                <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="title"></h3>
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <p x-text="message"></p>
                    <template x-if="name">
                        <p class="block mt-1 font-semibold text-gray-700 dark:text-gray-200" x-text="'&ldquo;' + name + '&rdquo;'"></p>
                    </template>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8">
                <button x-on:click="open = false" type="button" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10 transition-colors">
                    Cancel
                </button>
                <form x-bind:action="url" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-red-500/25">
                        {{ $buttonText }}
                    </button>
                </form>
            </div>
        </div>
    </x-ui.modal>
</div>
