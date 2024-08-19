# Panduan Menjalankan Polar Apps

Langkah-langkah untuk mengatur dan menjalankan Polar Apps, termasuk pengaturan file `.env`, migrasi database, seeding, dan menjalankan server lokal.

## Spesifikasi Sistem

- **Laravel Version**: 10.x
- **PHP Version**: 8.1 atau lebih tinggi
- **MySQL Version**: 5.7 atau lebih tinggi
- **Composer Version**: 2.2.0 atau lebih tinggi
- **Node.js Version**: 16.x atau lebih tinggi (rekomendasi)
- **PNPM, NPM, atau Yarn**: Salah satu dari ini diperlukan untuk manajemen paket JavaScript

## 1. Mengatur Environment

### Salin File `.env`

1. **Salin file `.env.example` menjadi `.env`**:
    ```bash
    cp .env.example .env
    ```

2. **Edit file `.env` untuk mengatur konfigurasi database**:
    - Gunakan editor teks untuk membuka file `.env` dan atur variabel berikut:
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```

    Gantilah `nama_database`, `your_password`, dan variabel lainnya sesuai kebutuhan.

## 2. Menginstal Dependensi

### Instal Dependensi PHP

1. **Instal dependensi PHP menggunakan Composer**:
    ```bash
    composer install
    ```

### Instal Dependensi JavaScript

2. **Jika menggunakan `pnpm`, instal dependensi dengan**:
    ```bash
    pnpm install
    ```

    Jika menggunakan `npm` atau `yarn`, gunakan salah satu dari perintah berikut:
    ```bash
    npm install
    ```
    atau
    ```bash
    yarn install
    ```

## 3. Menyiapkan Database

### Menjalankan Migrasi

1. **Jalankan migrasi untuk membuat tabel di database**:
    ```bash
    php artisan migrate
    ```

### Menjalankan Seeder

2. **Jalankan seeder untuk mengisi database dengan data awal**:
    ```bash
    php artisan db:seed
    ```

## 4. Menjalankan Server Lokal

1. **Jalankan server lokal Laravel**:
    ```bash
    php artisan serve
    ```

2. **Akses aplikasi di browser**:
    - Kunjungi [http://localhost:8000](http://localhost:8000) untuk mengakses Polar Apps.

## 5. Menjalankan Skrip Pengembangan Frontend

### Jika Menggunakan `pnpm`

- **Jalankan skrip pengembangan untuk frontend**:
    ```bash
    pnpm dev
    ```

### Jika Menggunakan `npm`

- **Jalankan skrip pengembangan untuk frontend**:
    ```bash
    npm run dev
    ```

## 6. Akses Polar Apps

### Admin Page

- **Admin**
   - **Username**: polaradmin@mail.ru
   - **Password**: districtpolar

- **Staff**
   - **Username**: polarstaff@mail.ru
   - **Password**: districtpolar

### Front Page

- **Student**
   - **Username**: polarstudent@mail.ru
   - **Password**: districtpolar

## 7. Memeriksa dan Mengatasi Masalah

- **Jika terjadi masalah saat menjalankan perintah**, periksa file log di `storage/logs` untuk informasi lebih lanjut.
- **Pastikan semua ekstensi PHP yang diperlukan** telah terinstal dan diaktifkan di file konfigurasi PHP (`php.ini`).

## 8. Pushing ke GitHub

1. **Tambahkan file ke staging area**:
    ```bash
    git add .
    ```

2. **Commit perubahan**:
    ```bash
    git commit -m "Setup Polar Apps environment and initial configuration"
    ```

3. **Push ke GitHub**:
    ```bash
    git push origin main
    ```

   Gantilah `main` dengan nama cabang (branch) yang sesuai jika menggunakan nama cabang yang berbeda.

---

Pastikan bahwa spesifikasi sistem dan versi perangkat lunak yang diperlukan sudah terpenuhi sebelum memulai.
# polar-app
