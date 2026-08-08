@if ($showReconcileModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width:1100px;">
            <div class="modal-content rounded-4 shadow-sm">
                {{-- =========================
                    MODAL HEADER
                ========================= --}}
                <div class="modal-header cr-header">

                    <div class="cr-header-content">

                        <h2 class="cr-title">
                            {{ $isReadOnly ? 'Business Day Register' : 'Cash Reconciliation' }}
                        </h2>

                        <div class="cr-subtitle">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('l, d M Y') }}
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>

                <div class="modal-body cr-body">

                    {{-- =========================
                        BUSINESS SUMMARY
                    ========================= --}}
                    <section class="cr-section">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="cr-card">
                                    <div class="cr-section-title">
                                        Business Summary
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <div class="cr-kpi cr-kpi-neutral">
                                    <div class="cr-kpi-label">
                                        Opening Cash
                                    </div>
                                    <div class="cr-kpi-value">
                                        {{ getFormattedCurrency($openingCash) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cr-kpi cr-kpi-success">
                                    <div class="cr-kpi-label">
                                        Cash Collection
                                    </div>
                                    <div class="cr-kpi-value cr-text-success">
                                        {{ getFormattedCurrency($cashCollection) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cr-kpi cr-kpi-danger">
                                    <div class="cr-kpi-label">
                                        Expenses
                                    </div>
                                    <div class="cr-kpi-value cr-text-danger">
                                        {{ getFormattedCurrency($expenseAmount) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cr-kpi cr-kpi-primary">
                                    <div class="cr-kpi-label">
                                        Expected Closing
                                    </div>
                                    <div class="cr-kpi-value cr-text-primary">
                                        {{ getFormattedCurrency($expectedClosing) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- =========================
                        CASH VERIFICATION ROW
                    ========================= --}}
                    <div class="row g-4">

                        {{-- CASH VERIFICATION --}}
                        <div class="col-lg-8">

                            <div class="cr-card">

                                <div class="cr-section-title">
                                    Closing Summary
                                </div>

                                <div class="row g-4">

                                    <div class="col-md-6">
                                        <div class="cr-field">

                                            <label class="cr-label">
                                                Cash Withdrawn Before Closing
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text">₹</span>

                                                <input type="number" class="form-control"
                                                    wire:model.live.debounce.300ms="withdrawAmount">

                                            </div>

                                            <div class="cr-helper">
                                                Cash removed from drawer before final cash counting.
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="cr-field">
                                            <label class="cr-label">
                                                Actual Cash Counted
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control"
                                                    wire:model.live.debounce.300ms="closingCash">
                                            </div>
                                            <div class="cr-helper">
                                                Total physical cash available in the drawer.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- DIFFERENCE --}}
                        <div class="col-lg-4">
                            <div class="cr-card">
                                <div class="cr-section-title">
                                    Difference
                                </div>
                                <div class="cr-difference cr-text-{{ $this->statusClass }}">
                                    {{ getFormattedCurrency(abs($difference)) }}
                                </div>
                                <div class="mb-3">
                                    <span class="cr-pill cr-pill-{{ $this->statusClass }}">
                                        @switch($this->statusClass)
                                            @case('success')
                                                ✓
                                            @break

                                            @case('danger')
                                                ●
                                            @break

                                            @case('info')
                                                ↑
                                            @break

                                            @default
                                                ○
                                        @endswitch
                                        &nbsp;
                                        {{ $this->statusText }}
                                    </span>
                                </div>
                                <div class="cr-status-message">
                                    {{ $this->statusMessage }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ========================================= --}}
                    {{-- BOTTOM ROW --}}
                    {{-- ========================================= --}}

                    <div class="row g-3 mt-1">

                        {{-- Closing Information --}}
                        <div class="col-lg-8">

                            <div class="cr-card h-100">

                                <div class="cr-section-title">
                                    Closing Information
                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="cr-detail">

                                            <div class="cr-detail-label">
                                                Closed By
                                            </div>

                                            <div class="cr-detail-value">
                                                {{ $closedBy ?? '-' }}
                                            </div>

                                        </div>

                                        <div class="cr-detail">

                                            <div class="cr-detail-label">
                                                Closed At
                                            </div>

                                            <div class="cr-detail-value">
                                                {{ $closedAt ?? '-' }}
                                            </div>

                                        </div>

                                        <div class="cr-detail">

                                            <div class="cr-detail-label">
                                                UPI Collection
                                            </div>

                                            <div class="cr-detail-value">
                                                {{ getFormattedCurrency($upiCollection) }}
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="cr-detail">

                                            <div class="cr-detail-label">
                                                Cash Withdrawn
                                            </div>

                                            <div class="cr-detail-value">
                                                {{ getFormattedCurrency($withdrawAmount) }}
                                            </div>

                                        </div>

                                        <div class="cr-detail">

                                            <div class="cr-detail-label">
                                                Current Status
                                            </div>

                                            <div class="cr-detail-value">

                                                @if ($isReadOnly)
                                                    <span class="cr-pill cr-pill-success">
                                                        Closed
                                                    </span>
                                                @else
                                                    <span class="cr-pill cr-pill-warning">
                                                        Open
                                                    </span>
                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Remarks --}}
                        <div class="col-lg-4">
                            <div class="cr-card">
                                <div class="cr-section-title">
                                    Remarks
                                </div>
                                <textarea rows="4" class="form-control" wire:model.defer="remarks"
                                    placeholder="Add any notes or remarks... (Optional notes about this reconciliation.)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================= --}}
                {{-- FOOTER --}}
                {{-- ========================================= --}}

                <div class="modal-footer cr-footer">

                    @if ($isReadOnly)
                        <button class="btn btn-outline-secondary cr-btn" wire:click="closeModal">

                            Close

                        </button>
                    @else
                        <button class="btn btn-light border cr-btn" wire:click="closeModal">

                            Cancel

                        </button>

                        <button class="btn btn-success cr-btn" wire:click="saveReconciliation">

                            Close Business Day

                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endif

<style>
    :root {

        --cr-primary: #2563EB;

        --cr-success: #16A34A;

        --cr-danger: #DC2626;

        --cr-info: #0284C7;

        --cr-border: #E5E7EB;

        --cr-text: #111827;

        --cr-muted: #6B7280;

    }

    /* ==========================================================
   CASH RECONCILIATION MODAL
========================================================== */

    .business-day-modal {
        background: #FFFFFF;
        border: none;
        border-radius: 18px;
        overflow: hidden;

        box-shadow:
            0 20px 60px rgba(15, 23, 42, 0.12),
            0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .modal-xl {
        max-width: 1360px;
        width: 95%;
    }

    .modal-content.business-day-modal {
        display: flex;
        flex-direction: column;
        min-height: 780px;
    }

    .modal-body {
        padding: 0;
        background: #FFFFFF;
    }

    .modal-footer {
        background: #FAFBFC;
    }

    /* ==========================================================
   HEADER
========================================================== */

    .cr-header {
        position: relative;
        padding: 15px;
        background: #FFFFFF;
        border-bottom: 1px solid #E5E7EB;
    }

    .cr-header-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .cr-title {
        margin: 0;
        font-size: 2rem !important;
        font-weight: 600;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: #1F2937;
    }

    .cr-subtitle {
        margin: 4px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .02em;
        line-height: 1.5;
        color: #64748B;
    }

    .cr-header .btn-close {
        position: absolute;
        top: 24px;
        right: 28px;
        opacity: .65;
        transition: opacity .2s ease, transform .2s ease;
    }

    .cr-header .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    /* ==========================================================
   BODY
========================================================== */

    .cr-body {
        background: #FAFBFC;
        padding: 15px;
    }

    .cr-section {
        margin-bottom: 20px;
    }

    .cr-section:last-child {
        margin-bottom: 0;
    }

    /* ==========================================================
   SECTION TITLE
========================================================== */

    .cr-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;

        color: #475569;
    }

    .cr-section-title::before {
        content: "";

        width: 3px;
        height: 16px;

        border-radius: 999px;

        background: #2563EB;

        flex-shrink: 0;
    }

    /* ==========================================================
   COMMON CARD
========================================================== */

    .cr-card {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 12px;
        background: #FFFFFF;
        /* border: 1px solid #E8EDF3;
        border-radius: 16px;

        box-shadow:
            0 1px 2px rgba(15, 23, 42, .03),
            0 6px 18px rgba(15, 23, 42, .04);

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            transform .2s ease; */
    }

    .cr-card:hover {
        /* border-color: #D8E2EC;

        box-shadow:
            0 4px 10px rgba(15, 23, 42, .05),
            0 12px 28px rgba(15, 23, 42, .06); */
    }

    /* Equal spacing between elements inside cards */

    .cr-card>*+* {
        margin-top: 18px;
    }

    .cr-card textarea {
        flex: 1;
    }

    /* ==========================================================
   KPI CARDS
========================================================== */

    .cr-kpi {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: auto;
        padding: 18px;

        border: 1px solid #E7ECF2;
        border-radius: 16px;

        transition:
            transform .18s ease,
            box-shadow .18s ease,
            border-color .18s ease;
    }

    .cr-kpi:hover {
        border-color: #D8E2EC;
        box-shadow:
            0 6px 16px rgba(15, 23, 42, .05),
            0 2px 4px rgba(15, 23, 42, .03);

    }

    /* ------------------------------------------
   Backgrounds
------------------------------------------ */

    .cr-kpi-neutral {
        background: #F8FAFC;
    }

    .cr-kpi-success {
        background: #F0FDF4;
    }

    .cr-kpi-danger {
        background: #FEF2F2;
    }

    .cr-kpi-primary {
        background: #EFF6FF;
    }

    /* ------------------------------------------
   Typography
------------------------------------------ */

    .cr-kpi-label {

        margin: 0 0 12px;

        font-size: 13px;

        font-weight: 600;

        letter-spacing: .02em;

        color: #64748B;

    }

    .cr-kpi-value {

        font-size: 2.15rem;

        font-weight: 700;

        line-height: 1.05;

        letter-spacing: -0.03em;

        color: #111827;

        word-break: break-word;

    }

    /* ------------------------------------------
   Value Colours
------------------------------------------ */

    .cr-text-success {
        color: #16A34A !important;
    }

    .cr-text-danger {
        color: #DC2626 !important;
    }

    .cr-text-primary {
        color: #2563EB !important;
    }

    /* ==========================================================
   FORM ELEMENTS
========================================================== */
    .cr-field {
        margin-bottom: 22px;
    }

    .cr-field:last-child {
        margin-bottom: 0;
    }

    .cr-label {
        display: block;

        margin: 0 0 10px;

        font-size: 14px;

        font-weight: 600;

        line-height: 1.4;

        color: #334155;
    }

    .cr-helper {

        margin-top: 10px;

        padding-left: 2px;

        font-size: 13px;

        line-height: 1.5;

        color: #64748B;
    }

    /* ------------------------------------------
   Input Group
------------------------------------------ */

    .input-group {
        border-radius: 12px;
    }

    .input-group-text {

        min-width: 46px;

        justify-content: center;

        background: #F8FAFC;

        border: 1px solid #D1D5DB;

        border-right: none;

        color: #475569;

        font-weight: 600;

        transition: .2s;
    }

    /* .form-control {

        height: 50px;

        border: 1px solid #D1D5DB;

        border-left: none;

        border-radius: 0;

        padding: 0 14px;

        font-size: 15px;

        font-weight: 500;

        color: #111827;

        background: #FFFFFF;

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .form-control::placeholder {

        color: #94A3B8;
    } */

    .input-group:focus-within .input-group-text {

        border-color: #2563EB;

        background: #EFF6FF;

        color: #2563EB;
    }

    /* .form-control:focus {

        border-color: #2563EB;

        box-shadow: none;

        background: #FFFFFF;
    } */

    /* ------------------------------------------
   Remove number arrows
------------------------------------------ */

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    /* ==========================================================
   DIFFERENCE CARD
========================================================== */

    .cr-difference-card {

        display: flex;
        flex-direction: column;
        justify-content: space-between;

        height: 100%;
    }

    /* ------------------------------------------
   Header
------------------------------------------ */

    .cr-diff-header {

        display: flex;
        justify-content: space-between;
        align-items: flex-start;

        gap: 16px;

        margin-bottom: 20px;
    }

    .cr-small-label {

        margin: 0 0 8px;

        font-size: 13px;

        font-weight: 600;

        text-transform: uppercase;

        letter-spacing: .08em;

        color: #64748B;
    }

    /* ------------------------------------------
   Amount
------------------------------------------ */

    .cr-difference {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.05;
        letter-spacing: -0.03em;
        color: #111827;
    }

    /* ------------------------------------------
   Status
------------------------------------------ */

    .cr-status {

        margin: 0;
    }

    /* ------------------------------------------
   Description
------------------------------------------ */

    .cr-status-message {
        margin-top: 4px;
        font-size: 13px;
        font-weight: 400;
        line-height: 1.6;
        color: #64748B;
    }

    /* ------------------------------------------
   Difference Colours
------------------------------------------ */

    .cr-difference.text-success {
        color: #16A34A;
    }

    .cr-difference.text-danger {
        color: #DC2626;
    }

    .cr-difference.text-info {
        color: #0284C7;
    }

    /* ==========================================================
   STATUS PILLS
========================================================== */

    .cr-pill {

        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 34px;

        padding: 0 16px;

        border-radius: 999px;

        font-size: 13px;

        font-weight: 600;

        letter-spacing: .02em;

        white-space: nowrap;

        border: 1px solid transparent;

        transition:
            background-color .2s ease,
            border-color .2s ease,
            color .2s ease;
    }

    /* -------------------------
   Balanced
------------------------- */

    .cr-pill-success {

        background: #ECFDF3;

        color: #15803D;

        border-color: #BBF7D0;

    }

    /* -------------------------
   Cash Short
------------------------- */

    .cr-pill-danger {

        background: #FEF2F2;

        color: #DC2626;

        border-color: #FECACA;

    }

    /* -------------------------
   Extra Cash
------------------------- */

    .cr-pill-info {

        background: #EFF6FF;

        color: #2563EB;

        border-color: #BFDBFE;

    }

    /* -------------------------
   Open
------------------------- */

    .cr-pill-warning {

        background: #FEF3C7;

        color: #B45309;

        border-color: #FCD34D;

    }

    /* ==========================================================
   CLOSING INFORMATION
========================================================== */

    .cr-detail {

        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #EEF2F7;
    }

    .cr-detail:last-child {
        border-bottom: none;
    }

    .cr-detail-label {
        font-size: 14px;
        font-weight: 500;
        color: #64748B;
    }

    .cr-detail-value {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 600;
        color: #111827;
        text-align: right;
    }

    .cr-detail-value .cr-pill {
        margin: 0;
    }

    /* ==========================================================
   REMARKS
========================================================== */

    textarea.form-control {

        min-height: 120px;

        padding: 14px 16px;

        resize: vertical;

        font-size: 14px;

        line-height: 1.6;

        color: #111827;

        background: #FFFFFF;

        border: 1px solid #D1D5DB;

        border-radius: 12px;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;

    }

    textarea.form-control:focus {

        border-color: #2563EB;

        box-shadow:
            0 0 0 4px rgba(37, 99, 235, .08);

        outline: none;

    }

    textarea.form-control::placeholder {

        color: #94A3B8;

        font-size: 14px;

        font-weight: 400;

    }

    /* ==========================================================
   FOOTER
========================================================== */

    .cr-footer {

        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;

        padding: 18px 36px;

        background: #FAFBFC;

        border-top: 1px solid #E5E7EB;
    }

    .cr-btn {

        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 160px;
        height: 46px;

        padding: 0 24px;

        border-radius: 10px;

        font-size: 14px;
        font-weight: 600;

        transition:
            background-color .2s ease,
            border-color .2s ease,
            color .2s ease,
            transform .15s ease,
            box-shadow .15s ease;
    }

    .cr-btn:focus {

        box-shadow: none;

    }

    .btn-success:hover {
        box-shadow: 0 4px 10px rgba(22, 163, 74, .18);
    }

    .btn-light:hover,
    .btn-outline-secondary:hover {
        background: #F8FAFC;
    }

    /* Mobile */

    @media (max-width:768px) {

        .cr-footer {

            flex-direction: column-reverse;
            align-items: stretch;

            padding: 18px 20px;
        }

        .cr-btn {

            width: 100%;
            min-width: 100%;
        }

    }


    /* ==========================================================
   RESPONSIVE
========================================================== */

    @media (max-width: 991.98px) {

        .modal-xl {
            max-width: 96%;
            margin: 1rem auto;
        }

        .cr-header {
            padding: 24px;
        }

        .cr-body {
            padding: 24px;
        }

        .cr-footer {
            padding: 20px 24px;
        }

        .cr-title {
            font-size: 2.2rem;
        }

        .cr-subtitle {
            font-size: 15px;
        }

        .cr-card {
            padding: 20px;
        }

        .cr-kpi {
            min-height: auto;
        }

        .cr-kpi-value {
            font-size: 2rem;
        }

        .cr-difference {
            font-size: 2.5rem;
        }

        .cr-diff-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .cr-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .cr-btn {
            min-width: 140px;
        }

    }

    @media (max-width: 767.98px) {

        .modal-xl {
            max-width: 100%;
            margin: 0;
        }

        .cr-header {
            padding: 20px;
        }

        .cr-body {
            padding: 20px;
        }

        .cr-footer {
            padding: 18px 20px;
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cr-title {
            font-size: 1.8rem;
        }

        .cr-subtitle {
            font-size: 14px;
        }

        .cr-section {
            margin-bottom: 24px;
        }

        .cr-section-title {
            font-size: 13px;
            margin-bottom: 16px;
        }

        .cr-kpi-value {
            font-size: 1.75rem;
        }

        .cr-difference {
            font-size: 2.2rem;
        }

        .cr-detail {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .cr-detail-value {
            width: 100%;
            justify-content: flex-start;
        }

        .cr-btn {
            width: 100%;
            min-width: 100%;
        }

    }
</style>
