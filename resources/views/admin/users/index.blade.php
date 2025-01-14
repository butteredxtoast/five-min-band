<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'user-updated')
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">User has been updated.</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col space-y-4">
                        <!-- Table Header -->
                        <div class="flex bg-gray-50 border-b border-gray-200">
                            <div class="flex-1 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Name</span>
                            </div>
                            <div class="flex-1 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</span>
                            </div>
                            <div class="flex-1 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</span>
                            </div>
                            <div class="w-32 px-6 py-3 text-right">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</span>
                            </div>
                        </div>

                        <!-- Table Body -->
                        <div class="flex flex-col divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <div class="flex items-center hover:bg-gray-50">
                                    <div class="flex-1 px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                    <div class="flex-1 px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    </div>
                                    <div class="flex-1 px-6 py-4">
                                        <div class="text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="w-32 px-6 py-4 text-right">
                                        <button 
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-user-{{ $user->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Edit
                                        </button>
                                    </div>
                                </div>

                                <x-modal name="edit-user-{{ $user->id }}" :show="false" focusable>
                                    <div class="p-8">
                                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                            @csrf
                                            @method('PUT')

                                            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                                                Edit User
                                            </h2>

                                            <div class="space-y-6">
                                                <div>
                                                    <x-input-label for="name" value="Name" class="text-sm font-medium" />
                                                    <x-text-input
                                                        id="name"
                                                        name="name"
                                                        type="text"
                                                        class="mt-2 block w-full"
                                                        :value="old('name', $user->name)"
                                                        required
                                                    />
                                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="email" value="Email" class="text-sm font-medium" />
                                                    <x-text-input
                                                        id="email"
                                                        name="email"
                                                        type="email"
                                                        class="mt-2 block w-full"
                                                        :value="old('email', $user->email)"
                                                        required
                                                    />
                                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="mt-8 flex justify-end space-x-3">
                                                <button type="button" 
                                                    x-on:click="$dispatch('close')"
                                                    class="px-4 py-2 bg-red-50 text-red-700 rounded-lg text-sm font-medium hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                                    Cancel
                                                </button>

                                                <button type="submit"
                                                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                                                    Save Changes
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </x-modal>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
