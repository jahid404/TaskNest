<x-common.component-card title="Select Inputs">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Searchable Select
        </label>
        <x-form.select 
            name="demo_select" 
            :options="[
                ['value' => 'marketing', 'label' => 'Marketing'],
                ['value' => 'template', 'label' => 'Template'],
                ['value' => 'development', 'label' => 'Development'],
            ]"
            placeholder="Select Option"
        />
    </div>

    <div>
        <x-form.select 
            name="demo_multiple_select" 
            placeholder="Search multiple options..."
            multiple
            :selected="[1, 3]"
            :options="[
                ['value' => 1, 'label' => 'Option 1'],
                ['value' => 2, 'label' => 'Option 2'],
                ['value' => 3, 'label' => 'Option 3'],
                ['value' => 4, 'label' => 'Option 4'],
                ['value' => 5, 'label' => 'Option 5'],
            ]"
        />
    </div>
</x-common.component-card>
