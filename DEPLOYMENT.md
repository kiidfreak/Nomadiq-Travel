# Nomadiq Deployment Guide

This guide covers deployment options for the Nomadiq platform.

## 🏗️ Architecture Overview

- **Backend**: Laravel (PHP) - API and Admin Panel
- **Frontend**: Next.js (React) - Client-facing website
- **Database**: MySQL (or PostgreSQL)
- **Email**: SendGrid (already configured)
- **Payments**: M-Pesa Daraja API

---

## Option 1: Railway + Vercel + Namecheap (Recommended)

### Why This Stack?
- ✅ **Railway**: Excellent for Laravel, easy database setup, automatic deployments
- ✅ **Vercel**: Best-in-class Next.js hosting, automatic deployments, CDN
- ✅ **Namecheap**: Domain registrar, point to Railway/Vercel

### Setup Steps

#### 1. Deploy Backend to Railway

1. **Sign up for Railway**: https://railway.app
2. **Create New Project**:
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Connect your `Nomadiq-Travel` repository
3. **Configure Environment**:
   - Railway will auto-detect Laravel
   - Add environment variables:
     ```env
     APP_NAME=Nomadiq
     APP_ENV=production
     APP_KEY=base64:... (generate with: php artisan key:generate --show)
     APP_DEBUG=false
     APP_URL=https://api.nomadiq.com (or your Railway URL)
     
     DB_CONNECTION=mysql
     DB_HOST=containers-us-west-xxx.railway.app
     DB_PORT=3306
     DB_DATABASE=railway
     DB_USERNAME=root
     DB_PASSWORD=... (from Railway MySQL service)
     
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.sendgrid.net
     MAIL_PORT=587
     MAIL_USERNAME=apikey
     MAIL_PASSWORD=your_sendgrid_api_key
     MAIL_ENCRYPTION=tls
     MAIL_FROM_ADDRESS=noreply@nomadiq.com
     MAIL_FROM_NAME="Nomadiq"
     
     MPESA_CONSUMER_KEY=your_consumer_key
     MPESA_CONSUMER_SECRET=your_consumer_secret
     MPESA_SHORTCODE=your_shortcode
     MPESA_PASSKEY=your_passkey
     MPESA_ENVIRONMENT=production
     ```
4. **Add MySQL Database**:
   - Click "+ New" → "Database" → "MySQL"
   - Railway will create MySQL instance
   - Copy connection details to `.env`
5. **Deploy**:
   - Railway will auto-deploy on push to `main` branch
   - Or click "Deploy" manually
6. **Run Migrations**:
   - In Railway dashboard, go to your service
   - Click "Deploy Logs" → "Shell"
   - Run: `php artisan migrate --force`
   - Run: `php artisan db:seed` (optional)
7. **Get Railway URL**:
   - Railway provides: `https://your-app.up.railway.app`
   - This is your backend API URL

#### 2. Deploy Frontend to Vercel

1. **Sign up for Vercel**: https://vercel.com
2. **Import Project**:
   - Click "Add New" → "Project"
   - Import from GitHub: `Nomadiq-Travel`
   - **Important**: Set root directory to `frontend`
3. **Configure Build Settings**:
   - Framework Preset: Next.js
   - Root Directory: `frontend`
   - Build Command: `npm run build` (default)
   - Output Directory: `.next` (default)
4. **Environment Variables**:
   ```env
   NEXT_PUBLIC_API_URL=https://api.nomadiq.com/api
   ```
   (Use your Railway backend URL or custom domain)
5. **Deploy**:
   - Vercel will auto-deploy on push to `main` branch
   - You'll get: `https://nomadiq-travel.vercel.app`

#### 3. Configure Domain with Namecheap

1. **Buy Domain** (if not already owned):
   - Go to Namecheap
   - Purchase `nomadiq.com` (or your preferred domain)
2. **Point Domain to Vercel**:
   - In Vercel dashboard → Project → Settings → Domains
   - Add `nomadiq.com` and `www.nomadiq.com`
   - Vercel will provide DNS records
3. **Configure DNS in Namecheap**:
   - Go to Namecheap → Domain List → Manage
   - Advanced DNS → Add records:
     ```
     Type: A Record
     Host: @
     Value: 76.76.21.21 (Vercel IP - check Vercel docs)
     TTL: Automatic
     
     Type: CNAME
     Host: www
     Value: cname.vercel-dns.com
     TTL: Automatic
     ```
4. **Point API Subdomain to Railway**:
   - Add A Record or CNAME:
     ```
     Type: CNAME
     Host: api
     Value: your-app.up.railway.app
     TTL: Automatic
     ```
   - Or use Railway's custom domain feature

#### 4. Update Environment Variables

**Backend (Railway)**:
```env
APP_URL=https://api.nomadiq.com
```

**Frontend (Vercel)**:
```env
NEXT_PUBLIC_API_URL=https://api.nomadiq.com/api
```

#### 5. Configure CORS

Update `config/cors.php`:
```php
'allowed_origins' => [
    'https://nomadiq.com',
    'https://www.nomadiq.com',
    'https://nomadiq-travel.vercel.app', // For testing
],
```

---

## Option 2: Hostinger (All-in-One)

