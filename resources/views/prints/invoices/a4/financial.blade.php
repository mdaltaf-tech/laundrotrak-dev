 <div class="financial-grid">

     <!-- LEFT SIDE -->

     <div class="financial-card">

         <div class="financial-header">

             Charges & Payments

         </div>

         <div class="financial-body">
             {{-- Additional Services --}}
             @if ($order->addons->count())

                 <div class="financial-subtitle">
                     Additional Services
                 </div>

                 <table class="financial-table">

                     @foreach ($order->addons as $addon)
                         <tr>
                             <td>{{ $addon->addon_name }}</td>
                             <td>{{ getFormattedCurrency($addon->addon_price) }}</td>
                         </tr>
                     @endforeach

                 </table>

                 <hr>

             @endif
             @if ($order->additionalCharges->count())

                 <div class="financial-subtitle">
                     Order Charges
                 </div>

                 <table class="financial-table">

                     @foreach ($order->additionalCharges as $charge)
                         <tr>

                             <td>

                                 {{ $charge->chargeType?->charge_name }}

                                 @if ($charge->remarks)
                                     <br>

                                     <small>{{ $charge->remarks }}</small>
                                 @endif

                             </td>

                             <td>

                                 {{ getFormattedCurrency($charge->amount) }}

                             </td>

                         </tr>
                     @endforeach

                 </table>

                 <hr>

             @endif

             <div class="financial-subtitle">
                 Payments
             </div>
             <table class="financial-table">

                 <tr>

                     <td>Amount Paid</td>

                     <td>{{ getFormattedCurrency($invoice['paid']) }}</td>

                 </tr>

                 <tr>

                     <td>Outstanding Balance</td>

                     <td>{{ getFormattedCurrency($invoice['balance']) }}</td>

                 </tr>

             </table>

         </div>

     </div>


     <!-- RIGHT SIDE -->

     <div class="financial-card">

         <div class="financial-header">

             Invoice Summary

         </div>

         <div class="financial-body">

             <table class="summary-table">

                 <tr>

                     <td>Items Total</td>

                     <td>{{ getFormattedCurrency($invoice['itemsTotal']) }}</td>

                 </tr>

                 @if ($invoice['addonTotal'] > 0)
                     <tr>

                         <td>Addons</td>

                         <td>{{ getFormattedCurrency($invoice['addonTotal']) }}</td>

                     </tr>
                 @endif

                 @if ($invoice['chargesTotal'] > 0)
                     <tr>

                         <td>Order Charges</td>

                         <td>{{ getFormattedCurrency($invoice['chargesTotal']) }}</td>

                     </tr>
                 @endif

                 <tr>

                     <td>Sub Total</td>

                     <td>{{ getFormattedCurrency($invoice['subTotal']) }}</td>

                 </tr>

                 @if ($invoice['discount'] > 0)
                     <tr>

                         <td>Discount</td>

                         <td>

                             -{{ getFormattedCurrency($invoice['discount']) }}

                         </td>

                     </tr>
                 @endif

                 @if ($invoice['taxAmount'] > 0)
                     <tr>

                         <td>

                             Tax ({{ $invoice['taxPercentage'] }}%)

                         </td>

                         <td>

                             {{ getFormattedCurrency($invoice['taxAmount']) }}

                         </td>

                     </tr>
                 @endif

                 <tr class="summary-divider">

                     <td colspan="2">

                         <hr>

                     </td>

                 </tr>

                 <tr class="summary-grand">

                     <td>

                         GRAND TOTAL

                     </td>

                     <td>

                         {{ getFormattedCurrency($invoice['grandTotal']) }}

                     </td>

                 </tr>
             </table>

         </div>

     </div>

 </div>
