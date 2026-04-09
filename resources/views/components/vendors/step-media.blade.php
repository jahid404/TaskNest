@props(['vendor', 'errors'])

<div x-show="step === 4" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
    <div
        class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">Branding & Media</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Store logo and banner images.</p>
        </div>

        <div class="space-y-8">
            <!-- Logo Upload -->
            <div>
                <x-form.label for="logo">Store Logo</x-form.label>
                <div class="mt-2 flex items-center gap-6">
                    <div class="relative group">
                        <img :src="logoUrl || '{{ $vendor->logo ? asset($vendor->logo) : asset('assets/images/placeholder.png') }}'" 
                            class="w-24 h-24 rounded-2xl object-cover border border-gray-200 dark:border-gray-800 shadow-sm"
                            alt="Logo Preview">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <input type="file" id="logo" name="logo" @change="logoPreview" 
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Choose a logo</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Recommended: Square, min 200x200px. PNG, JPG or SVG.</p>
                    </div>
                </div>
                @error('logo')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Banner Upload -->
            <div>
                <x-form.label for="banner">Store Banner</x-form.label>
                <div class="mt-2">
                    <div class="relative group rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800">
                        <img :src="bannerUrl || '{{ $vendor->banner ? asset($vendor->banner) : asset('assets/images/placeholder-banner.png') }}'" 
                            class="w-full h-48 object-cover shadow-sm"
                            alt="Banner Preview">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-white mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-white text-sm font-bold">Change Banner</span>
                            </div>
                        </div>
                        <input type="file" id="banner" name="banner" @change="bannerPreview" 
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Recommended: 1200x400px or similar aspect ratio. Max 4MB.</p>
                </div>
                @error('banner')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
