# Payment & Email Integration Plan

## Current Status

### ✅ Already Implemented:
- **Payment Model**: Supports M-Pesa, Card, and Bank Transfer
- **Email Templates**: Booking confirmation email template exists
- **BookingObserver**: Registered and ready to send emails
- **Payment Tracking**: Payment model with status tracking

### ❌ Missing:
- Payment API endpoints
- Frontend payment interface
- M-Pesa integration
- Email sending in BookingController
- Payment confirmation emails

---

## Phase 1: Email Integration (Quick Win - 2-3 hours)

### 1.1 Update BookingController to Send Emails
**File**: `app/Http/Controllers/Api/BookingController.php`
- Send booking confirmation email when booking is created
- Use existing `BookingConfirmation` mailable

### 1.2 Configure Email Settings
**File**: `.env`
- Set up SMTP or Mailgun/SendGrid
- Test email sending

### 1.3 Update Email Template
**File**: `resources/views/emails/booking-confirmation.blade.php`
- Add payment instructions
- Include payment methods (M-Pesa, Bank Transfer)
- Add payment details section

### 1.4 Create Payment Instructions Email
**File**: `app/Mail/PaymentInstructions.php` (new)
- Separate email for payment instructions
- Include M-Pesa paybill number, account number
- Bank transfer details

---

## Phase 2: Payment Integration (Medium Priority - 1-2 days)

### 2.1 Create Payment API Endpoints
**File**: `app/Http/Controllers/Api/PaymentController.php` (new)
- `POST /api/payments` - Create payment
- `GET /api/payments/{id}` - Get payment details
- `POST /api/payments/{id}/verify` - Verify payment (for M-Pesa webhooks)
- `GET /api/bookings/{id}/payments` - Get all payments for a booking

### 2.2 M-Pesa Integration (Daraja API)
**File**: `app/Services/MpesaService.php` (new)
- STK Push for M-Pesa payments
- Webhook handling for payment callbacks
- Payment verification

**Required:**
- Safaricom Daraja API credentials
- Consumer Key & Secret
- Passkey
- Business Short Code

### 2.3 Payment Methods Support
1. **M-Pesa** (Primary for Kenya)
   - STK Push integration
   - Paybill number
   - Account number (booking reference)

2. **Bank Transfer**
   - Bank account details
   - Reference number (booking reference)
   - Manual verification by admin

3. **Card Payment** (Future)
   - Stripe integration
   - Or Flutterwave for Africa

### 2.4 Frontend Payment Page
**File**: `frontend/app/bookings/[id]/payment/page.tsx` (new)
- Payment method selection
- M-Pesa STK Push trigger
- Bank transfer details display
- Payment status tracking
- Payment history

---

## Phase 3: Payment Flow Enhancement (1 day)

### 3.1 Payment Status Updates
- Update booking status when payment is received
- Send payment confirmation email
- Notify admin of new payments

### 3.2 Partial Payments
- Support multiple payments per booking
- Track payment progress
- Show balance remaining

### 3.3 Payment Reminders
- Email reminders for pending payments
- Automatic reminders before trip date

---

## Implementation Priority

### 🔴 High Priority (Do First):
1. ✅ Send booking confirmation email when booking is created
2. ✅ Add payment instructions to booking confirmation email
3. ✅ Configure email settings (.env)
4. ✅ Create payment API endpoints
5. ✅ Create frontend payment page

### 🟡 Medium Priority:
6. M-Pesa STK Push integration
7. Payment webhook handling
8. Payment status tracking UI

### 🟢 Low Priority (Future):
9. Stripe/Flutterwave card payments
10. Payment reminders automation
11. Payment analytics dashboard

---

## Technical Details

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # or your SMTP server
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nomadiq.com
MAIL_FROM_NAME="${APP_NAME}"
```

### M-Pesa Configuration
```env
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_SHORTCODE=your_shortcode
MPESA_PASSKEY=your_passkey
MPESA_ENVIRONMENT=sandbox  # or production
```

### Payment Methods
1. **M-Pesa**: Real-time payment via STK Push
2. **Bank Transfer**: Manual verification, show bank details
3. **Card**: Future integration with payment gateway

---

## Next Steps

1. **Start with Email Integration** (Easiest, immediate value)
   - Update BookingController
   - Configure email settings
   - Test email sending

2. **Create Payment API** (Foundation)
   - PaymentController
   - Payment routes
   - Basic payment creation

3. **Build Frontend Payment Page** (User-facing)
   - Payment method selection
   - Payment form
   - Status display

4. **Integrate M-Pesa** (Most complex)
   - Daraja API integration
   - STK Push
   - Webhook handling

---

## Files to Create/Modify

### New Files:
- `app/Http/Controllers/Api/PaymentController.php`
- `app/Services/MpesaService.php`
- `app/Mail/PaymentInstructions.php`
- `frontend/app/bookings/[id]/payment/page.tsx`
- `resources/views/emails/payment-instructions.blade.php`

### Files to Modify:
- `app/Http/Controllers/Api/BookingController.php` - Add email sending
- `resources/views/emails/booking-confirmation.blade.php` - Add payment info
- `routes/api.php` - Add payment routes
- `.env` - Add email and M-Pesa config
- `frontend/lib/api.ts` - Add payment API methods

---

## Testing Checklist

- [ ] Booking confirmation email sends successfully
- [ ] Email contains booking details
- [ ] Payment instructions are clear
- [ ] Payment API creates payment record
- [ ] Frontend payment page displays correctly
- [ ] M-Pesa STK Push works (sandbox)
- [ ] Payment webhook updates booking status
- [ ] Payment confirmation email sends
- [ ] Admin receives payment notifications

---

## Questions to Answer

1. **Email Provider**: Which email service? (Mailgun, SendGrid, SMTP?)
2. **M-Pesa Credentials**: Do you have Safaricom Daraja API credentials?
3. **Payment Priority**: Which payment method is most important? (M-Pesa for Kenya)
4. **Bank Details**: What are the bank account details for transfers?
5. **Paybill Number**: What's the M-Pesa paybill number?

