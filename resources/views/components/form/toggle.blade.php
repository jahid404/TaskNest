@props(['name', 'value' => 'disabled', 'id' => null])

@php
    $id = $id ?? $name;
    $isChecked = $value === 'active' || $value === 'enabled' || $value === '1' || $value === true;
@endphp

<div x-data="{ 
    enabled: {{ $isChecked ? 'true' : 'false' }},
    toggle() {
        this.enabled = !this.enabled;
        this.$refs.input.value = this.enabled ? 'active' : 'disabled';
    }
}" class="flex items-center">
    <input type="hidden" name="{{ $name }}" x-ref="input" :value="enabled ? 'active' : 'disabled'">
    
    <button type="button" 
        @click="toggle()"
        :class="enabled ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700'"
        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2" 
        role="switch" 
        aria-checked="false">
        <span class="sr-only">Toggle setting</span>
        <span aria-hidden="true" 
            :class="enabled ? 'translate-x-5' : 'translate-x-0'"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
    </button>
</div>
