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
                        toggleAll: false,
                        toggleAllCheckboxes() {
                            this.selectedParticipants = this.toggleAll ? @json($participants->pluck('id')) : [];
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
                             class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">
                                    <span x-text="selectedParticipants.length"></span> participants selected
                                </span>
                                <div class="space-x-2">
                                    <form x-data method="POST" action="{{ route('admin.participants.bulk-update') }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <template x-for="id in selectedParticipants" :key="id">
                                            <input type="hidden" name="participant_ids[]" :value="id">
                                        </template>
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-emerald-600 text-white text-sm font-medium rounded hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                                            Activate
                                        </button>
                                    </form>
                                    <form x-data method="POST" action="{{ route('admin.participants.bulk-update') }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <template x-for="id in selectedParticipants" :key="id">
                                            <input type="hidden" name="participant_ids[]" :value="id">
                                        </template>
                                        <input type="hidden" name="is_active" value="0">
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                            Deactivate
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" 
                                                    x-model="toggleAll"
                                                    @change="toggleAllCheckboxes()"
                                                    class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                                            </label>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Instruments
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Other
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Joined
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($participants as $participant)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" 
                                                        value="{{ $participant->id }}"
                                                        x-model="selectedParticipants"
                                                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                                                </label>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $participant->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ implode(', ', $participant->instruments) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $participant->other }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $participant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $participant->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $participant->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button 
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'edit-participant-{{ $participant->id }}')"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>

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
                                                            <div class="space-y-2">
                                                                @foreach(['Guitar', 'Bass', 'Drums', 'Vocals', 'Keys'] as $instrument)
                                                                    <label class="inline-flex items-center mr-6">
                                                                        <input
                                                                            type="checkbox"
                                                                            name="instruments[]"
                                                                            value="{{ $instrument }}"
                                                                            {{ in_array($instrument, $participant->instruments) ? 'checked' : '' }}
                                                                            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                                                                        >
                                                                        <span class="ml-2 text-sm text-gray-600">{{ $instrument }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                            <x-input-error :messages="$errors->get('instruments')" class="mt-2" />
                                                        </div>

                                                        <div>
                                                            <x-input-label for="other" value="Other Information" class="text-sm font-medium" />
                                                            <textarea
                                                                id="other"
                                                                name="other"
                                                                class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                                rows="3"
                                                            >{{ old('other', $participant->other) }}</textarea>
                                                            <x-input-error :messages="$errors->get('other')" class="mt-2" />
                                                        </div>

                                                        <div>
                                                            <input type="hidden" name="is_active" value="0">
                                                            <label class="inline-flex items-center">
                                                                <input
                                                                    type="checkbox"
                                                                    name="is_active"
                                                                    value="1"
                                                                    {{ $participant->is_active ? 'checked' : '' }}
                                                                    class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                                                                >
                                                                <span class="ml-2 text-sm font-medium text-gray-600">Active</span>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
