@props(['product', 'errors'])

<div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
    <div
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">Description & Status</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Content management and visibility settings.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2" x-data="{
            faqs: {{ Js::from($product->faqs ?? []) }},
            addFaq() {
                this.faqs.push({ question: '', answer: '' });
            },
            removeFaq(index) {
                this.faqs.splice(index, 1);
            }
        }">
            <!-- Short Description -->
            <div class="lg:col-span-2">
                <x-form.label for="short_description">Short Description</x-form.label>
                <textarea id="short_description" name="short_description" rows="3"
                    class="w-full rounded-2xl border border-gray-100 bg-slate-50 p-4 text-sm font-medium outline-none transition-all focus:border-brand-500/50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 dark:border-gray-800 dark:bg-white/5 dark:text-white"
                    placeholder="Briefly describe the product summary...">{{ old('short_description', $product->short_description) }}</textarea>
                @error('short_description') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Full Description -->
            <div class="lg:col-span-2">
                <x-form.label for="description">Full Detailed Description</x-form.label>
                <textarea id="description" name="description" rows="8"
                    class="w-full rounded-2xl border border-gray-100 bg-slate-50 p-4 text-sm font-medium outline-none transition-all focus:border-brand-500/50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 dark:border-gray-800 dark:bg-white/5 dark:text-white"
                    placeholder="Enter full product details, features, and specifications...">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Product FAQs -->
            <div class="lg:col-span-2 mt-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-white/90">Product FAQs</h4>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">Add frequently asked questions for this product.</p>
                    </div>
                    <button type="button" @click="addFaq()" 
                        class="flex h-9 items-center justify-center gap-2 rounded-xl bg-brand-50 px-4 text-[11px] font-black text-brand-600 uppercase tracking-widest transition-all hover:bg-brand-100 dark:bg-brand-500/10 dark:text-brand-400">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Add FAQ
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(faq, index) in faqs" :key="index">
                        <div class="p-5 rounded-2xl border border-gray-100 bg-slate-50/50 dark:bg-white/[0.02] dark:border-gray-800 relative group animate-in slide-in-from-top-2 duration-300">
                            <!-- FAQ ID -->
                            <template x-if="faq.id">
                                <input type="hidden" :name="'faqs[' + index + '][id]'" :value="faq.id">
                            </template>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-brand-500 mb-1.5 block">Question</label>
                                    <input type="text" :name="'faqs[' + index + '][question]'" x-model="faq.question" 
                                        class="h-10 w-full rounded-xl border border-gray-100 bg-white px-4 text-xs font-bold outline-none focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white transition-all"
                                        placeholder="e.g. Is this product waterproof?">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5 block">Answer</label>
                                    <textarea :name="'faqs[' + index + '][answer]'" x-model="faq.answer" rows="2"
                                        class="w-full rounded-xl border border-gray-100 bg-white p-4 text-xs font-medium outline-none transition-all focus:border-brand-500 dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                                        placeholder="Provide a clear and concise answer..."></textarea>
                                </div>
                            </div>
                            
                            <button type="button" @click="removeFaq(index)" 
                                class="absolute top-4 right-4 h-7 w-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-red-500 hover:text-white shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>

                    <div x-show="faqs.length === 0" class="py-10 text-center rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-800">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No FAQs added yet</p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mt-4">
                <x-form.label for="status">Publication Status</x-form.label>
                <x-form.select 
                    name="status" 
                    id="status"
                    :options="[
                        ['value' => 'active', 'label' => 'Active'],
                        ['value' => 'draft', 'label' => 'Draft'],
                        ['value' => 'inactive', 'label' => 'Inactive'],
                    ]"
                    :selected="old('status', $product->status)"
                    :error="$errors->first('status')"
                />
            </div>

            <!-- Flags -->
            <div class="flex flex-wrap items-center gap-8 px-4 mt-10">
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                        class="h-5 w-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-white/5">
                    <label for="is_featured" class="text-sm font-bold text-gray-700 dark:text-gray-300">Featured Product</label>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}
                        class="h-5 w-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-white/5">
                    <label for="is_new" class="text-sm font-bold text-gray-700 dark:text-gray-300">New Arrival</label>
                </div>
            </div>
        </div>
    </div>
</div>
