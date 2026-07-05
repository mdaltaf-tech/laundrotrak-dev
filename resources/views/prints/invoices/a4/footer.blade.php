<div class="invoice-card mt-20">

    <div class="section-header">

        Remarks & Instructions

    </div>

    <div class="footer-body">

        @if (!empty($order->remarks))
            <p class="mb-10">

                <strong>Customer Remarks</strong>

            </p>

            <p class="mb-20">

                {{ $order->remarks }}

            </p>
        @endif

        <ul class="remarks-list">

            <li>Please inspect garments at the time of delivery.</li>

            <li>Stains are treated professionally, but complete removal cannot be guaranteed.</li>

            <li>Please check all pockets before handing over garments.</li>

            <li>Garments not collected within 30 days may attract storage charges.</li>

        </ul>

    </div>

</div>
<div class="invoice-card mt-20">

    <div class="section-header">

        Print Information

    </div>

    <div class="footer-body">

        <table class="invoice-table">

            <tr>

                <td width="20%">

                    Order No

                </td>

                <td width="30%">

                    {{ $order->order_number }}

                </td>

                <td width="20%">

                    Printed On

                </td>

                <td>

                    {{ now()->format('d M Y h:i A') }}

                </td>

            </tr>

            <tr>

                <td>

                    Delivery Date

                </td>

                <td>

                    {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}

                </td>

                <td>

                    Printed By

                </td>

                <td>

                    {{ auth()->user()->name ?? '-' }}

                </td>

            </tr>

        </table>

    </div>

</div>
<div class="invoice-note">
    <h3>
        Thank You For Choosing Faeblo Laundry & Dry Clean Studio
    </h3>
    <p>
        We appreciate your trust in our services.
    </p>
    <p>
        Customer Care :
        {{ getCountryCode() }} {{ $phone }}
        @if ($store_email)
            | {{ $store_email }}
        @endif
    </p>

    <p class="text-muted mt-10" style="font-size:11px;">
        This is a computer generated invoice. No signature is required.
    </p>

    <p class="text-muted mt-10" style="font-size:11px;">
        Powered by <strong>LaundroTrak™</strong><br>
        <span style="font-size:10px;">An ERP Solution by <strong>Armem Infotech</strong></span>
    </p>

</div>
