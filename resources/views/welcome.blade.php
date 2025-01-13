<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Five Minute Band - Welcome</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Header Section -->
            <div class="mb-12">
                <h1 class="text-5xl font-bold text-gray-800 mb-4">Welcome to Five Minute Band</h1>
                
                <!-- CTA Buttons -->
                <div class="space-x-4">
                    <a href="{{ route('signup') }}" 
                       class="inline-block bg-emerald-600 text-white font-semibold px-8 py-4 rounded-lg text-lg hover:bg-emerald-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                        Sign Up
                    </a>
                    <a href="{{ route('match') }}" 
                       class="inline-block bg-indigo-600 text-white font-semibold px-8 py-4 rounded-lg text-lg hover:bg-indigo-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                        Band Matcher 2000
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>