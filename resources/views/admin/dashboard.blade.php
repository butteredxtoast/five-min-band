<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Users Overview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Users Overview</h3>
                        <p class="text-gray-600">Total Users: {{ $users->count() }}</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.users') }}" class="text-indigo-600 hover:text-indigo-900">View All Users →</a>
                        </div>
                    </div>
                </div>

                <!-- Participants Overview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Participants Overview</h3>
                        <p class="text-gray-600">Total Participants: {{ $participants->count() }}</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.participants') }}" class="text-indigo-600 hover:text-indigo-900">View All Participants →</a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="flex space-x-4">
                            <a href="{{ route('match') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Start Matching
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
