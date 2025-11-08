#!/bin/bash

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}Fixing Database Issue${NC}"
echo -e "${GREEN}================================${NC}"

SERVER_IP="203.161.56.115"
SERVER_USER="root"

echo -e "\n${YELLOW}Step 1: Fixing MySQL user permissions...${NC}"
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
mysql -u root << 'MYSQL_SCRIPT'
DROP USER IF EXISTS 'kpi_user'@'localhost';
CREATE USER 'kpi_user'@'localhost' IDENTIFIED BY 'KpiApp2024!@#$';
GRANT ALL PRIVILEGES ON kpi_app.* TO 'kpi_user'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT
echo "✓ MySQL user fixed"
ENDSSH

echo -e "\n${YELLOW}Step 2: Running migrations...${NC}"
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
cd /var/www/kpi-app
php artisan migrate:fresh --force
echo "✓ Migrations completed"
ENDSSH

echo -e "\n${YELLOW}Step 3: Creating admin user...${NC}"
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
cd /var/www/kpi-app
php artisan tinker << 'EOF'
\App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@29jewellery.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin'
]);
exit
EOF
echo "✓ Admin user created"
ENDSSH

echo -e "\n${YELLOW}Step 4: Clearing cache...${NC}"
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
cd /var/www/kpi-app
php artisan config:clear
php artisan cache:clear
php artisan optimize
echo "✓ Cache cleared"
ENDSSH

echo -e "\n${GREEN}================================${NC}"
echo -e "${GREEN}Database Fixed Successfully!${NC}"
echo -e "${GREEN}================================${NC}"
echo -e "\n${YELLOW}Login Credentials:${NC}"
echo -e "${GREEN}URL: http://203.161.56.115/login${NC}"
echo -e "${GREEN}Email: admin@29jewellery.com${NC}"
echo -e "${GREEN}Password: admin123${NC}"
