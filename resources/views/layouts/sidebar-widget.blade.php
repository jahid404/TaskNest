<div class="mx-auto mb-10 w-full max-w-60 rounded-2xl bg-gray-50 px-4 py-5 text-center dark:bg-white/[0.03]">
    <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">
        TaskNest <span
            class="rounded-full bg-gray-200 px-2 py-0.5 text-[10px] text-gray-600 dark:bg-white/10 dark:text-gray-400">v{{ json_decode(file_get_contents(base_path('package.json')))->version ?? '' }}</span>
    </h3>
    <p class="mb-4 text-gray-500 text-theme-sm dark:text-gray-400">
        Task Management Platform
    </p>
</div>
