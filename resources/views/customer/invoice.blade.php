<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $payment->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            margin: 0;
            font-size: 28px;
            color: #1f2937;
        }
        .invoice-title p {
            margin: 5px 0 0 0;
            color: #6b7280;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .customer-info, .invoice-info {
            width: 45%;
        }
        .customer-info h3, .invoice-info h3 {
            margin: 0 0 10px 0;
            color: #374151;
            font-size: 16px;
        }
        .customer-info p, .invoice-info p {
            margin: 5px 0;
            color: #6b7280;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #374151;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #6b7280;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .total-table {
            width: 300px;
        }
        .total-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .total-table .total-row {
            background-color: #f9fafb;
            font-weight: bold;
            color: #1f2937;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-failed {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">{{ $appName ?? 'Gym Management' }}</div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <p>Invoice #{{ $payment->id }}</p>
        </div>
    </div>

    <div class="invoice-details">
        <div class="customer-info">
            <h3>Bill To:</h3>
            <p><strong>{{ $customer->first_name }} {{ $customer->last_name }}</strong></p>
            <p>{{ $customer->email }}</p>
            @if($customer->phone)
                <p>{{ $customer->phone }}</p>
            @endif
            @if($customer->address)
                <p>{{ $customer->address }}</p>
            @endif
            @if($customer->city)
                <p>{{ $customer->city }}</p>
            @endif
        </div>
        
        <div class="invoice-info">
            <h3>Invoice Details:</h3>
            <p><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y') }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $payment->status }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </p>
            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Package</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $payment->package->name ?? 'Gym Membership' }}</td>
                <td>{{ $payment->package->name ?? 'Standard Package' }}</td>
                <td>{{ \App\Services\SettingsService::formatCurrency($payment->amount) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <table class="total-table">
            <tr>
                <td>Subtotal:</td>
                <td>{{ \App\Services\SettingsService::formatCurrency($payment->amount) }}</td>
            </tr>
            <tr>
                <td>Tax:</td>
                <td>{{ \App\Services\SettingsService::formatCurrency(0) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total:</td>
                <td>{{ \App\Services\SettingsService::formatCurrency($payment->amount) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>For any questions regarding this invoice, please contact our support team.</p>
        <p>{{ $appName ?? 'Gym Management' }} - {{ $customer->branch->name ?? 'Main Branch' }}</p>
    </div>
</body>
</html>

