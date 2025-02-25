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

                <div x-data="{ musician_count: 3 }">
                    <form method="POST" action="{{ route('admin.bands.generate') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="musician_count" class="block text-sm font-medium text-gray-700">Number of Musicians in Band</label>
                            <div class="mt-2 flex items-center space-x-4">
                                <input type="number"
                                       name="musician_count"
                                       id="musician_count"
                                       x-model="musician_count"
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

                        <div class="flex items-center space-x-2">
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

                    @if(session('band'))
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Generated Band Match</h2>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="font-medium text-gray-900 mb-4">Your Five Minute Band</h3>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    @foreach(session('band')->musicians as $musician)
                                        <li>
                                            {{ $musician->name }} -
                                            @if($musician->pivot->vocalist)
                                                <span class="text-purple-600 font-medium">Vocalist</span>
                                                @if($musician->pivot->instrument)
                                                    and
                                                    <span class="text-blue-600 font-medium">{{ $musician->pivot->instrument }}</span>
                                                @endif
                                            @else
                                                <span class="text-blue-600 font-medium">{{ $musician->pivot->instrument }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                @if(!session('band')->isComplete())
                                    <p class="mt-4 text-amber-600">
                                        <svg class="inline-block w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        This band is incomplete! Missing some required instruments.
                                    </p>
                                @endif

                                <div class="mt-6 text-sm text-gray-500">
                                    Generated at: {{ session('band')->created_at->format('M d, Y g:ia') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
