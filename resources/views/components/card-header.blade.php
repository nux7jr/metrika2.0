<div {{ $attributes->class([
    'card-body',
]) }}>

    <div {{ $attributes->class([
    'd-flex',
    'justify-content-between'
    ]) }}>

        <div>

            {{ $slot }}

        </div>


        @isset($right)
            <div>
                {{ $right }}
            </div>
        @endisset

    </div>

</div>


