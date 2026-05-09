@echo off
echo Starting LinkLearn System Update...

echo ===================================
echo 1. Pulling latest code from GitHub
echo ===================================
git pull origin main

echo ===================================
echo 2. Running Central Database Migrations
echo ===================================
php artisan migrate --force

echo ===================================
echo 3. Running Central Database Seeder
echo ===================================
php artisan db:seed --force

echo ===================================
echo 4. Running Tenant Database Migrations
echo ===================================
php artisan tenants:migrate --force

echo ===================================
echo 5. Running Tenant Database Seeder
echo ===================================
php artisan tenants:seed --force

echo ===================================
echo 6. Clearing Caches
echo ===================================
php artisan optimize:clear

echo ===================================
echo Update Completed Successfully!
echo ===================================
