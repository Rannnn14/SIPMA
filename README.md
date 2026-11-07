<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Menjalankan SIPMA dengan MySQL

### 1. Siapkan MySQL

Jalankan MySQL melalui XAMPP/Laragon atau pastikan layanan MySQL lokal aktif. Buat database `sipma_db` dengan salah satu cara berikut:

- phpMyAdmin: buka tab **SQL**, jalankan isi file `database/create_sipma_db.sql`.
- MySQL command line: jalankan `mysql -u root -p < database/create_sipma_db.sql` (hapus `-p` jika akun root tidak memakai password).

### 2. Atur koneksi aplikasi

Salin `.env.example` menjadi `.env` jika `.env` belum ada. Isi bagian database sesuai MySQL lokal:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipma_db
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password dengan instalasi MySQL. Pastikan PHP mengaktifkan ekstensi `pdo_mysql`.

### 3. Buat tabel dan akun contoh

Di folder proyek, jalankan:

```bash
composer install
php artisan key:generate
php artisan config:clear
php artisan migrate
php artisan storage:link
php -d upload_max_filesize=50M -d post_max_size=210M -d upload_tmp_dir=storage/app/tmp -d sys_temp_dir=storage/app/tmp artisan serve
```

Buka alamat yang dicetak oleh `php artisan serve`. Migrasi membuat tabel MySQL aplikasi. Mahasiswa masuk melalui Bridge API kampus; akun internal dibuat dari menu pengelolaan akun. `php artisan db:seed` hanya mengisi master kategori dan jenis pelanggaran, tanpa menghapus data atau membuat akun dan laporan dummy.

### 4. Coba alur kerja

Mahasiswa masuk menggunakan NIM dan kata sandi portal kampus melalui Bridge API. Akun Kemahasiswaan, Kajur, Dekan, Wadek III, dan Warek dikelola melalui menu pengelolaan akun. Setelah login, kirim laporan dengan bukti lampiran; Kemahasiswaan memeriksa dan meneruskannya ke Kajur, yang dapat mengisi tindak lanjut. Dashboard menampilkan data laporan yang benar-benar tersimpan.

Jika koneksi gagal, pastikan layanan MySQL hidup, database dan kredensial di `.env` benar, lalu jalankan `php artisan config:clear`.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
