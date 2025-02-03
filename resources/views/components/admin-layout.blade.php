<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="font-bold text-xl">
                                Admin Panel
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-indigo-400' : 'border-transparent' }} text-sm font-medium leading-5 {{ request()->routeIs('admin.dashboard') ? 'text-gray-900' : 'text-gray-500' }} hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.users') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.users') ? 'border-indigo-400' : 'border-transparent' }} text-sm font-medium leading-5 {{ request()->routeIs('admin.users') ? 'text-gray-900' : 'text-gray-500' }} hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Users
                            </a>
                            <a href="{{ route('admin.participants') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.participants') ? 'border-indigo-400' : 'border-transparent' }} text-sm font-medium leading-5 {{ request()->routeIs('admin.participants') ? 'text-gray-900' : 'text-gray-500' }} hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Participants
                            </a>
                            <a href="{{ route('admin.matches') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.matches') ? 'border-indigo-400' : 'border-transparent' }} text-sm font-medium leading-5 {{ request()->routeIs('admin.matches') ? 'text-gray-900' : 'text-gray-500' }} hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Matches
                            </a>
                            <a href="{{ route('match') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('match') ? 'border-indigo-400' : 'border-transparent' }} text-sm font-medium leading-5 {{ request()->routeIs('match') ? 'text-gray-900' : 'text-gray-500' }} hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Create Match
                            </a>
                            <a href="{{ route('signup') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('signup') ? 'border-indigo-400' : 'border-transparent' }} text-sm font-medium leading-5 {{ request()->routeIs('signup') ? 'text-gray-900' : 'text-gray-500' }} hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Band Signup
                            </a>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="ml-3 relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
