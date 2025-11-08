#!/bin/bash

# Complete Database Fix Script
# This will fix all database issues and create tables

SERVER_IP="203.161.56.115"
SERVER_USER="root"
SERVER_PASS="EuuPMK1R1t7w4Zvx26"

echo "================================"
echo "Complete Database Fix"
echo "================================"

# Create a temporary expect script for automation
cat > /tmp/db-fix.exp << 'EXPECT_SCRIPT'
#!/usr/bin/expect -f
set timeout -1

spawn ssh root@203.161.56.115

expect "password:"
send "EuuPMK1R1t7w4Zvx26\r"

expect "# "
send "sudo mysql << 'EOF'\r"
send "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'RootPass2024!@#';\r"
send "FLUSH PRIVILEGES;\r"
send "EXIT;\r"
send "EOF\r"

expect "# "
send "mysql -u root -p'RootPass2024!@#' << 'EOF'\r"
send "DROP DATABASE IF EXISTS kpi_app;\r"
send "CREATE DATABASE kpi_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\r"
send "DROP USER IF EXISTS 'kpi_user'@'localhost';\r"
send "CREATE USER 'kpi_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'KpiApp2024!@#\$';\r"
send "GRANT ALL PRIVILEGES ON kpi_app.* TO 'kpi_user'@'localhost';\r"
send "FLUSH PRIVILEGES;\r"
send "EXIT;\r"
send "EOF\r"

expect "# "
send "cd /var/www/kpi-app\r"

expect "# "
send "php artisan config:clear\r"

expect "# "
send "php artisan migrate:fresh --force\r"

expect "# "
send "php artisan db:seed --force\r"

expect "# "
send "php artisan tinker << 'TINKER'\r"
send "\\App\\Models\\User::create(['name' => 'Admin User', 'email' => 'admin@29jewellery.com', 'password' => bcrypt('admin123'), 'role' => 'admin']);\r"
send "exit\r"
send "TINKER\r"

expect "# "
send "php artisan optimize\r"

expect "# "
send "systemctl restart nginx\r"

expect "# "
send "systemctl restart php8.3-fpm\r"

expect "# "
send "exit\r"

expect eof
EXPECT_SCRIPT

chmod +x /tmp/db-fix.exp

# Check if expect is installed
if ! command -v expect &> /dev/null; then
    echo "Installing expect..."
    brew install expect 2>/dev/null || echo "Please install expect manually"
fi

# Run the expect script
/tmp/db-fix.exp

echo ""
echo "================================"
echo "Fix Complete!"
echo "================================"
echo ""
echo "Login at: http://203.161.56.115/login"
echo "Email: admin@29jewellery.com"
echo "Password: admin123"
