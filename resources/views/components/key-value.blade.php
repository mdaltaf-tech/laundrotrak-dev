@props(['label', 'value' => null, 'border' => false, 'labelClass' => '', 'valueClass' => ''])

<div {{ $attributes->merge([
    'class' => 'report-key-value' . ($border ? ' report-key-value-border' : ''),
]) }}>

    <div class="report-key {{ $labelClass }}">
        {{ $label }}
    </div>

    <div class="report-value {{ $valueClass }}">
        @if ($value !== null)
            {{ $value }}
        @else
            {{ $slot }}
        @endif
    </div>

</div>
