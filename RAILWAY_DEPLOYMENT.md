# Deploying Nomadiq Travel to Railway

This guide explains how to deploy the Laravel backend to Railway and connect it to your existing Vercel frontend.

## 1. Prepare Repository
Ensure your latest changes are pushed to GitHub.
- The project includes a `Dockerfile` and `docker/entrypoint.sh` configured for Railway.
- The entrypoint script automatically runs migrations and caches configuration on every deployment.

## 2. Deploy Backend to Railway

1.  **Login to Railway**: Go to [railway.app](https://railway.app/) and log in.
2.  **New Project**: Click "New Project" -> "Deploy from GitHub repo".
3.  **Select Repository**: Choose your `Nomadiq-Travel` repository.
4.  **Add Database**:
    - Right-click on the canvas or click "New" -> "Database" -> "MySQL".
    - This will create a MySQL service.
5.  **Configure Backend Service**:
    - Click on your repository card (the Laravel app).
    - Go to **Variables**.
    - Add the following variables (use the values from the MySQL service you just created):
        - `APP_NAME`: `Nomadiq`
        - `APP_ENV`: `production`
        - `APP_DEBUG`: `false`
        - `APP_URL`: (Leave blank for now, will update after domain generation)
        - `LOG_CHANNEL`: `stderr`
        - `DB_CONNECTION`: `mysql`
        - `DB_HOST`: (Get from MySQL service "Connect" tab -> `MYSQLHOST`)
        - `DB_PORT`: (Get from MySQL service "Connect" tab -> `MYSQLPORT`)
        - `DB_DATABASE`: (Get from MySQL service "Connect" tab -> `MYSQLDATABASE`)
        - `DB_USERNAME`: (Get from MySQL service "Connect" tab -> `MYSQLUSER`)
        - `DB_PASSWORD`: (Get from MySQL service "Connect" tab -> `MYSQLPASSWORD`)
        - `APP_KEY`: (Generate one locally using `php artisan key:generate --show` and paste it here)
    - Go to **Settings** -> **Networking**.
    - Click "Generate Domain". This will give you a URL like `web-production-xxxx.up.railway.app`.
    - Go back to **Variables** and update `APP_URL` with this domain (e.g., `https://web-production-xxxx.up.railway.app`).

6.  **Deploy**: Railway should automatically trigger a deployment. If not, click "Deploy".
    - Watch the "Deployments" logs to ensure the build succeeds and migrations run.

## 3. Connect Frontend (Vercel)

1.  **Go to Vercel**: Navigate to your project on Vercel.
2.  **Settings**: Go to "Settings" -> "Environment Variables".
3.  **Update API URL**:
    - Find `NEXT_PUBLIC_API_URL`.
    - Update its value to your Railway Backend URL (e.g., `https://web-production-xxxx.up.railway.app/api`).
    - **Important**: Ensure you append `/api` at the end if your frontend expects it.
4.  **Redeploy**: Go to "Deployments" and redeploy your latest commit to apply the new environment variable.

## 4. Troubleshooting

-   **Build Fails**: Check the "Build Logs" in Railway. Ensure `composer install` runs successfully.
-   **Database Connection**: Double-check the `DB_` variables in Railway match the MySQL service credentials.
-   **CORS Issues**: If the frontend cannot talk to the backend, check `config/cors.php` in the Laravel app. It should allow your Vercel domain.
    - You might need to add a `CORS_ALLOWED_ORIGINS` variable in Railway and set it to your Vercel URL (e.g., `https://nomadiq-travel.vercel.app`).
