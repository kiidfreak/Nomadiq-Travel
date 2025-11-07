<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post-Trip Follow-up - Nomadiq</title>
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
            @if($type === 'thank_you')
                Thank You for Choosing Nomadiq! 🙏
            @elseif($type === 'review')
                Share Your Nomadiq Experience ⭐
            @else
                Relive Your Nomadiq Adventure 📸
            @endif
        </h2>
    </div>

    <div class="content">
        <p>Dear {{ $booking->customer->name }},</p>
        
        @if($type === 'thank_you')
        <p>We hope you had an amazing time on your Nomadiq adventure!</p>
        <p>Thank you for choosing Nomadiq. We're grateful for the opportunity to share unforgettable coastal experiences with you.</p>
        
        <div class="highlight">
            <h3>💭 We'd Love Your Feedback</h3>
            <p>Your experience matters to us! We'd love to hear about your adventure and how we can improve.</p>
        </div>
        
        @elseif($type === 'review')
        <p>We hope you had an incredible time exploring the coast with Nomadiq!</p>
        <p>Your feedback helps us improve and helps other travelers discover amazing experiences. We'd be honored if you could share your experience with us.</p>
        
        <div class="highlight">
            <h3>⭐ Share Your Experience</h3>
            <p>Please take a moment to leave a review or testimonial. Your words inspire others to embark on their own coastal adventures!</p>
        </div>
        
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ config('app.url') }}/testimonials" class="button">Leave a Review</a>
        </div>
        
        @else
        <p>We hope you're still reliving the amazing memories from your Nomadiq adventure!</p>
        <p>We'd love to see your photos and hear about your favorite moments. Share your memories with us and the Nomadiq community!</p>
        
        <div class="highlight">
            <h3>📸 Share Your Memories</h3>
            <p>Tag us on social media or share your photos with us. We'd love to feature your adventure!</p>
        </div>
        
        <div class="info-box">
            <h3>🌐 Connect With Us</h3>
            <p><strong>Instagram:</strong> @nomadiq</p>
            <p><strong>Facebook:</strong> @nomadiq</p>
            <p><strong>Email:</strong> memories@nomadiq.com</p>
        </div>
        @endif

        <div class="info-box">
            <h3>📋 Your Adventure Details</h3>
            <p><strong>Booking Reference:</strong> {{ $booking->booking_reference }}</p>
            <p><strong>Package:</strong> {{ $booking->package->title ?? 'N/A' }}</p>
            <p><strong>Date:</strong> {{ $booking->start_date->format('F j, Y') }}</p>
        </div>

        @if($type === 'memories')
        <div class="highlight">
            <h3>🎁 Special Offer for Returning Travelers</h3>
            <p>As a valued Nomadiq traveler, enjoy <strong>10% off</strong> your next booking!</p>
            <p>Use code: <strong>RETURN10</strong> when booking your next adventure.</p>
        </div>
        @endif

        <div class="footer">
            <p><strong>We hope to see you again soon!</strong></p>
            <p>Thank you for being part of the Nomadiq family. Live. Connect. Belong.</p>
            <p style="margin-top: 20px;">
                <em>Until your next coastal adventure,</em><br>
                <strong>The Nomadiq Team</strong>
            </p>
        </div>
    </div>
</body>
</html>

