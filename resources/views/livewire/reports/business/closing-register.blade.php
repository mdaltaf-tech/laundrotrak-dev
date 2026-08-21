<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Closing Register - {{ $closure->cashRegister?->receipt_no ?? 'N/A' }}
    </title>

    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 80mm;
            background: #fff;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            font-size: 11px;
            line-height: 1.35;
        }

        .receipt {
            width: 72mm;
            margin: 0 auto;
            padding: 5mm 0 7mm;
        }

        /* -------------------------------------------------
           HEADER
        ------------------------------------------------- */

        .header {
            text-align: center;
        }

        .brand {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 1.5px;
            line-height: 1;
        }

        .business-name {
            margin-top: 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .location {
            margin-top: 2px;
            font-size: 9px;
        }

        .document-title {
            margin-top: 9px;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .document-subtitle {
            margin-top: 2px;
            font-size: 9px;
        }

        /* -------------------------------------------------
           CLOSING REGISTER NUMBER
        ------------------------------------------------- */

        .register-box {
            margin-top: 9px;
            padding: 6px 4px;
            border: 1.5px solid #000;
            text-align: center;
        }

        .register-label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .register-number {
            margin-top: 2px;
            font-family: "Courier New", monospace;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: .7px;
        }

        /* -------------------------------------------------
           DIVIDERS
        ------------------------------------------------- */

        .divider {
            border-top: 1px dashed #000;
            margin: 9px 0;
        }

        .divider-solid {
            border-top: 1px solid #000;
            margin: 9px 0;
        }

        /* -------------------------------------------------
           SECTION HEADINGS
        ------------------------------------------------- */

        .section-title {
            margin-bottom: 4px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        /* -------------------------------------------------
           ROWS
        ------------------------------------------------- */

        .row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 8px;
            padding: 2.5px 0;
        }

        .label {
            text-align: left;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
            font-weight: 600;
        }

        .strong {
            font-weight: 800;
        }

        /* -------------------------------------------------
           EXPECTED / COUNTED CASH
        ------------------------------------------------- */

        .cash-summary {
            margin-top: 4px;
        }

        .cash-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-weight: 700;
        }

        .cash-row .amount {
            font-size: 12px;
        }

        /* -------------------------------------------------
           DIFFERENCE
        ------------------------------------------------- */

        .status-box {
            margin-top: 8px;
            padding: 8px 5px;
            border: 1.5px solid #000;
            text-align: center;
        }

        .status-label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .status-amount {
            margin-top: 2px;
            font-size: 15px;
            font-weight: 900;
        }

        .status-text {
            margin-top: 2px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
        }

        /* -------------------------------------------------
           REMARKS
        ------------------------------------------------- */

        .remarks {
            font-size: 9px;
            line-height: 1.45;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* -------------------------------------------------
           AUDIT INFORMATION
        ------------------------------------------------- */

        .audit {
            font-size: 9px;
        }

        /* -------------------------------------------------
           FOOTER
        ------------------------------------------------- */

        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 8px;
            line-height: 1.45;
        }

        .footer-title {
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .5px;
        }

        .system-footer {
            margin-top: 5px;
            font-size: 7.5px;
        }

        /* -------------------------------------------------
           PRINT
        ------------------------------------------------- */

        @media print {

            html,
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }

            .receipt {
                width: 72mm;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>

    @php
        $businessDate = $closure->business_date ? \Carbon\Carbon::parse($closure->business_date) : null;

        $closedAt = $closure->closed_at ? \Carbon\Carbon::parse($closure->closed_at) : null;

        $difference = (float) ($closure->difference_amount ?? 0);

        if (abs($difference) < 0.01) {
            $status = 'BALANCED';
        } elseif ($difference < 0) {
            $status = 'CASH SHORT';
        } else {
            $status = 'EXTRA CASH';
        }
    @endphp


    <div class="receipt">

        {{-- =====================================================
         HEADER
    ====================================================== --}}

        <div class="header">

            <div class="brand">
                FAEBLO
            </div>

            <div class="business-name">
                Laundry & Dry Clean Studio
            </div>

            <div class="location">
                Khariar, Nuapada, Odisha
            </div>

            <div class="document-title">
                Business Day Closing
            </div>

            <div class="document-subtitle">
                Cash Reconciliation Register
            </div>

        </div>


        {{-- =====================================================
         REGISTER ID
    ====================================================== --}}

        <div class="register-box">

            <div class="register-label">
                Closing Register ID
            </div>

            <div class="register-number">
                {{ $closure->cashRegister?->receipt_no ?? '-' }}
            </div>

        </div>


        <div class="divider"></div>


        {{-- =====================================================
         BUSINESS DAY
    ====================================================== --}}

        <div class="section-title">
            Business Day
        </div>

        <div class="row">
            <span class="label">
                Date
            </span>

            <span class="amount">
                {{ $businessDate?->format('d M Y') ?? '-' }}
            </span>
        </div>


        <div class="divider"></div>


        {{-- =====================================================
         COLLECTION SUMMARY
    ====================================================== --}}

        <div class="section-title">
            Collection Summary
        </div>

        <div class="row">
            <span class="label">
                Opening Cash
            </span>

            <span class="amount">
                ₹{{ number_format($closure->opening_cash, 2) }}
            </span>
        </div>

        <div class="row">
            <span class="label">
                Cash Collection
            </span>

            <span class="amount">
                ₹{{ number_format($closure->cash_collection, 2) }}
            </span>
        </div>

        <div class="row">
            <span class="label">
                UPI Collection
            </span>

            <span class="amount">
                ₹{{ number_format($closure->upi_collection, 2) }}
            </span>
        </div>

        @if ((float) ($closure->card_collection ?? 0) > 0)
            <div class="row">
                <span class="label">
                    Card Collection
                </span>

                <span class="amount">
                    ₹{{ number_format($closure->card_collection, 2) }}
                </span>
            </div>
        @endif

        @if ((float) ($closure->wallet_collection ?? 0) > 0)
            <div class="row">
                <span class="label">
                    Wallet Collection
                </span>

                <span class="amount">
                    ₹{{ number_format($closure->wallet_collection, 2) }}
                </span>
            </div>
        @endif

        @if ((float) ($closure->other_collection ?? 0) > 0)
            <div class="row">
                <span class="label">
                    Other Collection
                </span>

                <span class="amount">
                    ₹{{ number_format($closure->other_collection, 2) }}
                </span>
            </div>
        @endif

        <div class="row">

            <span class="label">
                Expenses
            </span>

            <span class="amount">
                ₹{{ number_format($closure->expense_amount, 2) }}
            </span>

        </div>

        <div class="row">

            <span class="label">
                Cash Removed
            </span>

            <span class="amount">
                ₹{{ number_format($closure->withdraw_amount, 2) }}
            </span>

        </div>


        <div class="divider"></div>


        {{-- =====================================================
         CASH VERIFICATION
    ====================================================== --}}

        <div class="section-title">
            Cash Verification
        </div>

        <div class="cash-summary">

            <div class="cash-row">

                <span>
                    Expected Cash
                </span>

                <span class="amount">
                    ₹{{ number_format($closure->expected_cash, 2) }}
                </span>

            </div>

            <div class="cash-row">

                <span>
                    Counted Cash
                </span>

                <span class="amount">
                    ₹{{ number_format($closure->counted_cash, 2) }}
                </span>

            </div>

        </div>


        {{-- =====================================================
         DIFFERENCE / STATUS
    ====================================================== --}}

        <div class="status-box">

            <div class="status-label">
                Reconciliation Difference
            </div>

            <div class="status-amount">
                ₹{{ number_format(abs($difference), 2) }}
            </div>

            <div class="status-text">
                {{ $status }}
            </div>

        </div>


        {{-- =====================================================
         DIFFERENCE REASON
    ====================================================== --}}

        @if (!empty($closure->difference_reason))
            <div class="divider"></div>

            <div class="section-title">
                Difference Reason
            </div>

            <div class="remarks">
                {{ $closure->difference_reason }}
            </div>
        @endif


        {{-- =====================================================
         REMARKS
    ====================================================== --}}

        @if (!empty($closure->remarks))
            <div class="divider"></div>

            <div class="section-title">
                Remarks
            </div>

            <div class="remarks">
                {{ $closure->remarks }}
            </div>
        @endif


        <div class="divider"></div>


        {{-- =====================================================
         AUDIT INFORMATION
    ====================================================== --}}

        <div class="section-title">
            Closure Information
        </div>

        <div class="audit">

            <div class="row">

                <span class="label">
                    Closed By
                </span>

                <span class="amount">
                    {{ $closure->closedBy?->name ?? '-' }}
                </span>

            </div>

            <div class="row">

                <span class="label">
                    Closed At
                </span>

                <span class="amount">
                    {{ $closedAt?->format('d M Y h:i A') ?? '-' }}
                </span>

            </div>

        </div>


        <div class="divider-solid"></div>


        {{-- =====================================================
         FOOTER
    ====================================================== --}}

        <div class="footer">

            <div class="footer-title">
                BUSINESS DAY CLOSED
            </div>

            <div>
                This is an electronically generated
                closing register.
            </div>

            <div class="system-footer">
                Laundrotrak powered by Armem Infotech
            </div>

        </div>

    </div>


    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>

</body>

</html>
