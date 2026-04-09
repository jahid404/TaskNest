@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="$task->exists ? 'Edit Task' : 'Create Task'" :breadcrumbs="[
        ['title' => 'Home', 'url' => '/'],
        ['title' => 'Tasks', 'url' => route('dashboard.tasks.index')],
        ['title' => $task->exists ? 'Edit' : 'Create'],
    ]" />

    <div class="mb-10">
        <form action="{{ $task->exists ? route('dashboard.tasks.save', $task->id) : route('dashboard.tasks.save') }}"
            method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-8">
                <!-- Form Fields -->
                <div class="space-y-6">
                    <div
                        class="rounded-3xl border border-gray-100 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                        <h2 class="mb-6 text-lg font-bold text-gray-900 dark:text-white/90">Task Details</h2>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Name -->
                                <div class="space-y-2">
                                    <label for="name"
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300">Task Title</label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $task->name) }}"
                                        class="h-12 w-full rounded-2xl border border-gray-100 bg-slate-50 px-4 text-sm font-medium outline-none transition-all focus:border-brand-500/50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 dark:border-gray-800 dark:bg-white/5 dark:text-white"
                                        placeholder="Enter task title e.g. Design Homepage">
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="space-y-2">
                                    <label for="status"
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300">Status</label>
                                    <x-form.select name="status" id="status" :options="[
                                        ['value' => 'pending', 'label' => 'Pending'],
                                        ['value' => 'in_progress', 'label' => 'In Progress'],
                                        ['value' => 'completed', 'label' => 'Completed'],
                                    ]" :selected="old('status', $task->status)" required :error="$errors->first('status')" />
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="space-y-2">
                                <label for="description"
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300">Description</label>
                                <textarea id="description" name="description" rows="6"
                                    class="w-full rounded-2xl border border-gray-100 bg-slate-50 p-4 text-sm font-medium outline-none transition-all focus:border-brand-500/50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 dark:border-gray-800 dark:bg-white/5 dark:text-white"
                                    placeholder="Describe this task in detail...">{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="flex h-12 items-center justify-center gap-2 rounded-2xl bg-brand-500 px-8 text-sm font-bold text-white shadow-lg shadow-brand-500/20 transition-all hover:bg-brand-600 hover:shadow-brand-500/40">
                            {{ $task->exists ? 'Update Task' : 'Create Task' }}
                        </button>
                        <a href="{{ route('dashboard.tasks.index') }}"
                            class="flex h-12 items-center justify-center gap-2 rounded-2xl border border-gray-100 bg-white px-8 text-sm font-bold text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-white/5 dark:text-gray-300">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
