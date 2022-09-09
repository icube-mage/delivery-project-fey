@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-lg border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 transition duration-200']) !!}>