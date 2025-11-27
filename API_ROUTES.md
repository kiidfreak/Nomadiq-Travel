# Nomadiq Travel API Routes

**Backend (API)**: `https://nomadiq-travel-production.up.railway.app`  
**Frontend**: `https://nomadiq-travel.vercel.app`

## Admin Dashboard

**✅ Laravel Filament Admin Panel is INSTALLED!**

### Access URLs:
- **Local (Herd)**: `http://nevcompany2.test/admin`
- **Production (Railway)**: `https://nomadiq-travel-production.up.railway.app/admin`

### Admin Features:
The admin panel includes full management for:
- 📦 **Packages** - Travel packages and itineraries
- 📅 **Bookings** - Customer bookings and reservations
- 💳 **Payments** - Payment tracking and M-Pesa integration
- 👤 **Customers** - Customer database
- ✍️ **Blog** - Blog posts and categories
- ⭐ **Testimonials** - Customer reviews
- 📍 **Destinations** - Travel destinations
- 📸 **Floating Memories** - Photo gallery management
- 🎯 **Micro Experiences** - Add-on experiences
- 📨 **Inquiries & Proposals** - Customer requests
- ⚙️ **Settings** - Site configuration
- 👥 **Users** - Admin user management

### Admin Credentials:
Your admin user has been created. Use the credentials you set up to login.



## Health Check
- `GET /up` - Health check endpoint (returns JSON with status)

## Packages
- `GET /api/packages` - Get all packages
- `GET /api/packages/featured` - Get featured packages
- `GET /api/packages/{id}` - Get package by ID

## Destinations
- `GET /api/destinations` - Get all destinations
- `GET /api/destinations/featured` - Get featured destinations
- `GET /api/destinations/{id}` - Get destination by ID

## Testimonials
- `GET /api/testimonials` - Get all testimonials
- `GET /api/testimonials/featured` - Get featured testimonials
- `POST /api/testimonials` - Create new testimonial

## Memories (Floating Memories)
- `GET /api/memories` - Get all memories
- `GET /api/memories/latest` - Get latest memories
- `GET /api/memories/by-slots` - Get memories organized by slots
- `GET /api/memories/{id}` - Get memory by ID

## Blog Posts
- `GET /api/blog-posts` - Get all blog posts
- `GET /api/blog-posts/{id}` - Get blog post by ID
- `GET /api/blog-posts/slug/{slug}` - Get blog post by slug
- `POST /api/blog-posts` - Create new blog post

## Bookings
- `POST /api/bookings` - Create new booking
- `GET /api/bookings/{id}` - Get booking by ID
- `PATCH /api/bookings/{id}/confirm` - Confirm booking
- `GET /api/bookings/{id}/payments` - Get payments for booking

## Payments
- `POST /api/payments` - Create new payment
- `GET /api/payments/{id}` - Get payment by ID
- `PATCH /api/payments/{id}/verify` - Verify payment

## Inquiries
- `POST /api/inquiries` - Submit inquiry

## Proposals (Custom Travel Requests)
- `POST /api/proposals` - Submit custom travel proposal

## Micro Experiences
- `GET /api/micro-experiences` - Get all micro experiences
- `GET /api/micro-experiences/category` - Get micro experiences by category
- `GET /api/micro-experiences/{id}` - Get micro experience by ID

## Settings
- `GET /api/settings` - Get all settings
- `GET /api/settings/{key}` - Get setting by key

## M-Pesa Webhook
- `POST /api/mpesa/callback` - M-Pesa STK callback (for payment processing)

## Custom Itinerary
- `POST /api/custom-itinerary/submit` - Submit custom itinerary request

## Storage/Media
- `GET /storage/{path}` - Serve uploaded media files (images, etc.)
