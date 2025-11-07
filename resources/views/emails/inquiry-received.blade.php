<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Inquiry - Nomadiq</title>
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
        .inquiry-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #C67B52;
        }
        .detail-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #C67B52;
            display: block;
            margin-bottom: 5px;
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
        .button {
            display: inline-block;
            background: #4caf50;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌊 Nomadiq</h1>
        <h2>Thank You for Your Inquiry!</h2>
    </div>

    <div class="content">
        <p>Dear {{ $inquiry->name }},</p>
        
        <p>Thank you for your interest in Nomadiq! We're thrilled that you're considering us for your coastal adventure.</p>

        <div class="highlight">
            <h3>✅ Your Inquiry Has Been Received</h3>
            <p>We've received your inquiry and our expert safari consultants are already reviewing your requirements.</p>
        </div>

        <div class="inquiry-details">
            <h3>📋 Your Inquiry Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span>{{ $inquiry->name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span>{{ $inquiry->email }}</span>
            </div>
            
            @if($inquiry->phone)
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span>{{ $inquiry->phone }}</span>
            </div>
            @endif

            @if($inquiry->package)
            <div class="detail-row">
                <span class="detail-label">Interested Package:</span>
                <span>{{ $inquiry->package->title ?? $inquiry->package->name }}</span>
            </div>
            @endif
            
            @if($inquiry->message)
            <div class="detail-row">
                <span class="detail-label">Your Message:</span>
                <div style="background: #f5f5f5; padding: 10px; border-radius: 4px; margin-top: 5px;">
                    {{ $inquiry->message }}
                </div>
            </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">Inquiry Date:</span>
                <span>{{ $inquiry->created_at->format('F j, Y \a\t g:i A') }}</span>
            </div>
        </div>

        <div class="highlight">
            <h3>⏰ What Happens Next?</h3>
            <p><strong>Within 24 hours:</strong> Our safari consultant will personally review your inquiry and contact you with a customized proposal.</p>
            <p><strong>Within 48 hours:</strong> You'll receive detailed information about available packages, pricing, and next steps.</p>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3>🌟 Why Choose Nomadiq?</h3>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Expert local guides with extensive wildlife knowledge</li>
                <li>Small group sizes for personalized experiences</li>
                <li>Sustainable tourism supporting local communities</li>
                <li>Customizable packages to match your preferences</li>
                <li>24/7 support throughout your journey</li>
            </ul>
        </div>

        <div class="footer">
            <h3>📞 Contact Information</h3>
            <p><strong>Email:</strong> info@nomadiq.com</p>
            <p><strong>Phone:</strong> +254 700 000 000</p>
            <p><strong>Website:</strong> www.nomadiq.com</p>
            
            <p style="margin-top: 20px;">
                <em>Follow us on social media for daily safari moments and travel inspiration!</em>
            </p>
            
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                This is an automated confirmation email. Please do not reply to this email address.
                If you have any questions, please contact us using the information above.
            </p>
        </div>
    </div>
</body>
</html>