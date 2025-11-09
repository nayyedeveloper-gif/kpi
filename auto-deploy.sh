#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}KPI App Auto Deployment Script${NC}"
echo -e "${GREEN}================================${NC}"

# Server details
SERVER_IP="203.161.56.115"
SERVER_USER="root"
APP_DIR="/var/www/kpi-app"
DB_NAME="kpi_app"
DB_USER="kpi_user"
DB_PASS="KpiApp2024!@#$"

# Function to run commands with error handling
run_command() {
    echo -e "${YELLOW}$ ${1}${NC}"
    eval $1
    if [ $? -ne 0 ]; then
        echo -e "${RED}Error executing: ${1}${NC}"
        exit 1
    fi
}

# Function to run remote commands
run_remote() {
    echo -e "${YELLOW}Remote: $1${NC}"
    ssh $SERVER_USER@$SERVER_IP "$1"
    if [ $? -ne 0 ]; then
        echo -e "${RED}Error executing remote command: $1${NC}"
        exit 1
    fi
}

# Step 1: Push changes to GitHub (if any)
echo -e "\n${YELLOW}Step 1: Checking for local changes...${NC}"
if [ -n "$(git status --porcelain)" ]; then
    echo -e "${YELLOW}Found uncommitted changes. Committing...${NC}"
    run_command "git add ."
    run_command "git commit -m 'Auto-commit before deployment'"
    run_command "git push origin main"
else
    echo -e "${GREEN}No uncommitted changes. Pulling latest from main...${NC}"
    run_command "git pull origin main"
fi

# Step 2: Connect to server and deploy
echo -e "\n${YELLOW}Step 2: Connecting to server and deploying...${NC}"

# SSH into server and run deployment commands
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
set -e

echo -e "\n${YELLOW}Updating code from repository...${NC}"
cd '${APP_DIR}'
git pull origin main

# Install PHP dependencies
echo -e "\n${YELLOW}Installing PHP dependencies...${NC}"
composer install --optimize-autoloader --no-dev

# Install NPM dependencies and build assets
echo -e "\n${YELLOW}Installing NPM dependencies...${NC}
npm install
npm run build

# Set up environment file if it doesn't exist
if [ ! -f .env ]; then
    echo -e "\n${YELLOW}Creating .env file...${NC}"
    cp .env.example .env
    php artisan key:generate
    
    # Update database configuration
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" .env
    
    # Set production environment
    sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
    sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
    sed -i "s/APP_URL=.*/APP_URL=http:\/\/${SERVER_IP}/" .env
fi

# Run database migrations
echo -e "\n${YELLOW}Running database migrations...${NC}
php artisan migrate --force

# Clear caches
echo -e "\n${YELLOW}Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize the application
echo -e "\n${YELLOW}Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set file permissions
echo -e "\n${YELLOW}Setting file permissions...${NC}"
chown -R www-data:www-data ${APP_DIR}
chmod -R 755 ${APP_DIR}/storage
chmod -R 755 ${APP_DIR}/bootstrap/cache

# Restart services
echo -e "\n${YELLOW}Restarting services...${NC}"
systemctl restart nginx
systemctl restart php8.3-fpm

# Set up supervisor if not already set up
if [ ! -f /etc/supervisor/conf.d/laravel-worker.conf ]; then
    echo -e "\n${YELLOW}Setting up supervisor...${NC}"
    cat > /etc/supervisor/conf.d/laravel-worker.conf << 'EOF'
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php ${APP_DIR}/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=${APP_DIR}/storage/logs/worker.log
EOF

    supervisorctl reread
    supervisorctl update
    supervisorctl start laravel-worker:*
fi

# Set up SSL if not already set up
if [ ! -f /etc/letsencrypt/live/server1.29jewellery.com/fullchain.pem ]; then
    echo -e "\n${YELLOW}Setting up SSL certificate...${NC}"
    apt update
    apt install -y certbot python3-certbot-nginx
    certbot --nginx -d server1.29jewellery.com --non-interactive --agree-tos -m admin@29jewellery.com
    
    # Set up auto-renewal
    (crontab -l 2>/dev/null; echo "0 0,12 * * * root python3 -c 'import random; import time; time.sleep(random.random() * 3600)' && certbot renew -q") | crontab -
fi

echo -e "\n${GREEN}✓ Deployment completed successfully!${NC}"
ENDSSH

# Final message
echo -e "\n${GREEN}================================${NC}"
echo -e "${GREEN}Deployment Summary${NC}"
echo -e "${GREEN}================================${NC}"
echo -e "${YELLOW}Application URLs:${NC}"
echo -e "- http://${SERVER_IP}"
echo -e "- https://server1.29jewellery.com"
echo -e "\n${YELLOW}To access your server:${NC}"
echo -e "ssh ${SERVER_USER}@${SERVER_IP}"
echo -e "\n${YELLOW}To view logs:${NC}"
echo -e "ssh ${SERVER_USER}@${SERVER_IP} 'tail -f ${APP_DIR}/storage/logs/laravel.log'"
echo -e "\n${GREEN}Deployment completed at: $(date)${NC}"
