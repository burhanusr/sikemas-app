<header class="flex h-20 items-center justify-between border-b border-green-100 bg-white px-6 shadow-sm">
    <div class="flex items-center space-x-4">
        <!-- Mobile Menu Toggle -->
        <button onclick="toggleMobileMenu()"
            class="text-gray-600 transition-colors hover:text-green-600 focus:outline-none md:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Sidebar Toggle (Desktop) -->
        <button onclick="toggleSidebar()"
            class="hidden text-gray-600 transition-colors hover:text-green-600 focus:outline-none md:block">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Page Title -->
        <h1 class="text-xl font-semibold text-gray-800">
            {{ Auth::user()->organization }}
        </h1>


    </div>

    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        {{-- <button class="relative text-gray-600 transition-colors hover:text-green-600 focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span
                class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-medium text-white shadow-md">3</span>
        </button> --}}

        <!-- User Dropdown -->
        <div class="group relative">
            <button class="flex items-center space-x-3 focus:outline-none">
                <div class="hidden text-right md:block">
                    <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                    <p class="text-xs capitalize text-gray-500">{{ Auth::user()->role }}</p>
                </div>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-emerald-600 shadow-md">
                    <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name, 0, 2) }}</span>
                </div>

                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div
                class="invisible absolute right-0 z-50 mt-2 w-48 rounded-lg border border-green-100 bg-white opacity-0 shadow-xl transition-all duration-200 group-hover:visible group-hover:opacity-100">
                <div class="p-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center rounded-md px-4 py-2 text-sm text-red-600 transition-colors hover:bg-red-50">
                            <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
