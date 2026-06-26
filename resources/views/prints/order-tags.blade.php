<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tags - {{ $order->order_number }}</title>

    <style>
        @page {
            size: 38mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 38mm;
            background: #fff;
            font-family: Arial, sans-serif;
            color: #000;
        }

        .tag {
            width: 38mm;
            padding: 2.5mm 2mm;
            text-align: center;
            overflow: hidden;

            page-break-after: avoid;
            break-after: avoid;
            margin-bottom: 2mm;
        }

        .brand {
            font-size: 8.5pt;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
            letter-spacing: 0px;
        }

        .order-number {
            font-size: 7.5pt;
            font-weight: 700;
            margin-top: 1.2mm;
            line-height: 1;
        }

        .tag-number {
            font-size: 10pt;
            font-weight: 700;
            margin-top: 1mm;
            line-height: 1;
            white-space: nowrap;
            letter-spacing: -0.3px;
        }

        .tag-position {
            font-size: 7.5pt;
            font-weight: 700;
            margin-top: 0.5mm;
            line-height: 1;
        }

        .article-name {
            font-size: 9pt;
            font-weight: 700;
            line-height: 1;
            margin-top: 0.8mm;
            word-break: break-word;
        }

        .service-name {
            font-size: 6pt;
            line-height: 1.05;
            margin-top: 0.8mm;
            word-break: break-word;
        }

        .customer-name {
            font-size: 7pt;
            font-weight: 700;
            line-height: 1.05;
            margin-top: 1.5mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .barcode-wrap {
            margin-top: 0.8mm;
            display: flex;
            justify-content: center;
            overflow: hidden;
        }

        .cut-line {
            border-top: 1px dashed #000;
            margin-top: 0.5mm;
        }

        .barcode {
            max-width: 33mm;
            height:28px;
            margin-bottom: 0.5mm;
        }

        .page-break{
            page-break-after: always;
            break-after: page;
        }

        @media screen {
            body {
                margin: 0px;
            }
        }
    </style>
</head>
<body>
    @foreach ($articles as $index => $article)
        <div class="tag @if(!$loop->last) page-break @endif">
            <div class="brand">FAEBLO-[OD-NPD-KHR]</div>

            <div class="order-number">
                {{ $order->order_number }}
            </div>

            <div class="tag-number">
                {{ $article->tag_number }}
            </div>

            <div class="tag-position">
                {{ $index + 1 }}/{{ $totalTags }}
            </div>

            <div class="article-name">
                {{ $article->article_name }}
            </div>

            <div class="service-name">
                {{ $article->service_name }}
            </div>

            <div class="customer-name">
                {{ $order->customer_name }}
            </div>

            <div class="barcode-wrap">
                <svg
                    class="barcode"
                    jsbarcode-format="CODE128"
                    jsbarcode-value="{{ $article->tag_number }}"
                    jsbarcode-width="1"
                    jsbarcode-height="28"
                    jsbarcode-displayValue="false">
                </svg>
            </div>

            <div class="cut-line"></div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <script>
        window.addEventListener('load', function () {
            JsBarcode('.barcode').init();

            setTimeout(function () {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>
