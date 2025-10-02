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
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 1rem;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .receipt {
            width: 100%;
            max-width: 90vw; /* Responsive width */
            margin: 0 auto;
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive breakpoints */
        @media (min-width: 640px) {
            .receipt {
                max-width: 32rem; /* 512px */
            }
        }
        
        @media (min-width: 768px) {
            .receipt {
                max-width: 36rem; /* 576px */
            }
        }
        
        @media (min-width: 1024px) {
            .receipt {
                max-width: 40rem; /* 640px */
            }
        }
        
        @media (min-width: 1280px) {
            .receipt {
                max-width: 44rem; /* 704px */
            }
        }
        
        @media (min-width: 1536px) {
            .receipt {
                max-width: 48rem; /* 768px */
            }
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 1.25rem;
            margin-bottom: 1.875rem;
        }
        
        .logo {
            width: 6.25rem; /* 100px */
            height: auto;
            margin-bottom: 0.625rem;
        }
        
        .gym-name {
            font-size: 1.5rem; /* 24px */
            font-weight: bold;
            color: #333;
            margin-bottom: 0.625rem;
        }

        .gym-address {
            font-size: 0.75rem; /* 12px */
            color: #666;
            margin-bottom: 0.9375rem;
            line-height: 1.4;
        }
        
        .receipt-title {
            font-size: 1.125rem; /* 18px */
            color: #666;
            margin-bottom: 0.625rem;
        }
        
        .receipt-number {
            font-size: 1rem; /* 16px */
            color: #333;
            font-weight: bold;
        }
        
        .section {
            margin-bottom: 1.5625rem;
        }
        
        .section-title {
            font-size: 1rem; /* 16px */
            font-weight: bold;
            color: #333;
            margin-bottom: 0.9375rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.3125rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem; /* 14px */
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
            font-size: 1.25rem; /* 20px */
            color: #28a745;
            font-weight: bold;
        }
        
        .total-section {
            border-top: 2px solid #333;
            padding-top: 1.25rem;
            margin-top: 1.875rem;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.125rem; /* 18px */
            font-weight: bold;
            color: #333;
            margin-bottom: 0.625rem;
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
            margin-top: 2.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.75rem; /* 12px */
        }
        
        .print-button {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            background: #007bff;
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 0.3125rem;
            cursor: pointer;
            font-size: 0.875rem;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .membership-dates {
            background-color: #f8f9fa;
            padding: 0.9375rem;
            border-radius: 0.3125rem;
            margin-top: 0.9375rem;
        }
        
        .date-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .date-row:last-child {
            margin-bottom: 0;
        }

        .cashier-section {
            margin-top: 2.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #eee;
        }

        /* Tablet Responsive */
        @media (max-width: 768px) {
            body {
                padding: 0.75rem;
            }
            
            .receipt {
                max-width: 100%;
                padding: 1.25rem;
            }
            
            .gym-name {
                font-size: 1.375rem; /* 22px */
            }
            
            .gym-address {
                font-size: 0.6875rem; /* 11px */
            }
            
            .receipt-title {
                font-size: 1rem; /* 16px */
            }
            
            .receipt-number {
                font-size: 0.9375rem; /* 15px */
            }
            
            .section-title {
                font-size: 0.9375rem; /* 15px */
            }
            
            .info-row {
                font-size: 0.8125rem; /* 13px */
            }
            
            .amount {
                font-size: 1.125rem; /* 18px */
            }
            
            .total-row {
                font-size: 1rem; /* 16px */
            }
            
            .print-button {
                top: 0.75rem;
                right: 0.75rem;
                padding: 0.5rem 1rem;
                font-size: 0.8125rem;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
                align-items: flex-start;
                padding-top: 3rem;
            }
            
            .receipt {
                padding: 1rem;
                border-radius: 0.375rem;
            }
            
            .header {
                padding-bottom: 1rem;
                margin-bottom: 1.5rem;
            }
            
            .logo {
                width: 5rem; /* 80px */
            }
            
            .gym-name {
                font-size: 1.25rem; /* 20px */
            }
            
            .gym-address {
                font-size: 0.625rem; /* 10px */
            }
            
            .receipt-title {
                font-size: 0.9375rem; /* 15px */
            }
            
            .receipt-number {
                font-size: 0.875rem; /* 14px */
            }
            
            .section {
                margin-bottom: 1.25rem;
            }
            
            .section-title {
                font-size: 0.875rem; /* 14px */
                margin-bottom: 0.75rem;
            }
            
            .info-row {
                font-size: 0.75rem; /* 12px */
                margin-bottom: 0.375rem;
            }
            
            .amount {
                font-size: 1rem; /* 16px */
            }
            
            .total-row {
                font-size: 0.9375rem; /* 15px */
            }
            
            .total-section {
                padding-top: 1rem;
                margin-top: 1.5rem;
            }
            
            .membership-dates {
                padding: 0.75rem;
            }
            
            .cashier-section {
                margin-top: 2rem;
                padding-top: 1rem;
            }
            
            .footer {
                margin-top: 2rem;
                padding-top: 1rem;
                font-size: 0.6875rem; /* 11px */
            }
            
            .print-button {
                top: 0.5rem;
                right: 0.5rem;
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 360px) {
            .receipt {
                padding: 0.75rem;
            }
            
            .gym-name {
                font-size: 1.125rem; /* 18px */
            }
            
            .gym-address {
                font-size: 0.5625rem; /* 9px */
            }
            
            .info-row {
                font-size: 0.6875rem; /* 11px */
            }
            
            .amount {
                font-size: 0.9375rem; /* 15px */
            }
            
            .total-row {
                font-size: 0.875rem; /* 14px */
            }
        }

        /* Large Desktop */
        @media (min-width: 1200px) {
            .receipt {
                max-width: 32rem; /* 512px */
                padding: 2rem;
            }
            
            .gym-name {
                font-size: 1.75rem; /* 28px */
            }
            
            .gym-address {
                font-size: 0.875rem; /* 14px */
            }
            
            .receipt-title {
                font-size: 1.25rem; /* 20px */
            }
            
            .receipt-number {
                font-size: 1.125rem; /* 18px */
            }
            
            .section-title {
                font-size: 1.125rem; /* 18px */
            }
            
            .info-row {
                font-size: 1rem; /* 16px */
            }
            
            .amount {
                font-size: 1.5rem; /* 24px */
            }
            
            .total-row {
                font-size: 1.25rem; /* 20px */
            }
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