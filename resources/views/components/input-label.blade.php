@props(['value', 'messages' => false, 'required' => false])

<label {{ $attributes->class(['text-red-500' => $messages])->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
    @if ($required) 
    <span>*</span>
    @endif
</label>