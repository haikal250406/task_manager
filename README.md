# 🚀 Aplikasi Task Manager Berbasis Web

Aplikasi **Task Manager** adalah platform manajemen tugas kolaboratif berbasis web yang dibangun untuk memudahkan pelacakan, pengorganisasian, dan pembagian tugas dalam sebuah proyek. 

Proyek ini dibangun menggunakan **Framework Laravel (PHP)** dengan mengimplementasikan prinsip-prinsip **Pemrograman Berorientasi Objek (OOP)** secara menyeluruh pada arsitektur MVC (Model-View-Controller). Proyek ini ditujukan untuk memenuhi kriteria Tugas Akhir mata kuliah OOP.

## 👥 Anggota Tim & Kontribusi Individu

| Nama Anggota | NIM | Peran & Kontribusi Kode Spesifik |
| :--- | :--- | :--- |
| **M. Haikal** | *[24210181]* | 
| **Salman Al Farisi** | *[24210194]* | 
| **Erliadi** | *[24210145]* | 
| **Jefri Mulya Pratama** | *[24210178]* | 
| **Aldi Kurniawan** | *[24210075]* | 

## ✨ Fitur Utama
* **Sistem Autentikasi:** Login dan Registrasi pengguna yang aman.
* **Manajemen Proyek (CRUD):** Membuat, melihat, mengedit, dan mengelola proyek.
* **Papan Kanban (Kanban Board):** Visualisasi progres tugas interaktif dengan status: `To Do`, `In Progress`, dan `Done`.
* **Kategori Tugas Cerdas:** Pemisahan tugas berdasarkan tipe, seperti *Bug* dan *Feature*.
* **Soft Deletes:** Fitur pemulihan data; data yang dihapus tidak langsung hilang dari database.

## 💻 Penerapan Prinsip OOP
1. **Encapsulation (Enkapsulasi):** Memproteksi data dengan atribut `protected $fillable` pada layer Model.
2. **Inheritance (Pewarisan):** Model `BugTask` dan `FeatureTask` mewarisi seluruh atribut dari class induk `Task`.
3. **Polymorphism (Polimorfisme):** Penggunaan *Global Scopes* pada class anak untuk memanipulasi *query* tanpa mengubah induk class.
4. **Object & Class:** Pembuatan objek dinamis pada Controller untuk memproses *request* dari pengguna.

---

## 🛠️ Teknologi yang Digunakan
* **Backend:** PHP 8.3+, Framework Laravel
* **Database:** MySQL
* **Frontend:** HTML5, CSS3, Bootstrap 5, Blade Templating
* **Version Control:** Git & GitHub

---

## ⚙️ Cara Instalasi & Menjalankan Proyek (Setup Instructions)

Bagi dosen/penguji yang ingin menjalankan aplikasi ini di mesin lokal (seperti XAMPP atau Laragon), silakan ikuti langkah-langkah berikut:

### 1. Kloning Repositori
Buka Terminal / Command Prompt, lalu jalankan:
```bash
git clone [https://github.com/haikal250406/task_manager.git](https://github.com/haikal250406/task_manager.git)
cd task_manager
```
### 2. Instalasi Dependensi
Instal library PHP dan Frontend yang dibutuhkan:
```bash
composer install
npm install
npm run build
```bash
```
### 3. Pengaturan Konfigurasi Database (Environment)
Salin file .env.example menjadi .env:
```bash
cp .env.example .env
```bash
Buka file .env dan atur koneksi database. Pastikan sudah membuat database kosong di MySQL (misalnya db_task_manager):
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_task_manager
DB_USERNAME=root
DB_PASSWORD=
```
### 4. Generate Key Aplikasi & Migrasi Database
Jalankan perintah ini untuk mengamankan aplikasi dan membangun tabel di MySQL:
```bash
php artisan key:generate
php artisan migrate
```bash
```
### 5. Nyalakan Server Lokal
```bash
php artisan serve
```bash
Aplikasi sudah siap digunakan dan bisa diakses melalui browser pada alamat: http://localhost:8000