### Why Hostinger?
- ✅ Single platform for everything
- ✅ Shared hosting or VPS options
- ✅ Domain registration included
- ⚠️ Next.js requires VPS (not shared hosting)

### Setup Steps

#### 1. Purchase Hosting Plan

1. **Choose Plan**:
   - **Shared Hosting**: Can host Laravel backend only
   - **VPS**: Required for Next.js (or use Vercel for frontend)
   - **Recommended**: VPS or Shared Hosting + Vercel for frontend
2. **Buy Domain**: Purchase through Hostinger or use existing

#### 2. Deploy Backend to Hostinger

**Option A: Shared Hosting (Laravel only)**
1. **Upload Files**:
   - Use FileZilla or Hostinger File Manager
   - Upload all files except `frontend/` folder
   - Place in `public_html/` or subdomain folder
2. **Configure Database**:
   - Create MySQL database in Hostinger panel
   - Note database credentials
3. **Update `.env`**:
   ```env
   APP_NAME=Nomadiq
   APP_ENV=production
   APP_KEY=base64:...
   APP_DEBUG=false
   APP_URL=https://api.nomadiq.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```
4. **Run Migrations**:
   - Via SSH: `php artisan migrate --force`
   - Or use Hostinger terminal

**Option B: VPS (Full Control)**
1. **SSH into VPS**
2. **Install Dependencies**:
   ```bash
   # Install PHP 8.2+, Composer, MySQL, Nginx
   sudo apt update
   sudo apt install php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring composer mysql-server nginx
   ```
3. **Clone Repository**:
   ```bash
   git clone https://github.com/kiidfreak/Nomadiq-Travel.git
   cd Nomadiq-Travel
   composer install
   ```
4. **Configure Nginx**:
   - Point to `public/` directory
   - Set up SSL with Let's Encrypt
5. **Run Migrations**:
   ```bash
   php artisan migrate --force
   ```

#### 3. Deploy Frontend

**Option A: Use Vercel** (Recommended)
- Follow Vercel steps from Option 1
- Point domain to Vercel

**Option B: Deploy on Hostinger VPS**
1. **Install Node.js**:
   ```bash
   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
   sudo apt-get install -y nodejs
   ```
2. **Build Frontend**:
   ```bash
   cd frontend
   npm install
   npm run build
   ```
3. **Configure Nginx**:
   - Serve Next.js static files
   - Or use PM2 for Node.js process

---

## 🚀 Deployment Checklist

### Before Deployment

- [ ] Update all environment variables
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY` for production
- [ ] Update CORS settings for production domains
- [ ] Update M-Pesa credentials (production)
- [ ] Test email sending
- [ ] Backup database

### After Deployment

- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear cache: `php artisan config:cache`
- [ ] Clear routes: `php artisan route:cache`
- [ ] Clear views: `php artisan view:cache`
- [ ] Test API endpoints
- [ ] Test frontend connection to API
- [ ] Test booking flow
- [ ] Test payment flow
- [ ] Test email sending
- [ ] Set up SSL certificates
- [ ] Configure custom domains
- [ ] Set up scheduled jobs (cron)

---

## 📋 Scheduled Jobs Setup

### Railway
- Use Railway's Cron Jobs feature
- Or use external service like EasyCron

### Hostinger VPS
Add to crontab:
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Shared Hosting
- Use Hostinger's Cron Jobs feature
- Set to run every minute

---

## 🔒 Security Checklist

- [ ] Use HTTPS (SSL certificates)
- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong database passwords
- [ ] Keep dependencies updated
- [ ] Use environment variables (never commit secrets)
- [ ] Enable firewall rules
- [ ] Regular backups
- [ ] Monitor logs

---

## 💰 Cost Comparison

### Railway + Vercel + Namecheap
- **Railway**: ~$5-20/month (depends on usage)
- **Vercel**: Free tier available, Pro ~$20/month
- **Namecheap Domain**: ~$10-15/year
- **Total**: ~$15-35/month

### Hostinger
- **Shared Hosting**: ~$2-5/month
- **VPS**: ~$5-20/month
- **Domain**: ~$10-15/year (if purchased separately)
- **Total**: ~$2-20/month

---

## 🎯 Recommendation

**For Production**: **Railway + Vercel + Namecheap**
- Best performance for Next.js (Vercel)
- Easy Laravel deployment (Railway)
- Automatic deployments
- Better scalability
- Professional setup

**For Budget**: **Hostinger VPS**
- Single platform
- Lower cost
- More manual setup required

---

## 📞 Support

- Railway Docs: https://docs.railway.app
- Vercel Docs: https://vercel.com/docs
- Hostinger Docs: https://www.hostinger.com/tutorials
- Laravel Deployment: https://laravel.com/docs/deployment

---

## 🔄 Continuous Deployment

Both Railway and Vercel support automatic deployments:
- Push to `main` branch → Auto-deploy
- No manual deployment needed
- Rollback available if needed

---

## 📝 Notes

1. **Database Backups**: Set up regular backups (daily recommended)
2. **Monitoring**: Use Railway/Vercel monitoring or external services
3. **Logs**: Check logs regularly for errors
4. **Updates**: Keep Laravel, Next.js, and dependencies updated
5. **Testing**: Test thoroughly before deploying to production

