@props([
    'title' => null,
])

<div {{ $attributes->class('filter-bar') }}>

    @if ($title)
        <h5 class="filter-bar-title">
            {{ $title }}
        </h5>
    @endif

    <div class="filter-bar-actions">
        {{ $slot }}
    </div>

</div>
