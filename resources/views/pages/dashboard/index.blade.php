@extends('layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <x-common.page-breadcrumb :pageTitle="'Dashboard'" :breadcrumbs="[['title' => 'Home', 'url' => '/'], ['title' => 'Dashboard']]" />

    <div class="grid grid-cols-1 gap-4 md:gap-6">
        <!-- Welcome Section -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Here's an overview of your platform.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
            <!-- Total Tasks -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Tasks</span>
                        <h3 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white/90">
                            {{ \App\Models\Task::count() }}
                        </h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10">
                        <svg class="h-6 w-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Pending</span>
                        <h3 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white/90">
                            {{ \App\Models\Task::where('status', 'pending')->count() }}
                        </h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-warning-50 dark:bg-warning-500/10">
                        <svg class="h-6 w-6 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- In Progress -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">In Progress</span>
                        <h3 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white/90">
                            {{ \App\Models\Task::where('status', 'in_progress')->count() }}
                        </h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-500/10">
                        <svg class="h-6 w-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Completed</span>
                        <h3 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white/90">
                            {{ \App\Models\Task::where('status', 'completed')->count() }}
                        </h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-success-50 dark:bg-success-500/10">
                        <svg class="h-6 w-6 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recent Tasks</h3>
                <a href="{{ route('dashboard.tasks.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">View All</a>
            </div>
            
            @php
                $recentTasks = \App\Models\Task::latest()->take(5)->get();
            @endphp

            @if($recentTasks->isNotEmpty())
                <div class="space-y-4">
                    @foreach($recentTasks as $task)
                        <div class="flex items-center justify-between rounded-xl border border-gray-50 p-4 transition-colors hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-white/5">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 dark:text-white/90">{{ $task->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $task->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                {{ $task->status === 'completed' ? 'bg-success-50 text-success-600' : ($task->status === 'in_progress' ? 'bg-brand-50 text-brand-600' : 'bg-warning-50 text-warning-600') }}">
                                {{ str_replace('_', ' ', $task->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-gray-400 dark:text-gray-500">
                    <svg class="mb-3 h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-sm">No tasks found. Create your first task to see it here.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
