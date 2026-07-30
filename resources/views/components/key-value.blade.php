@props(['label', 'border' => false, 'labelClass' => '', 'valueClass' => '', 'emphasis' => false])

<div {{ $attributes->class(['report-key-value', 'report-key-value-border' => $border]) }}>

    <div @class(['report-key', $labelClass])>
        {{ $label }}
    </div>

    <div @class([
        'report-value',
        'report-value-emphasis' => $emphasis,
        $valueClass,
    ])>
        {{ $slot }}
    </div>

</div>
