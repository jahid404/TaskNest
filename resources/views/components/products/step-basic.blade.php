@props(['product', 'categories', 'brands', 'vendors', 'errors'])

<div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
    <div
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">Basic Information</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Core identity and organization for the product.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Name & Slug -->
            <div class="lg:col-span-2">
                <x-form.label for="name" required>Product Name</x-form.label>
                <x-form.input id="name" name="name" :value="old('name', $product->name)"
                    placeholder="e.g. Premium Wireless Headphones" :error="$errors->first('name')" required />
            </div>

            <div>
                <x-form.label for="slug">Product Slug (Auto-generated)</x-form.label>
                <x-form.input id="slug" name="slug" :value="old('slug', $product->slug)" placeholder="e.g. premium-wireless-headphones" 
                    readonly class="opacity-60 cursor-not-allowed bg-gray-100 dark:bg-gray-800"
                    :error="$errors->first('slug')" />
            </div>

            <!-- Vendor -->
            @unless (auth()->user()->hasRole('vendor'))
                <div>
                    <x-form.label for="vendor_id" required>Vendor / Store</x-form.label>
                    <x-form.select 
                        name="vendor_id" 
                        id="vendor_id"
                        :options="$vendors->map(fn($v) => [
                            'value' => $v->id, 
                            'label' => $v->store_name,
                            'image' => $v->logo ? asset($v->logo) : null
                        ])"
                        :selected="old('vendor_id', $product->vendor_id)"
                        placeholder="Select Vendor"
                        required
                        :error="$errors->first('vendor_id')"
                    />
                </div>
            @endunless

            <!-- Category -->
            <div>
                <x-form.label for="category_id" required>Category</x-form.label>
                <x-form.select 
                    name="category_id" 
                    id="category_id"
                    :options="$categories->map(fn($c) => [
                        'value' => $c->id, 
                        'label' => $c->name,
                        'image' => $c->thumbnail ? asset($c->thumbnail) : null
                    ])"
                    :selected="old('category_id', $product->category_id)"
                    placeholder="Select Category"
                    required
                    :error="$errors->first('category_id')"
                />
            </div>

            <!-- Brand -->
            <div>
                <x-form.label for="brand_id">Brand</x-form.label>
                <x-form.select 
                    name="brand_id" 
                    id="brand_id"
                    :options="$brands->map(fn($b) => [
                        'value' => $b->id, 
                        'label' => $b->name,
                        'image' => $b->logo ? asset($b->logo) : null
                    ])"
                    :selected="old('brand_id', $product->brand_id)"
                    placeholder="Select Brand (Optional)"
                    :error="$errors->first('brand_id')"
                />
            </div>
        </div>
    </div>
</div>
