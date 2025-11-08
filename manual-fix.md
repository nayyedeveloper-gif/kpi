# Manual Database Fix

## SSH ချိတ်ဆက်ပါ:

```bash
ssh root@203.161.56.115
# Password: EuuPMK1R1t7w4Zvx26
```

## Server မှာ အောက်ပါ commands တွေ run ပါ:

### 1. MySQL Root Password Set လုပ်ပါ

```bash
# Set MySQL root password
sudo mysql << 'EOF'
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'RootPass2024!@#';
FLUSH PRIVILEGES;
EXIT;
EOF
```

### 2. Database နဲ့ User ပြန်ဖန်တီးပါ

```bash
mysql -u root -p'RootPass2024!@#' << 'EOF'
DROP DATABASE IF EXISTS kpi_app;
CREATE DATABASE kpi_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS 'kpi_user'@'localhost';
CREATE USER 'kpi_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'KpiApp2024!@#$';
GRANT ALL PRIVILEGES ON kpi_app.* TO 'kpi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
EOF
```

### 3. Test Database Connection

```bash
mysql -u kpi_user -p'KpiApp2024!@#$' kpi_app -e "SELECT 'Connection successful!' as status;"
```

### 4. Update .env File

```bash
cd /var/www/kpi-app

# Backup current .env
cp .env .env.backup

# Update .env
cat > .env << 'EOF'
APP_NAME="Sales Administration System"
APP_ENV=production
APP_KEY=base64:YOUR_EXISTING_KEY_HERE
APP_DEBUG=false
APP_URL=http://server1.29jewellery.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kpi_app
DB_USERNAME=kpi_user
DB_PASSWORD=KpiApp2024!@#$

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
EOF

# Copy APP_KEY from backup
APP_KEY=$(grep APP_KEY .env.backup | cut -d '=' -f2)
sed -i "s|APP_KEY=base64:YOUR_EXISTING_KEY_HERE|APP_KEY=$APP_KEY|g" .env
```

### 5. Run Migrations

```bash
cd /var/www/kpi-app

# Clear config cache
php artisan config:clear

# Run migrations
php artisan migrate:fresh --force
```

### 6. Create Admin User

```bash
cd /var/www/kpi-app

php artisan tinker
```

Tinker console မှာ:

```php
\App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@29jewellery.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin'
]);
exit
```

### 7. Clear All Cache

```bash
cd /var/www/kpi-app

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

### 8. Restart Services

```bash
systemctl restart nginx
systemctl restart php8.3-fpm
```

---

## ✅ Test Application

Browser မှာ:
- **URL:** http://203.161.56.115/login
- **Email:** admin@29jewellery.com
- **Password:** admin123

---

## 🔍 Troubleshooting

### Check MySQL Status
```bash
systemctl status mysql
```

### Check PHP-FPM Logs
```bash
tail -f /var/log/php8.3-fpm.log
```

### Check Laravel Logs
```bash
tail -f /var/www/kpi-app/storage/logs/laravel.log
```

### Check Nginx Logs
```bash
tail -f /var/log/nginx/error.log
```

### Test Database Connection
```bash
cd /var/www/kpi-app
php artisan tinker
```

Then:
```php
DB::connection()->getPdo();
exit
```
