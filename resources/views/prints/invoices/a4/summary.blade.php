<div class="summary-ribbon">

    <div class="summary-box">

        <div class="summary-label">
            Garments
        </div>

        <div class="summary-value">
            {{ $invoice['pieces'] }}
        </div>

    </div>


    <div class="summary-box">

        <div class="summary-label">
            Services
        </div>

        <div class="summary-value">
            {{ $invoice['services'] }}
        </div>

    </div>


    <div class="summary-box">

        <div class="summary-label">
            Sub Total
        </div>

        <div class="summary-value currency">
            {{ getFormattedCurrency($invoice['subTotal']) }}
        </div>

    </div>


    <div class="summary-box">

        <div class="summary-label">
            Grand Total
        </div>

        <div class="summary-value currency">
            {{ getFormattedCurrency($order->total) }}
        </div>

    </div>


    <div class="summary-box">

        <div class="summary-label">
            Paid
        </div>

        <div class="summary-value currency">
            {{ getFormattedCurrency($invoice['paid']) }}
        </div>

    </div>


    <div class="summary-box highlight">

        <div class="summary-label">
            Balance
        </div>

        <div class="summary-value currency">
            {{ getFormattedCurrency($invoice['balance']) }}
        </div>

    </div>

</div>
