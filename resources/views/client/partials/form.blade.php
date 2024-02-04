<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            @isset ($id) {{ __('client.edit_client')}} @else {{ __('client.add_client')}} @endisset
        </h2>
    </header>

    @include('notifications')
    
    @if ($errors->any())
        <br>
        <p class="text-sm text-gray-600">
            <x-errors-alert>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </x-errors-alert>
        </p>
    @endif
    
    <form method="post" action="@isset ($id) {{ route('clients.update', [$id]) }} @else {{ route('clients.store') }} @endisset" class="mt-6 space-y-6">
        @csrf
        @isset ($id) @method('PUT') @endisset

        <div>
            <x-input-label for="first_name" :value="__('client.first_name')" :messages="$errors->get('first_name')" />
            <x-text-input id="first_name" name="first_name" type="text" :messages="$errors->get('first_name')" class="mt-1 block w-full" :value="old('first_name', $client['first_name'] ?? null)"  autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>
        <div>
            <x-input-label for="last_name" :value="__('client.last_name')" :messages="$errors->get('last_name')" :required="true"/>
            <x-text-input id="last_name" name="last_name" type="text" :messages="$errors->get('last_name')" class="mt-1 block w-full" :value="old('last_name', $client['last_name'] ?? null)"  autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>
        <div>
            <x-input-label for="middle_name" :value="__('client.middle_name')" :messages="$errors->get('middle_name')" />
            <x-text-input id="middle_name" name="middle_name" type="text" :messages="$errors->get('middle_name')" class="mt-1 block w-full" :value="old('middle_name', $client['middle_name'] ?? null)"  autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
        </div>
        <div>
            <x-input-label for="email" :value="__('Email')" :messages="$errors->get('email')" />
            <x-text-input id="email" name="email" type="email" :messages="$errors->get('email')" class="mt-1 block w-full" :value="old('email', $client['email'] ?? null)" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>
        <div>
            <x-input-label for="city_id" :value="__('client.city')" :messages="$errors->get('city_id')" />
            @php
                if ( isset($client['city_id']) ) {
                    $city = $client['city_id'];
                } else {
                    $city = 0;
                }
            @endphp
            <x-select name="city_id" id="city_id" :messages="$errors->get('city_id')" class="mt-1 block w-full">
                @foreach ($cities as $v)
                    <option value="{{ $v['id'] }}" {{ old('city_id',$city)==$v['id'] ? 'selected' : '' }} >{{ $v['title'] }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-input-label for="cell_phone" :value="__('client.cell_phone')" :messages="$errors->get('cell_phone')" :required="true"/>
            <span class="text-sm text-gray-600">{{ __('client.in_format')}} (ХХХ)ХХХ-ХХ-ХХ</span>
            <x-text-input id="cell_phone" name="cell_phone" type="text" :messages="$errors->get('cell_phone')" class="mt-1 block w-full" :value="old('cell_phone', $client['cell_phone'] ?? null )" placeholder="{{ __('(ХХХ)ХХХ-ХХ-ХХ') }}" autofocus  />
            <x-input-error class="mt-2" :messages="$errors->get('cell_phone')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('main.save') }}</x-primary-button>
        </div>
    </form>
</section>
