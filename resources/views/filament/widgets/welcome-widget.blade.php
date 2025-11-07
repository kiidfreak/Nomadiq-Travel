<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#C67B52] to-[#C67B52]/80 shadow-sm">
                    <span class="text-white font-serif text-xl font-bold">
                        {{ strtoupper(substr($this->getUserName(), 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $this->getGreeting() }}, {{ $this->getUserName() }}! 👋
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Welcome back to Nomadiq. Ready to create amazing coastal adventures?
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ filament()->getLogoutUrl() }}">
                @csrf
                <button
                    type="submit"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors border border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500"
                    title="Logout"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="hidden sm:inline">Logout</span>
                </button>
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

