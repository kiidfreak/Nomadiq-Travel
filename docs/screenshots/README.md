# Screenshots Directory

This directory contains screenshots of the Nomadiq platform for documentation purposes.

## Recommended Screenshots

### Admin Panel
1. **admin-dashboard.png** - Admin dashboard with welcome widget and statistics
2. **admin-bookings.png** - Bookings list page with refresh button
3. **admin-payments.png** - Payments list page with payment tracking
4. **admin-package-edit.png** - Package creation/editing interface
5. **admin-blog-post.png** - Blog post management interface

### Client-Facing
1. **homepage.png** - Homepage with hero section, stats, and featured packages
2. **packages-listing.png** - All packages listing page
3. **package-detail.png** - Individual package detail page with booking form
4. **booking-confirmation.png** - Booking confirmation page (shows after booking is created)
5. **payment-page.png** - Payment processing page (accessed from booking confirmation via "Make Payment" button)
6. **blog-listing.png** - Blog posts listing page
7. **blog-post.png** - Individual blog post page
8. **contact-page.png** - Contact form page
9. **about-page.png** - About Us page

## Booking Flow

The booking and payment flow works as follows:

1. **Package Detail Page** (`/packages/[id]`)
   - User views package details
   - Fills out booking form with customer details
   - Clicks "Book Now" button

2. **Booking Confirmation Page** (`/bookings/[id]`)
   - Shows booking reference and details
   - Displays customer and package information
   - Shows "Make Payment" button if balance > 0

3. **Payment Page** (`/bookings/[id]/payment`)
   - Accessed by clicking "Make Payment" on booking confirmation
   - User selects payment method (M-Pesa, Bank Transfer, Card)
   - Processes payment and shows payment history

## How to Add Screenshots

1. Take screenshots of the relevant pages
2. Save them in this directory with descriptive names
3. Update `README.md` to reference the screenshots
4. Use relative paths: `docs/screenshots/filename.png`

## Image Guidelines

- **Format**: PNG or JPG
- **Size**: Recommended max width 1920px
- **Naming**: Use kebab-case (e.g., `admin-dashboard.png`)
- **Quality**: Ensure text is readable and images are clear

