@props(['date', 'showToday' => true])

@php
    $date = \Carbon\Carbon::parse($date);
@endphp

<div class="report-date-card">
    <div class="report-date-day">
        {{ $date->format('d') }}
    </div>

    <div class="report-date-month">
        {{ strtoupper($date->format('M')) }}
    </div>

    <div class="report-date-weekday">
        {{ $date->format('D') }}
    </div>

    @if ($showToday && $date->isToday())
        <div class="report-date-today">
            Today
        </div>
    @endif
</div>
