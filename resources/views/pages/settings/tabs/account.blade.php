<div x-show="activeTab === 'account'" style="display: none;">
    <form action="{{ route('settings.password.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-form.label for="current_password">Current Password</x-form.label>
                <x-form.input type="password" id="current_password" name="current_password" required />
                @error('current_password')
                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-form.label for="password">New Password</x-form.label>
                <x-form.input type="password" id="password" name="password" required />
                @error('password')
                    <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-form.label for="password_confirmation">Confirm New Password</x-form.label>
                <x-form.input type="password" id="password_confirmation" name="password_confirmation"
                    required />
            </div>
        </div>

        <div>
            <x-ui.button type="submit">Update Password</x-ui.button>
        </div>
    </form>
</div>
