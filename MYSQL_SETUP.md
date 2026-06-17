# Setup MySQL Database untuk ATK Inventory

## 1. Install MySQL
```bash
# Ubuntu/Debian
sudo apt install mysql-server

# Start MySQL
sudo service mysql start
```

## 2. Buat Database
```bash
mysql -u root -p < database/setup_mysql.sql
```

## 3. Update .env
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=atk_inventory
DB_USERNAME=root
DB_PASSWORD=
```

## 4. Migrate & Seed
```bash
php artisan migrate
php artisan db:seed
```

## 5. Start Server
```bash
php artisan serve --port=8000
```

## 6. Test API
```bash
curl http://localhost:8000/api/barang
curl http://localhost:8000/api/barang/stats
curl -X POST http://localhost:8000/api/login -d "email=admin@example.com&password=password"
```

## Login Default
- Email: admin@example.com
- Password: password