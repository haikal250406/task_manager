project akhir - aplikasi task maneger

deskripsi project
aplikasi task maneger adalah platform berbasis web yg di kembangkan untuk memudahkan manajemen,pelacakan dan pengorganisasian tugas secara kolaboratif. project ini dibangun mengunakan framework laravel (PHP) dengan mengimplementasikan prinsip prinsip pemorgaman berorientasi objeck pada arsitektur MVC (model-view-controller). Project ini ditujukan untuk memenuhi kriteria tugas akhir kuliah OPP.

fitur utama project
manajemen tugas (CRUD): Pengguna dapat menambah, melihat, mengedit, dan menghapus tugas.
Pelacakan Status (Kanban Board): Memudahkan visualisasi progres tugas berdasarkan status (misalnya: To-Do, In Progress, Done).
Manajemen Database Relasional: Menggunakan sistem database MySQL yang terintegrasi dengan Eloquent ORM Laravel. =======
👥 Anggota Tim & Kontribusi Individu
Nama Anggota	NIM	Peran & Kontribusi Kode Spesifik
M. Haikal	[24210181]	
Salman Al Farisi	[24210194]	
Erliadi	[24210145]	
Jefri Mulya Pratama	[24210178]	
Aldi Kurniawan	[24210075]	
✨ Fitur Utama
Sistem Autentikasi: Login dan Registrasi pengguna yang aman.
Manajemen Proyek (CRUD): Membuat, melihat, mengedit, dan mengelola proyek.
Papan Kanban (Kanban Board): Visualisasi progres tugas interaktif dengan status: To Do, In Progress, dan Done.
Kategori Tugas Cerdas: Pemisahan tugas berdasarkan tipe, seperti Bug dan Feature.
Soft Deletes: Fitur pemulihan data; data yang dihapus tidak langsung hilang dari database.
💻 Penerapan Prinsip OOP
Encapsulation (Enkapsulasi): Memproteksi data dengan atribut protected $fillable pada layer Model.
Inheritance (Pewarisan): Model BugTask dan FeatureTask mewarisi seluruh atribut dari class induk Task.
Polymorphism (Polimorfisme): Penggunaan Global Scopes pada class anak untuk memanipulasi query tanpa mengubah induk class.
Object & Class: Pembuatan objek dinamis pada Controller untuk memproses request dari pengguna.
🛠️ Teknologi yang Digunakan
Backend: PHP 8.3+, Framework Laravel
Database: MySQL
Frontend: HTML5, CSS3, Bootstrap 5, Blade Templating
Version Control: Git & GitHub
⚙️ Cara Instalasi & Menjalankan Proyek (Setup Instructions)
Bagi dosen/penguji yang ingin menjalankan aplikasi ini di mesin lokal (seperti XAMPP atau Laragon), silakan ikuti langkah-langkah berikut:

1. Kloning Repositori
Buka Terminal / Command Prompt, lalu jalankan:

git clone [https://github.com/haikal250406/task_manager.git](https://github.com/haikal250406/task_manager.git)
cd task_manager
2. Instalasi Dependensi
Instal library PHP dan Frontend yang dibutuhkan:

composer install
npm install
npm run build
```bash
3. Pengaturan Konfigurasi Database (Environment)
Salin file .env.example menjadi .env:

cp .env.example .env
```bash
Buka file .env dan atur koneksi database. Pastikan sudah membuat database kosong di MySQL (misalnya db_task_manager):
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_task_manager
DB_USERNAME=root
DB_PASSWORD=
4. Generate Key Aplikasi & Migrasi Database
Jalankan perintah ini untuk mengamankan aplikasi dan membangun tabel di MySQL:

php artisan key:generate
php artisan migrate
```bash
5. Nyalakan Server Lokal
php artisan serve
```bash
Aplikasi sudah siap digunakan dan bisa diakses melalui browser pada alamat: http://localhost:8000
