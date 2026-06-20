# 🚀 Aplikasi Task Manager Berbasis Web

Aplikasi **Task Manager** adalah platform manajemen tugas kolaboratif berbasis web yang dibangun untuk memudahkan pelacakan, pengorganisasian, dan pembagian tugas dalam sebuah proyek. 

Proyek ini dibangun menggunakan **Framework Laravel (PHP)** dengan mengimplementasikan prinsip-prinsip **Pemrograman Berorientasi Objek (OOP)** secara menyeluruh pada arsitektur MVC (Model-View-Controller). Proyek ini ditujukan untuk memenuhi kriteria Tugas Akhir mata kuliah OOP.

## 👥 Anggota Tim & Kontribusi Individu

| Nama Anggota | NIM | Peran & Kontribusi Kode Spesifik |
| :--- | :--- | :--- |
| **M. Haikal** | *[Isi NIM]* | *[Contoh: Backend Developer, mengatur Migrasi Database & ERD, membuat logika OOP pada Controller]* |
| **Salman Al Farisi** | *[Isi NIM]* | *[Contoh: Frontend Developer, merancang UI Kanban Board menggunakan Bootstrap 5]* |
| **Erliadi** | *[Isi NIM]* | *[Contoh: Mengerjakan fitur Sistem Autentikasi (Login/Register) dan Session]* |
| **Jefri Mulya Pratama** | *[Isi NIM]* | *[Contoh: Mengerjakan fitur CRUD untuk Proyek dan Tugas]* |
| **Aldi Kurniawan** | *[Isi NIM]* | *[Contoh: Quality Assurance (Testing), Bug Fixing, dan penyusunan README]* |

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