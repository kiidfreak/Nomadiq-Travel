# 🚀 Next Steps: Complete Deployment

## ✅ Completed

- [x] Frontend deployed to Vercel: https://nomadiq-travel.vercel.app/
- [x] All code pushed to GitHub
- [x] Deployment configuration files created

---

## 📋 Next Steps

### 1. Deploy Backend to Railway (15-20 minutes)

#### Step 1: Create Railway Account & Project
1. **Sign up**: https://railway.app (use GitHub auth)
2. **Create New Project**:
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose `Nomadiq-Travel` repository
   - Railway will auto-detect Laravel

#### Step 2: Add MySQL Database
1. In Railway project, click **"+ New"** → **"Database"** → **"MySQL"**
2. Railway will create MySQL instance
3. **Copy connection details** from database service

#### Step 3: Set Environment Variables
In Railway project → **Variables** tab, add:

```env
# App Configuration
APP_NAME=Nomadiq
APP_ENV=production
APP_KEY=base64:... (generate with: php artisan key:generate --show)
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

# Database (Railway auto-injects these, but verify)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# Mail Configuration (SendGrid)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nomadiq.com
MAIL_FROM_NAME="Nomadiq"

# M-Pesa Configuration (if using)
MPESA_CONSUMER_KEY=your_mpesa_consumer_key
MPESA_CONSUMER_SECRET=your_mpesa_consumer_secret
MPESA_SHORTCODE=your_shortcode
MPESA_PASSKEY=your_passkey
MPESA_ENVIRONMENT=production

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Filesystem
FILESYSTEM_DISK=public
```

**Important**: Generate `APP_KEY`:
```bash
php artisan key:generate --show
# Copy the output and paste as APP_KEY value
```

#### Step 4: Configure Service
1. Railway will auto-detect Laravel
2. **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
3. **Healthcheck Path**: `/up`

#### Step 5: Run Migrations
1. In Railway dashboard, go to your service
2. Click **"Deploy Logs"** tab → **"Shell"** button
3. Run:
   ```bash
   php artisan migrate --force
   php artisan storage:link
   php artisan db:seed
   ```

#### Step 6: Get Railway URL
- Railway provides: `https://your-app.up.railway.app`
- This is your backend API URL
- **Note this URL** for next step

---

### 2. Connect Frontend to Backend (5 minutes)

#### Step 1: Update Vercel Environment Variables
1. Go to Vercel Dashboard → Your Project → **Settings** → **Environment Variables**
2. Add/Update:
   ```env
   NEXT_PUBLIC_API_URL=https://your-app.up.railway.app/api
   ```
   (Replace `your-app.up.railway.app` with your actual Railway URL)

3. **Important**: Add this for:
   - Production
   - Preview
   - Development

#### Step 2: Redeploy Frontend
1. Go to **Deployments** tab
2. Click **"..."** on latest deployment → **"Redeploy"**
3. Or push a new commit to trigger redeploy

---

### 3. Test Integration (10 minutes)

#### Test API Connection
1. Visit: https://nomadiq-travel.vercel.app/
2. Open browser console (F12)
3. Check for API errors
4. Test features:
   - View packages
   - View memories
   - View testimonials
   - Make a booking
   - Submit a proposal

#### Test Admin Panel
1. Visit: `https://your-app.up.railway.app/admin`
2. Login with admin credentials
3. Test:
   - Create/edit packages
   - Upload memories
   - View bookings
   - View payments

---

### 4. Configure Custom Domains (Optional, 30 minutes)

#### Backend Domain (api.nomadiq.com)
1. **In Railway**:
   - Go to service → **Settings** → **Networking**
   - Click **"Generate Domain"** or **"Custom Domain"**
   - Add: `api.nomadiq.com`

2. **In Namecheap (or your registrar)**:
   - Go to **Advanced DNS**
   - Add **CNAME Record**:
     - Host: `api`
     - Value: `your-app.up.railway.app`
     - TTL: Automatic

3. **Update Environment Variables**:
   - Railway: `APP_URL=https://api.nomadiq.com`
   - Vercel: `NEXT_PUBLIC_API_URL=https://api.nomadiq.com/api`

#### Frontend Domain (nomadiq.com)
1. **In Vercel**:
   - Go to project → **Settings** → **Domains**
   - Add: `nomadiq.com` and `www.nomadiq.com`

2. **In Namecheap**:
   - Add **A Record**:
     - Host: `@`
     - Value: `76.76.21.21` (Vercel IP)
     - TTL: Automatic
   - Add **CNAME Record**:
     - Host: `www`
     - Value: `cname.vercel-dns.com`
     - TTL: Automatic

3. **SSL Certificates**:
   - Railway: Automatic via Railway
   - Vercel: Automatic via Vercel

---

### 5. Post-Deployment Tasks

#### Update CORS Configuration
1. Update `config/cors.php` with production domains:
   ```php
   'allowed_origins' => [
       'https://nomadiq.com',
       'https://www.nomadiq.com',
       'https://nomadiq-travel.vercel.app',
   ],
   ```

2. Push changes and redeploy backend

#### Configure Scheduled Tasks
1. **In Railway**:
   - Add **Cron Job** service
   - Set schedule: `* * * * *` (every minute)
   - Command: `php artisan schedule:run`
   - Or use external cron service (cron-job.org)

#### Set Up Monitoring
1. **Railway**: View logs in Railway dashboard
2. **Vercel**: View logs in Vercel dashboard
3. **Optional**: Set up error tracking (Sentry, etc.)

#### Configure Backups
1. **Database**: Railway Pro plan includes backups
2. **Storage**: Use S3 for production (recommended)
3. **Manual**: Set up regular database dumps

---

## 🎯 Priority Order

1. **Deploy Backend to Railway** (Critical)
2. **Connect Frontend to Backend** (Critical)
3. **Test Integration** (Critical)
4. **Configure Custom Domains** (Optional)
5. **Post-Deployment Tasks** (Optional)

---

## 📝 Quick Reference

### Railway Backend URL
- Format: `https://your-app.up.railway.app`
- API: `https://your-app.up.railway.app/api`
- Admin: `https://your-app.up.railway.app/admin`

### Vercel Frontend URL
- Current: https://nomadiq-travel.vercel.app/
- Custom: `https://nomadiq.com` (after domain setup)

### Environment Variables Needed

**Railway:**
- `APP_KEY` (generate with `php artisan key:generate --show`)
- `APP_URL` (Railway URL or custom domain)
- Database credentials (auto-injected by Railway)
- SendGrid API key
- M-Pesa credentials (if using)

**Vercel:**
- `NEXT_PUBLIC_API_URL` (Railway backend URL + `/api`)

---

## 🆘 Troubleshooting

### Backend Not Connecting
- Check Railway URL is correct
- Verify CORS configuration
- Check environment variables
- View Railway logs for errors

### Frontend Not Loading Data
- Verify `NEXT_PUBLIC_API_URL` is set correctly
- Check browser console for errors
- Verify backend is accessible
- Check CORS headers

### Images Not Loading
- Verify storage link created (`php artisan storage:link`)
- Check `APP_URL` is correct
- Verify CORS for storage route
- Check file permissions

---

## 🎉 Ready to Deploy Backend!

Follow the steps above to deploy your backend to Railway, then connect it to your Vercel frontend. Once connected, your full-stack application will be live!

**Next**: Deploy backend to Railway → Connect to frontend → Test everything!

