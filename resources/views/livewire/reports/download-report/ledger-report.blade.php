<!DOCTYPE html>
<html>
<head>

<style>

body{
    font-family: DejaVu Sans;
    font-size:12px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #ddd;
    padding:6px;
}

th{
    background:#f2f2f2;
}

.heading{
    text-align:center;
    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="heading">

<h2>Ledger Report</h2>

<h4>{{ $customer->name }}</h4>

<p>
{{ $start_date }}
to
{{ $end_date }}
</p>

</div>

@php
$debits=0+($first_data['debits']??0);
$credits=0+($first_data['credits']??0);
$balance=$debits-$credits;
@endphp


<table>

<thead>

<tr>

<th>Date</th>
<th>From</th>
<th>Particulars</th>
<th>Debit</th>
<th>Credit</th>
<th>Balance</th>

</tr>

</thead>

<tbody>

@foreach($data as $item)

@php

if($item['type']=='debit')
{
    $debits += $item['total'];
}
else
{
    $credits += $item['received_amount'];
}

$balance=$debits-$credits;

@endphp

<tr>

<td>
{{\Carbon\Carbon::parse($item['date'])->format('d/m/Y')}}
</td>

<td>
{{ $item['type']=='debit' ? 'Order':'Payment' }}
</td>

<td>

@if($item['type']=='debit')

Sales-#{{$item['order_number']}}

@else

Payment

@endif

</td>

<td>

@if($item['type']=='debit')
{{getFormattedCurrency($item['total'])}}
@else
0
@endif

</td>

<td>

@if($item['type']=='credit')
{{getFormattedCurrency($item['received_amount'])}}
@else
0
@endif

</td>

<td>

@if($balance<0)

{{getFormattedCurrency(abs($balance))}} Cr

@else

{{getFormattedCurrency($balance)}} Dr

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</body>
</html>