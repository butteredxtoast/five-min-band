<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Band') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Band Matcher 2000</h1>
                    <p class="text-gray-600">Generate a random band match from our pool of musicians!</p>
                </div>

                <div x-data="{ bandTypeSelected: 'random', showMusicianCount: true }">
                    <form method="POST" action="{{ route('admin.bands.generate') }}" class="space-y-6">
                        @csrf

                        <!-- Band Type Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Band Type</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <div class="flex items-center">
                                    <input type="radio"
                                           name="band_type"
                                           id="band_type_random"
                                           x-model="bandTypeSelected"
                                           value="random"
                                           @click="showMusicianCount = true"
                                           class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="band_type_random" class="ml-2 block text-sm text-gray-700">
                                        Random
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio"
                                           name="band_type"
                                           id="band_type_punk"
                                           x-model="bandTypeSelected"
                                           value="punk"
                                           @click="showMusicianCount = false"
                                           class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="band_type_punk" class="ml-2 block text-sm text-gray-700">
                                        Punk Band
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio"
                                           name="band_type"
                                           id="band_type_rock"
                                           x-model="bandTypeSelected"
                                           value="rock"
                                           @click="showMusicianCount = false"
                                           class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="band_type_rock" class="ml-2 block text-sm text-gray-700">
                                        Rock Band
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio"
                                           name="band_type"
                                           id="band_type_indie"
                                           x-model="bandTypeSelected"
                                           value="indie"
                                           @click="showMusicianCount = false"
                                           class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="band_type_indie" class="ml-2 block text-sm text-gray-700">
                                        Indie Band
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio"
                                           name="band_type"
                                           id="band_type_jazz"
                                           x-model="bandTypeSelected"
                                           value="jazz"
                                           @click="showMusicianCount = false"
                                           class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="band_type_jazz" class="ml-2 block text-sm text-gray-700">
                                        Jazz Band
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio"
                                           name="band_type"
                                           id="band_type_electronic"
                                           x-model="bandTypeSelected"
                                           value="electronic"
                                           @click="showMusicianCount = false"
                                           class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="band_type_electronic" class="ml-2 block text-sm text-gray-700">
                                        Electronic
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Musician Count (only visible when Random is selected) -->
                        <div x-show="showMusicianCount">
                            <label for="musician_count" class="block text-sm font-medium text-gray-700">Number of Musicians in Band</label>
                            <div class="mt-2 flex items-center space-x-4">
                                <input type="number"
                                       name="musician_count"
                                       id="musician_count"
                                       min="2"
                                       max="10"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       value="3">
                            </div>
                            @error('musician_count')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Band Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   placeholder="Enter band name">
                        </div>

                        <!-- Vocalist option (only available for Random type) -->
                        <div x-show="showMusicianCount" class="flex items-center space-x-2">
                            <input type="checkbox"
                                   name="include_vocalist"
                                   id="include_vocalist"
                                   value="1"
                                   checked
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            <label for="include_vocalist" class="text-sm text-gray-700">Include Vocalist</label>
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

                    @if(session('band_data'))
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Generated Band Match</h2>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="font-medium text-gray-900 mb-4">{{ session('band_data')['name'] ?? 'Your Five Minute Band' }}</h3>

                                @if(isset(session('band_data')['metadata']['band_type']))
                                    <div class="mb-4 inline-block px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-800 rounded-full">
                                        {{ ucfirst(session('band_data')['metadata']['band_type']) }} Band
                                    </div>
                                @endif

                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    @foreach(session('band_data')['musicians'] as $musician)
                                        <li>
                                            {{ $musician['name'] }} -
                                            @if($musician['pivot']['vocalist'])
                                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                                    Vocalist
                                                </span>
                                                @if($musician['pivot']['instrument'])
                                                    {{ 'and' }}
                                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                                        {{ ucfirst($musician['pivot']['instrument']) }}
                                                        @if($musician['pivot']['instrument'] === 'other' && isset($musician['other']))
                                                            ({{ $musician['other'] }})
                                                        @endif
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                                    {{ ucfirst($musician['pivot']['instrument']) }}
                                                    @if($musician['pivot']['instrument'] === 'other' && isset($musician['other']))
                                                        ({{ $musician['other'] }})
                                                    @endif
                                                </span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
