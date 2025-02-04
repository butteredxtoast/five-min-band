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

                <div x-data="{ musicians: 3 }">
                    <form method="POST" action="{{ route('admin.bands.generate') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="musicians" class="block text-sm font-medium text-gray-700">Number of Musicians in Band</label>
                            <div class="mt-2 flex items-center space-x-4">
                                <input type="number" 
                                       name="musicians" 
                                       id="musicians"
                                       x-model="musicians"
                                       min="2"
                                       max="10"
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       value="3">
                                <span class="text-sm text-gray-500">musicians</span>
                            </div>
                            @error('musicians')
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

                    @if(session('band'))
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Generated Band Match</h2>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="font-medium text-gray-900 mb-4">Your Five Minute Band</h3>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    @foreach(session('band') as $musician)
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
</x-admin-layout>