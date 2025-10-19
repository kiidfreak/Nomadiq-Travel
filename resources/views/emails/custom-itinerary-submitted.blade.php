<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Itinerary Submitted</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { color: #e74c3c; font-size: 28px; font-weight: bold; }
        .content { margin-bottom: 30px; }
        .highlight-box { background: #f8f9fa; padding: 20px; border-radius: 5px; border-left: 4px solid #e74c3c; margin: 20px 0; }
        .footer { border-top: 1px solid #eee; padding-top: 20px; text-align: center; color: #666; font-size: 14px; }
        .reference-id { font-size: 18px; font-weight: bold; color: #e74c3c; }
        ul { padding-left: 20px; }
        li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🦁 Kanyanga Safari</div>
            <h2>Custom Itinerary Submitted Successfully!</h2>
        </div>

        <div class="content">
            <p>Dear {{ $customer_name }},</p>
            
            <p>Thank you for submitting your custom itinerary request for <strong>{{ $package_name }}</strong>. We have received your preferences and our expert safari planning team is excited to review your proposal.</p>

            <div class="highlight-box">
                <h3>📋 Your Submission Details:</h3>
                <ul>
                    <li><strong>Reference ID:</strong> <span class="reference-id">{{ $reference_id }}</span></li>
                    <li><strong>Package:</strong> {{ $package_name }}</li>
                    <li><strong>Itinerary Days:</strong> {{ $itinerary_days }} days</li>
                    @if($special_requests)
                    <li><strong>Special Requests:</strong> {{ $special_requests }}</li>
                    @endif
                </ul>
            </div>

            <h3>🔄 What Happens Next?</h3>
            <ul>
                <li><strong>Review Process:</strong> Our safari experts will carefully review your custom itinerary within 24 hours</li>
                <li><strong>Personal Contact:</strong> We'll contact you directly to discuss your preferences and finalize details</li>
                <li><strong>Customization:</strong> We may suggest improvements or alternatives based on our local expertise</li>
                <li><strong>Booking:</strong> Once approved, we'll help you proceed with the booking process</li>
            </ul>

            <div class="highlight-box">
                <p><strong>💡 Important:</strong> Please save your reference ID <strong>{{ $reference_id }}</strong> for all future communications about this custom itinerary.</p>
            </div>

            <p>We appreciate your interest in creating a personalized safari experience with us. Kenya's wildlife and landscapes await your discovery!</p>

            <p>Best regards,<br>
            <strong>The Kanyanga Safari Team</strong><br>
            Safari Planning Specialists</p>
        </div>

        <div class="footer">
            <p><strong>Kanyanga Safari</strong><br>
            Email: info@kanyangasafari.com | Phone: +254 700 000 000<br>
            Website: www.kanyangasafari.com</p>
            <p><em>Creating unforgettable safari memories since 2020</em></p>
        </div>
    </div>
</body>
</html>