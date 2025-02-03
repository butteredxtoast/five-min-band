<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Band Matcher 2000 - Five Minute Band</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Dashboard Button -->
    <div class="absolute top-4 left-4">
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-block bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
            ← Dashboard
        </a>
    </div>

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Band Matcher 2000</h1>
                    <p class="text-gray-600">Generate a random band match from our pool of participants!</p>
                </div>

                <div x-data="{ participants: 3 }">
                    <form method="POST" action="{{ route('match.generate') }}" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="participants" class="block text-sm font-medium text-gray-700">Number of Participants in Band</label>
                            <div class="mt-2 flex items-center space-x-4">
                                <input type="number" 
                                       name="participants" 
                                       id="participants"
                                       x-model="participants"
                                       min="2"
                                       max="10"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       value="3">
                                <span class="text-sm text-gray-500">participants</span>
                            </div>
                            @error('participants')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-center">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Generate Band Match
                            </button>
                        </div>
                    </form>

                    @if(session('error'))
                        <div class="mt-8 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('match'))
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Generated Band Match</h2>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="font-medium text-gray-900 mb-4">Your Five Minute Band</h3>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    @foreach(session('match') as $musician)
                                        <li>{{ $musician->name }} ({{ implode(', ', $musician->instruments) }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>