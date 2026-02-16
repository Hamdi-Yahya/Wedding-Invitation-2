# 💍 Wedding Invitation — Undangan Pernikahan Digital

Aplikasi undangan pernikahan digital berbasis web dengan dashboard admin untuk mengelola tamu, RSVP, ucapan, galeri foto, QR code check-in, dan pengaturan tema.

---

## 🛠️ Tech Stack

| Layer        | Teknologi                        |
|--------------|----------------------------------|
| **Backend**  | PHP 8.2+, Laravel 12             |
| **Frontend** | Blade Templates, Vanilla CSS, JavaScript |
| **Database** | SQLite (default)                 |
| **Bundler**  | Vite 7                           |
| **CSS**      | Tailwind CSS 4 (dashboard layout), Custom CSS (invitation) |
| **Server**   | XAMPP / PHP Built-in Server      |

---

## ✨ Fitur

- 🏠 **Halaman Undangan** — Hero section, countdown, detail acara, galeri, ucapan
- 👫 **Undangan Personal** — Link unik per tamu (`/invite/{slug}`)
- 📋 **RSVP** — Konfirmasi kehadiran + jumlah tamu
- 💬 **Ucapan & Doa** — Tamu bisa kirim ucapan (perlu approval admin)
- 📸 **Galeri Foto** — Upload foto mempelai & hero background
- 📊 **Dashboard Admin** — Statistik, grafik RSVP, progress kehadiran, tamu check-in terbaru
- 🎫 **QR Code Scanner** — Check-in tamu di acara via QR code
- 🎨 **Design & Theme** — Kustomisasi warna, font, dan tema visual
- 📤 **Export** — Export kartu undangan
- 📱 **Responsive** — Mobile-friendly dengan hamburger menu

---

## 📦 Instalasi

### Prasyarat

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x & **npm**
- **XAMPP** (opsional, untuk Apache + MySQL)

### Langkah-langkah

**1. Clone Repository**

```bash
git clone https://github.com/Hamdi-Yahya/Wedding-Invitation-2.git
cd undangan-pernikahan
```

**2. Install Dependencies**

```bash
composer install
npm install
```

**3. Setup Environment**

```bash
cp .env.example .env
php artisan key:generate
```

**4. Setup Database**

Secara default menggunakan SQLite. Pastikan file database sudah ada:

```bash
touch database/database.sqlite
php artisan migrate
```

**5. Jalankan Server**

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (untuk asset building)
npm run dev
```

Buka browser dan akses: **http://localhost:8000**

---

## 🔐 Akses Dashboard Admin

Buka **http://localhost:8000/login** dan masuk dengan akun admin.

Jika belum ada akun admin, buat melalui seeder atau tinker:

```bash
php artisan tinker
```

```php
App\Models\Admin::create([
    'name' => 'Admin',
    'password' => bcrypt('password123'),
]);
```

---

## 📁 Struktur Direktori Utama

```
├── app/
│   ├── Http/Controllers/    # Controller (Dashboard, RSVP, Guest, dll)
│   ├── Models/              # Eloquent Models (Guest, Wish, EventSetting, dll)
│   └── Helpers/             # Helper functions
├── resources/
│   ├── views/
│   │   ├── invitation/      # Halaman undangan publik
│   │   ├── dashboard/       # Halaman admin dashboard
│   │   └── layouts/         # Layout templates
│   └── css/                 # Source CSS
├── public/
│   └── css/                 # CSS yang di-serve langsung
│       ├── invitation.css   # Styling halaman undangan
│       └── dashboard.css    # Styling halaman dashboard
├── routes/
│   ├── web.php              # Route web (publik + dashboard)
│   └── api.php              # Route API
└── database/
    ├── migrations/          # Database migrations
    └── database.sqlite      # Database file (SQLite)
```

---

## 📄 Lisensi

Project ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).
