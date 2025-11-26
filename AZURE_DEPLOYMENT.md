# Azure Deployment Guide for Nomadiq

This guide details the steps to deploy the Nomadiq application (Laravel Backend + Next.js Frontend) to Microsoft Azure using Docker Containers and Azure App Service.

## Prerequisites

1.  **Azure Account**: You need an active Azure subscription.
2.  **Azure CLI**: Installed on your local machine (optional but recommended).
3.  **Docker**: Installed and running locally.

## Architecture Overview

-   **Backend**: Docker container (Nginx + PHP-FPM) running on **Azure App Service for Containers**.
-   **Frontend**: Docker container (Next.js) running on **Azure App Service for Containers**.
-   **Database**: **Azure Database for MySQL - Flexible Server**.
-   **Registry**: **Azure Container Registry (ACR)** to store our Docker images.

---

## Step 1: Create Azure Resources

You can do this via the Azure Portal (https://portal.azure.com) or Azure CLI.

### 1.1 Resource Group
Create a new Resource Group to hold all your resources.
-   **Name**: `rg-nomadiq-prod`
-   **Region**: Select a region close to your users (e.g., `East US`, `West Europe`).

### 1.2 Azure Container Registry (ACR)
Create a registry to store your Docker images.
-   **Name**: `acrnomadiq` (must be unique globally)
-   **SKU**: `Basic`
-   **Admin User**: **Enable** (This allows easy authentication).
-   **Note down**: Login server (e.g., `acrnomadiq.azurecr.io`), Username, and Password (from Access Keys blade).

### 1.3 Azure Database for MySQL - Flexible Server
-   **Name**: `mysql-nomadiq-prod`
-   **Version**: `8.0` or `5.7` (match your local dev, usually 8.0).
-   **Compute**: `Burstable B1ms` (good for dev/test) or `General Purpose` for prod.
-   **Authentication**: MySQL authentication only (or both). Create an admin user.
-   **Networking**: Allow public access from any Azure service (checkbox). Add your client IP for local access if needed.
-   **Database**: Create a new database named `nomadiq`.

---

## Step 2: Build and Push Docker Images

### 2.1 Login to ACR
```bash
docker login acrnomadiq.azurecr.io --username <username> --password <password>
```

### 2.2 Build & Push Backend
Navigate to the root of your project:
```bash
# Build
docker build -t acrnomadiq.azurecr.io/nomadiq-backend:latest .

# Push
docker push acrnomadiq.azurecr.io/nomadiq-backend:latest
```

### 2.3 Build & Push Frontend
```bash
# Build
docker build -t acrnomadiq.azurecr.io/nomadiq-frontend:latest ./frontend

# Push
docker push acrnomadiq.azurecr.io/nomadiq-frontend:latest
```

---

## Step 3: Deploy Backend to App Service

1.  Go to **App Services** in Azure Portal -> **Create**.
2.  **Basics**:
    -   Resource Group: `rg-nomadiq-prod`
    -   Name: `app-nomadiq-api` (must be unique)
    -   Publish: **Docker Container**
    -   OS: **Linux**
    -   Plan: Create new (e.g., `asp-nomadiq`, B1 SKU).
3.  **Docker**:
    -   Options: Single Container
    -   Image Source: **Azure Container Registry**
    -   Registry: `acrnomadiq`
    -   Image: `nomadiq-backend`
    -   Tag: `latest`
4.  **Review + Create**.

### Configuration (Environment Variables)
Once created, go to **Settings -> Environment variables** and add:

| Name | Value |
|------|-------|
| `APP_NAME` | `Nomadiq` |
| `APP_ENV` | `production` |
| `APP_KEY` | `base64:...` (Run `php artisan key:generate --show` locally to get one) |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://app-nomadiq-api.azurewebsites.net` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `mysql-nomadiq-prod.mysql.database.azure.com` |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | `nomadiq` |
| `DB_USERNAME` | `<your_mysql_admin>` |
| `DB_PASSWORD` | `<your_mysql_password>` |
| `LOG_CHANNEL` | `stderr` |

**Save** and Restart.

### Run Migrations
You can run migrations via SSH in the portal:
1.  Go to **Development Tools -> SSH**.
2.  Run:
    ```bash
    cd /var/www
    php artisan migrate --force
    php artisan storage:link
    ```

---

## Step 4: Deploy Frontend to App Service

1.  Create another App Service.
    -   Name: `app-nomadiq-web`
    -   Plan: Use the same one (`asp-nomadiq`) to save money.
2.  **Docker**:
    -   Image: `nomadiq-frontend`
    -   Tag: `latest`
3.  **Review + Create**.

### Configuration
Go to **Settings -> Environment variables**:

| Name | Value |
|------|-------|
| `NEXT_PUBLIC_API_URL` | `https://app-nomadiq-api.azurewebsites.net/api` |
| `PORT` | `3000` |

**Save** and Restart.

---

## Step 5: Troubleshooting

-   **Application Error :** Check **Log Stream** in the App Service blade.
-   **Database Connection Failed:** Ensure "Allow public access from any Azure service within Azure to this server" is CHECKED in MySQL Networking settings.
-   **CORS Issues:** Update `config/cors.php` in Laravel to allow the frontend URL (`https://app-nomadiq-web.azurewebsites.net`).

## Step 6: Updates

To update your app:
1.  Make code changes.
2.  Re-build and push Docker images (`docker push ...`).
3.  Restart the App Services (or enable **Continuous Deployment** in the Deployment Center blade of the App Service to auto-pull new images).
