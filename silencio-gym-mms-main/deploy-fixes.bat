@echo off
echo Deploying Silencio Gym Management System fixes...

echo.
echo 1. Clearing Laravel caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo 2. Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo.
echo 3. Optimizing Composer autoloader...
composer dump-autoload --optimize

echo.
echo 4. Building frontend assets...
npm run build

echo.
echo 5. Setting up storage link...
php artisan storage:link

echo.
echo Deployment completed successfully!
echo.
echo The following fixes have been deployed:
echo - Fixed email validation for account editing
echo - Added admin.only middleware to account routes
echo - Fixed route names in AccountController
echo - Improved employee deletion functionality
echo.
echo Your application should now be working properly at http://156.67.221.184:8001/
echo.
pause
