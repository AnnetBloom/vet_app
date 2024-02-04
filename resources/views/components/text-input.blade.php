@props(['disabled' => false, 'messages' => false])

<input 
{{ $disabled ? 'disabled' : '' }} 
{!! $attributes->class(['border-red-500' => $messages])->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'])  !!}
>
