<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('client.pet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="container">
                            <x-table>
                                <x-slot name="header">
                                    <x-table-th></x-table-th>
                                    <x-table-th></x-table-th>
                                </x-slot>
                                <x-table-row-hover>
                                    <x-table-column-medium>ID</x-table-column-medium>
                                    <x-table-column-medium>{{ $pet['id'] }}</x-table-column-medium>
                                </x-table-row-hover>
                                <x-table-row-hover>
                                    <x-table-column-medium>{{ __('client.pet_alias') }}</x-table-column-medium>
                                    <x-table-column>{{ $pet['alias'] }}</x-table-column>
                                </x-table-row-hover>
                                <x-table-row-hover>
                                    <x-table-column-medium>{{ __('client.pet_type') }}</x-table-column-medium>
                                    <x-table-column>{{ $pet['type']['title'] }}</x-table-column>
                                </x-table-row-hover>
                                <x-table-row-hover>
                                    <x-table-column-medium>{{ __('client.breed') }}</x-table-column-medium>
                                    <x-table-column>{{ $pet['breed']['title'] }}</x-table-column>
                                </x-table-row-hover>
                                <x-table-row-hover>
                                    <x-table-column-medium>{{ __('client.owner') }}</x-table-column-medium>
                                    <x-table-column>{{ $pet['owner']['last_name'] . ' ' . $pet['owner']['first_name'] . ' ' . $pet['owner']['middle_name'] }}</x-table-column>
                                </x-table-row-hover>
                            </x-table>
                            
                            <br/>
                            <x-button-link href="{{route('clients.show', [$pet['owner']['id'] ] )}}" class="float-right">{{ __('client.all_pets') }}</x-button-link>
                            </br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
