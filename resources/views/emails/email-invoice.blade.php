<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
</head>
<body>
    <div style="padding: 20px; background-color: #fff; color: {{ $color ?? '#000000' }};">
        @include('public.favicon')
        <img src="{{ $logo ?? asset('default-logo.png') }}" alt="Company Logo" style="width: 100px; height: auto; margin-bottom: 20px;">
        <h1 style="color: {{ $color ?? '#000000' }};">Invoice: {{ $invoiceData['invoice_number'] }}</h1>
        <p><strong>Client:</strong> {{ $invoiceData['client_name'] ?? 'Unknown' }}</p>
        <p><strong>Email:</strong> {{ $invoiceData['client_email'] ?? 'Not Provided' }}</p>
        <p><strong>Date:</strong> {{ $invoiceData['invoice_date'] ?? 'N/A' }}</p>
        <p><strong>Due:</strong> {{ $invoiceData['due_date'] ?? 'N/A' }}</p>

        <hr>
        <h2>Items</h2>
        <ul>
            @if (!empty($invoiceData['items']))
                @foreach ($invoiceData['items'] as $item)
                    <li>
                        {{ $item['description'] ?? 'No description' }} - 
                        {{ $item['quantity'] ?? 0 }} x RM{{ number_format($item['unit_price'] ?? 0, 2) }} = 
                        RM{{ number_format($item['total_unit_price'] ?? 0, 2) }}
                    </li>
                @endforeach
            @else
                <li>No items found</li>
            @endif
        </ul>
        <p><strong>Total:</strong> RM{{ number_format($invoiceData['total'] ?? 0, 2) }}</p>

        <hr>
        <h2>Sender Information</h2>
        <p><strong>Sender:</strong> {{ $invoiceData['sender_name'] }}</p>
        <p><strong>Email:</strong> {{ $invoiceData['sender_email'] }}</p>
    </div>
</body>
</html>
