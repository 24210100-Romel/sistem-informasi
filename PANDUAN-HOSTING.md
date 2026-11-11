# Panduan Hosting (Laravel 13)

Kebutuhan server: **PHP 8.4.1 atau lebih baru** (wajib: komponen Symfony 8 di folder `vendor` tidak berjalan di PHP 8.3) (ekstensi: mbstring, pdo_mysql, fileinfo, openssl, ctype, json, tokenizer, xml), **MySQL/MariaDB**, dan akses **Terminal/SSH** (cPanel biasanya punya menu *Terminal*). Domain sebaiknya sudah memakai **HTTPS** (AutoSSL/Let's Encrypt).

Paket ini sudah berisi folder `vendor` (tanpa paket pengembangan), jadi **tidak perlu Composer di server**. Bila PHP hosting hanya sampai 8.3, minta penyedia hosting menaikkan ke 8.4, atau pasang ulang dependensi di komputer Anda (`composer update --no-dev -o` memakai PHP 8.3, lalu unggah folder `vendor` yang baru).

> **Tanpa Terminal/SSH?** Langkah 5 memerlukan perintah `php artisan`. Jika hosting Anda tidak punya Terminal, jalankan langkah itu di komputer Anda terhadap database hosting (izinkan *Remote MySQL* untuk IP Anda di cPanel), lalu unggah `.env` dan folder yang dihasilkan; atau pilih hosting/VPS yang menyediakan Terminal.

## A. cPanel / shared hosting

1. **Buat database.** cPanel → *MySQL Databases*: buat database, buat user, tambahkan user ke database dengan *All Privileges*. Catat nama database, user, dan password (biasanya berawalan nama akun, mis. `akun_diskominfo`).
2. **Unggah dan ekstrak** zip ke folder di *luar* `public_html`, misalnya `/home/AKUN/diskominfo`.
3. **Arahkan document root ke folder `public`.** cPanel → *Domains* (atau *Subdomains*) → ubah *Document Root* domain/subdomain ke `/home/AKUN/diskominfo/public`.
   - Jika document root tidak bisa diubah: biarkan proyek di `public_html` (atau subfolder). File `.htaccess` di root proyek akan meneruskan semua permintaan ke `public/` dan memblokir `.env`. Ini cadangan, bukan cara yang diutamakan.
4. **Buat `.env`:** (tidak ada `.env` di dalam zip; **jangan** unggah `.env` dari komputer lokal) salin `.env.production.example` menjadi `.env`, lalu isi `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`. Isi juga `ADMIN_PASSWORD` dengan password admin pilihan Anda (opsional; bila kosong, password acak ditampilkan sekali di layar saat seeding).
5. **Di Terminal cPanel:**
   ```bash
   cd ~/diskominfo
   php artisan key:generate --force
   php artisan migrate --seed --force
   php artisan storage:link
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
   Bila `php` bawaan terlalu lama, pilih PHP 8.4 di *MultiPHP Manager* atau gunakan `/opt/cpanel/ea-php84/root/usr/bin/php`.
6. **Izin tulis:** `storage/` dan `bootstrap/cache/` harus dapat ditulis (biasanya `chmod -R 775 storage bootstrap/cache`).
7. **Uji:** buka `https://domain-anda/up` (harus tampil normal), lalu `/login`.

> Jika menjalankan `migrate --seed` menampilkan *"Akun admin dibuat. Username: admin | Password: ..."*, **catat password itu sekarang**; tidak akan ditampilkan lagi.

## B. VPS (Ubuntu + Nginx)

```bash
sudo apt install nginx mysql-server php8.4-fpm php8.4-{mysql,mbstring,xml,curl,zip,bcmath} composer unzip
# unggah proyek ke /var/www/diskominfo, lalu:
cd /var/www/diskominfo && cp .env.production.example .env   # isi nilai .env
bash deploy.sh
sudo chown -R www-data:www-data storage bootstrap/cache
```

Nginx (`/etc/nginx/sites-available/diskominfo`):
```nginx
server {
    server_name domain-anda;
    root /var/www/diskominfo/public;
    index index.php;
    client_max_body_size 12M;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    }
    location ~ /\.(?!well-known) { deny all; }
    location ~* ^/(assets/uploads|storage)/.*\.(php|phtml|phar)$ { deny all; }
}
```
Aktifkan HTTPS dengan `sudo certbot --nginx`.

## C. Daftar periksa sebelum diumumkan

- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` berisi `https://...`
- [ ] Password admin bukan `password`; login dan ganti lewat menu **Pengguna**
- [ ] `https://domain-anda/.env` menghasilkan 403/404 (bukan isi file)
- [ ] Gambar unggahan tampil (bila tidak: jalankan `php artisan storage:link`)
- [ ] Isi **Pengaturan** (alamat, telepon, email), halaman **Profil Instansi**, **Tentang Kami**, **Visi dan Misi**, dan Menu Manager
- [ ] Jika di belakang Cloudflare/proxy: isi `TRUSTED_PROXIES=*`
- [ ] `ANTHROPIC_API_KEY` hanya di `.env` server (kosong = asisten AI memakai mode pencarian gratis)
- [ ] Jadwalkan **backup** database dan folder `storage/app/public` (gambar unggahan)

## D. Memperbarui versi

Unggah kode baru (jangan timpa `.env`, `storage/`), lalu jalankan `bash deploy.sh` (VPS/SSH) atau ulangi langkah `migrate --force` dan `config:cache`, `route:cache`, `view:cache` di cPanel.

## E. Masalah umum

| Gejala | Penyebab / solusi |
|---|---|
| Upload foto gagal / "Gambar harus JPG, PNG, atau WEBP" padahal format benar / 419 saat simpan | Batas PHP terlalu kecil. `public/.user.ini` sudah menaikkannya (tunggu ±5 menit di cPanel); di VPS ubah `upload_max_filesize=12M` dan `post_max_size=14M` di `php.ini`, lalu `systemctl restart php8.3-fpm` |
| "Server tidak dapat menyimpan ... folder sementara" saat upload | Folder tmp PHP bermasalah. Sudah ditangani: browser mengirim berkas lewat base64 sehingga tidak memakai folder tmp. Syaratnya `storage/` dapat ditulis (`chmod -R 775 storage`) dan JavaScript aktif |
| 404 Not Found di semua halaman selain beranda | Rewrite URL tidak aktif atau document root salah. Arahkan domain ke folder `public/`; bila tidak bisa, `.htaccess` di root proyek sudah meneruskan ke `public/` (Apache). Untuk Nginx pastikan `try_files $uri $uri/ /index.php?$query_string;` |
| Halaman 500 tanpa pesan | Cek `storage/logs/laravel-*.log`; biasanya `APP_KEY` kosong atau `storage/` tidak dapat ditulis |
| `No application encryption key` | Jalankan `php artisan key:generate --force`, lalu `php artisan config:cache` |
| Semua halaman selain beranda 404 | Document root belum ke `public/`, atau `mod_rewrite`/`.htaccess` tidak aktif |
| `SQLSTATE ... Access denied` | `DB_*` salah; di cPanel nama DB/user berawalan nama akun |
| Setelah mengubah `.env` tidak berpengaruh | Jalankan `php artisan config:clear` lalu `config:cache` |
| Gambar unggahan 404 | `php artisan storage:link` belum dijalankan (atau hosting memblokir symlink; minta bantuan penyedia hosting) |
| Perubahan tampilan tidak muncul | `php artisan view:clear` |
