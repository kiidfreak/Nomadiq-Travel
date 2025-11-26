# 🚀 Nomadiq Azure Deployment Checklist

## Pre-Deployment Preparation

### Code & Repository
- [ ] All code committed to GitHub
- [ ] `Dockerfile` for Backend created and tested
- [ ] `Dockerfile` for Frontend created and tested
- [ ] `docker-compose.yml` working locally
- [ ] `.env.example` updated with all required variables

### Azure Prerequisites
- [ ] Azure Account created and active
- [ ] Azure CLI installed (optional but recommended)
- [ ] Azure Subscription identified

---

## Phase 1: Azure Resources Setup

### Resource Group
- [ ] Create Resource Group (e.g., `rg-nomadiq-prod`)

### Database (Azure Database for MySQL)
- [ ] Create Azure Database for MySQL - Flexible Server
- [ ] Configure firewall to allow Azure services
- [ ] Create database `nomadiq`
- [ ] Note down: Host, Username, Password

### Container Registry (Azure Container Registry - ACR)
- [ ] Create Azure Container Registry (e.g., `acrnomadiq`)
- [ ] Enable Admin User (for simple authentication)
- [ ] Note down: Login Server, Username, Password

### App Service Plan
- [ ] Create App Service Plan (Linux, B1 or higher recommended for prod)

---

## Phase 2: Backend Deployment (Azure App Service)

### Build & Push Image
- [ ] Login to ACR: `az acr login --name <registry_name>`
- [ ] Build Backend: `docker build -t <registry_name>.azurecr.io/nomadiq-backend:latest .`
- [ ] Push Backend: `docker push <registry_name>.azurecr.io/nomadiq-backend:latest`

### App Service Creation
- [ ] Create Web App for Containers (Backend)
- [ ] Select Image source: Azure Container Registry
- [ ] Select `nomadiq-backend:latest`

### Configuration (Environment Variables)
- [ ] `APP_NAME=Nomadiq`
- [ ] `APP_ENV=production`
- [ ] `APP_KEY=` (generated key)
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=` (https://<your-backend-app>.azurewebsites.net)
- [ ] `DB_CONNECTION=mysql`
- [ ] `DB_HOST=` (MySQL Flexible Server Host)
- [ ] `DB_PORT=3306`
- [ ] `DB_DATABASE=nomadiq`
- [ ] `DB_USERNAME=`
- [ ] `DB_PASSWORD=`
- [ ] `FILESYSTEM_DISK=public` (or s3/azure if configured)

### Post-Deployment
- [ ] SSH into container (via Azure Portal) or use Startup Command
- [ ] Run Migrations: `php artisan migrate --force`
- [ ] Run Seeders: `php artisan db:seed --force`
- [ ] Link Storage: `php artisan storage:link`

---

## Phase 3: Frontend Deployment (Azure App Service)

### Build & Push Image
- [ ] Build Frontend: `docker build -t <registry_name>.azurecr.io/nomadiq-frontend:latest ./frontend`
- [ ] Push Frontend: `docker push <registry_name>.azurecr.io/nomadiq-frontend:latest`

### App Service Creation
- [ ] Create Web App for Containers (Frontend)
- [ ] Select Image source: Azure Container Registry
- [ ] Select `nomadiq-frontend:latest`

### Configuration
- [ ] `NEXT_PUBLIC_API_URL=` (https://<your-backend-app>.azurewebsites.net/api)
- [ ] `PORT=3000`
- [ ] Startup Command: `node server.js` (if needed, usually auto-detected from Dockerfile)

---

## Phase 4: Verification & Domain

### Verification
- [ ] Backend Health Check (`/up` or `/api/health`)
- [ ] Frontend loads
- [ ] Frontend connects to Backend (Try logging in or viewing data)

### Domain (Optional)
- [ ] Map Custom Domain to Frontend App Service
- [ ] Map Custom Domain to Backend App Service
- [ ] Update `APP_URL` and `NEXT_PUBLIC_API_URL`
