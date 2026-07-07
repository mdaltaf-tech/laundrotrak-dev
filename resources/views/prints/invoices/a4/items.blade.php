<div class="items-section">

    <div class="section-title">
        Garment Details
    </div>

    <table class="items-table">

        <thead>

            <tr>

                <th class="invoice-col-sl center">#</th>

                <th class="invoice-col-article">Article</th>

                <th class="invoice-col-service">Service</th>

                <th class="invoice-col-qty center">Qty</th>

                <th class="invoice-col-rate right">Rate</th>

                <th class="invoice-col-amount right">Amount</th>

            </tr>
        </thead>

        <tbody>

            @foreach ($orderdetails as $item)
                <tr>

                    <td class="center">
                        {{ $loop->iteration }}
                    </td>

                    <td>

                        <div class="article">

                            {{ $item->service?->service_name }}

                        </div>

                    </td>

                    <td>

                        <div class="service">

                            {{ $item->service_name }}

                        </div>

                    </td>

                    <td class="center">

                        {{ $item->service_quantity }}

                    </td>

                    <td class="right">

                        {{ getFormattedCurrency($item->service_price) }}

                    </td>

                    <td class="right">

                        <strong>

                            {{ getFormattedCurrency($item->service_detail_total) }}

                        </strong>

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</div>
