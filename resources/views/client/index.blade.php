<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark-blue-300 leading-tight">
            {{ __('client.clients') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-dark-blue-300 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('search_form')
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">

                        @include('notifications')

                        @if (session('status') && session('status') === 'deleted')
                            <br>
                            <x-success-alert>
                                {{ __('main.excellent')}}
                                <x-slot name="message">{{ __('main.deleted') }}</x-slot>
                            </x-success-alert>
                        @endif

                        <br><br>
                        {{ $clients->links() }}

                        @if ($clients)
                            <x-table>
                                <x-slot name="header">
                                    <x-table-th>ID</x-table-th>
                                    <x-table-th>{{ __('client.last_name') }}</x-table-th>
                                    <x-table-th>{{ __('client.first_name') }}</x-table-th>
                                    <x-table-th>{{ __('client.middle_name') }}</x-table-th>
                                    <x-table-th>{{ __('client.city') }}</x-table-th>
                                    <x-table-th col="3"></x-table-th>
                                </x-slot>
                                @foreach ($clients as $user)
                                    <x-table-row-hover>
                                        <x-table-column-medium>{{$user['id']}}</x-table-column-medium>
                                        <x-table-column>{{$user['last_name']}}</x-table-column>
                                        <x-table-column>{{$user['first_name']}}</x-table-column>
                                        <x-table-column>{{$user['middle_name']}}</x-table-column>
                                        <x-table-column>{{ isset($user['city_data']['title']) ? $user['city_data']['title'] : ''}}</x-table-column>
                                        <x-table-column>
                                            <x-icon-view href="{{ route('clients.show', ['client' => $user['id'] ]) }}" atr="{{ __('main.view')}}"></x-icon-view>
                                        </x-table-column>
                                        <x-table-column>
                                            <x-icon-edit href="{{ route('clients.edit', ['client' => $user['id'] ]) }}" atr="{{ __('main.edit')}}"></x-icon-edit>
                                        </x-table-column>
                                        <x-table-column>
                                            <x-icon-delete
                                                x-data=""
                                                x-on:click.prevent="$dispatch('open-modal', { name: 'confirm-deletion', action: '{{route('clients.destroy', [ $user['id'] ])}}' }  )"
                                                href=""
                                                atr="{{ __('main.delete')}}" >
                                            </x-icon-delete>
                                        </x-table-column>
                                    </x-table-row-hover>
                                @endforeach
                            </x-table>
                        @else 
                            <p class="justify-center my-6 text-center text-sm text-gray-500">{{ __('client.clients_empty') }}</p>
                        @endif
                        <br>
                        {{ $clients->links() }}

                        @include('modal_window', [ 'modal_message' => 'client.want_delete_client'] )
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>