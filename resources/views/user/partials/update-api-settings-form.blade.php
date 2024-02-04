<section x-data="getUrl()">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('main.settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('main.add_url_and_key') }}
        </p>
    </header>

    <form @submit.prevent="submitData()" method="post" action="" class="mt-6 space-y-6">
        <div>
            <x-input-label for="domen" :value="__('domen')" />
            <x-text-input id="domen" name="domen" x-model="domen" value="" type="text" class="mt-1 block w-full"  required autofocus  />
            <x-input-error class="mt-2" :messages="$errors->get('domen')" />
        </div>
        <x-primary-button>{{ __('main.get_url') }}</x-primary-button>
    </form>

    <form method="post" action="{{ route('user_settings.store') }}" class="mt-6 space-y-6">
        @csrf
        @method('post')
        <div>
            <x-input-label for="url" :value="__('Url')" :messages="$errors->get('url')" />
            <x-text-input id="url" name="url" x-model="user_url" type="text" :messages="$errors->get('url')" class="mt-1 block w-full" value=""  autofocus autocomplete="url" />
            <x-input-error class="mt-2" :messages="$errors->get('url')" />
        </div>
        <div>
            <x-input-label for="key" :value="__('Key')" :messages="$errors->get('key')" />
            <x-text-input id="key" name="key" type="text" :messages="$errors->get('key')" class="mt-1 block w-full" :value="old('key', $user->keySettings->key ?? '')"  />
            <x-input-error class="mt-2" :messages="$errors->get('key')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('main.save')}}</x-primary-button>

            @if (session('success'))
                <br>
                <x-success-alert>
                    {{ __('main.excellent')}}
                    <x-slot name="message">{{ session('success') }}</x-slot>
                </x-success-alert>
            @endif
        </div>
    </form>
</section>

<script >

document.addEventListener('alpine:init', () => {
    Alpine.data('getUrl', () => ({
        domen: '',
        user_url: "{{ old('url', $user->keySettings->url ?? '')}}",
        meta:  document.head.querySelector('meta[name=csrf-token]').content,
        submitData: async function () {
            let post_req = {
                domen: this.domen
            };
            let response = await fetch("{{route('user_settings.getUserUrl')}}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json;charset=utf-8','X-CSRF-TOKEN': this.meta},
                body: JSON.stringify(post_req)
            });

            if (response.ok) { // если HTTP-статус в диапазоне 200-299
                let json = await response.json();
                this.user_url = json.url;
            } else {
                alert("Ошибка HTTP: " + response.status);
            }
        }
    }));
})
</script>


