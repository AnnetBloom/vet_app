<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            @isset ($id) {{ __('client.edit_pet')}} @else {{ __('client.add_pet')}} @endisset
        </h2>
    </header>
    @php
        $owner_id = $pet['owner_id'] ?? request('client_id');
    @endphp

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
  
    <form x-data="pets()" method="post" action="@isset ($id) {{route('pets.update', [$id])}} @else {{route('pets.store')}} @endisset" class="mt-6 space-y-6">
        @csrf
        @isset ($id) @method('PUT') @endisset
        <input type="hidden" id="owner_id" name="owner_id" value="{{ $owner_id }}" />
        <div>
            <x-input-label for="alias" :value="__('client.pet_alias')" :messages="$errors->get('alias')" :required="true" />
            <x-text-input id="alias" name="alias" type="text" :messages="$errors->get('alias')" class="mt-1 block w-full" :value="old('alias', $pet['alias'] ?? null )"  autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('alias')" />
        </div>
        <div>
            <x-input-label for="type_id" :value="__('client.pet_type')" :messages="$errors->get('type_id')" :required="true" />
            <x-select id="type_id" name="type_id" x-model="type_id" @change="typeChange" :messages="$errors->get('type_id')" class="mt-1 block w-full" >
                <option value="0" >{{ __('main.select') }}</option>
                <template x-for="pet in pet_types.data" :key="pet.id">
                    <option :key="pet.id" :value="pet.id" x-text="pet.title" :selected="type_id === pet.id"></option>
                </template>
            </x-select>
            <x-input-error class="mt-2" :messages="$errors->get('type_id')" />
        </div>
        <div>
            <x-input-label for="breed_id" :value="__('client.breed')" :messages="$errors->get('breed_id')" :required="true" />
            <x-select id="breed_id" name="breed_id" :messages="$errors->get('breed_id')" class="mt-1 block w-full" >
                <option value="0" >{{ __('main.select') }}</option>
                <template x-for="breed in breed_options" >
                    <option :value="breed.id" x-text="breed.title" :selected="breed.id === breed_id"></option>
                </template>
            </x-select>
            <x-input-error class="mt-2" :messages="$errors->get('breed_id')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('main.save') }}</x-primary-button>
            <x-button-link href="{{route('clients.show', [$owner_id])}}" class="float-right">{{ __('main.back') }}</x-button-link>
        </div>
    </form>
</section>

<script>
    function pets() {
        return {
            init() {
                this.fillBreeds();
            },
            type_id: @if ( old('type_id',0)) {{ old('type_id', 0) }} @elseif ( isset($pet['type_id']) ) {{$pet['type_id']}} @else 0 @endif,
            breed_id: @if ( old('breed_id',0)) {{ old('breed_id', 0) }} @elseif ( isset($pet['breed_id']) ) {{$pet['breed_id']}} @else 0 @endif,
            pet_types: @json($petTypes),
            breed_options: {},
            typeChange: function(e) {
                this.type_id = e.target.selectedIndex; 
                this.fillBreeds();
            },
            fillBreeds: function() {
                this.breed_options = {};
                for (const [key, value] of Object.entries(this.pet_types.data)) {
                    if (value.id === this.type_id) {
                        this.breed_options = value.breeds;
                    }
                }
            }
        };
    }
</script>