@props([
    'name',
    'id' => null,
    'options' => [], // Array of ['value' => 1, 'label' => 'Name']
    'selected' => null, // Can be single value or array for multiple
    'placeholder' => 'Select an option',
    'required' => false,
    'error' => null,
    'multiple' => false,
])

<div class="w-full">
    <div x-data="{
        open: false,
        multiple: @js($multiple),
        position: 'bottom',
        value: @js($selected),
        label: '', // For single select
        image: null, // For single select image
        selectedOptions: [], // For multiple select
        search: '',
        options: @js($options),
        get filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(option => 
                option.label.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        toggle() {
            if (this.open) {
                this.open = false;
                return;
            }
            
            const rect = this.$el.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const dropdownHeight = 350;
            
            if (spaceBelow < dropdownHeight && rect.top > spaceBelow) {
                this.position = 'top';
            } else {
                this.position = 'bottom';
            }
            
            this.open = true;
        },
        selectOption(option) {
            if (this.multiple) {
                if (!Array.isArray(this.value)) this.value = [];
                
                const index = this.value.indexOf(option.value);
                if (index > -1) {
                    this.value.splice(index, 1);
                    this.selectedOptions = this.selectedOptions.filter(o => o.value != option.value);
                } else {
                    this.value.push(option.value);
                    this.selectedOptions.push(option);
                }
            } else {
                this.value = option.value;
                this.label = option.label;
                this.image = option.image || null;
                this.open = false;
            }
            
            this.search = '';
            this.$dispatch('change', this.value);
            @if($attributes->has('onchange'))
                {{ $attributes->get('onchange') }}
            @endif
        },
        removeOption(optionValue) {
            if (!this.multiple) return;
            this.value = this.value.filter(v => v != optionValue);
            this.selectedOptions = this.selectedOptions.filter(o => o.value != optionValue);
        },
        init() {
            if (this.multiple) {
                if (!this.value) this.value = [];
                else if (!Array.isArray(this.value)) this.value = [this.value];
                
                this.selectedOptions = this.options.filter(o => this.value.includes(o.value));
            } else {
                const found = this.options.find(o => o.value == this.value);
                if (found) {
                    this.label = found.label;
                    this.image = found.image || null;
                }
            }
            
            $watch('open', value => {
                if (value) {
                    this.search = '';
                    $nextTick(() => $refs.searchInput.focus());
                }
            });

            $watch('value', () => {
                if (!this.multiple) {
                    const found = this.options.find(o => o.value == this.value);
                    this.label = found ? found.label : '';
                    this.image = found ? (found.image || null) : null;
                } else {
                    this.selectedOptions = this.options.filter(o => this.value.includes(o.value));
                }
            });
        }
    }" class="relative w-full" @click.outside="open = false" @keydown.esc="open = false">
        {{-- Hidden Inputs for form submission --}}
        <template x-if="!multiple">
            <input type="hidden" name="{{ $name }}" :value="value" @if($id) id="{{ $id }}" @endif @if($required) required @endif>
        </template>
        <template x-if="multiple">
            <div>
                <template x-for="v in value" :key="v">
                    <input type="hidden" name="{{ $name }}[]" :value="v">
                </template>
            </div>
        </template>

        {{-- Select Trigger --}}
        <div @click="toggle()" 
            {{ $attributes->merge(['class' => 'flex h-12 w-full cursor-pointer items-center justify-between rounded-2xl border bg-slate-50 px-4 text-sm font-bold text-gray-700 transition-all hover:bg-white dark:bg-white/5 dark:text-gray-200']) }}
            :class="[
                open ? 'border-brand-500/50 bg-white ring-4 ring-brand-500/5 dark:border-brand-500/50 dark:bg-gray-900/10' : 'border-gray-100 dark:border-gray-800',
                @js($error) ? 'border-red-500 ring-4 ring-red-500/5' : ''
            ]">
            
            <div class="flex flex-1 flex-wrap gap-1.5 items-center text-left">
                <template x-if="!multiple">
                    <div class="flex items-center gap-2">
                        <template x-if="image">
                            <img :src="image" class="h-6 w-6 rounded-lg object-cover border border-gray-100 dark:border-gray-800">
                        </template>
                        <span x-text="label || '{{ $placeholder }}'" :class="!label ? 'text-gray-400 font-normal' : 'text-gray-700 dark:text-gray-200'"></span>
                    </div>
                </template>
                
                <template x-if="multiple">
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="option in selectedOptions" :key="option.value">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-500/10 px-2.5 py-1 text-[11px] font-bold text-brand-600 dark:bg-brand-500/20 dark:text-brand-400">
                                <template x-if="option.image">
                                    <img :src="option.image" class="h-4 w-4 rounded-full object-cover">
                                </template>
                                <span x-text="option.label"></span>
                                <button type="button" @click.stop="removeOption(option.value)" class="flex h-3.5 w-3.5 items-center justify-center rounded-full hover:bg-brand-500/20">
                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <span x-show="selectedOptions.length === 0" class="text-gray-400 font-normal">{{ $placeholder }}</span>
                    </div>
                </template>
            </div>

            <div class="flex items-center gap-2 ml-2">
                <button x-show="!multiple && value && !@js($required)" type="button" @click.stop="value = ''; label = '';" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Dropdown --}}
        <div x-show="open" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-[0.98]"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-[0.98]"
            class="absolute z-[60] w-full overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-900"
            :class="{ 
                'top-full mt-2': position === 'bottom', 
                'bottom-full mb-2': position === 'top' 
            }"
            style="display: none;">
            
            {{-- Search Input --}}
            <div class="sticky top-0 z-10 border-b border-gray-50 bg-white/80 p-2 backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/80">
                <div class="relative">
                    <input type="text" x-model="search" placeholder="Search..."
                        class="h-10 w-full rounded-xl border border-gray-100 bg-slate-50 pl-9 pr-4 text-sm outline-none transition-all focus:border-brand-500/50 focus:bg-white dark:border-gray-800 dark:bg-white/5 dark:focus:border-brand-500/50"
                        @click.stop x-ref="searchInput"
                        @keydown.enter.prevent="if(filteredOptions.length > 0) selectOption(filteredOptions[0])">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Options List --}}
            <div class="max-h-64 overflow-y-auto p-1 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-gray-700">
                <template x-for="option in filteredOptions" :key="option.value">
                    <div @click="selectOption(option)"
                        class="group flex cursor-pointer items-center justify-between rounded-xl px-3 py-2 text-sm transition-all hover:bg-brand-50 dark:hover:bg-brand-500/10"
                        :class="((multiple && value.includes(option.value)) || (!multiple && value == option.value)) 
                            ? 'bg-brand-50 font-bold text-brand-600 dark:bg-brand-500/10 dark:text-brand-400' 
                            : 'text-gray-600 dark:text-gray-300'">
                        <div class="flex items-center gap-2.5">
                            <template x-if="option.image">
                                <img :src="option.image" class="h-8 w-8 rounded-lg object-cover border border-gray-100/50 group-hover:border-brand-500/20 dark:border-gray-800">
                            </template>
                            <span x-text="option.label"></span>
                        </div>
                        <svg x-show="(multiple && value.includes(option.value)) || (!multiple && value == option.value)" 
                            class="h-4 w-4 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </template>
                
                <div x-show="filteredOptions.length === 0" class="flex flex-col items-center justify-center py-8 px-4 text-center">
                    <svg class="h-10 w-10 text-gray-200 dark:text-gray-800 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="text-sm text-gray-500">No results found for '<span x-text="search" class="font-medium"></span>'</p>
                </div>
            </div>
        </div>
    </div>

    @if($error)
        <p class="mt-2 text-xs font-medium text-red-500">{{ $error }}</p>
    @endif
</div>
