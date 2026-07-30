@props([
    'title' => null,
    'subtitle' => null,
    'flush' => false,
])

<div {{ $attributes->merge(['class' => 'report-card']) }}>

    {{-- Header --}}
    @if ($title || $subtitle)
        <div class="report-card-header">
            <div class="report-card-header-left">
                @if ($title)
                    <h4 class="report-card-title">{{ $title }}</h4>
                @endif
                @if ($subtitle)
                    <p class="report-card-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($headerActions)
                <div class="report-card-header-right">
                    {{ $headerActions }}
                </div>
            @endisset
        </div>
    @endif

    {{-- Body --}}
    <div @class(['report-card-body', 'report-card-body-flush' => $flush])>
        {{ $slot }}
    </div>

    {{-- Footer --}}
    @isset($footer)
        <div class="report-card-footer">
            {{ $footer }}
        </div>
    @endisset

</div>
