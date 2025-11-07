<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Trip Reminder - Nomadiq</title>
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
            background: linear-gradient(135deg, #C67B52, #C67B52);
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
            border-left: 4px solid #C67B52;
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
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
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
            @if($daysBefore === 7)
                Your Adventure Starts in 7 Days! 🎉
            @elseif($daysBefore === 3)
                Your Adventure Starts in 3 Days! ⏰
            @else
                Your Adventure Starts Tomorrow! 🚀
            @endif
        </h2>
    </div>

    <div class="content">
        <p>Dear {{ $booking->customer->name }},</p>
        
        <p>We're excited to remind you that your Nomadiq adventure is just <strong>{{ $daysBefore }} {{ $daysBefore === 1 ? 'day' : 'days' }}</strong> away!</p>

        <div class="info-box">
            <h3>📋 Booking Details</h3>
            
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
                <span><strong>{{ $booking->start_date->format('F j, Y') }}</strong></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Duration:</span>
                <span>{{ $booking->package->duration_days ?? 'N/A' }} {{ $booking->package->duration_days == 1 ? 'day' : 'days' }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Number of People:</span>
                <span>{{ $booking->number_of_people }} {{ $booking->number_of_people == 1 ? 'person' : 'people' }}</span>
            </div>
        </div>

        @if($daysBefore === 7)
        <div class="highlight">
            <h3>📝 What to Prepare</h3>
            <ul>
                <li>Review your itinerary (we'll send it soon!)</li>
                <li>Check your travel documents</li>
                <li>Start packing (packing list coming soon!)</li>
                <li>Confirm your travel arrangements</li>
            </ul>
        </div>
        @elseif($daysBefore === 3)
        <div class="highlight">
            <h3>⏰ Final Preparations</h3>
            <ul>
                <li>Complete your packing</li>
                <li>Confirm meeting point and time</li>
                <li>Check weather forecast</li>
                <li>Review itinerary details</li>
                <li>Save emergency contact numbers</li>
            </ul>
        </div>
        @else
        <div class="highlight">
            <h3>🚀 You're Almost There!</h3>
            <ul>
                <li>Double-check your packing</li>
                <li>Confirm meeting point: <strong>We'll contact you with details</strong></li>
                <li>Get a good night's rest</li>
                <li>Charge your camera/phone</li>
                <li>We can't wait to see you tomorrow!</li>
            </ul>
        </div>
        @endif

        <div class="info-box">
            <h3>📞 Important Contacts</h3>
            <p><strong>Email:</strong> info@nomadiq.com</p>
            <p><strong>Phone:</strong> +254 700 757 129</p>
            <p><strong>Emergency:</strong> Available 24/7 during your trip</p>
        </div>

        <div class="info-box">
            <h3>💡 What to Bring</h3>
            <ul>
                <li>Sunscreen and hat</li>
                <li>Comfortable walking shoes</li>
                <li>Swimwear</li>
                <li>Camera or smartphone</li>
                <li>Light clothing</li>
                <li>Water bottle</li>
                <li>Sense of adventure! 🌊</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ config('app.url') }}/bookings/{{ $booking->id }}" class="button">View Booking Details</a>
        </div>

        <div class="footer">
            <p><strong>We're excited to share unforgettable coastal adventures with you!</strong></p>
            <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
            <p style="margin-top: 20px;">
                <em>Thank you for choosing Nomadiq. Live. Connect. Belong.</em>
            </p>
        </div>
    </div>
</body>
</html>

