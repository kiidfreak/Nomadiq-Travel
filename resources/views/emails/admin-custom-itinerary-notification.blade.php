<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Custom Itinerary Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; background: #e74c3c; color: white; padding: 20px; border-radius: 5px; }
        .logo { font-size: 24px; font-weight: bold; }
        .content { margin-bottom: 30px; }
        .info-box { background: #f8f9fa; padding: 20px; border-radius: 5px; border-left: 4px solid #28a745; margin: 20px 0; }
        .urgent-box { background: #fff3cd; padding: 20px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0; }
        .footer { border-top: 1px solid #eee; padding-top: 20px; text-align: center; color: #666; font-size: 14px; }
        .reference-id { font-size: 18px; font-weight: bold; color: #e74c3c; }
        .customer-info { background: #e9ecef; padding: 15px; border-radius: 5px; margin: 15px 0; }
        ul { padding-left: 20px; }
        li { margin-bottom: 8px; }
        .action-required { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔔 Nomadiq Admin</div>
            <h2>New Custom Itinerary Submission</h2>
        </div>

        <div class="content">
            <div class="urgent-box">
                <h3 class="action-required">⚡ ACTION REQUIRED - Customer Awaiting Response</h3>
                <p>A new custom itinerary has been submitted and requires review within 24 hours.</p>
            </div>

            <div class="info-box">
                <h3>📋 Submission Details:</h3>
                <ul>
                    <li><strong>Reference ID:</strong> <span class="reference-id">{{ $reference_id }}</span></li>
                    <li><strong>Package:</strong> {{ $package_name }}</li>
                    <li><strong>Itinerary Days:</strong> {{ $itinerary_days }} days</li>
                    <li><strong>Submitted:</strong> {{ $submitted_at }}</li>
                </ul>
            </div>

            <div class="customer-info">
                <h3>👤 Customer Information:</h3>
                <ul>
                    <li><strong>Name:</strong> {{ $customer_name }}</li>
                    <li><strong>Email:</strong> <a href="mailto:{{ $customer_email }}">{{ $customer_email }}</a></li>
                    @if($customer_phone)
                    <li><strong>Phone:</strong> <a href="tel:{{ $customer_phone }}">{{ $customer_phone }}</a></li>
                    @endif
                </ul>
            </div>

            @if($special_requests)
            <div class="info-box">
                <h3>💬 Special Requests:</h3>
                <p><em>"{{ $special_requests }}"</em></p>
            </div>
            @endif

            <div class="urgent-box">
                <h3>🎯 Next Steps:</h3>
                <ul>
                    <li><strong>Review Itinerary:</strong> Log into the admin panel to view full itinerary details</li>
                    <li><strong>Contact Customer:</strong> Call or email within 24 hours to discuss preferences</li>
                    <li><strong>Provide Feedback:</strong> Suggest improvements or approve as-is</li>
                    <li><strong>Update Status:</strong> Mark as approved/needs revision in the system</li>
                </ul>
            </div>

            <div class="info-box">
                <h3>📞 Quick Contact Options:</h3>
                <ul>
                    <li><strong>Email:</strong> <a href="mailto:{{ $customer_email }}?subject=Re: Custom Itinerary {{ $reference_id }}">Send Email</a></li>
                    @if($customer_phone)
                    <li><strong>Call:</strong> <a href="tel:{{ $customer_phone }}">{{ $customer_phone }}</a></li>
                    @endif
                    <li><strong>Admin Panel:</strong> <a href="https://admin.nomadiq.com">Review in Dashboard</a></li>
                </ul>
            </div>

            <p><strong>Customer is expecting contact within 24 hours.</strong></p>
        </div>

        <div class="footer">
            <p><strong>Nomadiq Admin System</strong><br>
            This email was automatically generated for reference ID: {{ $reference_id }}</p>
        </div>
    </div>
</body>
</html>
