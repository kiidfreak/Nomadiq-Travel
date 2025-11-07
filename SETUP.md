# Nomadiq Setup Guide

Complete setup instructions for the Nomadiq platform.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm/yarn
- MySQL or PostgreSQL database
- Git

## Step 1: Clone and Setup Backend

```bash
# If you haven't already cloned the repo
git clone <repository-url>
cd NevCompany2

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=nomadiq
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# (Optional) Seed database
php artisan db:seed
```

## Step 2: Setup Frontend

```bash
# Navigate to frontend directory
cd frontend

# Install dependencies
npm install

# Create environment file
echo "NEXT_PUBLIC_API_URL=http://localhost:8000/api" > .env.local
```

## Step 3: Start Development Servers

### Option 1: Run separately

**Terminal 1 - Backend:**
```bash
# From project root
php artisan serve
# Backend runs on http://localhost:8000
```

**Terminal 2 - Frontend:**
```bash
# From frontend directory
npm run dev
# Frontend runs on http://localhost:3000
```

### Option 2: Use Laravel's dev command (includes queue)

```bash
# From project root
composer dev
# This runs: php artisan serve, queue:listen, and npm run dev
```

## Step 4: Access the Application

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api
- **Admin Panel**: http://localhost:8000/admin (if Filament is configured)

## Step 5: Create Admin User (Optional)

```bash
php artisan make:filament-user
# Follow the prompts to create an admin user
```

## Testing the API

You can test the API endpoints:

```bash
# Get all packages
curl http://localhost:8000/api/packages

# Get featured packages
curl http://localhost:8000/api/packages/featured

# Get testimonials
curl http://localhost:8000/api/testimonials/featured
```

## Adding Sample Data

### Create Packages via Admin Panel

1. Go to http://localhost:8000/admin
2. Login with your admin credentials
3. Navigate to Packages
4. Create new packages:
   - **Weekend Bash**: 2 days, $200, Watamu
   - **Explorer Weekend**: 3 days, $300, Watamu

### Or via Database Seeder

Create a seeder file:
```bash
php artisan make:seeder PackageSeeder
```

Then run:
```bash
php artisan db:seed --class=PackageSeeder
```

## Troubleshooting

### Frontend can't connect to API

1. Check that backend is running on port 8000
2. Verify `NEXT_PUBLIC_API_URL` in `frontend/.env.local`
3. Check CORS settings in Laravel (should allow localhost:3000)

### Database connection errors

1. Verify database credentials in `.env`
2. Ensure database exists: `CREATE DATABASE nomadiq;`
3. Run migrations: `php artisan migrate`

### Port already in use

- Backend: Change port with `php artisan serve --port=8001`
- Frontend: Change port in `package.json` scripts or use `npm run dev -- -p 3001`

## Production Deployment

### Backend

1. Set `APP_ENV=production` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Deploy to your server (Laravel Forge, DigitalOcean, etc.)

### Frontend

1. Build the application:
```bash
cd frontend
npm run build
```

2. Deploy to Vercel, Netlify, or your preferred hosting
3. Set `NEXT_PUBLIC_API_URL` to your production API URL

## Next Steps

- [ ] Add real images for packages
- [ ] Configure email settings for booking confirmations
- [ ] Set up payment gateway integration
- [ ] Add analytics tracking
- [ ] Configure SEO meta tags
- [ ] Set up CI/CD pipeline

## Support

For issues or questions, please contact the development team.

