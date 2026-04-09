@props(['product', 'allAttributes', 'errors'])

<div x-show="step === 4" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
    <div
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]"
            x-init="
                if (variations.length > 0) {
                    const usedValueIds = [...new Set(variations.flatMap(v => v.attribute_values))];
                    availableAttributes.forEach(attr => {
                        const values = attr.values.filter(v => usedValueIds.includes(v.id)).map(v => v.id);
                        if (values.length > 0) {
                            selectedAttributes.push({ id: attr.id, values });
                        }
                    });
                }
            "
            x-data="{
                selectedAttributes: [],
                variations: {{ Js::from($product->variations->map(function($v) {
                    return [
                        'id' => $v->id,
                        'sku' => $v->sku,
                        'price' => $v->price,
                        'sale_price' => $v->sale_price,
                        'stock_quantity' => $v->stock_quantity,
                        'attribute_values' => $v->attributeValues->pluck('id')->toArray(),
                        'display_name' => $v->attributeValues->pluck('value')->implode(' / '),
                    ];
                })) }},
                availableAttributes: {{ Js::from($allAttributes->filter(function($a) {
                    return !in_array(strtolower($a->name), ['storage', 'ram type', 'ram']);
                })->values()->map(function($a) {
                    return [
                        'id' => $a->id,
                        'name' => $a->name,
                        'values' => $a->values->map(fn($v) => ['id' => $v->id, 'value' => $v->value])->toArray()
                    ];
                })) }},
                bulkPrice: '',
                bulkStock: '',
                colorMap: {
                    'Black': '#0F172A', 'White': '#F8FAFC', 'Red': '#EF4444', 'Blue': '#3B82F6', 
                    'Green': '#22C55E', 'Yellow': '#EAB308', 'Purple': '#A855F7', 'Orange': '#F97316', 
                    'Pink': '#EC4899', 'Gray': '#64748B', 'Brown': '#78350F', 'Silver': '#CBD5E1', 
                    'Gold': '#FBBF24', 'Cyan': '#06B6D4', 'Indigo': '#6366F1', 'Rose': '#F43F5E', 
                    'Teal': '#14B8A6', 'Violet': '#8B5CF6'
                },
                
                get currentAttr() {
                    return (id) => this.availableAttributes.find(a => Number(a.id) === Number(id));
                },
                
                get currentValues() {
                    return (id) => {
                        const attr = this.currentAttr(id);
                        return attr ? attr.values : [];
                    };
                },
                
                handleParamChange(selected, event) {
                    selected.id = event.detail;
                    selected.values = [];
                },
                
                addCustomColor(selected, color) {
                    if (!color) return;
                    const attr = this.currentAttr(selected.id);
                    if (!attr) return;
                    
                    let existingValue = attr.values.find(v => v.value.toLowerCase() === color.toLowerCase());
                    if (!existingValue) {
                        const newId = 'custom-' + Date.now();
                        existingValue = { id: newId, value: color, isCustom: true };
                        attr.values.push(existingValue);
                    }
                    
                    if (!selected.values.includes(existingValue.id)) {
                        selected.values.push(existingValue.id);
                    }
                },
                
                addAttribute() {
                    this.selectedAttributes.push({ id: '', values: [] });
                },
                
                applyBulk() {
                    this.variations.forEach(v => {
                        if (this.bulkPrice) v.price = this.bulkPrice;
                        if (this.bulkStock) v.stock_quantity = this.bulkStock;
                    });
                },
                
                removeAttribute(index) {
                    this.selectedAttributes.splice(index, 1);
                },
                
                generateVariations() {
                    if (this.selectedAttributes.length === 0) return;
                    
                    const attrData = this.selectedAttributes.filter(a => a.id && a.values.length > 0).map(a => {
                        const attr = this.currentAttr(a.id);
                        return a.values.map(valId => {
                            const val = attr.values.find(v => v.id == valId);
                            return { id: val.id, text: val.value, attrName: attr.name };
                        });
                    });
                    
                    if (attrData.length === 0) {
                        this.variations = [];
                        return;
                    }
                    
                    const combinations = attrData.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
                    
                    this.variations = combinations.map(combo => {
                        const comboArray = Array.isArray(combo) ? combo : [combo];
                        const displayName = comboArray.map(c => c.text).join(' / ');
                        const attrValueIds = comboArray.map(c => c.id);
                        
                        const existing = this.variations.find(v => {
                            if (!v.attribute_values) return false;
                            return JSON.stringify(v.attribute_values.slice().sort()) === JSON.stringify(attrValueIds.slice().sort());
                        });
                        
                        const slugInput = document.getElementById('slug');
                        const baseSku = slugInput ? (slugInput.value || 'sku') : 'sku';
                        
                        return {
                            id: existing ? existing.id : null,
                            sku: existing ? existing.sku : baseSku + '-' + displayName.toLowerCase().replace(/ \/ /g, '-').replace(/ /g, '-'),
                            price: existing ? existing.price : '0.00',
                            sale_price: existing ? existing.sale_price : '',
                            stock_quantity: existing ? existing.stock_quantity : 0,
                            attribute_values: attrValueIds,
                            display_name: displayName
                        };
                    });
                }
            }">
        
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">Product Variations</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Generate dynamic combinations for sizes, colors, and stock levels.</p>
                </div>
                <button type="button" @click="addAttribute()"
                    class="flex h-11 items-center justify-center gap-2 rounded-2xl bg-brand-500 px-6 text-sm font-bold text-white shadow-lg shadow-brand-500/20 transition-all hover:bg-brand-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add Attribute Group
                </button>
            </div>

            <!-- Attribute Configurators -->
            <div class="space-y-4 mb-8">
                <template x-for="(selected, index) in selectedAttributes" :key="index">
                    <div class="flex flex-col gap-5 p-6 rounded-3xl bg-slate-50/50 dark:bg-white/[0.02] border border-gray-100 dark:border-gray-800 relative group transition-all hover:shadow-md animate-in slide-in-from-right-4">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-brand-500">Parameter</label>
                                <div @change="handleParamChange(selected, $event)">
                                    <x-form.select 
                                        name="attribute_group" 
                                        x-model="selected.id" 
                                        class="!font-bold"
                                        :options="$allAttributes->filter(function($a) {
                                            return !in_array(strtolower($a->name), ['storage', 'ram type', 'ram']);
                                        })->map(fn($a) => ['value' => $a->id, 'label' => $a->name])->values()->toArray()"
                                    />
                                </div>
                            </div>
                            <div class="lg:col-span-3 space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400" 
                                    x-text="selected.id ? (currentAttr(selected.id)?.name || 'Loading...') + ' Selection' : 'Available Selectors'"></label>
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <template x-if="selected.id">
                                        <div class="flex flex-wrap items-center gap-2.5">
                                            <template x-for="val in currentValues(selected.id)" :key="val.id">
                                                <label class="inline-flex items-center cursor-pointer group/val">
                                                    <input type="checkbox" :value="val.id" x-model="selected.values" class="hidden peer">
                                                    
                                                    <!-- Dynamic Selection UI: Text or Color Swatch -->
                                                    <template x-if="currentAttr(selected.id)?.name?.toLowerCase() === 'color'">
                                                        <div class="relative px-3 py-2 rounded-xl border border-gray-200 bg-white peer-checked:border-brand-500 peer-checked:ring-2 peer-checked:ring-brand-500/20 transition-all shadow-sm hover:shadow-md group-hover/val:border-brand-200 flex items-center gap-2">
                                                            <div class="w-5 h-5 rounded-full border border-gray-100 shadow-inner" :style="'background-color: ' + (colorMap[val.value] || val.value)"></div>
                                                            <span class="text-[11px] font-bold text-gray-600" x-text="val.value"></span>
                                                            <div class="absolute -top-1 -right-1 hidden peer-checked:flex h-4 w-4 items-center justify-center rounded-full bg-brand-500 text-white animate-in zoom-in">
                                                                <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    
                                                    <template x-if="currentAttr(selected.id)?.name?.toLowerCase() !== 'color'">
                                                        <div class="px-4 py-2.5 rounded-xl border border-gray-100 bg-white text-xs font-bold text-gray-500 peer-checked:border-brand-500 peer-checked:bg-brand-500 peer-checked:text-white transition-all shadow-sm hover:border-brand-200">
                                                            <span x-text="val.value"></span>
                                                        </div>
                                                    </template>
                                                </label>
                                            </template>

                                            <!-- Custom Color Palette Button -->
                                            <template x-if="currentAttr(selected.id)?.name?.toLowerCase() === 'color'">
                                                <div class="relative" x-data="{ open: false }">
                                                    <button type="button" @click="open = !open" 
                                                        class="h-9 px-3 rounded-xl border-2 border-dashed border-gray-200 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-brand-400 hover:text-brand-500 transition-all flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                                        Add Color
                                                    </button>
                                                    
                                                    <div x-show="open" @click.outside="open = false" x-transition 
                                                        class="absolute left-0 mt-2 p-3 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 w-48 animate-in fade-in zoom-in-95">
                                                        <div class="grid grid-cols-4 gap-2 mb-3">
                                                            <template x-for="c in ['#000000', '#FFFFFF', '#FF0000', '#0000FF', '#00FF00', '#FFFF00', '#FFA500', '#800080', '#A52A2A', '#808080', '#FFC0CB', '#40E0D0']">
                                                                <button type="button" @click="addCustomColor(selected, c); open = false" 
                                                                    class="w-8 h-8 rounded-lg border border-gray-100 hover:scale-110 transition-transform shadow-sm"
                                                                    :style="'background-color: ' + c"></button>
                                                            </template>
                                                        </div>
                                                        <div class="relative">
                                                            <input type="color" @change="addCustomColor(selected, $event.target.value); open = false" 
                                                                class="w-full h-8 rounded-lg cursor-pointer opacity-0 absolute inset-0">
                                                            <div class="h-8 w-full border border-gray-100 rounded-lg flex items-center justify-center text-[9px] font-black uppercase text-gray-400 bg-gray-50 pointer-events-none">
                                                                Custom HEX
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!selected.id">
                                        <div class="h-12 flex items-center text-[11px] font-medium text-gray-400 italic bg-white px-4 rounded-xl border border-gray-100 w-full shadow-sm"> 
                                            Select an attribute group to load values...
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="removeAttribute(index)" class="absolute -top-3 -right-3 h-8 w-8 rounded-full bg-red-500 text-white shadow-xl flex items-center justify-center transform hover:scale-110 transition-all border-2 border-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <div x-show="selectedAttributes.length > 0" class="flex justify-center mt-6">
                <button type="button" @click="generateVariations()"
                    class="group flex h-14 items-center justify-center gap-3 rounded-2xl bg-gray-900 px-10 text-sm font-bold text-white shadow-2xl transition-all hover:bg-black">
                    <svg class="h-5 w-5 text-brand-400 transition-transform group-hover:rotate-180 duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Re-Generate All Combinations
                </button>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div x-show="variations.length > 0" class="mb-8 p-6 rounded-[2rem] bg-brand-500/5 border border-brand-500/10 flex flex-col md:flex-row items-center gap-6 animate-in fade-in zoom-in-95">
            <div class="flex-1">
                <h4 class="text-sm font-black text-brand-600 uppercase tracking-tighter">Bulk Editor</h4>
                <p class="text-[10px] text-brand-500/70 font-bold">Apply values to all combinations below at once.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative w-32">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400">$</span>
                    <input type="number" x-model="bulkPrice" placeholder="Price" class="h-10 w-full pl-6 pr-3 rounded-xl border-none ring-1 ring-gray-200 text-xs font-bold focus:ring-brand-500 transition-all">
                </div>
                <div class="w-32">
                    <input type="number" x-model="bulkStock" placeholder="Stock" class="h-10 w-full px-3 rounded-xl border-none ring-1 ring-gray-200 text-xs font-bold focus:ring-brand-500 transition-all">
                </div>
                <button type="button" @click="applyBulk()" class="h-10 px-6 rounded-xl bg-brand-600 text-white text-[11px] font-black uppercase tracking-widest hover:bg-brand-700 transition-all active:scale-95 shadow-lg shadow-brand-500/20">Apply</button>
            </div>
        </div>

        <!-- Variation List -->
        <div class="space-y-4">
            <template x-for="(variation, vIndex) in variations" :key="vIndex">
                <div class="overflow-hidden rounded-3xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-white/5 transition-all hover:border-brand-500/30 hover:shadow-xl dark:hover:bg-white/[0.04]">
                    <div class="bg-slate-50/50 dark:bg-white/[0.02] border-b border-gray-100 dark:border-gray-800 px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-white dark:bg-gray-800 text-[10px] font-black text-gray-400" x-text="vIndex + 1"></div>
                            <div class="flex items-center gap-2">
                                <template x-for="valId in variation.attribute_values">
                                    <template x-if="availableAttributes.some(a => a.name.toLowerCase() === 'color' && a.values.some(v => v.id == valId))">
                                        <div class="w-4 h-4 rounded-full border border-gray-200 shadow-sm" 
                                            :style="'background-color: ' + (colorMap[availableAttributes.find(a => a.name.toLowerCase() === 'color').values.find(v => v.id == valId).value] || availableAttributes.find(a => a.name.toLowerCase() === 'color').values.find(v => v.id == valId).value)">
                                        </div>
                                    </template>
                                </template>
                                <span class="text-sm font-black text-gray-800 dark:text-white" x-text="variation.display_name"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-success-500"></div>
                                <span class="ml-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider text-gray-400">SKU Identifier</label>
                            <input type="text" :name="'variations['+vIndex+'][sku]'" x-model="variation.sku" required
                                class="h-12 w-full rounded-2xl border border-gray-100 bg-slate-50 px-4 text-xs font-bold outline-none focus:border-brand-500 focus:bg-white dark:border-gray-800 dark:bg-gray-900/50 dark:text-white transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider text-gray-400">Base Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">$</span>
                                <input type="number" step="0.01" :name="'variations['+vIndex+'][price]'" x-model="variation.price" required
                                    class="h-12 w-full rounded-2xl border border-gray-100 bg-slate-50 pl-8 pr-4 text-xs font-black outline-none focus:border-brand-500 focus:bg-white dark:border-gray-800 dark:bg-gray-900/50 dark:text-white transition-all">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider text-success-500">Sale Price (Offer)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">$</span>
                                <input type="number" step="0.01" :name="'variations['+vIndex+'][sale_price]'" x-model="variation.sale_price"
                                    class="h-12 w-full rounded-2xl border border-gray-100 bg-slate-50 pl-8 pr-4 text-xs font-black outline-none focus:border-brand-500 focus:bg-white dark:border-gray-800 dark:bg-gray-900/50 dark:text-white transition-all">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-wider text-gray-400">Current Stock</label>
                            <input type="number" :name="'variations['+vIndex+'][stock_quantity]'" x-model="variation.stock_quantity" required
                                class="h-12 w-full rounded-2xl border border-gray-100 bg-slate-50 px-4 text-xs font-black outline-none focus:border-brand-500 focus:bg-white dark:border-gray-800 dark:bg-gray-900/50 dark:text-white transition-all">
                        </div>
                        
                        <!-- Hidden inputs for ID and attribute value IDs -->
                        <template x-if="variation.id">
                            <input type="hidden" :name="'variations['+vIndex+'][id]'" :value="variation.id">
                        </template>
                        <template x-for="valId in variation.attribute_values">
                            <input type="hidden" :name="'variations['+vIndex+'][attribute_values][]'" :value="valId">
                        </template>
                    </div>
                </div>
            </template>
            
            <div x-show="variations.length === 0" class="flex flex-col items-center justify-center py-20 text-center text-gray-400 bg-slate-50/50 dark:bg-white/[0.02] rounded-[3rem] border border-dashed border-gray-200 dark:border-gray-800 animate-pulse">
                <div class="h-20 w-20 rounded-full bg-white dark:bg-gray-800 shadow-xl flex items-center justify-center mb-6">
                    <svg class="h-10 w-10 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h4 class="text-xl font-black text-gray-800 dark:text-white/80 uppercase tracking-tighter">Ready to Generate</h4>
                <p class="text-sm mt-2 px-12 max-w-lg mx-auto text-gray-500">Pick your attributes above (like Color and Size) and click the generate button to create unique stock items.</p>
            </div>
        </div>
    </div>
</div>
