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
3. Untuk **Unit Test** gunakan command ```composer test```
4. Dockerfile harus dijalankan di project fresh lumen dan tidak dapat dijalankan pada project ini karena dependecies pada project ini menggunakan php7.4
5. Untuk mengubah link connect sentry hanya tinggal mengganti pada file ```.env``` dan sehabis itu menjalankan command ```php artisan sentry:test```
6. Check login untuk hit API diterapkan pada Soal no. 1 (Book API Endpoint)
7. Dokumentasi Swagger terdapat pada base route.
8. Copy kode dibawah ini ke dalam file ```.env``` sebagai setup untuk Mailgun
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME="budi.postmaster@sandboxe21523596fa144c3a137d96437ac1cf5.mailgun.org"
MAIL_PASSWORD="budi.6014c746505911184aa9a6b095df4e19-c485922e-194f3db1"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="budi.postmaster@sandboxe21523596fa144c3a137d96437ac1cf5.mailgun.org"
MAIL_FROM_NAME="Test IBID"
MAILGUN_DOMAIN="budi.sandboxe21523596fa144c3a137d96437ac1cf5.mailgun.org"
MAILGUN_SECRET="budi.220392701aaaeac235fb265a5ae56327-c485922e-46c5ac1e"
```
Setelah di copy ke ```.env``` pada
```
MAIL_USERNAME="budi.postmaster@sandboxe21523596fa144c3a137d96437ac1cf5.mailgun.org"
MAIL_PASSWORD="budi.6014c746505911184aa9a6b095df4e19-c485922e-194f3db1"
MAIL_FROM_ADDRESS="budi.postmaster@sandboxe21523596fa144c3a137d96437ac1cf5.mailgun.org"
MAILGUN_DOMAIN="budi.sandboxe21523596fa144c3a137d96437ac1cf5.mailgun.org"
MAILGUN_SECRET="budi.220392701aaaeac235fb265a5ae56327-c485922e-46c5ac1e"
```
hapus semua tulisan yang mengandung ```budi.``` pada tiap awalan string. Saya menambahkan string ```budi.``` karena sebelumnya Mailgun mendeteksi leaks of username and password
