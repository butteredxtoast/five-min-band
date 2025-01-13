<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Band Matcher 2000 - Five Minute Band</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Home Button -->
    <div class="absolute top-4 left-4">
        <a href="{{ route('welcome') }}" 
           class="inline-block bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
            ← Home
        </a>
    </div>

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold text-gray-800">You've made it to the match page!</h1>
        </div>
    </div>
</body>
</html>