<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: #CCAAFF;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.5);">
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.5);">
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.5);">
                <div class="max-w-2xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <!--Footer-->
    @include('components.footer')
</x-app-layout>
