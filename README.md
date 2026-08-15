# 📝 Laravel Task Manager - Dockerized & CI/CD Pipeline

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Nginx](https://img.shields.io/badge/Nginx-Reverse_Proxy-009639?style=for-the-badge&logo=nginx&logoColor=white)
![GitHub Actions](https://img.shields.io/badge/GitHub_Actions-CI%2FCD-2088FF?style=for-the-badge&logo=githubactions&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-Modern_UI-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)

Aplikasi manajemen tugas (To-Do List) sederhana berbasis **Laravel** yang dirancang dengan arsitektur penyiapan container **Docker** modern dan pipeline **CI/CD otomatis** menggunakan GitHub Actions serta Self-Hosted Runner.

---

## 📌 Fitur Utama Aplikasi

- **Task Management:** Menambahkan tugas baru, menandai status selesai/belum, dan menghapus tugas.
- **UI Modern & Responsif:** Dibangun menggunakan Tailwind CSS dan FontAwesome icon.
- **Statistik Real-time:** Menampilkan total tugas dan ringkasan tugas yang telah diselesaikan.
- **Validasi Input & Konfirmasi:** Dilengkapi validasi server-side dan dialog konfirmasi native browser.

---

## 🏗️ Arsitektur Infrastruktur & DevOps

Aplikasi ini berjalan di atas arsitektur *multi-container* yang terisolasi:

- **Multi-Stage Build Dockerfile:** Menggunakan Alpine Linux untuk menghasilkan container image yang ringan, aman, dan cepat.
- **Separation of Concerns:** Nginx bertindak sebagai reverse proxy yang menangani permintaan statis dan meneruskan permintaan PHP ke container PHP-FPM (`app`).
- **Persistensi Data:** Memanfaatkan Docker Volume untuk mengamankan data SQLite dan direktori `storage/` dari *restart* container.
- **Automated CI/CD Pipeline:** Pembentukan image otomatis saat *push* ke branch `main`, dipublikasikan ke Docker Hub, dan di-deploy ke server/lokal via Self-Hosted Runner.

```text
               ┌─────────────────────────────────────────┐
               │              Docker Host                │
               │                                         │
               │  ┌───────────────┐   ┌───────────────┐  │
 HTTP (8000) ──┼─>│ Nginx Server  │──>│    PHP-FPM    │  │
               │  │  (webserver)  │   │     (app)     │  │
               │  └───────────────┘   └───────┬───────┘  │
               │                              │          │
               │                      ┌───────▼───────┐  │
               │                      │ SQLite Volume │  │
               │                      └───────────────┘  │
               └─────────────────────────────────────────┘
```

## 🚀 Panduan Memulai (Pengembangan Lokal)
Prasyarat
Sebelum menjalankan proyek ini, pastikan mesin lokal kamu sudah terinstal:
- Git
- Docker Engine & Docker Compose

### Langkah Instalasi
 ```
    git clone [https://github.com/USERNAME/laravel-todolist.git](https://github.com/USERNAME/laravel-todolist.git)
    cd laravel-todolist

    cp .env.example .env

    docker compose up -d --build

    # Generate Application Key
    docker compose exec app php artisan key:generate

    # Set Hak Akses Direktori Database & Storage
    docker compose exec -u root app chown -R www-data:www-data /var/www/html/database /var/www/html/storage
    docker compose exec -u root app chmod -R 775 /var/www/html/database /var/www/html/storage

    # Jalankan Migrasi Database
    docker compose exec app php artisan migrate --force
```

  Buka browser: http://localhost:8000

## 🔄 Alur CI/CD Pipeline (GitHub Actions)
Pipeline ini dikonfigurasi pada .github/workflows/deploy.yml dan bekerja secara otomatis ketika terjadi perintah git push origin main:

```
[ Git Push ] ──> [ Job 1: Build & Push ] ──> Push Image ke Docker Hub (:latest & :sha)
                                                   │
                                                   ▼
                 [ Job 2: Local Deploy ]  <── Triggered via Self-Hosted Runner
                 ├── docker compose pull
                 ├── docker compose up -d
                 └── artisan migrate & cache clear
```


## 📄 Lisensi
Proyek ini menggunakan lisensi terbuka MIT License.
