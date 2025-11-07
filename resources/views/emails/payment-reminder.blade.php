<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reminder - Nomadiq</title>
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
            background: linear-gradient(135deg, 
                @if($daysSince >= 14) #f44336, #d32f2f 
                @elseif($daysSince >= 7) #ff9800, #f57c00 
                @else #C67B52, #C67B52 
                @endif
            );
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
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid 
                @if($daysSince >= 14) #f44336
                @elseif($daysSince >= 7) #ff9800
                @else #C67B52
                @endif;
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
            color: #C67B52;
        }
        .highlight {
            background: 
                @if($daysSince >= 14) #ffebee
                @elseif($daysSince >= 7) #fff3e0
                @else #e8f5e8
                @endif;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid 
                @if($daysSince >= 14) #f44336
                @elseif($daysSince >= 7) #ff9800
                @else #4caf50
                @endif;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #C67B52;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌊 Nomadiq</h1>
        <h2>
            @if($daysSince >= 14)
                Final Payment Reminder ⚠️
            @elseif($daysSince >= 7)
                Important Payment Reminder
            @else
                Friendly Payment Reminder
            @endif
        </h2>
    </div>

    <div class="content">
        <p>Dear {{ $booking->customer->name }},</p>
        
        @if($daysSince >= 14)
        <p>This is a <strong>final reminder</strong> regarding your pending payment for booking <strong>{{ $booking->booking_reference }}</strong>.</p>
        <p>Your booking will be cancelled if payment is not received soon. Please complete your payment to secure your adventure.</p>
        @elseif($daysSince >= 7)
        <p>We noticed that your payment for booking <strong>{{ $booking->booking_reference }}</strong> is still pending.</p>
        <p>To secure your booking and avoid any inconvenience, please complete your payment as soon as possible.</p>
        @else
        <p>We hope you're as excited as we are about your upcoming Nomadiq adventure!</p>
        <p>This is a friendly reminder that your payment for booking <strong>{{ $booking->booking_reference }}</strong> is still pending.</p>
        @endif

        <div class="info-box">
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
                <span>${{ number_format($booking->total_paid, 2) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Remaining Balance:</span>
                <span><strong style="color: #C67B52;">${{ number_format($booking->balance, 2) }}</strong></span>
            </div>
        </div>

        <div class="highlight">
            <h3>💳 Complete Your Payment</h3>
            <p>To secure your booking, please complete your payment using one of the methods below:</p>
            
            <div style="margin: 15px 0;">
                <h4>📱 M-Pesa Payment (Recommended for Kenya)</h4>
                <p><strong>Paybill Number:</strong> 123456</p>
                <p><strong>Account Number:</strong> {{ $booking->booking_reference }}</p>
                <p><strong>Amount:</strong> KES {{ number_format($booking->balance * 130, 2) }} (Approx. ${{ number_format($booking->balance, 2) }})</p>
            </div>

            <div style="margin: 15px 0; padding-top: 15px; border-top: 1px solid #ddd;">
                <h4>🏦 Bank Transfer</h4>
                <p><strong>Bank:</strong> KCB Bank Kenya</p>
                <p><strong>Account:</strong> Nomadiq</p>
                <p><strong>Account Number:</strong> 1234567890</p>
                <p><strong>Reference:</strong> {{ $booking->booking_reference }}</p>
            </div>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ config('app.url') }}/bookings/{{ $booking->id }}/payment" class="button">Make Payment Now</a>
        </div>

        @if($daysSince >= 14)
        <div class="highlight" style="background: #ffebee; border-left: 4px solid #f44336;">
            <h3>⚠️ Important Notice</h3>
            <p>Your booking will be automatically cancelled if payment is not received within the next 24 hours.</p>
            <p>If you need more time or have any questions, please contact us immediately.</p>
        </div>
        @endif

        <div class="footer">
            <p><strong>Questions?</strong> We're here to help!</p>
            <p><strong>Email:</strong> info@nomadiq.com</p>
            <p><strong>Phone:</strong> +254 700 757 129</p>
            <p style="margin-top: 20px;">
                <em>Thank you for choosing Nomadiq. We can't wait to share unforgettable coastal adventures with you!</em>
            </p>
        </div>
    </div>
</body>
</html>

