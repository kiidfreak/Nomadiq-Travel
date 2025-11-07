<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($emailType === 'created')
            Booking Received - Nomadiq
        @elseif($emailType === 'confirmed')
            Booking Confirmed - Nomadiq
        @elseif($emailType === 'cancelled')
            Booking Cancelled - Nomadiq
        @else
            Booking Update - Nomadiq
        @endif
    </title>
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
                @if($emailType === 'cancelled') #d32f2f, #f44336 
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
        .booking-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid 
                @if($emailType === 'cancelled') #f44336
                @else #4caf50
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
            color: @if($emailType === 'cancelled') #d32f2f @else #2e7d32 @endif;
        }
        .highlight {
            background: @if($emailType === 'cancelled') #ffebee @else #e8f5e8 @endif;
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
            background: 
                @if($booking->status === 'confirmed') #4caf50
                @elseif($booking->status === 'cancelled') #f44336
                @elseif($booking->status === 'pending') #ff9800
                @else #2196f3
                @endif;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌊 Nomadiq</h1>
        <h2>
            @if($emailType === 'created')
                Booking Received
            @elseif($emailType === 'confirmed')
                Booking Confirmed! 🎉
            @elseif($emailType === 'cancelled')
                Booking Cancelled
            @else
                Booking Update
            @endif
        </h2>
    </div>

    <div class="content">
        <p>Dear {{ $booking->customer->name }},</p>
        
        @if($emailType === 'created')
            <p>Thank you for choosing Nomadiq! We have received your booking request and our team is reviewing it.</p>
        @elseif($emailType === 'confirmed')
            <p>Great news! Your safari booking has been <strong>confirmed</strong>. We're excited to share the magic of African wildlife with you!</p>
        @elseif($emailType === 'cancelled')
            <p>We're sorry to inform you that your safari booking has been cancelled. If you have any questions about this cancellation, please don't hesitate to contact us.</p>
        @else
            <p>This is an update regarding your safari booking with us.</p>
        @endif

        <div class="highlight">
            <h3>Booking Reference: <strong>{{ $booking->booking_reference }}</strong></h3>
            <p>Please keep this reference number for your records</p>
        </div>

        <div class="booking-details">
            <h3>🎯 Booking Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Safari Package:</span>
                <span>{{ $booking->package->title ?? $booking->package->name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Start Date:</span>
                <span>{{ $booking->start_date->format('F j, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Number of People:</span>
                <span>{{ $booking->number_of_people }} {{ Str::plural('person', $booking->number_of_people) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span><strong>${{ number_format($booking->total_amount, 2) }}</strong></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span><span class="status-badge">{{ ucfirst($booking->status) }}</span></span>
            </div>

            @if($booking->special_requests)
            <div class="detail-row">
                <span class="detail-label">Special Requests:</span>
                <span>{{ $booking->special_requests }}</span>
            </div>
            @endif
        </div>

        @if($emailType === 'created')
            <div class="highlight">
                <h3>⏳ What Happens Next?</h3>
                <p>Our team will review your booking request and contact you within 24 hours to confirm availability.</p>
                <p><strong>Payment Instructions:</strong> Please see the payment details below to secure your booking.</p>
            </div>

            <div class="booking-details">
                <h3>💳 Payment Instructions</h3>
                <p style="margin-bottom: 15px;">To secure your booking, please make payment using one of the methods below:</p>
                
                <div style="margin-bottom: 20px;">
                    <h4 style="color: #C67B52; margin-bottom: 10px;">📱 M-Pesa Payment (Recommended for Kenya)</h4>
                    <div class="detail-row">
                        <span class="detail-label">Paybill Number:</span>
                        <span><strong>123456</strong></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Account Number:</span>
                        <span><strong>{{ $booking->booking_reference }}</strong></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount:</span>
                        <span><strong>KES {{ number_format($booking->total_amount * 130, 2) }}</strong> (Approx. ${{ number_format($booking->total_amount, 2) }})</span>
                    </div>
                    <p style="font-size: 12px; color: #666; margin-top: 10px;">
                        <em>Go to M-Pesa → Pay Bill → Enter Paybill Number → Enter Account Number (your booking reference) → Enter Amount</em>
                    </p>
                </div>

                <div style="margin-bottom: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <h4 style="color: #C67B52; margin-bottom: 10px;">🏦 Bank Transfer</h4>
                    <div class="detail-row">
                        <span class="detail-label">Bank Name:</span>
                        <span>KCB Bank Kenya</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Account Name:</span>
                        <span>Nomadiq</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Account Number:</span>
                        <span><strong>1234567890</strong></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Swift Code:</span>
                        <span>KCBLKENX</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount:</span>
                        <span><strong>${{ number_format($booking->total_amount, 2) }}</strong></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Reference:</span>
                        <span><strong>{{ $booking->booking_reference }}</strong></span>
                    </div>
                </div>

                <div style="background: #fff3e0; padding: 15px; border-radius: 5px; margin-top: 15px; border-left: 4px solid #ff9800;">
                    <h4 style="margin-top: 0; color: #e65100;">⚠️ Important</h4>
                    <p style="margin-bottom: 5px;"><strong>Always use your booking reference "{{ $booking->booking_reference }}" when making payment.</strong></p>
                    <p style="margin-bottom: 5px;">After payment, please send proof of payment (receipt/screenshot) to: <strong>payments@nomadiq.com</strong></p>
                    <p style="margin-bottom: 0;">Your booking will be confirmed once payment is received and verified.</p>
                </div>
            </div>
        @elseif($emailType === 'confirmed')
            <div class="highlight">
                <h3>🌍 Your Safari Awaits!</h3>
                <p>Your booking is now confirmed! We'll send you a detailed itinerary and packing list closer to your safari date.</p>
                <p>Please proceed with payment using the account details below.</p>
            </div>

            <div class="booking-details">
                <h3>💳 Payment Account Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Bank Name:</span>
                    <span>KCB Bank Kenya</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Account Name:</span>
                    <span>Nomadiq</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Account Number:</span>
                    <span><strong>1234567890</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Bank Code:</span>
                    <span>01</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Swift Code:</span>
                    <span>KCBLKENX</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Amount to Pay:</span>
                    <span><strong>${{ number_format($booking->total_amount, 2) }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Reference:</span>
                    <span><strong>{{ $booking->booking_reference }}</strong></span>
                </div>
            </div>

            <div class="highlight" style="background: #fff3e0; border-left: 4px solid #ff9800;">
                <h3>⚠️ Important Payment Instructions</h3>
                <p><strong>Please use your booking reference "{{ $booking->booking_reference }}" when making the payment.</strong></p>
                <p>After payment, please send proof of payment (receipt/screenshot) to: <strong>payments@nomadiq.com</strong></p>
                <p>Your safari will be finalized once payment is confirmed.</p>
            </div>
        @elseif($emailType === 'cancelled')
            <div class="highlight">
                <h3>💔 We're Sorry</h3>
                <p>If this cancellation was unexpected, please contact us immediately. We're here to help and may be able to reschedule your safari.</p>
                <p>Any payments made will be processed for refund according to our cancellation policy.</p>
            </div>
        @endif

        <div class="footer">
            <h3>📞 Contact Information</h3>
            <p><strong>Email:</strong> info@nomadiq.com</p>
            <p><strong>Phone:</strong> +254 700 000 000</p>
            <p><strong>Website:</strong> www.nomadiq.com</p>
            
            @if($emailType !== 'cancelled')
            <p style="margin-top: 20px;">
                <em>Thank you for choosing Nomadiq. Live. Connect. Belong. We can't wait to share unforgettable coastal adventures with you!</em>
            </p>
            @else
            <p style="margin-top: 20px;">
                <em>We hope to serve you in the future. Thank you for considering Nomadiq.</em>
            </p>
            @endif
            
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                This is an automated email. Please do not reply to this email address.
                If you have any questions, please contact us using the information above.
            </p>
        </div>
    </div>
</body>
</html>