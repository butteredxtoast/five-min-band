<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Participants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'participant-updated')
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">Participant has been updated.</span>
                </div>
            @endif
            @if (session('status') === 'participants-activated')
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">Selected participants have been activated.</span>
                </div>
            @endif
            @if (session('status') === 'participants-deactivated')
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">Selected participants have been deactivated.</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div x-data="{
                        selectedParticipants: [],
                        toggleAllCheckboxes() {
                            if (this.selectedParticipants.length === {{ count($participants) }}) {
                                this.selectedParticipants = [];
                            } else {
                                this.selectedParticipants = {{ $participants->pluck('id') }};
                            }
                        }
                    }" class="space-y-4">
                        <!-- Bulk Actions -->
                        <div x-show="selectedParticipants.length > 0" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="bg-white px-4 py-3 border-b border-gray-200 sm:px-6 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="text-gray-700" x-text="'Selected ' + selectedParticipants.length + ' participant(s)'"></span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <form method="POST" action="{{ route('admin.participants.bulk-activate') }}">
                                        @csrf
                                        <template x-for="id in selectedParticipants" :key="id">
                                            <input type="hidden" name="participants[]" :value="id">
                                        </template>
                                        <button type="submit"
                                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                                            Activate Selected
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.participants.bulk-deactivate') }}">
                                        @csrf
                                        <template x-for="id in selectedParticipants" :key="id">
                                            <input type="hidden" name="participants[]" :value="id">
                                        </template>
                                        <button type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                            Deactivate Selected
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
                                        :checked="selectedParticipants.length === {{ count($participants) }}">
                                </label>
                            </div>
                            <div class="flex-1 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Name</span>
                            </div>
                            <div class="flex-1 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Instruments</span>
                            </div>
                            <div class="flex-1 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Other</span>
                            </div>
                            <div class="w-24 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</span>
                            </div>
                            <div class="w-32 px-6 py-3">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</span>
                            </div>
                            <div class="w-24 px-6 py-3 text-right">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</span>
                            </div>
                        </div>

                        <!-- Table Body -->
                        <div class="flex flex-col divide-y divide-gray-200">
                            @foreach ($participants as $participant)
                                <div class="flex items-center hover:bg-gray-50">
                                    <div class="w-12 px-6 py-4">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" 
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                value="{{ $participant->id }}"
                                                x-model="selectedParticipants">
                                        </label>
                                    </div>
                                    <div class="flex-1 px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $participant->name }}</div>
                                    </div>
                                    <div class="flex-1 px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ is_array($participant->instruments) ? implode(', ', $participant->instruments) : $participant->instruments }}</div>
                                    </div>
                                    <div class="flex-1 px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $participant->other }}</div>
                                    </div>
                                    <div class="w-24 px-6 py-4">
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 {{ $participant->is_active ? 'text-green-800 bg-green-100' : 'text-red-800 bg-red-100' }} rounded-full">
                                            {{ $participant->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div class="w-32 px-6 py-4">
                                        <div class="text-sm text-gray-500">{{ $participant->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="w-24 px-6 py-4 text-right">
                                        <button 
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-participant-{{ $participant->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Edit
                                        </button>
                                    </div>
                                </div>

                                <x-modal name="edit-participant-{{ $participant->id }}" :show="false" focusable>
                                    <div class="p-8">
                                        <form method="POST" action="{{ route('admin.participants.update', $participant) }}">
                                            @csrf
                                            @method('PUT')

                                            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                                                Edit Participant
                                            </h2>

                                            <div class="space-y-6">
                                                <div>
                                                    <x-input-label for="name" value="Name" class="text-sm font-medium" />
                                                    <x-text-input
                                                        id="name"
                                                        name="name"
                                                        type="text"
                                                        class="mt-2 block w-full"
                                                        :value="old('name', $participant->name)"
                                                        required
                                                    />
                                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label value="Instruments" class="text-sm font-medium mb-2" />
                                                    <div class="grid grid-cols-2 gap-4">
                                                        @foreach($availableInstruments as $instrument)
                                                            <label class="inline-flex items-center">
                                                                <input
                                                                    type="checkbox"
                                                                    name="instruments[]"
                                                                    value="{{ $instrument }}"
                                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                                    {{ is_array($participant->instruments) && in_array($instrument, $participant->instruments) ? 'checked' : '' }}
                                                                >
                                                                <span class="ml-2 text-sm text-gray-600">{{ $instrument }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    <x-input-error :messages="$errors->get('instruments')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="other" value="Other" class="text-sm font-medium" />
                                                    <x-text-input
                                                        id="other"
                                                        name="other"
                                                        type="text"
                                                        class="mt-2 block w-full"
                                                        :value="old('other', $participant->other)"
                                                    />
                                                    <x-input-error :messages="$errors->get('other')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <input type="hidden" name="is_active" value="0">
                                                    <label class="inline-flex items-center">
                                                        <input
                                                            type="checkbox"
                                                            name="is_active"
                                                            value="1"
                                                            {{ old('is_active', $participant->is_active) ? 'checked' : '' }}
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                        >
                                                        <span class="ml-2 text-sm text-gray-600">Active</span>
                                                    </label>
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
