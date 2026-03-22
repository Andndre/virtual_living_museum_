# Virtual Living Museum - Guest Features Testing Checklist

## 🔓 FITUR TAMU (Sebelum Login)

Fitur yang bisa diakses tanpa login

### Autentikasi & Manajemen Akun

- [ ] **Register Page** - Halaman registrasi akun
    - Route: `/register` (GET)
    - Aksi: POST `/register`
    - Check: Form registrasi berfungsi, validasi password, email verification

- [ ] **Login Page** - Halaman login
    - Route: `/login` (GET)
    - Aksi: POST `/login`
    - Check: Login berhasil, error handling untuk credentials yang salah

- [ ] **Forgot Password** - Lupa password
    - Route: `/forgot-password` (GET)
    - Aksi: POST `/forgot-password`
    - Check: Email reset dikirim, link reset password valid

- [ ] **Reset Password** - Reset password dengan token
    - Route: `/reset-password/{token}` (GET)
    - Aksi: POST `/reset-password`
    - Check: Password berhasil direset, token expiry validation

### Utilitas

- [ ] **Language Switcher** - Ganti bahasa
    - Route: `/language/{locale}` (GET)
    - Check: Bahasa berubah sesuai pilihan, session language tersimpan

---

## 🔐 FITUR PENGGUNA TERAUTENTIKASI (Setelah Login)

Fitur yang bisa diakses setelah login dengan middleware `auth` + `user`

### Dashboard

- [ ] **Home/Dashboard** - Halaman utama user
    - Route: `/home`
    - Check: Tampilkan user data, progress learning, latest activities, menu fitur

---

## 📚 1. KUNJUNGI PENINGGALAN (Pembelajaran)

Sistem e-learning utama dengan progress tracking

- [ ] **Kunjungi Peninggalan (Main Page)** - E-Learning hub
    - Route: `/kunjungi-peninggalan`
    - Check: List semua era/materials, kategori peninggalan

- [ ] **E-Learning by Era** - E-Learning terorganisir per era
    - Route: `/kunjungi-peninggalan/era/{era_id}`
    - Check: Tampilkan materials dalam era tertentu, progress tracking

- [ ] **Material Details** - Halaman detail materi dengan 4 tab
    - Route: `/kunjungi-peninggalan/materi/{materi_id}`
    - Tab 1: Pre-test
    - Tab 2: E-Book
    - Tab 3: Kunjungi Situs (Virtual Living Museum - WebXR)
    - Tab 4: Post-test
    - Check: Semua tab tersedia, tab lock/unlock sesuai progress, UI responsif

- [ ] **Pre-Test** - Soal pre-learning assessment (Tab 1)
    - Route: GET `/kunjungi-peninggalan/materi/{materi_id}/pretest`
    - Aksi: POST `/kunjungi-peninggalan/materi/{materi_id}/pretest`
    - Check: Render pertanyaan, submit form, score calculation, progress update

- [ ] **E-Book** - Material pembelajaran berbentuk flipbook (Tab 2)
    - Route: `/kunjungi-peninggalan/ebook/{ebook_id}`
    - Aksi: POST `/kunjungi-peninggalan/ebook/{ebook_id}/read` (mark as read)
    - Check: PDF flipper works, page navigation, completion tracking

- [ ] **Kunjungi Situs (Virtual Living Museum - WebXR)** - Museum 3D dengan AR (Tab 3)
    - Route: `/situs/{situs_id}/ar/{museum_id}` (token-based access)
    - Check: Scene loading, object interaction, lighting/shadows, hit-test, WebXR support check

- [ ] **Post-Test** - Soal post-learning assessment (Tab 4)
    - Route: GET `/kunjungi-peninggalan/materi/{materi_id}/posttest`
    - Aksi: POST `/kunjungi-peninggalan/materi/{materi_id}/posttest`
    - Check: Render pertanyaan, scoring, progress to next level

- [ ] **Tugas (Assignments)** - Tugas/assignments pembelajaran
    - Route: `/kunjungi-peninggalan/materi/{materi_id}/tugas`
    - Check: Daftar tugas, deadline, submission status

---

## 🗺️ 2. PETA (Maps)

Fitur peta dan lokasi interaktif

- [ ] **Maps Main Page** - Halaman peta utama
    - Route: `/maps`
    - Check: Map load, basic interface

- [ ] **Maps View** - View mode peta interaktif
    - Route: `/maps/view`
    - Check: Tampilkan markers, pan/zoom functionality

- [ ] **Heritage Sites Map** - Filter peta berdasarkan situs peninggalan
    - Route: `/maps/peninggalan`
    - Check: Filter by category, show details on click, distance info

