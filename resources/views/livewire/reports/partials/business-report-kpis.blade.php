<div class="report-kpis">

    @include('livewire.reports.partials.kpi-card', [
        'title' => 'Total Collection',
        'value' => getFormattedCurrency($this->summary->totalCollection),
        'class' => 'kpi-green',
    ])

    @include('livewire.reports.partials.kpi-card', [
        'title' => 'Cash Collection',
        'value' => getFormattedCurrency($this->summary->cashCollection),
        'class' => 'kpi-orange',
    ])

    @include('livewire.reports.partials.kpi-card', [
        'title' => 'UPI Collection',
        'value' => getFormattedCurrency($this->summary->upiCollection),
        'class' => 'kpi-purple',
    ])

    @include('livewire.reports.partials.kpi-card', [
        'title' => 'Expenses',
        'value' => getFormattedCurrency($this->summary->expenses),
        'class' => 'kpi-red',
    ])

    @include('livewire.reports.partials.kpi-card', [
        'title' => 'Withdrawals',
        'value' => getFormattedCurrency($this->summary->withdrawals),
        'class' => 'kpi-blue',
    ])

    @include('livewire.reports.partials.kpi-card', [
        'title' => 'Closed Days',
        'value' => $this->summary->closedDays . ' / ' . $this->summary->totalDays,
        'class' => 'kpi-green',
    ])

</div>
