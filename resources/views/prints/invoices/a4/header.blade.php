<div class="invoice-header">

    <div class="company">

        {{-- <div class="company-logo">

            @if (getSiteLogo())
                <img src="{{ getSiteLogo() }}" alt="Logo">
            @endif

        </div> --}}

        <div class="company-details">

            <h1>{{ strtoupper($sitename) }}</h1>

            <div class="company-tagline">
                Premium Laundry • Dry Cleaning • Shoe Care • Steam Iron
            </div>

            <div class="company-info">

                {{ $address }}
                @if (!empty($zipcode))
                    - {{ $zipcode }}
                @endif

                <br>

                {{ getCountryCode() }} {{ $phone }}

                @if (!empty($store_email))
                    <br>{{ $store_email }}
                @endif

                @if (!empty($tax_number))
                    <br>GSTIN : {{ $tax_number }}
                @endif

            </div>

        </div>

    </div>

    <div class="invoice-title">

        <h2>INVOICE</h2>

        <span class="copy">
            CUSTOMER COPY
        </span>

    </div>

</div>
