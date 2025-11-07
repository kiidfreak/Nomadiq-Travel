# Scheduled Emails Setup Guide

## ✅ What's Been Implemented

1. **Pre-Trip Reminders** - Sent 7, 3, and 1 day before trip
2. **Payment Reminders** - Sent 3, 7, and 14 days after booking
3. **Post-Trip Follow-ups** - Sent 1, 3, and 7 days after trip ends

## 📋 Commands Created

- `php artisan bookings:send-pre-trip-reminders` - Sends pre-trip reminders
- `php artisan bookings:send-payment-reminders` - Sends payment reminders
- `php artisan bookings:send-post-trip-followups` - Sends post-trip follow-ups

## ⚙️ Setting Up Scheduled Jobs

### Option 1: Using Laravel Scheduler (Recommended)

Laravel's scheduler runs these commands automatically. You need to set up a cron job that runs every minute.

#### On Windows (Using Task Scheduler):

1. Open Task Scheduler
2. Create a new task
3. Set trigger: "Daily" or "At startup"
4. Set action: "Start a program"
5. Program: `php`
6. Arguments: `C:\path\to\your\project\artisan schedule:run`
7. Start in: `C:\path\to\your\project`

#### On Linux/Mac:

Add this to your crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

To edit crontab:
```bash
crontab -e
```

### Option 2: Manual Testing

You can test the commands manually:

```bash
# Test pre-trip reminders
php artisan bookings:send-pre-trip-reminders

# Test payment reminders
php artisan bookings:send-payment-reminders

# Test post-trip follow-ups
php artisan bookings:send-post-trip-followups
```

### Option 3: Using Queue Workers (For Production)

For better performance, you can queue emails:

1. Update mailable classes to implement `ShouldQueue`:
```php
class PreTripReminder extends Mailable implements ShouldQueue
{
    // ...
}
```

2. Run queue worker:
```bash
php artisan queue:work
```

Or use supervisor for production:
```ini
[program:nomadiq-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/worker.log
```

## 📧 Email Templates

All email templates are in `resources/views/emails/`:
- `pre-trip-reminder.blade.php` - Pre-trip reminders
- `payment-reminder.blade.php` - Payment reminders
- `post-trip-followup.blade.php` - Post-trip follow-ups
- `payment-confirmation.blade.php` - Payment confirmations
- `booking-confirmation.blade.php` - Booking confirmations

## 🔧 Configuration

### Email Settings

Make sure your `.env` file has correct email settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@nomadiq.com
MAIL_FROM_NAME="Nomadiq"
```

### Schedule Times

Default schedule times (in `app/Console/Kernel.php`):
- Pre-trip reminders: 9:00 AM daily
- Payment reminders: 10:00 AM daily
- Post-trip follow-ups: 11:00 AM daily

You can change these times in `app/Console/Kernel.php`:
```php
$schedule->command('bookings:send-pre-trip-reminders')
    ->daily()
    ->at('09:00'); // Change time here
```

## 🧪 Testing

### Test Individual Commands

```bash
# Test pre-trip reminders
php artisan bookings:send-pre-trip-reminders

# Test payment reminders
php artisan bookings:send-payment-reminders

# Test post-trip follow-ups
php artisan bookings:send-post-trip-followups
```

### Test Schedule

```bash
# Run scheduler manually
php artisan schedule:run

# List scheduled tasks
php artisan schedule:list
```

## 📊 Monitoring

### Check Logs

All email sending is logged. Check logs in `storage/logs/laravel.log`:
```bash
tail -f storage/logs/laravel.log
```

### Check Queue Jobs

If using queues:
```bash
# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

## 🐛 Troubleshooting

### Emails Not Sending

1. Check email configuration in `.env`
2. Check logs: `storage/logs/laravel.log`
3. Test email sending manually:
```bash
php artisan tinker
Mail::raw('Test email', function ($message) {
    $message->to('your-email@example.com')->subject('Test');
});
```

### Scheduled Jobs Not Running

1. Check if cron job is set up correctly
2. Check Laravel logs for errors
3. Test scheduler manually: `php artisan schedule:run`
4. Check if commands exist: `php artisan list`

### Queue Jobs Not Processing

1. Make sure queue worker is running: `php artisan queue:work`
2. Check queue connection in `.env`: `QUEUE_CONNECTION=database`
3. Check failed jobs: `php artisan queue:failed`

## 📝 Notes

- **Pre-trip reminders** check bookings starting in 7, 3, or 1 day
- **Payment reminders** check bookings created 3, 7, or 14 days ago
- **Post-trip follow-ups** check bookings that ended 1, 3, or 7 days ago
- All emails are sent to the customer's email address
- All email sending is logged for monitoring

## 🚀 Production Checklist

- [ ] Set up cron job for scheduler
- [ ] Configure email settings in `.env`
- [ ] Test all email commands manually
- [ ] Set up queue workers (optional but recommended)
- [ ] Monitor logs for errors
- [ ] Set up email monitoring/alerts
- [ ] Test email delivery
- [ ] Configure email templates with branding

## 💡 Next Steps

1. Set up cron job
2. Test email commands
3. Monitor email delivery
4. Customize email templates
5. Add email analytics (optional)
6. Set up email monitoring/alerts

