@php
    $invoice['store'] = [
        'name' => $sitename,
        'address' => $address,
        'zipcode' => $zipcode,
        'phone' => $phone,
        'email' => $store_email,
        'gst' => $tax_number,
        'logo' => getSiteLogo(),
    ];

    $invoice['customer'] = [
        'name' => $customer->name ?? 'Walk-In Customer',
        'phone' => $customer->phone ?? '',
        'email' => $customer->email ?? '',
        'address' => $customer->address ?? '',
        'gst' => $customer->tax_number ?? '',
    ];
    $invoice = [
        'itemsTotal' => $orderdetails->sum('service_detail_total'),
        'addonTotal' => $order->addon_total ?? 0,
        'chargesTotal' => $order->additionalCharges->sum('amount'),
    ];
    $invoice['subTotal'] = $invoice['itemsTotal'] + $invoice['addonTotal'] + $invoice['chargesTotal'];
    $invoice['paid'] = $payments->sum('received_amount');
    $invoice['balance'] = max(0, $order->total - $invoice['paid']);
    $invoice['pieces'] = $orderdetails->sum('service_quantity');
    $invoice['services'] = $orderdetails->count();
    $invoice['discount'] = $order->discount ?? 0;
    $invoice['taxAmount'] = $order->tax_amount ?? 0;
    $invoice['taxPercentage'] = $order->tax_percentage ?? 0;
    $invoice['grandTotal'] = $order->total ?? 0;
    $invoice['status'] = getOrderStatus($order->status, 1);
    $invoice['paymentStatus'] = $invoice['balance'] <= 0 ? 'PAID' : ($invoice['paid'] > 0 ? 'PARTIAL' : 'UNPAID');

@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>{{ $lang->data['print_invoice'] ?? 'Print Invoice' }}</title>

    @include('prints.invoices.a4.styles')

</head>

<body>

    <div class="invoice-wrapper">

        <div class="invoice">

            {{-- Header --}}
            @include('prints.invoices.a4.header')

            {{-- Customer --}}
            @include('prints.invoices.a4.customer-info')

            {{-- Summary --}}
            @include('prints.invoices.a4.summary')

            {{-- Items --}}
            @include('prints.invoices.a4.items')

            {{-- Financial --}}
            @include('prints.invoices.a4.financial')

            {{-- Footer --}}
            @include('prints.invoices.a4.footer')

        </div>

    </div>

</body>

</html>
