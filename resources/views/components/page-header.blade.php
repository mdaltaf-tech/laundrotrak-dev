@props(['title', 'subtitle' => null])

<div {{ $attributes->class('page-header') }}>
    <div class="page-header-left">
        <h1 class="page-header-title">
            {{ $title }}
        </h1>
        @if ($subtitle)
            <p class="page-header-subtitle">
                {{ $subtitle }}
            </p>
        @endif
    </div>
    @isset($actions)
        <div class="page-header-actions">
            {{ $actions }}
        </div>
    @endisset
</div>
