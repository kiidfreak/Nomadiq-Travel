<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Itinerary Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; background: #dc3545; color: white; padding: 20px; border-radius: 5px; }
        .logo { font-size: 28px; font-weight: bold; }
        .content { margin-bottom: 30px; }
        .info-box { background: #f8d7da; padding: 20px; border-radius: 5px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .highlight-box { background: #f8f9fa; padding: 20px; border-radius: 5px; border-left: 4px solid #007bff; margin: 20px 0; }
        .footer { border-top: 1px solid #eee; padding-top: 20px; text-align: center; color: #666; font-size: 14px; }
        .reference-id { font-size: 18px; font-weight: bold; color: #dc3545; }
        .cta-button { display: inline-block; background: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 10px 0; }
        ul { padding-left: 20px; }
        li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🦁 Nomadiq</div>
            <h2>Custom Itinerary Update</h2>
        </div>

        <div class="content">
            <p>Dear {{ $customer_name }},</p>
            
            <p>Thank you for submitting your custom itinerary request for <strong>{{ $package_name }}</strong>. We have carefully reviewed your proposal and would like to discuss some adjustments.</p>

            <div class="info-box">
                <h3>📋 Your Submission Details:</h3>
                <ul>
                    <li><strong>Reference ID:</strong> <span class="reference-id">{{ $reference_id }}</span></li>
                    <li><strong>Package:</strong> {{ $package_name }}</li>
                    <li><strong>Status:</strong> Under Review</li>
                </ul>
            </div>

            <h3>💬 Let's Discuss Your coastal adventure</h3>
            <p>While we love your enthusiasm and the thoughtful details in your itinerary, our local expertise suggests some modifications that could enhance your coastal adventure experience. We'd like to discuss:</p>

            <ul>
                <li><strong>Seasonal Considerations:</strong> Optimal timing for wildlife viewing and weather</li>
                <li><strong>Accommodation Options:</strong> Alternative lodges that better match your preferences</li>
                <li><strong>Activity Sequencing:</strong> Better flow and pacing for your adventure</li>
                <li><strong>Local Insights:</strong> Hidden gems and experiences you might not have considered</li>
            </ul>

            <div class="highlight-box">
                <h3>🎯 What's Next?</h3>
                <p><strong>Personal Consultation:</strong> One of our coastal adventure specialists will contact you within 24 hours to discuss modifications and create an even better itinerary together.</p>
            </div>

            <div class="info-box">
                <p><strong>📞 We Value Your Vision:</strong> Our goal is to combine your preferences with our local expertise to create the perfect coastal adventure experience. This is a collaborative process, and we're here to make your dream coastal adventure a reality!</p>
            </div>

            <p>We appreciate your patience and look forward to working together to design an unforgettable African adventure.</p>

            <p>Best regards,<br>
            <strong>The Nomadiq Team</strong><br>
            coastal adventure Planning Specialists</p>
        </div>

        <div class="footer">
            <p><strong>Nomadiq</strong><br>
            Email: info@nomadiq.com | Phone: +254 700 000 000<br>
            Website: www.nomadiq.com</p>
            <p><em>Crafting perfect coastal adventure experiences together</em></p>
        </div>
    </div>
</body>
</html>
