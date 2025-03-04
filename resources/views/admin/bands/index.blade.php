<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bands') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div x-data="{
                        selectedBands: [],
                        toggleAllCheckboxes() {
                            if (this.selectedBands.length === {{ count($bands) }}) {
                                this.selectedBands = [];
                            } else {
                                this.selectedBands = {{ $bands->pluck('id') }};
                            }
                        }
                    }" class="space-y-4">
                        <!-- Bulk Actions -->
                        <div x-show="selectedBands.length > 0"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="bg-white px-4 py-3 border-b border-gray-200 sm:px-6 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="text-gray-700" x-text="'Selected ' + selectedBands.length + ' band(s)'"></span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <form method="POST" action="{{ route('admin.bands.bulk-activate') }}">
                                        @csrf
                                        <template x-for="id in selectedBands" :key="id">
                                            <input type="hidden" name="bands[]" :value="id">
                                        </template>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                                            Set Active
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.bands.bulk-deactivate') }}">
                                        @csrf
                                        <template x-for="id in selectedBands" :key="id">
                                            <input type="hidden" name="bands[]" :value="id">
                                        </template>
                                        <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                            Set Inactive
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.bands.bulk-set-played') }}">
                                        @csrf
                                        <template x-for="id in selectedBands" :key="id">
                                            <input type="hidden" name="bands[]" :value="id">
                                        </template>
                                        <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                                            Mark as Played
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.bands.bulk-set-not-played') }}">
                                        @csrf
                                        <template x-for="id in selectedBands" :key="id">
                                            <input type="hidden" name="bands[]" :value="id">
                                        </template>
                                        <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                            Mark as Not Played
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Table Header -->
                        <div class="flex bg-gray-50 border-b border-gray-200">
                            <div class="w-12 px-6 py-3">
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           x-on:click="toggleAllCheckboxes()"
                                           :checked="selectedBands.length === {{ count($bands) }}">
                                </label>
                            </div>
                            <div class="flex-1 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Band Name</span>
                            </div>
                            <div class="flex-1 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Members</span>
                            </div>
                            <div class="w-32 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</span>
                            </div>
                            <div class="w-32 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Played</span>
                            </div>
                            <div class="w-32 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created</span>
                            </div>
                            <div class="w-24 px-6 py-3 text-right">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</span>
                            </div>
                        </div>

                        <!-- Table Body -->
                        <div class="flex flex-col divide-y divide-gray-200">
                            @foreach ($bands as $band)
                                <div class="flex items-center hover:bg-gray-50">
                                    <div class="w-12 px-6 py-4">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                   value="{{ $band->id }}"
                                                   x-model="selectedBands">
                                        </label>
                                    </div>
                                    <div class="flex-1 px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $band->name }}</div>
                                    </div>
                                    <div class="flex-1 px-6 py-4">
                                        <div class="space-y-1">
                                            @foreach ($band->musicians as $musician)
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm text-gray-900">{{ $musician->name }}</span>
                                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                                        {{ $musician->pivot->instrument }}
                                                    </span>
                                                    @if ($musician->pivot->vocalist)
                                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-purple-800 bg-purple-100 rounded-full">
                                                            Vocalist
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="w-32 px-6 py-4">
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 {{ $band->status === 'active' ? 'text-green-800 bg-green-100' : 'text-gray-800 bg-gray-100' }} rounded-full">
                                            {{ ucfirst($band->status) }}
                                        </span>
                                    </div>
                                    <div class="w-32 px-6 py-4">
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 {{ $band->played ? 'text-green-800 bg-green-100' : 'text-yellow-800 bg-yellow-100' }} rounded-full">
                                            {{ $band->played ? 'Played' : 'Not Played' }}
                                        </span>
                                    </div>
                                    <div class="w-32 px-6 py-4">
                                        <div class="text-sm text-gray-500">{{ $band->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="w-24 px-6 py-4 text-right">
                                        <button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-band-{{ $band->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Edit
                                        </button>
                                    </div>
                                </div>

                                <x-modal name="edit-band-{{ $band->id }}" :show="false" focusable>
                                    <div class="p-8">
                                        <form method="POST" action="{{ route('admin.bands.update', $band) }}">
                                            @csrf
                                            @method('PUT')

                                            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                                                Edit Band
                                            </h2>

                                            <div class="space-y-6">
                                                <div>
                                                    <x-input-label for="name" value="Name" class="text-sm font-medium" />
                                                    <x-text-input
                                                        id="name"
                                                        name="name"
                                                        type="text"
                                                        class="mt-2 block w-full"
                                                        :value="old('name', $band->name)"
                                                        required
                                                    />
                                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                </div>

                                                <div class="mt-4">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox"
                                                               name="played"
                                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                            {{ $band->played ? 'checked' : '' }}
                                                        >
                                                        <span class="ml-2 text-sm text-gray-600">Mark as Played</span>
                                                    </label>
                                                </div>

                                                <div>
                                                    <x-input-label for="status" value="Status" class="text-sm font-medium" />
                                                    <select
                                                        id="status"
                                                        name="status"
                                                        class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    >
                                                        <option value="active" {{ $band->status === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $band->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
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
