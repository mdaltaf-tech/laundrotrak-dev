<div class="container-fluid business-report-page">
    {{-- Header --}}
    @include('livewire.reports.partials.business-report-header')
    {{-- KPI Cards --}}
    @include('livewire.reports.partials.business-report-kpis')
    {{-- Daily Register --}}
    @include('livewire.reports.partials.daily-register-card')
    {{-- Modals --}}
    @include('livewire.reports.partials.business-day-modal')
</div>
