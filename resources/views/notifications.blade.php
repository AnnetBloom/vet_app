@if (session('success'))
    <br>
    <x-success-alert>
        {{ __('main.excellent')}}
        <x-slot name="message">{{ session('success')}}</x-slot>
    </x-success-alert>
@endif

@if (session('error'))
    <br>
    <p class="text-sm text-gray-600">
        <x-error-alert>
        {{session('error')}}
        </x-error-alert>
    </p>
@endif
