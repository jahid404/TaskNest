@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Tasks' . ($tasks->total() > 0 ? " ({$tasks->total()})" : '');
    @endphp
    <x-common.page-breadcrumb :pageTitle="$pageTitle" :breadcrumbs="[['title' => 'Home', 'url' => '/'], ['title' => 'Tasks']]" />

    <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div
            class="rounded-3xl border border-gray-100 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">All Tasks</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $taskStats['total'] }}</p>
        </div>
        <div
            class="rounded-3xl border border-gray-100 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
            <p class="mt-2 text-3xl font-bold text-brand-500">{{ $taskStats['in_progress'] }}</p>
        </div>
        <div
            class="rounded-3xl border border-gray-100 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
            <p class="mt-2 text-3xl font-bold text-success-500">{{ $taskStats['completed'] }}</p>
        </div>
        <div
            class="rounded-3xl border border-gray-100 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Overdue</p>
            <p class="mt-2 text-3xl font-bold text-red-500">{{ $taskStats['overdue'] }}</p>
        </div>
    </div>

    <div x-data="{ showFilters: {{ request()->hasAny(['search', 'status', 'priority', 'due', 'sort']) ? 'true' : 'false' }} }" class="mb-8">
        <!-- Action Toolbar -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button @click="showFilters = !showFilters"
                    class="flex h-12 items-center gap-2 rounded-2xl border border-gray-100 bg-white px-6 text-sm font-bold text-gray-700 transition-all hover:bg-gray-50 dark:border-gray-800 dark:bg-white/5 dark:text-gray-300"
                    :class="showFilters ? 'ring-4 ring-brand-500/5 border-brand-500/30' : ''">
                    <svg class="h-4 w-4 transition-transform duration-300" :class="showFilters ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                </button>

                @if (request()->hasAny(['search', 'status', 'priority', 'due', 'sort']))
                    <a href="{{ route('dashboard.tasks.index') }}"
                        class="flex h-12 items-center gap-2 rounded-2xl border border-red-50 bg-red-50 px-6 text-sm font-bold text-red-500 transition-all hover:bg-red-500 hover:text-white dark:border-red-500/10 dark:bg-red-500/5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear Filters
                    </a>
                @endif
            </div>

            <a href="{{ route('dashboard.tasks.modify') }}"
                class="flex h-12 items-center gap-2 rounded-2xl bg-brand-500 px-8 text-sm font-bold text-white shadow-lg shadow-brand-500/20 transition-all hover:bg-brand-600 hover:shadow-brand-500/40">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Task
            </a>
        </div>

        <!-- Filter Panel -->
        <div x-show="showFilters" x-collapse x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="rounded-3xl border border-gray-100 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <form action="{{ route('dashboard.tasks.index') }}" method="GET">
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                    <div class="space-y-2 xl:col-span-3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400">Search</label>
                        <div class="relative group">
                            <span
                                class="absolute left-4 top-1/2 -mt-2.5 text-gray-400 transition-colors group-focus-within:text-brand-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search tasks..."
                                class="h-12 w-full rounded-2xl border border-gray-100 bg-slate-50 pl-11 pr-4 text-sm font-medium outline-none transition-all placeholder:text-gray-400 focus:border-brand-500/50 focus:bg-white focus:ring-4 focus:ring-brand-500/5 dark:border-gray-800 dark:bg-white/5 dark:text-white dark:focus:border-brand-500/50">
                        </div>
                    </div>

                    <div class="space-y-2 xl:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400">Status</label>
                        <x-form.select name="status" :options="collect($statusOptions)
                            ->map(fn($label, $value) => ['value' => $value, 'label' => $label])
                            ->values()
                            ->all()" :selected="request('status')" placeholder="Any Status"
                            onchange="this.closest('form').submit()" />
                    </div>

                    <div class="space-y-2 xl:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400">Priority</label>
                        <x-form.select name="priority" :options="collect($priorityOptions)
                            ->map(fn($label, $value) => ['value' => $value, 'label' => $label])
                            ->values()
                            ->all()" :selected="request('priority')" placeholder="Any Priority"
                            onchange="this.closest('form').submit()" />
                    </div>

                    <div class="space-y-2 xl:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400">Timeline</label>
                        <x-form.select name="due" :options="[
                            ['value' => 'overdue', 'label' => 'Overdue'],
                            ['value' => 'today', 'label' => 'Due Today'],
                            ['value' => 'upcoming', 'label' => 'Upcoming'],
                            ['value' => 'undated', 'label' => 'No Due Date'],
                        ]" :selected="request('due')" placeholder="Any Timeline"
                            onchange="this.closest('form').submit()" />
                    </div>

                    <div class="space-y-2 xl:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400">Sort By</label>
                        <x-form.select name="sort" :options="[
                            ['value' => 'latest', 'label' => 'Latest First'],
                            ['value' => 'due_soon', 'label' => 'Due Soon'],
                            ['value' => 'priority', 'label' => 'Priority'],
                            ['value' => 'oldest', 'label' => 'Oldest First'],
                        ]" :selected="request('sort', 'latest')"
                            onchange="this.closest('form').submit()" />
                    </div>

                    <div class="space-y-2 xl:col-span-1">
                        <label class="invisible block text-xs font-bold uppercase tracking-wider">&nbsp;</label>
                        <button type="submit"
                            class="flex h-12 w-full items-center justify-center rounded-2xl bg-brand-500 text-white shadow-lg shadow-brand-500/20 transition-all hover:bg-brand-600 hover:shadow-brand-500/40">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
            @forelse($tasks as $task)
                <div
                    class="group relative flex flex-col overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-theme-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-gray-800 dark:bg-white/[0.03]">

                    <!-- Icon Section -->
                    <div class="relative h-40 w-full flex items-center justify-center p-8 bg-gray-50 dark:bg-white/5">
                        <div class="flex flex-col items-center justify-center">
                            @php
                                $statusIcon = match ($task->status) {
                                    'completed'
                                        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                    'in_progress'
                                        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                    default
                                        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                };
                                $statusColor = match ($task->status) {
                                    'completed' => 'text-success-500',
                                    'in_progress' => 'text-brand-500',
                                    default => 'text-warning-500',
                                };
                            @endphp
                            <svg class="h-16 w-16 {{ $statusColor }}" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                {!! $statusIcon !!}
                            </svg>
                        </div>

                        <!-- Badges -->
                        <div class="absolute inset-x-0 top-0 flex items-start justify-end p-4">
                            @php
                                $statusBadgeClass = match ($task->status) {
                                    'completed' => 'bg-success-500/90',
                                    'in_progress' => 'bg-brand-500/90',
                                    default => 'bg-warning-500/90',
                                };
                            @endphp
                            <span
                                class="rounded-full {{ $statusBadgeClass }} px-3 py-1 text-[10px] font-bold text-white shadow-lg backdrop-blur-sm">
                                {{ strtoupper($task->status_label) }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="flex flex-1 flex-col p-6">
                        <div class="mb-4">
                            <h3
                                class="text-lg font-bold text-gray-900 transition-colors group-hover:text-brand-500 dark:text-white/90">
                                {{ $task->name }}
                            </h3>
                            <p class="mt-2 text-xs text-gray-500 line-clamp-2 dark:text-gray-400">
                                {{ $task->description ?: 'No description provided.' }}
                            </p>
                        </div>

                        @php
                            $priorityBadgeClass = match ($task->priority) {
                                'high' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
                                'medium' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                default
                                    => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                            };
                        @endphp

                        <div class="mb-4 flex flex-wrap gap-2">
                            <span class="rounded-full px-3 py-1 text-[11px] font-bold {{ $priorityBadgeClass }}">
                                {{ $task->priority_label }} Priority
                            </span>

                            @if ($task->due_date)
                                <span
                                    class="rounded-full px-3 py-1 text-[11px] font-bold {{ $task->is_overdue ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400' : 'bg-slate-100 text-slate-600 dark:bg-white/5 dark:text-gray-300' }}">
                                    {{ $task->is_overdue ? 'Overdue' : 'Due' }} {{ $task->due_date->format('M d, Y') }}
                                </span>
                            @else
                                <span
                                    class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600 dark:bg-white/5 dark:text-gray-300">
                                    No due date
                                </span>
                            @endif
                        </div>

                        <div class="mb-4 space-y-1 text-xs text-gray-500 dark:text-gray-400">
                            <p>Created {{ $task->created_at->diffForHumans() }}</p>
                            @if ($task->completed_at)
                                <p>Completed {{ $task->completed_at->diffForHumans() }}</p>
                            @endif
                        </div>

                        <!-- Footer Section -->
                        <div class="mt-auto flex items-center gap-2 pt-4 border-t border-gray-50 dark:border-gray-800/50">
                            <a href="{{ route('dashboard.tasks.modify', $task->id) }}"
                                class="flex h-10 flex-1 items-center justify-center gap-2 rounded-xl border border-gray-100 bg-slate-50 text-sm font-bold text-slate-600 transition-all hover:bg-slate-100 hover:text-slate-900 dark:border-gray-800 dark:bg-white/5 dark:text-gray-400">
                                Edit
                            </a>
                            <button type="button"
                                @click="$dispatch('open-delete-modal', { url: '{{ route('dashboard.tasks.destroy', $task) }}', name: '{{ $task->name }}' })"
                                class="flex h-10 w-10 items-center justify-center rounded-xl border border-red-50/50 bg-red-50/30 text-red-500 transition-all hover:bg-red-500 hover:text-white dark:border-red-500/10 dark:bg-red-500/5">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full flex flex-col items-center justify-center rounded-3xl border border-gray-200 bg-white p-12 text-center dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">No tasks found</h3>
                    <p class="mt-2 max-w-md text-sm text-gray-500 dark:text-gray-400">
                        Try adjusting your filters or create a task with a clear owner-facing title, priority, and due date.
                    </p>
                    <a href="{{ route('dashboard.tasks.modify') }}"
                        class="mt-6 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white">Add First Task</a>
                </div>
            @endforelse

            @if ($tasks->isNotEmpty())
                <a href="{{ route('dashboard.tasks.modify') }}"
                    class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-gray-200 p-6 hover:border-brand-500 hover:bg-brand-50/50 dark:border-gray-800">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="mt-4 text-sm font-medium text-gray-600 dark:text-gray-400">Add New Task</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-ui.delete-modal title="Delete Task"
        message="Are you sure you want to delete this task? This action cannot be undone." />

    <div class="mt-8">
        <x-ui.pagination :paginator="$tasks" />
    </div>
@endsection
