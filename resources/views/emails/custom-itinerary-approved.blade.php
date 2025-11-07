<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Itinerary Approved</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; background: #28a745; color: white; padding: 20px; border-radius: 5px; }
        .logo { font-size: 28px; font-weight: bold; }
        .content { margin-bottom: 30px; }
        .success-box { background: #d4edda; padding: 20px; border-radius: 5px; border-left: 4px solid #28a745; margin: 20px 0; }
        .highlight-box { background: #f8f9fa; padding: 20px; border-radius: 5px; border-left: 4px solid #007bff; margin: 20px 0; }
        .footer { border-top: 1px solid #eee; padding-top: 20px; text-align: center; color: #666; font-size: 14px; }
        .reference-id { font-size: 18px; font-weight: bold; color: #28a745; }
        .cta-button { display: inline-block; background: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 10px 0; }
        ul { padding-left: 20px; }
        li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎉 Nomadiq</div>
            <h2>Your Custom Itinerary is Approved!</h2>
        </div>

        <div class="content">
            <p>Dear {{ $customer_name }},</p>
            
            <div class="success-box">
                <h3>✅ Great News!</h3>
                <p>We're excited to inform you that your custom itinerary request for <strong>{{ $package_name }}</strong> has been <strong>APPROVED</strong> by our coastal adventure planning team!</p>
            </div>

            <div class="highlight-box">
                <h3>📋 Your Approved Itinerary Details:</h3>
                <ul>
                    <li><strong>Reference ID:</strong> <span class="reference-id">{{ $reference_id }}</span></li>
                    <li><strong>Package:</strong> {{ $package_name }}</li>
                    <li><strong>Status:</strong> ✅ Approved</li>
                </ul>
            </div>

            <h3>🎯 What's Next?</h3>
            <ul>
                <li><strong>Booking Process:</strong> We'll contact you within 24 hours to initiate the booking process</li>
                <li><strong>Payment Details:</strong> You'll receive detailed payment information and booking terms</li>
                <li><strong>Final Itinerary:</strong> We'll provide you with a detailed day-by-day itinerary document</li>
                <li><strong>Pre-coastal adventure Briefing:</strong> Our team will schedule a pre-coastal adventure consultation call</li>
            </ul>

            <div class="success-box">
                <h3>🦁 Your coastal adventure Adventure Awaits!</h3>
                <p>Our expert guides and local partners are already preparing to make your customized coastal adventure experience truly unforgettable. Kenya's incredible wildlife and breathtaking landscapes are waiting for you!</p>
            </div>

            <div class="highlight-box">
                <p><strong>📞 Next Steps:</strong> Keep an eye on your phone and email - one of our coastal adventure specialists will be in touch very soon to move forward with your booking.</p>
            </div>

            <p>Thank you for choosing Nomadiq for your African adventure. We can't wait to welcome you to Kenya!</p>

            <p>Best regards,<br>
            <strong>The Nomadiq Team</strong><br>
            coastal adventure Planning Specialists</p>
        </div>

        <div class="footer">
            <p><strong>Nomadiq</strong><br>
            Email: info@nomadiq.com | Phone: +254 700 000 000<br>
            Website: www.nomadiq.com</p>
            <p><em>Your custom coastal adventure adventure begins here!</em></p>
        </div>
    </div>
</body>
</html>
