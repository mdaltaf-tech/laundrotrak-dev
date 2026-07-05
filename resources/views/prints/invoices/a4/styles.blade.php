<style>
    :root {

        --primary: #1f2937;
        --secondary: #6b7280;

        --text: #1f2937;
        --muted: #6b7280;

        --border: #e5e7eb;
        --border-dark: #d1d5db;

        --background: #ffffff;
        --background-light: #f9fafb;

        --success: #15803d;
        --warning: #d97706;
        --danger: #dc2626;

        --radius: 8px;

        --shadow: 0 2px 8px rgba(0, 0, 0, .06);

    }

    /* ===============================
                    PAGE
            =============================== */

    @page {
        size: A4 portrait;
        margin: 5mm;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        background: #efefef;
        font-family: "Segoe UI", Arial, Helvetica, sans-serif;
        font-size: 13px;
        color: #222;
    }

    @media print {

        html,
        body {
            background: #fff;
        }

        .invoice-wrapper {
            box-shadow: none !important;
            margin: 0;
        }
    }

    /* ===============================
            MAIN PAGE
        =============================== */

    .invoice-wrapper {
        width: 210mm;
        min-height: 297mm;
        margin: 20px auto;
        background: #fff;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .15);
    }

    .invoice {
        padding: 16mm;
    }

    /* ===============================
            TYPOGRAPHY
        =============================== */

    h1 {
        font-size: 28px;
        font-weight: 700;
    }

    h2 {
        font-size: 22px;
        font-weight: 700;
    }

    h3 {
        font-size: 18px;
        font-weight: 600;
    }

    h4 {
        font-size: 15px;
        font-weight: 600;
    }

    h5 {
        font-size: 13px;
        font-weight: 600;
    }

    p {
        line-height: 1.6;
    }

    small {
        font-size: 11px;
        color: #666;
    }

    /* ===============================
            UTILITIES
        =============================== */

    .d-flex {
        display: flex;
    }

    .justify-between {
        justify-content: space-between;
    }

    .justify-center {
        justify-content: center;
    }

    .align-center {
        align-items: center;
    }

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .fw-bold {
        font-weight: bold;
    }

    .text-muted {
        color: #777;
    }

    .uppercase {
        text-transform: uppercase;
    }

    /* ===============================
            SPACING
        =============================== */

    .mb-5 {
        margin-bottom: 5px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .mb-15 {
        margin-bottom: 15px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .mb-30 {
        margin-bottom: 30px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .mt-20 {
        margin-top: 20px;
    }

    .mt-30 {
        margin-top: 30px;
    }

    /* ===============================
            CARDS
        =============================== */

    .card {
        border: 1px solid #e5e5e5;
        border-radius: 6px;
        padding: 14px;
        background: #fff;
    }

    .card-title {
        font-size: 11px;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #777;
        margin-bottom: 10px;
        font-weight: 700;
    }

    /* ===============================
            TABLE
        =============================== */

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 8px 10px;
    }

    th {
        background: #f7f7f7;
        border-bottom: 2px solid #ddd;
        text-align: left;
        font-size: 12px;
    }

    td {
        border-bottom: 1px solid #eee;
    }

    /* ===============================
            HORIZONTAL LINE
        =============================== */

    hr {
        border: none;
        border-top: 1px solid #ddd;
        margin: 20px 0;
    }

    /* ===============================
            PLACEHOLDER
        =============================== */

    .placeholder {
        border: 2px dashed #ddd;
        border-radius: 8px;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 20px;
        font-weight: 300;
    }

    /* ======================================
    HEADER
====================================== */

    .invoice-header {
        display: grid;
        grid-template-columns: 1fr 230px;
        column-gap: 20px;
        align-items: start;
        justify-content: space-between;
        padding-bottom: 12px;
        margin-bottom: 18px;
        border-bottom: 2px solid var(--primary);
    }

    .company {
        display: flex;
        gap: 14px;
    }

    .company-logo {

        width: 70px;
        height: 70px;

        border: 1px solid var(--border);
        border-radius: 6px;

        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;
    }

    .company-logo img {

        max-width: 60px;
        max-height: 60px;

    }

    .company-details h1 {
        font-size: 18px;
        margin-bottom: 2px;
        line-height: 1.2;
    }

    .company-tagline {
        color: #666;
        font-size: 12px;
        margin-bottom: 6px;
    }

    .company-info {
        font-size: 11px;
        line-height: 1.5;
        color: #444;
    }

    .invoice-title {
        text-align: right;
    }

    .invoice-title h2 {
        font-size: 28px;
        letter-spacing: 1px;
        line-height: 1.1;
        color: #111;
    }

    .invoice-title .copy {
        margin-top: 8px;
        display: inline-block;
        background: #222;
        color: #fff;
        padding: 5px 12px;
        border-radius: 160px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    /* ======================================
    INFORMATION SECTION
====================================== */

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 25px;
    }

    .info-card {
        border: 1px solid #e4e4e4;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .info-card-header {
        background: #f6f6f6;
        border-bottom: 1px solid #ddd;
        padding: 10px 15px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #444;
    }

    .info-card-body {
        padding: 14px 15px;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table td {
        border: none;
        padding: 7px 0;
        vertical-align: top;
    }

    .info-label {
        width: 38%;
        color: #666;
        font-weight: 600;
    }

    .info-value {
        font-weight: 600;
        color: #222;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        background: #f2f2f2;
        color: #222;
    }

    /* ======================================
    SUMMARY RIBBON
====================================== */

    .summary-ribbon {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
        margin-bottom: 28px;
    }

    .summary-box {
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        padding: 14px 10px;
        text-align: center;
        background: #fafafa;
    }

    .summary-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #777;
        letter-spacing: .5px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .summary-value {
        font-size: 22px;
        font-weight: 700;
        color: #222;
    }

    .summary-value.currency {
        font-size: 18px;
    }

    .summary-box.highlight {
        background: #222;
        color: #fff;
        border-color: #222;
    }

    .summary-box.highlight .summary-label {
        color: #ddd;
    }

    .summary-box.highlight .summary-value {
        color: #fff;
    }

    /* ======================================
    ITEMS TABLE
====================================== */

    .items-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #444;
        margin-bottom: 10px;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .items-table thead {
        display: table-header-group;
    }

    .items-table thead th {

        background: #2d3748;
        color: #fff;

        padding: 12px 10px;

        font-size: 12px;

        font-weight: 700;

        text-transform: uppercase;

        border: none;

        letter-spacing: .5px;

    }

    .items-table tbody tr {

        page-break-inside: avoid;

    }

    .items-table tbody tr:nth-child(even) {

        background: #fafafa;

    }

    .items-table td {

        padding: 11px 10px;

        border-bottom: 1px solid #ececec;

        vertical-align: middle;

        font-size: 13px;

    }

    .items-table .center {

        text-align: center;

    }

    .items-table .right {

        text-align: right;

    }

    .items-table .article {

        font-weight: 600;

    }

    .items-table .service {

        color: #666;

        font-size: 12px;

    }

    .items-table tfoot td {

        border: none;

    }

    .page-break {

        page-break-inside: avoid;

    }

    @media print {

        .items-table {

            page-break-inside: auto;

        }

        .items-table tr {

            page-break-inside: avoid;

        }

        .items-table thead {

            display: table-header-group;

        }

    }

    /* ======================================
    FINANCIAL SECTION
====================================== */

    .financial-grid {
        display: grid;
        grid-template-columns: 1.2fr .8fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .financial-subtitle {

        margin: 18px 0 10px;

        padding-bottom: 6px;

        border-bottom: 1px solid var(--border);

        font-size: 12px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .8px;

        color: var(--primary);

    }

    .financial-card {
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .financial-header {
        background: #f6f6f6;
        border-bottom: 1px solid #ddd;
        padding: 12px 15px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
    }

    .financial-body {
        padding: 15px;
    }

    .financial-table {
        width: 100%;
        border-collapse: collapse;
    }

    .financial-table td {
        border: none;
        padding: 8px 0;
        font-size: 13px;
    }

    .financial-table tr:not(:last-child) td {
        border-bottom: 1px dashed #ececec;
    }

    .financial-table td:last-child {
        text-align: right;
        font-weight: 600;
    }

    .summary-table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary-table td {
        border: none;
        padding: 8px 0;
        font-size: 13px;
    }

    .summary-table td:last-child {
        text-align: right;
        font-weight: 600;
    }

    .summary-divider td {
        padding: 12px 0;
    }

    .summary-divider hr {
        margin: 0;
    }

    .summary-grand {

        background: #2d3748;
        color: #fff;

    }

    .summary-grand td {

        padding: 12px 10px;

        font-size: 15px;

        font-weight: 700;

    }

    .summary-grand td:last-child {

        text-align: right;

    }

    .summary-section-title {

        padding: 14px 0 10px !important;

        font-size: 12px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .8px;

        color: var(--primary);

        border-bottom: 1px solid var(--border);

    }

    .summary-balance td {

        font-weight: 700;

        font-size: 14px;

        border-top: 1px solid var(--border);

        padding-top: 10px;

    }

    /* ======================================
    FOOTER
====================================== */

    .footer-grid {
        display: grid;
        grid-template-columns: 1.3fr .7fr;
        gap: 20px;
        margin-top: 20px;
    }

    .footer-card {
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .footer-header {
        background: #f6f6f6;
        border-bottom: 1px solid #ddd;
        padding: 10px 15px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
    }

    .footer-body {
        padding: 15px;
        line-height: 1.7;
    }

    .footer-body p {
        margin-bottom: 8px;
        line-height: 1.7;
    }

    .footer-body ul {
        margin: 0;
        padding-left: 18px;
    }

    .footer-body li {
        margin-bottom: 6px;
        line-height: 1.6;
    }

    .remarks-list {

        margin: 0;

        padding-left: 20px;

    }

    .remarks-list li {

        margin-bottom: 8px;

    }

    .signature-section {

        display: grid;

        grid-template-columns: 1fr 1fr;

        gap: 50px;

        margin-top: 35px;

    }

    .signature-box {

        text-align: center;

    }

    .signature-line {

        border-top: 1px solid #444;

        margin-bottom: 8px;

        padding-top: 8px;

        font-size: 12px;

        font-weight: 600;

    }

    .invoice-note {

        margin-top: 30px;

        border-top: 2px solid #222;

        padding-top: 15px;

        text-align: center;

    }

    .invoice-note h3 {

        margin-bottom: 6px;

        font-size: 18px;

    }

    .invoice-note p {

        color: #666;

        font-size: 12px;

    }

    .invoice-col-sl {
        width: 5%;
    }

    .invoice-col-article {
        width: 25%;
    }

    .invoice-col-service {
        width: 30%;
    }

    .invoice-col-qty {
        width: 10%;
    }

    .invoice-col-rate {
        width: 15%;
    }

    .invoice-col-amount {
        width: 15%;
    }

    .invoice-table {

        width: 100%;

        border-collapse: collapse;

    }

    .invoice-table td {

        padding: 6px 0;

        border: none;

    }

    .invoice-note {

        margin-top: 25px;

        text-align: center;

        border-top: 2px solid var(--primary);

        padding-top: 18px;

    }

    .invoice-note h3 {

        margin-bottom: 8px;

    }

    .invoice-note p {

        color: var(--secondary);

        margin-bottom: 4px;

    }

    .footer-grid {

        page-break-inside: avoid;

    }
</style>
