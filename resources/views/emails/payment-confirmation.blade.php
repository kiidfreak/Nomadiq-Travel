<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Received - Nomadiq</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #4caf50, #45a049);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #ddd;
        }
        .payment-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #4caf50;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #2e7d32;
        }
        .highlight {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
            background: #4caf50;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌊 Nomadiq</h1>
        <h2>Payment Received! ✅</h2>
    </div>

    <div class="content">
        <p>Dear {{ $booking->customer->name }},</p>
        
        <p>Great news! We have received your payment for booking <strong>{{ $booking->booking_reference }}</strong>.</p>

        <div class="highlight">
            <h3>Payment Confirmed</h3>
            <p>Your payment has been successfully processed and recorded.</p>
        </div>

        <div class="payment-details">
            <h3>💳 Payment Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Payment Amount:</span>
                <span><strong>${{ number_format($payment->amount, 2) }}</strong></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Transaction ID:</span>
                <span><strong>{{ $payment->transaction_id }}</strong></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Date:</span>
                <span>{{ $payment->paid_at ? $payment->paid_at->format('F j, Y g:i A') : now()->format('F j, Y g:i A') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span><span class="status-badge">Completed</span></span>
            </div>
        </div>

        <div class="payment-details">
            <h3>📋 Booking Summary</h3>
            
            <div class="detail-row">
                <span class="detail-label">Booking Reference:</span>
                <span><strong>{{ $booking->booking_reference }}</strong></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Package:</span>
                <span>{{ $booking->package->title ?? 'N/A' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Start Date:</span>
                <span>{{ $booking->start_date->format('F j, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span><strong>${{ number_format($booking->total_amount, 2) }}</strong></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Total Paid:</span>
                <span><strong class="text-green-600">${{ number_format($booking->total_paid, 2) }}</strong></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Remaining Balance:</span>
                <span><strong>${{ number_format($booking->balance, 2) }}</strong></span>
            </div>
        </div>

        @if($booking->isFullyPaid())
        <div class="highlight" style="background: #e3f2fd; border-left: 4px solid #2196f3;">
            <h3>🎉 Booking Fully Paid!</h3>
            <p>Your booking is now <strong>confirmed</strong>! We'll send you a detailed itinerary and pre-trip information closer to your travel date.</p>
            <p>Thank you for choosing Nomadiq. We're excited to share unforgettable coastal adventures with you!</p>
        </div>
        @else
        <div class="highlight" style="background: #fff3e0; border-left: 4px solid #ff9800;">
            <h3>💰 Remaining Balance</h3>
            <p>Your booking still has a remaining balance of <strong>${{ number_format($booking->balance, 2) }}</strong>.</p>
            <p>Please complete the payment to confirm your booking. You can make another payment using the same methods.</p>
        </div>
        @endif

        <div class="footer">
            <h3>📞 Contact Information</h3>
            <p><strong>Email:</strong> info@nomadiq.com</p>
            <p><strong>Phone:</strong> +254 700 757 129</p>
            <p><strong>Website:</strong> www.nomadiq.com</p>
            
            <p style="margin-top: 20px;">
                <em>Thank you for choosing Nomadiq. Live. Connect. Belong. We can't wait to share unforgettable coastal adventures with you!</em>
            </p>
            
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                This is an automated email. Please do not reply to this email address.
                If you have any questions, please contact us using the information above.
            </p>
        </div>
    </div>
</body>
</html>

