<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - #{{ $payment->id }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .receipt { box-shadow: none; border: 1px solid #000; }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .gym-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .gym-address {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .receipt-title {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .receipt-number {
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .label {
            color: #666;
            font-weight: 500;
        }
        
        .value {
            color: #333;
            font-weight: bold;
        }
        
        .amount {
            font-size: 20px;
            color: #28a745;
            font-weight: bold;
        }
        
        .total-section {
            border-top: 2px solid #333;
            padding-top: 20px;
            margin-top: 30px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .discount-row {
            color: #dc3545;
            font-style: italic;
        }
        
        .discount {
            color: #dc3545;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .membership-dates {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        
        .date-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .date-row:last-child {
            margin-bottom: 0;
        }

        .cashier-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">Print Receipt</button>
    
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/rba-logo/rba logo.png') }}" alt="Ripped Body Anytime Logo" class="logo">
            <div class="gym-name">Ripped Body Anytime</div>
            <div class="gym-address">
                Block 7 Lot 2 Sto. Tomas Village,<br>
                Brgy. 168 Deparo, City of Caloocan,<br>
                Caloocan, Philippines, 1400
            </div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
            <div class="receipt-number">Receipt #{{ $payment->id }}</div>
        </div>
        
        <!-- Payment Information -->
        <div class="section">
            <div class="section-title">Payment Details</div>
            <div class="info-row">
                <span class="label">Payment ID:</span>
                <span class="value">#{{ $payment->id }}</span>
            </div>
            <div class="info-row">
                <span class="label">Date:</span>
                <span class="value">{{ $payment->payment_date->format('M d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Time:</span>
                <span class="value">{{ $payment->payment_time ? \Carbon\Carbon::parse($payment->payment_time)->setTimezone('Asia/Manila')->format('h:i:s A') : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Payment Method:</span>
                <span class="value">Cash</span>
            </div>
            @if($payment->tin)
            <div class="info-row">
                <span class="label">TIN:</span>
                <span class="value">{{ $payment->tin }}</span>
            </div>
            @endif
        </div>
        
        <!-- Membership Details -->
        <div class="section">
            <div class="section-title">Membership Details</div>
            <div class="info-row">
                <span class="label">Plan Type:</span>
                <span class="value">{{ $payment->plan_type === 'vip' ? 'VIP' : ucfirst($payment->plan_type) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Duration:</span>
                <span class="value">{{ ucfirst($payment->duration_type) }}</span>
            </div>
            
            <div class="membership-dates">
                <div class="date-row">
                    <span class="label">Start Date:</span>
                    <span class="value">{{ $payment->membership_start_date->format('M d, Y') }}</span>
                </div>
                <div class="date-row">
                    <span class="label">Expiration Date:</span>
                    <span class="value">{{ $payment->membership_expiration_date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="total-section">
            @if($payment->hasDiscount())
                <div class="total-row">
                    <span>Original Amount:</span>
                    <span class="amount">₱{{ number_format($payment->original_amount, 2) }}</span>
                </div>
                <div class="total-row discount-row">
                    <span>Discount ({{ $payment->discount_description }}):</span>
                    <span class="amount discount">-₱{{ number_format($payment->discount_amount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span class="amount">₱{{ number_format($payment->amount, 2) }}</span>
                </div>
            @else
                <div class="total-row">
                    <span>Total:</span>
                    <span class="amount">₱{{ number_format($payment->amount, 2) }}</span>
                </div>
            @endif
            <div class="total-row">
                <span>Amount Received:</span>
                <span class="amount">₱{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Change:</span>
                <span class="amount">₱0.00</span>
            </div>
        </div>

        <!-- Cashier Section -->
        <div class="cashier-section">
            <div class="info-row">
                <span class="label">Cashier Name:</span>
                <span class="value">{{ auth()->user()->name }}</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing Ripped Body Anytime!</p>
            <p>Generated on {{ now()->setTimezone('Asia/Manila')->format('M d, Y \a\t h:i:s A') }}</p>
        </div>
    </div>
</body>
</html>