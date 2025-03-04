<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Five Minute Band </title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen">
    <div class="hamburger-menu">
        <button class="hamburger-icon" onclick="toggleMenu()">
            &#9776;
        </button>
        <div class="menu-content flex flex-col space-y-4 p-4" id="menuContent" style="display: none;">
            @if(auth()->check())
                <a href="{{ route('admin.dashboard') }}" class="block">Dashboard</a>
                <a href="{{ route('logout') }}" class="block" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="block">Login</a>
            @endif
        </div>
    </div>
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Header Section -->
            <div class="mb-12">
                <h1 class="text-6xl font-bold mb-6">Five Minute Band</h1>
                <!-- CTA Buttons -->
                <div class="space-x-4">
                    <a href="{{ route('signup') }}"
                       class="custom-button inline-block font-semibold text-lg">
                        Join the Band
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
    function toggleMenu() {
        var menu = document.getElementById('menuContent');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }
    </script>
</body>
</html>
