# 🚀 Aplikasi Task Manager Berbasis Web

Aplikasi **Task Manager** adalah platform manajemen tugas kolaboratif berbasis web yang dibangun untuk memudahkan pelacakan, pengorganisasian, dan pembagian tugas dalam sebuah proyek. 

Proyek ini dibangun menggunakan **Framework Laravel (PHP)** dengan mengimplementasikan prinsip-prinsip **Pemrograman Berorientasi Objek (OOP)** secara menyeluruh pada arsitektur MVC (Model-View-Controller). Proyek ini ditujukan untuk memenuhi kriteria Tugas Akhir mata kuliah OOP.

## 👥 Anggota Kelompok
1. **M. Haikal**
2. **Salman Al Farisi**
3. **Erliadi**
4. **Jefri Mulya Pratama**
5. **Aldi Kurniawan**

---

## ✨ Fitur Utama
* **Sistem Autentikasi:** Fitur Login dan Registrasi pengguna yang aman.
* **Manajemen Proyek (CRUD):** Pengguna dapat membuat, melihat, mengedit, dan mengelola proyek tim.
* **Papan Kanban (Kanban Board):** Visualisasi progres tugas yang interaktif dengan tiga status utama: `To Do`, `In Progress`, dan `Done`.
* **Kategori Tugas Cerdas:** Pemisahan tugas berdasarkan tipe, seperti *Bug* dan *Feature*.
* **Soft Deletes:** Fitur pemulihan keamanan data. Data proyek atau tugas yang dihapus tidak akan hilang permanen dari *database*.

---

## 💻 Penerapan Prinsip OOP (Object-Oriented Programming)
Aplikasi ini tidak sekadar menggunakan fungsionalitas dasar framework, melainkan secara aktif mendemonstrasikan pilar-pilar OOP:

1. **Encapsulation (Enkapsulasi):** Memproteksi data *database* menggunakan modifier `protected $fillable` pada setiap class Model agar hanya kolom tertentu yang diizinkan untuk diisi pengguna.
2. **Inheritance (Pewarisan):** Model `BugTask` dan `FeatureTask` mewarisi (*extends*) seluruh atribut dan fungsionalitas dari class induk `Task`.
3. **Polymorphism (Polimorfisme):** Penggunaan *Global Scopes* dan method `booted()` pada class anak (seperti `BugTask`) untuk mengubah perilaku otomatis *query builder* tanpa mengubah class induknya.
4. **Object & Class:** Pembuatan dan manipulasi objek secara dinamis di dalam layer *Controller* untuk memproses setiap antarmuka pengguna (View).

---

## 🛠️ Teknologi yang Digunakan
* **Bahasa & Framework:** PHP 8.3+, Laravel 
* **Database:** MySQL (Terintegrasi dengan Eloquent ORM Laravel)
* **Frontend:** HTML5, CSS3, Bootstrap 5, SASS
* **Version Control:** Git & GitHub

---

## ⚙️ Cara Instalasi & Menjalankan Proyek (Setup Guide)

Bagi dosen atau penguji yang ingin menjalankan aplikasi ini di mesin lokal (seperti XAMPP / Laragon), silakan ikuti langkah-langkah berikut:

1. **Clone Repositori:**
   ```bash
   git clone [https://github.com/](https://github.com/)[URL_GITHUB_KALIAN_DISINI]/task_manager.git
   cd task_manager

## 1. Instalasi Dependensi PHP & Frontend:
```bash
composer install
npm install
npm run build
```
## 2. Pengaturan Database (Environment):
* Salin file .env.example menjadi .env.

* Buka file .env dan sesuaikan koneksi database (buat database baru di MySQL, misalnya db_task_manager):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_task_manager
DB_USERNAME=root
DB_PASSWORD=
```
## 3. Generate Kunci Aplikasi & Migrasi Database:
``` bash   
php artisan key:generate
php artisan migrate
```
## 4. Nyalakan Server Lokal:
``` bash
php artisan serve
```
Aplikasi siap diakses melalui browser pada alamat: http://localhost:8000