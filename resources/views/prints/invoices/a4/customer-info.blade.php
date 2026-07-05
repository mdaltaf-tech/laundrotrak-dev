<div class="info-grid">

    <!-- Customer Details -->

    <div class="info-card">

        <div class="info-card-header">
            Customer Details
        </div>

        <div class="info-card-body">

            <table class="info-table">

                <tr>
                    <td class="info-label">Customer</td>
                    <td class="info-value">
                        {{ $customer->name ?? 'Walk-In Customer' }}
                    </td>
                </tr>

                @if ($customer && $customer->phone)
                    <tr>
                        <td class="info-label">Mobile</td>
                        <td class="info-value">
                            {{ getCountryCode() }} {{ $customer->phone }}
                        </td>
                    </tr>
                @endif

                @if ($customer && $customer->email)
                    <tr>
                        <td class="info-label">Email</td>
                        <td class="info-value">
                            {{ $customer->email }}
                        </td>
                    </tr>
                @endif

                @if ($customer && $customer->address)
                    <tr>
                        <td class="info-label">Address</td>
                        <td class="info-value">
                            {{ $customer->address }}
                        </td>
                    </tr>
                @endif

                @if ($customer && $customer->tax_number)
                    <tr>
                        <td class="info-label">GSTIN</td>
                        <td class="info-value">
                            {{ $customer->tax_number }}
                        </td>
                    </tr>
                @endif

            </table>

        </div>

    </div>

    <!-- Invoice Details -->

    <div class="info-card">

        <div class="info-card-header">
            Invoice Details
        </div>

        <div class="info-card-body">

            <table class="info-table">

                <tr>
                    <td class="info-label">Order No</td>
                    <td class="info-value">
                        {{ $order->order_number }}
                    </td>
                </tr>

                <tr>
                    <td class="info-label">Order Date</td>
                    <td class="info-value">
                        {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                    </td>
                </tr>

                <tr>
                    <td class="info-label">Delivery</td>
                    <td class="info-value">
                        {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                    </td>
                </tr>

                <tr>
                    <td class="info-label">Order Status</td>
                    <td class="info-value">
                        <span class="status-badge">
                            {{ getOrderStatus($order->status, 1) }}
                        </span>
                    </td>
                </tr>

                @php
                    $paid = $payments->sum('received_amount');
                    $balance = max(0, $order->total - $paid);
                @endphp

                <tr>
                    <td class="info-label">Payment</td>
                    <td class="info-value">

                        @if ($balance == 0)
                            <span class="status-badge">
                                PAID
                            </span>
                        @elseif($paid > 0)
                            <span class="status-badge">
                                PARTIAL
                            </span>
                        @else
                            <span class="status-badge">
                                UNPAID
                            </span>
                        @endif

                    </td>
                </tr>

            </table>

        </div>

    </div>

</div>
