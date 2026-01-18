# Panduan Deployment Laravel OCR ke Ubuntu VPS

## Daftar Isi
1. [Setup Awal VPS](#setup-awal-vps)
2. [Install Dependencies](#install-dependencies)
3. [Setup Database](#setup-database)
4. [Deploy Project](#deploy-project)
5. [Konfigurasi Web Server](#konfigurasi-web-server)
6. [SSL Certificate](#ssl-certificate)
7. [Optimisasi & Production](#optimisasi--production)
8. [Perubahan Code Project](#perubahan-code-project)

---

## Setup Awal VPS

### 1. Login ke VPS
```bash
ssh root@your-vps-ip
```

### 2. Update System
```bash
apt update && apt upgrade -y
```

### 3. Buat User Non-Root (Recommended)
```bash
# Buat user baru
adduser deployer

# Tambahkan ke sudo group
usermod -aG sudo deployer

# Switch ke user baru
su - deployer
```

### 4. Setup Firewall
```bash
# Install UFW
sudo apt install ufw -y

# Allow SSH, HTTP, HTTPS
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable
sudo ufw status
```

---

## Install Dependencies

### 1. Install PHP 8.2
```bash
# Add PHP repository
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 dan extensions yang dibutuhkan
sudo apt install php8.2 php8.2-fpm php8.2-cli php8.2-common \
    php8.2-mysql php8.2-pgsql php8.2-zip php8.2-gd \
    php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
    php8.2-redis php8.2-intl -y

# Verify PHP version
php -v
```

### 2. Install Composer
```bash
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
composer --version
```

### 3. Install Node.js & NPM
```bash
# Install Node.js 20.x LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y

# Verify installation
node -v
npm -v
```

### 4. Install Database (PostgreSQL atau MySQL)

#### Option A: PostgreSQL
```bash
# Install PostgreSQL
sudo apt install postgresql postgresql-contrib -y

# Start service
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Setup database
sudo -u postgres psql
```

Di PostgreSQL console:
```sql
CREATE DATABASE searchbox_db;
CREATE USER searchbox_user WITH PASSWORD 'your_strong_password';
GRANT ALL PRIVILEGES ON DATABASE searchbox_db TO searchbox_user;
\q
```

#### Option B: MySQL
```bash
# Install MySQL
sudo apt install mysql-server -y

# Secure installation
sudo mysql_secure_installation

# Setup database
sudo mysql
```

Di MySQL console:
```sql
CREATE DATABASE searchbox_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'searchbox_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON searchbox_db.* TO 'searchbox_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Install Python & OCR Dependencies
```bash
# Install Python 3
sudo apt install python3 python3-pip python3-venv -y

# Install Tesseract OCR
sudo apt install tesseract-ocr -y

# Verify installation
tesseract --version
python3 --version
```

### 6. Install Nginx
```bash
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx
```

### 7. Install Redis (Optional - untuk cache & queue)
```bash
sudo apt install redis-server -y
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

### 8. Install Git
```bash
sudo apt install git -y
git --version
```

---

## Setup Database

### Konfigurasi PostgreSQL untuk Remote Access (jika diperlukan)
```bash
# Edit postgresql.conf
sudo nano /etc/postgresql/14/main/postgresql.conf
# Ubah: listen_addresses = 'localhost'

# Edit pg_hba.conf
sudo nano /etc/postgresql/14/main/pg_hba.conf
# Tambahkan: host all all 0.0.0.0/0 md5

# Restart PostgreSQL
sudo systemctl restart postgresql
```

---

## Deploy Project

### 1. Clone Project
```bash
# Buat directory untuk project
sudo mkdir -p /var/www
sudo chown -R $USER:$USER /var/www

# Clone repository (ganti dengan repo anda)
cd /var/www
git clone https://github.com/your-username/your-repo.git searchbox
cd searchbox/Search-Box-Ratio-Legis
```

### 2. Install PHP Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 3. Setup Python Environment untuk OCR
```bash
# Pindah ke folder OCR
cd /var/www/searchbox/OCR

# Buat virtual environment
python3 -m venv venv

# Activate virtual environment
source venv/bin/activate

# Install Python packages
pip install -r requirements.txt

# Download Tesseract Indonesian language data
sudo mkdir -p /usr/share/tesseract-ocr/4.00/tessdata
sudo wget -O /usr/share/tesseract-ocr/4.00/tessdata/ind.traineddata \
    https://github.com/tesseract-ocr/tessdata/raw/main/ind.traineddata

# Atau copy dari project
sudo cp ind.traineddata /usr/share/tesseract-ocr/4.00/tessdata/

# Test OCR setup
python test_ocr_setup.py

# Deactivate
deactivate
```

### 4. Setup Environment Laravel
```bash
# Kembali ke folder Laravel
cd /var/www/searchbox/Search-Box-Ratio-Legis

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Edit .env File
```bash
nano .env
```

**Konfigurasi yang perlu diubah:**
```env
APP_NAME="Search Box Ratio Legis"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=searchbox_db
DB_USERNAME=searchbox_user
DB_PASSWORD=your_strong_password

# Atau MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=searchbox_db
# DB_USERNAME=searchbox_user
# DB_PASSWORD=your_strong_password

# Cache Configuration (Redis)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Filesystem (production menggunakan public)
FILESYSTEM_DISK=public

# OCR Python Configuration
OCR_PYTHON_PATH=/var/www/searchbox/OCR/venv/bin/python
OCR_SCRIPT_PATH=/var/www/searchbox/OCR/ocr_pdf.py
OCR_UPLOAD_PATH=/var/www/searchbox/Search-Box-Ratio-Legis/storage/app/public/uploads
```

### 6. Setup Storage & Permissions
```bash
# Create storage directories
mkdir -p storage/app/public/uploads
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs

# Link storage
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data /var/www/searchbox
sudo chmod -R 775 storage bootstrap/cache
```

### 7. Run Migrations
```bash
php artisan migrate --force
```

### 8. Seed Database (Optional)
```bash
php artisan db:seed --force
```

### 9. Build Frontend Assets
```bash
# Install node dependencies
npm install

# Build for production
npm run build
```

### 10. Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Konfigurasi Web Server

### Nginx Configuration
```bash
sudo nano /etc/nginx/sites-available/searchbox
```

**Isi file konfigurasi:**
```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/searchbox/Search-Box-Ratio-Legis/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Max upload size (untuk OCR PDF bisa besar)
    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Enable site:**
```bash
# Link configuration
sudo ln -s /etc/nginx/sites-available/searchbox /etc/nginx/sites-enabled/

# Remove default
sudo rm /etc/nginx/sites-enabled/default

# Test configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

---

## SSL Certificate

### Install Certbot & Setup SSL
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test automatic renewal
sudo certbot renew --dry-run
```

Certbot akan otomatis update konfigurasi Nginx untuk HTTPS.

---

## Optimisasi & Production

### 1. Setup Supervisor untuk Queue Workers
```bash
# Install Supervisor
sudo apt install supervisor -y

# Create configuration
sudo nano /etc/supervisor/conf.d/searchbox-worker.conf
```

**Isi file:**
```ini
[program:searchbox-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/searchbox/Search-Box-Ratio-Legis/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/searchbox/Search-Box-Ratio-Legis/storage/logs/worker.log
stopwaitsecs=3600
```

**Start Supervisor:**
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start searchbox-worker:*
```

### 2. Setup Cron untuk Scheduler
```bash
# Edit crontab
crontab -e
```

**Tambahkan:**
```cron
* * * * * cd /var/www/searchbox/Search-Box-Ratio-Legis && php artisan schedule:run >> /dev/null 2>&1
```

### 3. PHP-FPM Optimization
```bash
sudo nano /etc/php/8.2/fpm/pool.d/www.conf
```

**Ubah settings:**
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

**Restart PHP-FPM:**
```bash
sudo systemctl restart php8.2-fpm
```

### 4. Monitoring & Logging
```bash
# View Laravel logs
tail -f /var/www/searchbox/Search-Box-Ratio-Legis/storage/logs/laravel.log

# View Nginx logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# View queue worker logs
tail -f /var/www/searchbox/Search-Box-Ratio-Legis/storage/logs/worker.log
```

---

## Perubahan Code Project

### 1. File yang WAJIB Diubah/Update

#### A. `.env` (sudah dijelaskan di atas)
- Database credentials
- APP_URL
- OCR paths
- Cache/Queue configuration

#### B. `config/filesystems.php`
Pastikan disk 'public' configured dengan benar:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

#### C. `app/Http/Controllers/SearchBoxController.php`
Update OCR integration untuk menggunakan absolute paths:
```php
// Pastikan paths menggunakan environment variables
$pythonPath = env('OCR_PYTHON_PATH', 'python3');
$scriptPath = env('OCR_SCRIPT_PATH', base_path('../OCR/ocr_pdf.py'));
$uploadPath = env('OCR_UPLOAD_PATH', storage_path('app/public/uploads'));
```

#### D. `OCR/ocr_pdf.py`
Pastikan compatible dengan Linux paths (ganti `\` dengan `/`).

#### E. `.gitignore`
Tambahkan:
```
.env
.env.production
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
public/storage
venv/
__pycache__/
```

### 2. Security Improvements

#### A. Disable Debug Mode
Di `.env`:
```env
APP_DEBUG=false
```

#### B. Setup CSRF Protection
Pastikan semua forms menggunakan `@csrf` token.

#### C. Rate Limiting
Di `routes/web.php` atau `app/Http/Kernel.php`, tambahkan rate limiting untuk endpoints sensitif.

#### D. File Upload Validation
Di controller, pastikan validasi file upload ketat:
```php
$request->validate([
    'file' => 'required|file|mimes:pdf|max:51200', // max 50MB
]);
```

### 3. Performance Improvements

#### A. Enable OPcache
```bash
sudo nano /etc/php/8.2/fpm/php.ini
```
Uncomment dan set:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

#### B. Database Indexing
Pastikan migrations memiliki index pada kolom yang sering di-query:
```php
$table->string('document_number')->index();
$table->string('document_type')->index();
```

### 4. Backup Strategy

#### Create Backup Script
```bash
nano /var/www/searchbox/backup.sh
```

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/searchbox"
mkdir -p $BACKUP_DIR

# Backup database
pg_dump -U searchbox_user searchbox_db | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup uploaded files
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz /var/www/searchbox/Search-Box-Ratio-Legis/storage/app/public

# Keep only last 7 days backups
find $BACKUP_DIR -type f -mtime +7 -delete
```

```bash
chmod +x /var/www/searchbox/backup.sh

# Add to crontab
crontab -e
# Add: 0 2 * * * /var/www/searchbox/backup.sh
```

---

## Troubleshooting

### 1. Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/searchbox
sudo chmod -R 775 /var/www/searchbox/Search-Box-Ratio-Legis/storage
sudo chmod -R 775 /var/www/searchbox/Search-Box-Ratio-Legis/bootstrap/cache
```

### 2. OCR Not Working
```bash
# Test Python script manually
cd /var/www/searchbox/OCR
source venv/bin/activate
python ocr_pdf.py path/to/test.pdf
```

### 3. 500 Internal Server Error
```bash
# Check Laravel logs
tail -f /var/www/searchbox/Search-Box-Ratio-Legis/storage/logs/laravel.log

# Check Nginx logs
tail -f /var/log/nginx/error.log
```

### 4. Database Connection Failed
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### 5. Clear All Cache
```bash
cd /var/www/searchbox/Search-Box-Ratio-Legis
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Update/Maintenance

### Pull Latest Changes
```bash
cd /var/www/searchbox/Search-Box-Ratio-Legis

# Pull changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl restart php8.2-fpm
sudo supervisorctl restart searchbox-worker:*
```

---

## Checklist Deployment

- [ ] VPS Ubuntu ready (minimal 2GB RAM)
- [ ] Domain pointing ke VPS IP
- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] Node.js & NPM installed
- [ ] Database (PostgreSQL/MySQL) installed & configured
- [ ] Python 3 & Tesseract OCR installed
- [ ] Nginx installed & configured
- [ ] Project cloned & dependencies installed
- [ ] .env configured correctly
- [ ] Storage linked & permissions set
- [ ] Migrations run
- [ ] SSL certificate installed
- [ ] Queue worker configured (Supervisor)
- [ ] Cron scheduler configured
- [ ] Firewall configured
- [ ] Backup strategy implemented

---

## Resources

- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [Digital Ocean Laravel Deploy Guide](https://www.digitalocean.com/community/tutorials/how-to-deploy-laravel-application-on-ubuntu)
- [Tesseract OCR Documentation](https://tesseract-ocr.github.io/)
- [Nginx Configuration Guide](https://nginx.org/en/docs/)

---

**Last Updated:** January 17, 2026
**Project:** Search Box Ratio Legis - Laravel + OCR Integration
