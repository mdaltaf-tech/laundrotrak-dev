@props([
    'variant' => 'secondary',
    'size' => 'md',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'report-status-sm',
        'lg' => 'report-status-lg',
        default => 'report-status-md',
    };

    $variantClass = match ($variant) {
        'success' => 'report-status-success',
        'info' => 'report-status-info',
        'warning' => 'report-status-warning',
        'danger' => 'report-status-danger',
        'secondary' => 'report-status-secondary',
        'purple' => 'report-status-purple',
        default => 'report-status-secondary',
    };
@endphp

<span {{ $attributes->merge([
    'class' => "report-status {$variantClass} {$sizeClass}",
]) }}>
    {{ $slot }}
</span>