- [ ] **Situs Peninggalan Detail** - Detail informasi situs warisan budaya
    - Route: `/situs/{situs_id}`
    - Check: Tampilkan deskripsi, galeri foto, GPS location, AR option

---

## 📊 3. STATISTIK (Statistics)

Pelacakan progress dan pencapaian pembelajaran

- [ ] **Statistik** - Halaman statistics/progress
    - Route: `/statistik`
    - Check: Tampilkan learning progress, badges/achievements, completion rates

- [ ] **Rapor (Report Card)** - Laporan akademik
    - Route: `/statistik/rapor`
    - Check: Tampilkan nilai pre-test, post-test, grades per materi

---

## 📝 4. LAPORAN PENINGGALAN (Heritage Reports)

User-generated content dan community reporting

- [ ] **Laporan Peninggalan (List)** - User-generated reports
    - Route: `/laporan-peninggalan`
    - Check: List reports, filtering, pagination

- [ ] **Create Heritage Report** - Buat laporan baru
    - Route: `/laporan-peninggalan/create`
    - Aksi: POST `/laporan-peninggalan`
    - Check: Form validation, file upload, successful submission

- [ ] **View Heritage Report Detail** - Detail laporan
    - Route: `/laporan-peninggalan/{id}`
    - Check: Full report display, images, comments, likes

- [ ] **Like Heritage Report** - Suka/unlike laporan
    - Route: POST `/laporan-peninggalan/{id}/like`
    - Check: Like count updated, toggle works

- [ ] **Comment on Report** - Komentar pada laporan
    - Route: POST `/laporan-peninggalan/{id}/comment`
    - Check: Comment submitted, displayed realtime/on refresh

---

## 🎥 5. VIDEO PENINGGALAN (Heritage Video)

Konten video pembelajaran

- [ ] **Video Peninggalan List** - Daftar video konten
    - Route: `/video-peninggalan`
    - Check: List all videos, thumbnails, metadata

- [ ] **Video Peninggalan Detail** - Tonton video detail
    - Route: `/video-peninggalan/{id}`
    - Check: Video player works, fullscreen, quality selection

---

## 💬 6. KRITIK & SARAN (Feedback)

Form feedback dan saran pengguna

- [ ] **Kritik & Saran (Feedback)** - Form feedback & suggestions
    - Route: GET `/kritik-saran`
    - Aksi: POST `/kritik-saran`
    - Check: Form submission, success message, validation

---

## 🎨 7. AR MARKER (Pemindaian Marker)

Fitur pemindaian marker untuk augmented reality

- [ ] **AR Marker Page** - Halaman utama AR marker
    - Route: `/marker`
    - Check: Loading, menu option untuk katalog dan kamera

- [ ] **AR Marker Katalog** - Katalog marker yang tersedia
    - Route: `/ar-marker/katalog`
    - Check: List markers, thumbnails, descriptions, download option

- [ ] **AR Marker Camera** - Kamera untuk scan marker
    - Route: `/ar-marker/camera`
    - Check: Camera permission, marker detection, 3D object rendering, touch gestures

## PENGATURAN & LAINNYA

Halaman pengaturan dan fitur tambahan lainnya

- [ ] **Pengaturan (Settings)** - Halaman pengaturan user
    - Route: `/pengaturan`
    - Check: Update profile, change password, preferences

- [ ] **Informasi Pengembang** - Developer info/credits
    - Route: `/pengembang`
    - Check: Tampilkan developer team, version info

- [ ] **Panduan** - Halaman panduan penggunaan
    - Route: `/panduan`
    - Check: Dokumentasi fitur, tutorial

- [ ] **Confirm Password** - Confirm password untuk action sensitif
    - Route: `/confirm-password` (GET)
    - Check: Password prompt appears, validation works

- [ ] **Change Password** - Ubah password (authenticated)
    - Route: `/password` (PATCH)
    - Check: Old password validation, new password requirements, success

---

## 🔧 Tips Checklist Pengujian

### Pengujian Lintas-Browser

- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari
- [ ] Edge

### Pengujian Perangkat

- [ ] Desktop (1920x1080)
- [ ] Tablet (768px width)
- [ ] Mobile (375px width)

### Pengecekan Umum untuk Setiap Fitur

- [ ] Page loads without errors
- [ ] UI responsive & properly styled
- [ ] Form validation works
- [ ] Submit actions complete successfully
- [ ] Error messages are clear
- [ ] Redirects work correctly
- [ ] Session maintains properly
- [ ] Loading indicators/spinners show if needed

---

## 🎯 Pelacakan Progres

**Total Fitur Terautentikasi:** ~33+  
**Total Fitur Tamu:** 5  
**Progress:** [ ] / [ ]
