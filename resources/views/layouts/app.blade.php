<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'SIKEMAS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Header -->
            @include('partials.header')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 z-20 hidden bg-black bg-opacity-50 md:hidden"
        onclick="toggleMobileMenu()"></div>

    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarText = document.querySelectorAll('.sidebar-text');
            const logo = document.getElementById('logo');
            const logoIcon = document.getElementById('logo-icon');
            const orgSection = document.getElementById('organization-section');

            sidebar.classList.toggle('w-72');
            sidebar.classList.toggle('w-20');

            sidebarText.forEach(text => {
                text.classList.toggle('hidden');
            });

            logo.classList.toggle('hidden');
            logoIcon.classList.toggle('hidden');

            if (orgSection) {
                orgSection.classList.toggle('hidden');
            }
        }

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>

</html>
