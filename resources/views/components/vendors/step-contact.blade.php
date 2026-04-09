@props(['vendor', 'errors'])

<div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
    <div
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">Contact Details</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Business contact details and location.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
                <x-form.label for="phone">Business Phone</x-form.label>
                <x-form.input id="phone" name="phone" :value="old('phone', $vendor->phone)"
                    placeholder="+880 1234 567890" :error="$errors->first('phone')" />
            </div>

            <div>
                <x-form.label for="email">Business Email (Optional)</x-form.label>
                <x-form.input id="email" name="email" type="email" :value="old('email', $vendor->email)"
                    placeholder="e.g. support@acme.com" :error="$errors->first('email')" />
            </div>

            <div class="lg:col-span-2">
                <x-form.label for="address">Business Address</x-form.label>
                <textarea id="address" name="address" rows="3" 
                    placeholder="Full business address..."
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:focus:ring-brand-400 dark:focus:border-brand-400 {{ $errors->first('address') ? 'border-red-500' : '' }}">{{ old('address', $vendor->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
