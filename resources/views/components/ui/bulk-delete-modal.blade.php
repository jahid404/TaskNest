@props([
    'id' => 'global-bulk-delete-modal',
    'title' => 'Bulk Delete Confirmation',
    'message' => 'Are you sure you want to delete these selected items? This action cannot be undone.',
    'buttonText' => 'Delete Selected',
    'action' => '',
])

<div x-data="{
        ids: [],
        title: '{{ $title }}',
        message: '{{ $message }}',
        init() {
            this.title = '{{ $title }}';
            this.message = '{{ $message }}';
        }
    }"
    x-on:open-bulk-delete-modal.window="
        if ($event.detail.id && $event.detail.id !== '{{ $id }}') return;
        ids = $event.detail.ids || [];
        title = $event.detail.title || '{{ $title }}';
        message = $event.detail.message || '{{ $message }}';
        $dispatch('open-modal-internal', { id: '{{ $id }}' });
    ">
    <x-ui.modal {{ $attributes->merge(['class' => 'max-w-md']) }} :show-close-button="false" x-on:open-modal-internal.window="if ($event.detail.id === '{{ $id }}') open = true">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full dark:bg-red-500/10">
                <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16" />
                </svg>
            </div>
            
            <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="title"></h3>
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <p x-text="message"></p>
                <p class="mt-1 font-bold text-red-500" x-text="ids.length + ' items selected'"></p>
            </div>

            <div class="flex items-center gap-3 mt-8">
                <button x-on:click="open = false" type="button" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10 transition-colors">
                    Cancel
                </button>
                <form action="{{ $action }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in ids" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" :disabled="ids.length === 0" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-500 rounded-xl hover:bg-red-600 transition-colors shadow-lg shadow-red-500/25 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ $buttonText }}
                    </button>
                </form>
            </div>
        </div>
    </x-ui.modal>
</div>
