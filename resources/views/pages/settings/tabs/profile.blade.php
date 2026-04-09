<div x-show="activeTab === 'profile'" class="space-y-6">
    <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="space-y-3">
            <x-form.label>Profile Photo</x-form.label>
            <div class="relative h-32 w-32 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                <template x-if="photoPreview">
                    <img :src="photoPreview" class="h-full w-full object-cover" />
                </template>
                <template x-if="!photoPreview">
                    <img src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : '/images/user/blank-user.png' }}"
                        class="h-full w-full object-cover" />
                </template>
                <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity hover:opacity-100">
                    <label for="photo" class="cursor-pointer text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                </div>
            </div>
            <input type="file" name="photo" id="photo" class="hidden" @change="handlePhotoChange" accept="image/*" />
            <p class="text-xs text-gray-500">Recommended: Square, JPG/PNG, Max 2MB</p>
            @error('photo')
                <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-form.label for="name">Full Name</x-form.label>
                <x-form.input id="name" name="name" :value="old('name', auth()->user()->name)" required />
                @error('name')
                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <x-form.label for="email">E-mail</x-form.label>
                <x-form.input type="email" id="email" name="email" :value="old('email', auth()->user()->email)" required />
                @error('email')
                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <x-ui.button type="submit">Save Changes</x-ui.button>
        </div>
    </form>
</div>
