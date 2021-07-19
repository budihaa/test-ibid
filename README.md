# Guide for running app (Debian & Ubuntu)

1. Install MongoDB Driver and Extention for PHP.
```
sudo apt install php-mongodb
sudo pecl install mongod
```

2. Enable PUT/PACTH method in PHP by installing php-apfd.
```sudo pecl install apfd```

3. Install the grpc & protobuf extension to use Firestore.
```
sudo pecl install grpc
sudo pecl install protobuf
```
4. Install composer dengan command
```composer install --ignore-platform-reqs```

# Notes
1. Ubah ```.env.example.com``` menjadi ```.env```
2. Beberapa jawaban untuk membuat API Endpoint ada file ```routes/web.php```
3. Untuk **Unit Test** gunakan command ```vendor/bin/phpunit```. Untuk Test Case update dan delete book anda harus menggunakan book ID yang sebenarnya agar test pass.
4. Dockerfile harus dijalankan di project fresh lumen dan tidak dapat dijalankan pada project ini karena dependecies pada project ini menggunakan php7.4
5. Untuk mengubah link connect sentry hanya tinggal mengganti pada file ```.env``` dan sehabis itu menjalankan command ```php artisan sentry:test```
6. Check login untuk hit API diterapkan pada Soal no. 1 (Book API Endpoint)
7. Dokumentasi Swagger terdapat pada base route.
8. Konfigurasi Mailgun terlebih dahulu pada file ```.env```
