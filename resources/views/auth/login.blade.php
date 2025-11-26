<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'SIKEMAS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="flex min-h-screen items-center justify-center bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 p-4 font-['Instrument_Sans']">

    <!-- Background Decorative Elements -->
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div
            class="absolute left-20 top-20 h-72 w-72 animate-pulse rounded-full bg-green-200 opacity-30 mix-blend-multiply blur-xl">
        </div>
        <div
            class="absolute bottom-20 right-20 h-72 w-72 animate-pulse rounded-full bg-emerald-200 opacity-30 mix-blend-multiply blur-xl delay-1000">
        </div>
    </div>

    <!-- Content -->
    <div class="relative z-10 w-full max-w-md">
        <div class="space-y-6 rounded-2xl border border-green-100 bg-white p-8 shadow-xl">
            <div class="flex flex-col items-center justify-center">
                <div class="flex w-32 items-center justify-center">
                    <img src="{{ asset('images/sikemas-logo-2.png') }}" alt="SIKEMAS Logo">
                </div>
                <p class="mt-2 text-sm text-gray-500">Masukan email dan kata sandi untuk mengakses akun!</p>
            </div>

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}"
                            class="block w-full rounded-lg border border-gray-300 py-3 pl-10 pr-3 outline-none transition-all duration-200 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="block w-full rounded-lg border border-gray-300 py-3 pl-10 pr-3 outline-none transition-all duration-200 focus:border-transparent focus:ring-2 focus:ring-green-500"
                            placeholder="••••••••">
                    </div>
                </div>



                <button type="submit"
                    class="mt-4 w-full transform rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 px-4 py-3 font-medium text-white shadow-lg transition-all duration-200 hover:scale-[1.02] hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:scale-[0.98]">
                    Masuk
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                © {{ date('Y') }} SIKEMAS. Semua hak dilindungi.
            </p>
        </div>
    </div>

</body>

</html>
