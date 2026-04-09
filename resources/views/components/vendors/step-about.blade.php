@props(['vendor', 'errors'])

<div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
    <div
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">About Vendor</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Description and platform-level configurations.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-form.label for="description">Store Description</x-form.label>
                <textarea id="description" name="description" rows="4" 
                    placeholder="Tell something about the store..."
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-brand-400 dark:focus:border-brand-400 {{ $errors->first('description') ? 'border-red-500' : '' }}">{{ old('description', $vendor->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-form.label for="status">Vendor Status</x-form.label>
                <x-form.select 
                    name="status" 
                    id="status"
                    :options="[
                        ['value' => 'pending', 'label' => 'Pending Approval'],
                        ['value' => 'active', 'label' => 'Active'],
                        ['value' => 'suspended', 'label' => 'Suspended'],
                    ]"
                    :selected="old('status', $vendor->status)"
                    :error="$errors->first('status')"
                />
            </div>

            <div>
                <x-form.label for="commission_rate" required>Commission Rate (%)</x-form.label>
                <x-form.input id="commission_rate" name="commission_rate" type="number" step="0.01" 
                    :value="old('commission_rate', $vendor->commission_rate ?? 0)"
                    placeholder="e.g. 10" :error="$errors->first('commission_rate')" required />
            </div>
        </div>
    </div>
</div>
