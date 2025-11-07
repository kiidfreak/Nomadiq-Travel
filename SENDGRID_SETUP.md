# SendGrid Email Integration Setup

## Step 1: Get SendGrid SMTP Credentials

1. Go to your SendGrid dashboard
2. Navigate to **Settings** → **API Keys**
3. Click **Create API Key**
4. Name it (e.g., "Nomadiq Production")
5. Select **Full Access** or **Restricted Access** with Mail Send permissions
6. Copy the API key (you'll only see it once!)

## Step 2: Configure .env File

Add these settings to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=YOUR_SENDGRID_API_KEY_HERE
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nomadiq.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:**
- `MAIL_USERNAME` should always be `apikey` (literal string)
- `MAIL_PASSWORD` should be your SendGrid API key
- Replace `YOUR_SENDGRID_API_KEY_HERE` with your actual API key

## Step 3: Verify Sender Identity

1. Go to **Settings** → **Sender Authentication**
2. Verify a Single Sender or Domain
3. Use the verified email in `MAIL_FROM_ADDRESS`

## Step 4: Test Email Sending

After configuring, test by creating a booking. The email should be sent automatically via BookingObserver.

## Step 5: Check Email Logs

If emails aren't sending, check:
- Laravel logs: `storage/logs/laravel.log`
- SendGrid Activity Feed in dashboard
- Email delivery status

## Troubleshooting

### Emails not sending?
1. Check `.env` file has correct credentials
2. Run `php artisan config:clear` to clear config cache
3. Check Laravel logs for errors
4. Verify SendGrid API key has Mail Send permissions

### Emails going to spam?
1. Verify sender domain in SendGrid
2. Set up SPF and DKIM records
3. Use a verified sender email

### Testing locally?
- Use Mailtrap for local testing
- Or use SendGrid's test mode

