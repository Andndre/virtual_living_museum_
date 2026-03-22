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

- [ ] **Material Details** - Detail konten pembelajaran
    - Route: `/kunjungi-peninggalan/materi/{materi_id}`
    - Check: Deskripsi materi, buttons untuk pre-test, ebook, post-test

- [ ] **Pre-Test** - Soal pre-learning assessment
    - Route: GET `/kunjungi-peninggalan/materi/{materi_id}/pretest`
    - Aksi: POST `/kunjungi-peninggalan/materi/{materi_id}/pretest`
    - Check: Render pertanyaan, submit form, score calculation, progress update

- [ ] **E-Book** - Material pembelajaran berbentuk flipbook
    - Route: `/kunjungi-peninggalan/ebook/{ebook_id}`
    - Aksi: POST `/kunjungi-peninggalan/ebook/{ebook_id}/read` (mark as read)
    - Check: PDF flipper works, page navigation, completion tracking

- [ ] **Post-Test** - Soal post-learning assessment
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

## 🎨 FITUR TAMBAHAN

AR dan pengaturan lainnya

### Fitur AR

- [ ] **AR Marker Catalog** - Halaman katalog AR marker
    - Route: `/marker` or `/ar-marker/katalog`
    - Check: List available markers, descriptions, download options

- [ ] **AR Camera** - Kamera AR untuk scan marker
    - Route: `/ar-marker/camera`
    - Check: Camera permission, marker detection, 3D model rendering

- [ ] **Virtual Living Museum (WebXR)** - Museum 3D dengan WebXR
    - Route: `/situs/{situs_id}/ar/{museum_id}` (token-based access)
    - Check: Scene loading, object interaction, lighting/shadows, hit-test

### Pengaturan & Lainnya

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

**Total Fitur Terautentikasi:** ~35+  
**Total Fitur Tamu:** 5  
**Progress:** [ ] / [ ]
