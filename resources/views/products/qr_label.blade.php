<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>QR Label - {{ $product->nama_produk }}</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: #f3f4f6;
        }
        .label-card {
            background: #fff;
            padding: 24px 32px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(15,23,42,.12);
            text-align: center;
        }
        .label-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .label-sku {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .label-qr img {
            width: 220px;
            height: 220px;
        }
        .label-footer {
            margin-top: 12px;
            font-size: 12px;
            color: #9ca3af;
        }
        @media print {
            body { background: #fff; }
            .label-card {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
<div class="label-card">
    <div class="label-title">{{ $product->nama_produk }}</div>
    <div class="label-sku">SKU: {{ $product->sku }}</div>
    <div class="label-qr">
        <img src="data:image/png;base64,{{ $pngData }}" alt="QR {{ $product->sku }}">
    </div>
    <div class="label-footer">
        Scan untuk identifikasi produk
    </div>
</div>
</body>
</html>
