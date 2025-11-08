# Simple Fix - Copy & Paste ဒီ commands တွေကို Terminal မှာ run ပါ

## Step 1: SSH ချိတ်ဆက်ပါ

```bash
ssh root@203.161.56.115
```
Password: `EuuPMK1R1t7w4Zvx26`

---

## Step 2: အောက်ပါ command block တစ်ခုလုံးကို copy လုပ်ပြီး paste လုပ်ပါ

```bash
# Set MySQL root password
sudo mysql << 'EOF'
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'RootPass2024!@#';
FLUSH PRIVILEGES;
EXIT;
EOF

# Create database and user
mysql -u root -p'RootPass2024!@#' << 'EOF'
DROP DATABASE IF EXISTS kpi_app;
CREATE DATABASE kpi_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS 'kpi_user'@'localhost';
CREATE USER 'kpi_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'KpiApp2024!@#$';
GRANT ALL PRIVILEGES ON kpi_app.* TO 'kpi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
EOF

# Go to app directory
cd /var/www/kpi-app

# Clear config
php artisan config:clear

# Run migrations
php artisan migrate:fresh --force

# Create admin user
php artisan tinker << 'TINKER'
\App\Models\User::create(['name' => 'Admin User', 'email' => 'admin@29jewellery.com', 'password' => bcrypt('admin123'), 'role' => 'admin']);
exit
TINKER

# Optimize
php artisan optimize

# Restart services
systemctl restart nginx
systemctl restart php8.3-fpm

echo ""
echo "✅ Setup Complete!"
echo "Login at: http://203.161.56.115/login"
echo "Email: admin@29jewellery.com"
echo "Password: admin123"
```

---

## ✅ ပြီးပါပြီ!

Browser မှာ:
- **URL:** http://203.161.56.115/login
- **Email:** admin@29jewellery.com
- **Password:** admin123

---

## 🔍 အကယ်၍ error ရှိရင်:

### Check Laravel Logs:
```bash
tail -50 /var/www/kpi-app/storage/logs/laravel.log
```

### Check Nginx Error:
```bash
tail -50 /var/log/nginx/error.log
```

### Check PHP-FPM:
```bash
systemctl status php8.3-fpm
```

### Restart Everything:
```bash
systemctl restart mysql
systemctl restart nginx
systemctl restart php8.3-fpm
```
