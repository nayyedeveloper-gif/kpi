#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}KPI App Deployment Script${NC}"
echo -e "${GREEN}================================${NC}"

# Server details
SERVER_IP="203.161.56.115"
SERVER_USER="root"
APP_DIR="/var/www/kpi-app"
DB_NAME="kpi_app"
DB_USER="kpi_user"
DB_PASS="KpiApp2024!@#$"

echo -e "\n${YELLOW}Step 1: Installing server packages...${NC}"
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
apt update && apt upgrade -y
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

echo "=== Installations Complete ==="
php -v
composer -V
node -v
ENDSSH

echo -e "\n${GREEN}✓ Server packages installed${NC}"

echo -e "\n${YELLOW}Step 2: Configuring MySQL...${NC}"
ssh $SERVER_USER@$SERVER_IP << ENDSSH
mysql -u root << MYSQL_SCRIPT
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT
echo "Database created successfully"
ENDSSH

echo -e "\n${GREEN}✓ MySQL configured${NC}"

echo -e "\n${YELLOW}Step 3: Creating application directory...${NC}"
ssh $SERVER_USER@$SERVER_IP "mkdir -p $APP_DIR"

echo -e "\n${YELLOW}Step 4: Uploading application files...${NC}"
rsync -avz --exclude 'node_modules' --exclude '.git' --exclude 'storage/logs/*' \
    --exclude '.env' --exclude 'vendor' \
    ./ $SERVER_USER@$SERVER_IP:$APP_DIR/

echo -e "\n${GREEN}✓ Files uploaded${NC}"

echo -e "\n${YELLOW}Step 5: Creating production .env file...${NC}"
ssh $SERVER_USER@$SERVER_IP << ENDSSH
cat > $APP_DIR/.env << 'EOF'
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
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASS

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
ENDSSH

echo -e "\n${YELLOW}Step 6: Installing dependencies and building...${NC}"
ssh $SERVER_USER@$SERVER_IP << ENDSSH
cd $APP_DIR

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Generate app key
php artisan key:generate

# Install and build frontend
npm install
npm run build

# Setup storage
php artisan storage:link

# Set permissions
chown -R www-data:www-data $APP_DIR
chmod -R 755 $APP_DIR
chmod -R 775 $APP_DIR/storage
chmod -R 775 $APP_DIR/bootstrap/cache

# Run migrations
php artisan migrate --force

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
ENDSSH

echo -e "\n${GREEN}✓ Application configured${NC}"

echo -e "\n${YELLOW}Step 7: Configuring Nginx...${NC}"
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
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
ENDSSH

echo -e "\n${GREEN}✓ Nginx configured${NC}"

echo -e "\n${YELLOW}Step 8: Configuring firewall...${NC}"
ssh $SERVER_USER@$SERVER_IP << ENDSSH
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable
ENDSSH

echo -e "\n${GREEN}✓ Firewall configured${NC}"

echo -e "\n${GREEN}================================${NC}"
echo -e "${GREEN}Deployment Complete!${NC}"
echo -e "${GREEN}================================${NC}"
echo -e "\n${YELLOW}Your application is now live at:${NC}"
echo -e "${GREEN}http://203.161.56.115${NC}"
echo -e "${GREEN}http://server1.29jewellery.com${NC}"
echo -e "\n${YELLOW}Default login credentials:${NC}"
echo -e "Check your database seeders or create a user manually"
