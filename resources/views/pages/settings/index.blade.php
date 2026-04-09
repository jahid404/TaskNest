@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Settings'" :breadcrumbs="[['title' => 'Home', 'url' => '/'], ['title' => 'Settings']]" />

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
        <div x-data="{
            activeTab: '{{ session('activeTab', $errors->hasAny(['current_password', 'password', 'password_confirmation']) ? 'account' : 'profile') }}',
            init() {
                const hash = window.location.hash.replace('#', '');
                if (['profile', 'account', 'notifications', 'store', 'payment', 'courier'].includes(hash)) {
                    this.activeTab = hash;
                }
                window.addEventListener('hashchange', () => {
                    const newHash = window.location.hash.replace('#', '');
                    if (['profile', 'account', 'notifications', 'store', 'payment', 'courier'].includes(newHash)) {
                        this.activeTab = newHash;
                    }
                });
            },
            changeTab(tab) {
                this.activeTab = tab;
                window.location.hash = tab;
            },
            photoPreview: null,
            handlePhotoChange(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.photoPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }">
            <!-- Tabs Navigation -->
            <div class="mb-6 flex flex-wrap gap-x-6 border-b border-gray-200 dark:border-gray-800">
                <button @click="changeTab('profile')"
                    :class="activeTab === 'profile' ? 'border-brand-500 text-brand-500 dark:border-white dark:text-white' :
                        'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="border-b-2 pb-4 text-sm font-medium transition-colors">
                    <div class="flex items-center gap-2 [&_svg]:size-5">
                        {!! \App\Helpers\MenuHelper::getIconSvg('user-profile') !!}
                        Profile
                    </div>
                </button>
                <button @click="changeTab('account')"
                    :class="activeTab === 'account' ? 'border-brand-500 text-brand-500 dark:border-white dark:text-white' :
                        'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                    class="border-b-2 pb-4 text-sm font-medium transition-colors">
                    <div class="flex items-center gap-2 [&_svg]:size-5">
                        {!! \App\Helpers\MenuHelper::getIconSvg('account') !!}
                        Account
                    </div>
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="mt-6">
                @include('pages.settings.tabs.profile')
                @include('pages.settings.tabs.account')
            </div>

        </div>
    </div>
@endsection
