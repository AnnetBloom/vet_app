<x-modal name="confirm-deletion" focusable>
    <form method="post" id="confirm-deletion" action="" class="p-6">
        @csrf
        @method('delete')
        <p class="mt-1 text-sm text-gray-600">
            {{ __($modal_message) }}
        </p>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('main.cancel') }}
            </x-secondary-button>
            <x-danger-button class="ml-3">
                {{ __('main.delete') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>