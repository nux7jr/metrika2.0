@props(['required' => false])

<label {{ $attributes->class([
    $required ? 'required' : '',
]) }}>

    {{ $slot }}</label>
