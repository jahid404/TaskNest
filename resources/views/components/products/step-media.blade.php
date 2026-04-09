@props(['product', 'errors'])

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

<div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0"
    x-data="{
        galleryPreviews: [],
        deletedImages: [],
        replaceIndex: null,
    
        init() {
            @if ($product->images) @foreach ($product->images as $img)
                    this.galleryPreviews.push({
                        id: 'existing-{{ $img->id }}',
                        url: '{{ asset($img->image_path) }}',
                        isExisting: true,
                        existingId: {{ $img->id }}
                    });
                @endforeach @endif
    
            this.$nextTick(() => {
                new Sortable(this.$refs.galleryGrid, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    draggable: '.gallery-item',
                    onEnd: () => {
                        // Robust re-mapping: Read the DOM order directly
                        const domItems = Array.from(this.$refs.galleryGrid.querySelectorAll('.gallery-item'));
                        const newOrderIds = domItems.map(el => el.dataset.id);
    
                        // Rebuild array based on DOM order
                        const newItemsOrder = newOrderIds.map(id => {
                            return this.galleryPreviews.find(item => String(item.id) === String(id));
                        }).filter(Boolean);
    
                        this.galleryPreviews = newItemsOrder;
                        this.syncFiles();
                    }
                });
            });
        },
    
        handleGallery(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.galleryPreviews.push({
                        id: Date.now() + Math.random(),
                        url: e.target.result,
                        file: file,
                        isExisting: false
                    });
                    this.syncFiles();
                };
                reader.readAsDataURL(file);
            });
            e.target.value = '';
        },
    
        triggerReplace(index) {
            this.replaceIndex = index;
            this.$refs.replaceInput.click();
        },
    
        handleReplace(e) {
            const file = e.target.files[0];
            if (!file) return;
    
            const reader = new FileReader();
            reader.onload = (e) => {
                const item = this.galleryPreviews[this.replaceIndex];
                if (item.isExisting) {
                    this.deletedImages.push(item.existingId);
                }
    
                this.galleryPreviews.splice(this.replaceIndex, 1, {
                    id: Date.now() + Math.random(),
                    url: e.target.result,
                    file: file,
                    isExisting: false
                });
    
                this.syncFiles();
                this.$refs.replaceInput.value = '';
            };
            reader.readAsDataURL(file);
        },
    
        removeGalleryItem(index) {
            const item = this.galleryPreviews[index];
            if (item.isExisting) {
                this.deletedImages.push(item.existingId);
            }
            this.galleryPreviews.splice(index, 1);
            this.$nextTick(() => this.syncFiles());
        },
    
        syncFiles() {
            const dataTransfer = new DataTransfer();
            this.galleryPreviews.forEach(item => {
                if (item.file) dataTransfer.items.add(item.file);
            });
            this.$refs.galleryInput.files = dataTransfer.files;
        }
    }">

    <!-- Hidden Order & Deletion tracking -->
    <template x-for="(item, index) in galleryPreviews" :key="'order-' + index">
        <input type="hidden" name="gallery_order[]" :value="item.isExisting ? item.existingId : 'new'">
    </template>

    <template x-for="id in deletedImages" :key="'del-' + id">
        <input type="hidden" name="deleted_images[]" :value="id">
    </template>

    <!-- Hidden Replace Input -->
    <input type="file" x-ref="replaceInput" @change="handleReplace" class="hidden" accept="image/*">

    <div
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">Product Media</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Enhance your product's appeal with high-resolution
                imagery.</p>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-5">
            <!-- Thumbnail (Primary) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between mb-2">
                    <x-form.label class="!mb-0">Main Thumbnail</x-form.label>
                    <span
                        class="text-[10px] font-bold text-brand-500 uppercase tracking-widest bg-brand-50 dark:bg-brand-500/10 px-2 py-0.5 rounded">Primary</span>
                </div>

                <div
                    class="relative group aspect-square w-full max-w-[320px] mx-auto overflow-hidden rounded-[2.5rem] border-2 border-dashed border-gray-200 dark:border-gray-800 transition-all hover:border-brand-500/50 hover:bg-slate-50 dark:hover:bg-white/5">
                    <template x-if="thumbnailUrl || '{{ $product->thumbnail }}'">
                        <img :src="thumbnailUrl || '{{ asset($product->thumbnail) }}'"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-pointer"
                            @click="$refs.thumbnailInput.click()">
                    </template>
                    <template x-if="!thumbnailUrl && !'{{ $product->thumbnail }}'">
                        <div class="flex h-full w-full flex-col items-center justify-center text-gray-400 p-8 text-center cursor-pointer"
                            @click="$refs.thumbnailInput.click()">
                            <div
                                class="mb-4 rounded-2xl bg-gray-50 p-4 dark:bg-white/5 group-hover:bg-brand-50 transition-colors">
                                <svg class="h-8 w-8 text-gray-400 group-hover:text-brand-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400">Click or Drag Image</span>
                            <span class="mt-1 text-[10px] text-gray-400 uppercase">Recommended: 1:1 Aspect Ratio</span>
                        </div>
                    </template>
                    <input type="file" name="thumbnail" x-ref="thumbnailInput" @change="thumbnailPreview"
                        class="hidden">

                    <!-- Overlay Button -->
                    <div
                        class="absolute inset-x-0 bottom-6 flex justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="rounded-full bg-white/90 px-4 py-2 text-[10px] font-bold text-gray-700 shadow-xl backdrop-blur-md cursor-pointer"
                            @click="$refs.thumbnailInput.click()">Change Photo</div>
                    </div>
                </div>
                @error('thumbnail')
                    <p class="mt-2 text-center text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gallery (Secondary) -->
            <div class="lg:col-span-3 space-y-4">
                <x-form.label>Additional Gallery Shots</x-form.label>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 select-none" x-ref="galleryGrid">
                    <!-- Unified Gallery Loop (New & Existing) -->
                    <template x-for="(item, index) in galleryPreviews" :key="item.id">
                        <div :data-id="item.id"
                            class="gallery-item relative aspect-square overflow-hidden rounded-3xl border border-gray-100 dark:border-gray-800 group/item cursor-move shadow-sm hover:shadow-md transition-all select-none">
                            <img :src="item.url"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover/item:scale-110 pointer-events-none"
                                @click="triggerReplace(index)">

                            <!-- Overlay Actions -->
                            <div
                                class="absolute inset-0 bg-black/20 opacity-0 group-hover/item:opacity-100 transition-opacity flex items-center justify-center">
                                <div class="flex gap-2">
                                    <button type="button" @click.stop="triggerReplace(index)"
                                        class="h-8 w-8 rounded-full bg-white/90 text-gray-700 shadow-lg flex items-center justify-center hover:bg-brand-500 hover:text-white transition-all transform scale-90 group-hover/item:scale-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button type="button" @click.stop="removeGalleryItem(index)"
                                        class="h-8 w-8 rounded-full bg-red-500/90 text-white shadow-lg flex items-center justify-center hover:bg-red-600 transition-all transform scale-90 group-hover/item:scale-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Badges -->
                            <div class="absolute bottom-2 left-2 flex gap-1 items-center">
                                <template x-if="!item.isExisting">
                                    <div
                                        class="px-2 py-0.5 rounded-lg bg-success-500/90 text-[8px] font-black text-white uppercase tracking-tighter">
                                        New</div>
                                </template>
                                <template x-if="item.isExisting">
                                    <div
                                        class="px-2 py-0.5 rounded-lg bg-brand-500/90 text-[8px] font-black text-white uppercase tracking-tighter">
                                        Existing</div>
                                </template>
                                <div
                                    class="px-2 py-0.5 rounded-lg bg-black/40 backdrop-blur-md text-[8px] font-black text-white uppercase tracking-tighter">
                                    Pos: <span x-text="index + 1"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Upload Box -->
                    <label
                        class="relative aspect-square flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-800 bg-slate-50/50 dark:bg-white/5 cursor-pointer hover:border-brand-500/50 hover:bg-brand-50/30 transition-all group">
                        <div
                            class="h-10 w-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="h-5 w-5 text-brand-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="mt-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Add More</span>
                        <input type="file" name="gallery[]" x-ref="galleryInput" multiple @change="handleGallery"
                            class="hidden" accept="image/*">
                    </label>
                </div>

                <div x-show="galleryPreviews.length === 0"
                    class="mt-4 flex items-center gap-3 rounded-2xl bg-amber-50 p-4 dark:bg-amber-500/5">
                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-500/10">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-[11px] font-medium text-amber-700 dark:text-amber-400">Add at least 3-4 images of
                        different angles for better engagement.</p>
                </div>
            </div>
        </div>
    </div>
</div>
