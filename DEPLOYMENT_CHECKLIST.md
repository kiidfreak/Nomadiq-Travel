# 🚀 Nomadiq Deployment Checklist

## Pre-Deployment Preparation

### Code & Repository
- [ ] All code committed to GitHub
- [ ] All migrations run locally and working
- [ ] `.env.example` updated with all required variables
- [ ] `railway.json` created
- [ ] `.railwayignore` created
- [ ] `README.md` updated with deployment info

### Environment Variables Preparation
- [ ] `APP_KEY` generated (`php artisan key:generate --show`)
- [ ] SendGrid API key ready
- [ ] M-Pesa credentials ready (if using payments)
- [ ] Database credentials ready (Railway will provide)

### Testing
- [ ] All features tested locally
- [ ] API endpoints working
- [ ] Frontend connecting to backend
- [ ] Images loading correctly
- [ ] Payments working (if applicable)
- [ ] Email sending working

---

## Phase 1: Railway Backend Deployment

### Account & Project Setup
- [ ] Railway account created (https://railway.app)
- [ ] GitHub account connected to Railway
- [ ] New project created in Railway
- [ ] Repository connected (`Nomadiq-Travel`)

### Database Setup
- [ ] MySQL database added to Railway project
- [ ] Database connection details copied
- [ ] Database service running

### Environment Variables
- [ ] `APP_NAME=Nomadiq`
- [ ] `APP_ENV=production`
- [ ] `APP_KEY=` (generated key)
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=` (Railway URL initially)
- [ ] Database variables (Railway auto-injects, verify)
- [ ] Mail variables (SendGrid)
- [ ] M-Pesa variables (if using)
- [ ] Session/Cache drivers set

### Service Configuration
- [ ] Start command set: `php artisan serve --host=0.0.0.0 --port=$PORT`
- [ ] Healthcheck path: `/up`
- [ ] Build command configured (or using railway.json)

### Deployment
- [ ] First deployment successful
- [ ] Build logs checked (no errors)
- [ ] Service is running
- [ ] Railway URL accessible

### Database Setup
- [ ] Migrations run: `php artisan migrate --force`
- [ ] Seeders run (if needed): `php artisan db:seed`
- [ ] Storage link created: `php artisan storage:link`
- [ ] Admin user created (if needed)

### Storage Configuration
- [ ] Storage directory writable
- [ ] Storage link working
- [ ] Test image upload working
- [ ] (Optional) S3 configured for production

### Verification
- [ ] API accessible at Railway URL
- [ ] Health check endpoint working (`/up`)
- [ ] Admin panel accessible (`/admin`)
- [ ] API endpoints responding
- [ ] CORS headers present

---

## Phase 2: Vercel Frontend Deployment

### Account & Project Setup
- [ ] Vercel account created (https://vercel.com)
- [ ] GitHub account connected to Vercel
- [ ] New project created in Vercel
- [ ] Repository imported (`Nomadiq-Travel`)
- [ ] Root directory set to `frontend`

### Build Configuration
- [ ] Framework preset: Next.js
- [ ] **Root directory: `frontend`** ⚠️ **CRITICAL - Must be set!**
- [ ] Build command: `npm run build` (default, runs from frontend/)
- [ ] Output directory: `.next` (default)
- [ ] Install command: `npm install` (default, runs from frontend/)
- [ ] **Verify**: `vercel.json` exists in root (already created)

### Environment Variables
- [ ] `NEXT_PUBLIC_API_URL=` (Railway backend URL + `/api`)
- [ ] Variables added for Production, Preview, Development

### Deployment
- [ ] First deployment successful
- [ ] Build logs checked (no errors)
- [ ] Vercel URL accessible
- [ ] Frontend loading correctly

### Verification
- [ ] Frontend accessible at Vercel URL
- [ ] API connection working
- [ ] Images loading from backend
- [ ] All pages working
- [ ] No console errors

---

## Phase 3: Domain Configuration

### Backend Domain (api.nomadiq.com)
- [ ] Domain purchased (if not owned)
- [ ] Custom domain added in Railway
- [ ] DNS CNAME record added:
  - Host: `api`
  - Value: Railway URL
  - TTL: Automatic
- [ ] SSL certificate active (Railway auto-provisions)
- [ ] `APP_URL` updated to `https://api.nomadiq.com`
- [ ] Backend accessible at custom domain

### Frontend Domain (nomadiq.com)
- [ ] Domain added in Vercel
- [ ] DNS records added:
  - A record: `@` → Vercel IP
  - CNAME: `www` → Vercel CNAME
- [ ] SSL certificate active (Vercel auto-provisions)
- [ ] `NEXT_PUBLIC_API_URL` updated to use custom domain
- [ ] Frontend accessible at custom domain

### Environment Variable Updates
- [ ] Railway: `APP_URL=https://api.nomadiq.com`
- [ ] Vercel: `NEXT_PUBLIC_API_URL=https://api.nomadiq.com/api`
- [ ] Redeploy both services
- [ ] Test with custom domains

---

## Phase 4: Post-Deployment Configuration

### CORS Configuration
- [ ] `config/cors.php` updated with production domains
- [ ] Frontend domain added to allowed origins
- [ ] CORS working correctly

### Storage Configuration
- [ ] Storage URLs using correct domain
- [ ] Images accessible from frontend
- [ ] File uploads working

### Email Configuration
- [ ] SendGrid API key added
- [ ] Test email sent successfully
- [ ] Booking confirmations working
- [ ] Payment confirmations working

### Payment Configuration (if applicable)
- [ ] M-Pesa credentials added
- [ ] Webhook URL configured
- [ ] Test payment successful
- [ ] Payment confirmations working

### Scheduled Tasks
- [ ] Cron job configured (Railway or external)
- [ ] Schedule command: `php artisan schedule:run`
- [ ] Email reminders working
- [ ] Post-trip follow-ups working

### Monitoring & Logs
- [ ] Railway logs accessible
- [ ] Vercel logs accessible
- [ ] Error tracking set up (optional)
- [ ] Performance monitoring set up (optional)

### Backups
- [ ] Database backup strategy in place
- [ ] Storage backup strategy (if using local storage)
- [ ] Backup schedule configured

---

## Phase 5: Testing & Verification

### Functional Testing
- [ ] Homepage loads correctly
- [ ] Packages page loads
- [ ] Package detail page works
- [ ] Booking flow works
- [ ] Payment flow works
- [ ] Email confirmations sent
- [ ] Admin panel accessible
- [ ] Admin can create/edit packages
- [ ] Admin can create/edit memories
- [ ] Images upload and display correctly
- [ ] Videos upload and display correctly (if using)

### Performance Testing
- [ ] Page load times acceptable
- [ ] Images optimized
- [ ] API response times acceptable
- [ ] Database queries optimized

### Security Testing
- [ ] HTTPS enabled on all domains
- [ ] Environment variables secure
- [ ] API endpoints protected
- [ ] Admin panel protected
- [ ] CORS configured correctly
- [ ] No sensitive data exposed

---

## Phase 6: Go Live 🎉

### Final Checks
- [ ] All features working
- [ ] All tests passing
- [ ] Performance acceptable
- [ ] Security verified
- [ ] Backup strategy in place
- [ ] Monitoring set up

### Launch
- [ ] Announce to team
- [ ] Monitor for issues
- [ ] Ready to accept bookings!

---

## Troubleshooting Quick Reference

### Railway Issues
- **Build fails**: Check build logs, verify composer.json
- **Database errors**: Verify env variables, check connection
- **Storage issues**: Run `php artisan storage:link`, check permissions
- **502 errors**: Check start command, verify service is running

### Vercel Issues
- **Build fails**: Check build logs, verify package.json
- **API errors**: Verify NEXT_PUBLIC_API_URL, check CORS
- **Image errors**: Update next.config.js domains
- **404 errors**: Check routing, verify file structure

### Domain Issues
- **SSL errors**: Wait for certificate provisioning (can take a few minutes)
- **DNS errors**: Verify DNS records, wait for propagation
- **CORS errors**: Update allowed origins in config/cors.php

---

## Support Resources

- **Railway Docs**: https://docs.railway.app
- **Vercel Docs**: https://vercel.com/docs
- **Laravel Docs**: https://laravel.com/docs
- **Next.js Docs**: https://nextjs.org/docs

---

## Notes

- Keep this checklist updated as you deploy
- Check off items as you complete them
- Document any issues and solutions
- Update team on deployment progress

**Good luck with your deployment! 🚀**

