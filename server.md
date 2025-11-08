# KPI App Server Deployment Guide

## Server Details
- **IP:** 203.161.56.115
- **Hostname:** server1.29jewellery.com
- **OS:** Ubuntu 24.04
- **Password:** EuuPMK1R1t7w4Zvx26

## Quick Deploy (Automated)
```bash
cd /Users/developer/Documents/kpi/kpi-app
./deploy.sh
```

---

## Manual Deployment Steps
 တ
### 1. Connect to Server
```bash
ssh root@203.161.56.115
# Password: EuuPMK1R1t7w4Zvx26
```

### 2. Install Server Packages
```bash
# Update system
apt update && apt upgrade -y

# Install LEMP Stack
apt install -y nginx mysql-server php8.3-fpm php8.3-mysql php8.3-mbstring \
    php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd \
    php8.3-redis php8.3-intl git unzip curl supervisor redis-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Verify installations
php -v
composer -V
node -v
nginx -v
mysql --version
```

### 3. Configure MySQL
```bash
mysql -u root << 'MYSQL_SCRIPT'
CREATE DATABASE kpi_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kpi_user'@'localhost' IDENTIFIED BY 'KpiApp2024!@#$';
GRANT ALL PRIVILEGES ON kpi_app.* TO 'kpi_user'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT
```

### 4. Upload Application (From Local Machine)
```bash
# On your Mac
cd /Users/developer/Documents/kpi/kpi-app
rsync -avz --exclude 'node_modules' --exclude '.git' --exclude 'storage/logs/*' \
    --exclude '.env' --exclude 'vendor' \
    ./ root@203.161.56.115:/var/www/kpi-app/
```

### 5. Configure Application (On Server)
```bash
cd /var/www/kpi-app

# Create .env file
cat > .env << 'EOF'
APP_NAME="Sales Administration System"
APP_ENV=production
APP_KEY=
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
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
EOF

# Install dependencies
composer install --optimize-autoloader --no-dev
php artisan key:generate

# Build frontend
npm install
npm run build

# Setup storage
php artisan storage:link

# Set permissions
chown -R www-data:www-data /var/www/kpi-app
chmod -R 755 /var/www/kpi-app
chmod -R 775 /var/www/kpi-app/storage
chmod -R 775 /var/www/kpi-app/bootstrap/cache

# Run migrations
php artisan migrate --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 6. Configure Nginx
```bash
cat > /etc/nginx/sites-available/kpi-app << 'EOF'
server {
    listen 80;
    listen [::]:80;
    server_name server1.29jewellery.com 203.161.56.115;
    root /var/www/kpi-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 100M;
}
EOF

# Enable site
ln -sf /etc/nginx/sites-available/kpi-app /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test and restart
nginx -t
systemctl restart nginx
systemctl restart php8.3-fpm
```

### 7. Configure Firewall
```bash
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable
ufw status
```

### 8. Test Application
Open in browser:
- http://203.161.56.115
- http://server1.29jewellery.com

---

## Troubleshooting

### Check Logs
```bash
# Nginx error log
tail -f /var/log/nginx/error.log

# Laravel log
tail -f /var/www/kpi-app/storage/logs/laravel.log

# PHP-FPM status
systemctl status php8.3-fpm

# Nginx status
systemctl status nginx
```

### Clear Cache
```bash
cd /var/www/kpi-app
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Restart Services
```bash
systemctl restart nginx
systemctl restart php8.3-fpm
systemctl restart mysql
```

---

## Database Credentials
- **Database:** kpi_app
- **Username:** kpi_user
- **Password:** KpiApp2024!@#$

## Application URLs
- **Production:** http://server1.29jewellery.com
- **IP Access:** http://203.161.56.115
mysql --version