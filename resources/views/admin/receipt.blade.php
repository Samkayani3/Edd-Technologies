<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $equipment->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .section { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Repair Receipt</div>
        <div>Date: {{ now()->format('F d, Y') }}</div>
    </div>

    <div class="section">
        <strong>Equipment Name:</strong> {{ $equipment->name }}<br>
        <strong>Description:</strong> {{ $equipment->description }}<br>
        <strong>Status:</strong> {{ $equipment->status }}
    </div>

    <div class="section">
        <strong>Customer:</strong> {{ $equipment->customer?->name ?? '—' }}<br>
        <strong>Technician:</strong> {{ $equipment->technician?->name ?? '—' }}
    </div>

    <div class="section">
        <strong>Total Price:</strong> ₱{{ number_format($equipment->price, 2) }}
    </div>

    <div class="section">
        <em>Thank you for choosing our services!</em>
    </div>
</body>
</html>
