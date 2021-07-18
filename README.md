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
4. Jalanan kan composer dengan command
```composer install --ignore-platform-reqs```

# Notes
1. Beberapa jawaban untuk membuat API Endpoint ada file ```routes/web.php```
2. Untuk **Unit Test** tinggal menjalankan command ```composer test```
