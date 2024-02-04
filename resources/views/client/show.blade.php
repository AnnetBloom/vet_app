<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark-blue-300 leading-tight">
            {{ __('client.client') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <x-button-link href="{{route('pets.create', ['client_id' => $client['id'] ] )}}" class="float-right mx-5">{{ __('client.add_pet') }}</x-button-link>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="container">

                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('client.show_client')}}
                                </h2>
                            </header>

                            <x-table>
                                <x-slot name="header">
                                    <x-table-th></x-table-th>
                                    <x-table-th></x-table-th>
                                </x-slot>
                                <x-table-row-hover>
                                    <x-table-column-medium>ID</x-table-column-medium>
                                    <x-table-column-medium>{{ $client['id'] }}</x-table-column-medium>
                                </x-table-row-hover>
                                <x-table-row-hover>
                                    <x-table-column-medium>{{ __('client.last_name') }}</x-table-column-medium>
                                    <x-table-column>{{ $client['last_name'] }}</x-table-column>
                                </x-table-row-hover>
                                <x-table-row-hover>
                                    <x-table-column-medium>{{ __('client.first_name') }}</x-table-column-medium>
                                    <x-table-column>{{ $client['first_name'] }}</x-table-column>
                                </x-table-row-hover>
                                <x-table-row-hover>
                                    <x-table-column-medium>{{ __('client.middle_name') }}</x-table-column-medium>
                                    <x-table-column>{{ $client['middle_name'] }}</x-table-column>
                                </x-table-row-hover>
                                <x-table-row-hover>
                                    <x-table-column-medium>{{ __('client.city') }}</x-table-column-medium>
                                    <x-table-column> @if (isset($client['city_data']['title']))  {{ $client['city_data']['title'] }} @elseif ($client['city'] !== '') {{ $client['city'] }} @endif </x-table-column>
                                </x-table-row-hover>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-2 text-gray-900">
                            <div class="container">

                                @include('notifications')
                            
                                <div class="p-6 sm:p-2 bg-dark-blue-300 shadow sm:rounded-lg">
                                    <div class="max-w-xl">
                                        <h2 class="font-semibold text-xl text-white leading-tight">
                                            {{ __('client.pets') }}
                                        </h2>
                                    </div>
                                    <div class="flex flex-row-reverse ...">
                                        <img class="block px-1 w-[50px] h-[30px] w-auto fill-current" src="/images/pet.png" alt="pet_logo"/>
                                    </div>
                                </div>
                                
                                <x-table>
                                    <x-slot name="header">
                                        <x-table-th>{{ __('client.pet_alias') }}</x-table-th>
                                        <x-table-th>{{ __('client.pet_type') }}</x-table-th>
                                        <x-table-th>{{ __('client.breed') }}</x-table-th>
                                        <x-table-th></x-table-th>
                                    </x-slot>
                                    @foreach ($pets as $pet)
                                        <x-table-row-hover>
                                            <x-table-column>{{ $pet['alias'] }}</x-table-column>
                                            <x-table-column>{{ $pet['type']['title'] }}</x-table-column>
                                            <x-table-column>{{ $pet['breed']['title'] }}</x-table-column>
                                            <x-table-column>
                                                <x-icon-view href="{{ route('pets.show', [ $pet['id'], 'client_id' => $client['id'] ]) }}" atr="{{ __('client.view')}}"></x-icon-view>
                                            </x-table-column>
                                            <x-table-column>
                                                <x-icon-edit href="{{ route('pets.edit', [ $pet['id'] ]) }}" atr="{{ __('client.edit')}}"></x-icon-edit>
                                            </x-table-column>
                                            <x-table-column>
                                                <x-icon-delete
                                                    x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', { name: 'confirm-deletion', action: '{{route('pets.destroy', [ $pet['id'] ])}}' }  )"
                                                    href=""
                                                    atr="{{ __('main.delete')}}" >
                                                </x-icon-delete>
                                            </x-table-column>
                                        </x-table-row-hover>
                                    @endforeach
                                </x-table>
                                
                                @include('modal_window', ['modal_message' => 'client.want_delete_pet' ] )
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>