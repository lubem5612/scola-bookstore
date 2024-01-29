<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt for Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Receipt for Order #{{ $order->id }}</h1>

        <p>Invoice Number: {{ $order->invoice_number }}</p>
        <p>Payment Reference: {{ $order->payment_reference }}</p>
        <p>Date: {{ $order->order_date }}</p>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->resource_id }} ({{ $item->resource_type }})</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ $item->unit_price }}</td>
                        <td>${{ $item->total_amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>Total Amount: ${{ $order->orderItems->total_amount }}</p> 
        <p>Thank you for your patronage</p>
        <p style="margin-top: 20px;">For any inquiries, please contact our support team.</p>
    </div>
</body>
</html>
