@props(['vendor', 'errors'])

<div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
    <div
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">Vendor Identity</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Core information about the vendor and account owner.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Owner Info -->
            <div>
                <x-form.label for="owner_name" required>Owner Name</x-form.label>
                <x-form.input id="owner_name" name="owner_name" :value="old('owner_name', $vendor->user->name ?? '')" placeholder="e.g. John Doe"
                    :error="$errors->first('owner_name')" required />
            </div>

            <div>
                <x-form.label for="owner_email" required>Owner Email</x-form.label>
                <x-form.input id="owner_email" name="owner_email" type="email" :value="old('owner_email', $vendor->user->email ?? '')"
                    placeholder="e.g. john@example.com" :error="$errors->first('owner_email')" required />
            </div>

            <div>
                <x-form.label for="password" :required="!$vendor->exists">
                    {{ $vendor->exists ? 'Update Password (Leave blank to keep current)' : 'Account Password' }}
                </x-form.label>
                <x-form.input id="password" name="password" type="password" placeholder="••••••••" :error="$errors->first('password')"
                    :required="!$vendor->exists" />
            </div>

            <div>
                <x-form.label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Featured
                    Placement</x-form.label>
                <label
                    class="flex items-center gap-3 h-12 px-4 cursor-pointer rounded-2xl border border-gray-100 bg-slate-50 transition-all hover:bg-gray-100 dark:border-gray-800 dark:bg-white/5">
                    <input type="checkbox" name="is_featured" value="1"
                        {{ old('is_featured', $vendor->is_featured) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500 dark:bg-gray-800 dark:border-gray-700">
                    <span class="text-sm font-bold text-gray-600 dark:text-gray-400">Mark as Featured Vendor</span>
                </label>
            </div>

            <hr class="lg:col-span-2 border-gray-100 dark:border-gray-800 my-2">

            <!-- Store Info -->
            <div>
                <x-form.label for="store_name" required>Store Name</x-form.label>
                <x-form.input id="store_name" name="store_name" :value="old('store_name', $vendor->store_name)" placeholder="e.g. Acme Supplies"
                    :error="$errors->first('store_name')" required />
            </div>

            <div>
                <x-form.label for="slug">Store Slug (Auto-generated)</x-form.label>
                <x-form.input id="slug" :value="old('slug', $vendor->slug)" placeholder="e.g. acme-supplies" disabled readonly
                    class="opacity-60 cursor-not-allowed bg-gray-100 dark:bg-gray-800" :error="$errors->first('slug')" />
            </div>
        </div>
    </div>
</div>
