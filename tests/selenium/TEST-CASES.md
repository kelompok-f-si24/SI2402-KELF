# 📋 Dokumen Test Case — Fitur Autentikasi Lokerin

| Info | Keterangan |
|---|---|
| Proyek | Lokerin — SI2402-KELF |
| Tanggal pengujian | 19 November 2026 |
| Metode | Selenium WebDriver (Chrome), otomatis |
| Lingkungan | Laravel 13.39.0 · PHP 8.4.27 · Chrome · server tes `http://127.0.0.1:8090` |
| Akun demo | `seeker@lokerin.test` / `employer@lokerin.test` (password: `password`) |
| Ringkasan | **10 test case — 10 Pass, 0 Fail** (7 positive, 3 negative) |
| Script | `tests/selenium/test_lokerin.py` |

> Catatan: pada server tes, `RECAPTCHA_ENABLED=false` agar pengujian deterministik (widget reCAPTCHA pihak ketiga tidak diuji otomatis). Di server normal (`localhost:8000`), reCAPTCHA aktif seperti biasa.

---

## SC-LOGIN — Login

| Scenario ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Steps | Steps Description | Test Data | Expected Result | Actual Result | Status (Pass/Fail) |
|---|---|---|---|---|---|---|---|---|---|---|---|
| SC-LOGIN | TC-LOGIN-01 | Menampilkan form login | Positive | Form login tampil dengan field email, password, "Ingat saya", dan link daftar | Pengguna belum login | 1–4 | 1. Buka halaman `/login`<br>2. Periksa field email (`type=email`, wajib)<br>3. Periksa field password (`type=password`, wajib)<br>4. Periksa checkbox "Ingat saya" dan link "Daftar gratis" | — | Judul "Selamat Datang Kembali", semua field tampil, input wajib bertanda `required`, link daftar tersedia | Semua elemen tampil sesuai; field email & password bertanda wajib; checkbox "Ingat saya" dan link "Daftar gratis" ada | **Pass** |
| SC-LOGIN | TC-LOGIN-02 | Login sukses sebagai Job Seeker | Positive | Pengguna job seeker dapat masuk dan diarahkan ke dashboard-nya | Akun `seeker@lokerin.test` terdaftar dan aktif | 1–4 | 1. Buka `/login`<br>2. Isi email<br>3. Isi password<br>4. Klik tombol **Masuk** | Email: `seeker@lokerin.test`<br>Password: `password` | Redirect ke `/seeker/dashboard`, dashboard tampil, label peran "Job Seeker" terlihat | Redirect ke `/seeker/dashboard`; panel "Kelengkapan Profil" tampil; label "Job Seeker" tampil di header | **Pass** |
| SC-LOGIN | TC-LOGIN-03 | Login gagal karena password salah | Negative | Sistem menolak login dengan password salah dan menampilkan pesan error | Akun `seeker@lokerin.test` terdaftar | 1–4 | 1. Buka `/login`<br>2. Isi email<br>3. Isi password salah<br>4. Klik tombol **Masuk** | Email: `seeker@lokerin.test`<br>Password: `password-salah` | Tetap di `/login` dan muncul pesan "Email atau password salah." | Pesan "Email atau password salah." tampil; pengguna tetap di `/login` | **Pass** |
| SC-LOGIN | TC-LOGIN-04 | Login sukses sebagai Employer | Positive | Pengguna employer dapat masuk dan diarahkan ke dashboard-nya | Akun `employer@lokerin.test` terdaftar dan aktif | 1–4 | 1. Buka `/login`<br>2. Isi email<br>3. Isi password<br>4. Klik tombol **Masuk** | Email: `employer@lokerin.test`<br>Password: `password` | Redirect ke `/employer/dashboard`, dashboard tampil (empty state profil perusahaan karena belum dibuat) | Redirect ke `/employer/dashboard`; empty state "Profil perusahaan belum dibuat" tampil; label "Employer" tampil | **Pass** |

---

## SC-REGISTER — Registrasi (TF-5)

| Scenario ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Steps | Steps Description | Test Data | Expected Result | Actual Result | Status (Pass/Fail) |
|---|---|---|---|---|---|---|---|---|---|---|---|
| SC-REGISTER | TC-REG-01 | Menampilkan form registrasi | Positive | Form daftar tampil lengkap dengan pilihan peran | Pengguna belum login | 1–3 | 1. Buka `/register`<br>2. Periksa field nama, email, password, konfirmasi password<br>3. Periksa pilihan peran dan link "Masuk" | — | Judul "Buat Akun Baru", 4 field tampil, **2 pilihan peran** (Job Seeker & Employer; **admin tidak bisa dipilih**), link "Masuk" ada | Semua field tampil; radio peran berjumlah 2 (`job_seeker`, `employer`), admin tidak tersedia; link "Masuk" ada | **Pass** |
| SC-REGISTER | TC-REG-02 | Validasi form kosong | Negative | Sistem menolak registrasi dengan field kosong dan menampilkan pesan validasi | Pengguna belum login di halaman `/register` | 1–3 | 1. Kosongkan semua field<br>2. Klik tombol **Daftar**<br>3. Amati pesan validasi | Semua field kosong | Tetap di `/register`; muncul "Nama wajib diisi.", "Email wajib diisi.", "Password wajib diisi." | Ketiga pesan validasi tampil; pengguna tetap di `/register` | **Pass** |
| SC-REGISTER | TC-REG-03 | Validasi email duplikat & konfirmasi password tidak cocok | Negative | Sistem menolak email yang sudah terdaftar dan password konfirmasi berbeda | Email `seeker@lokerin.test` sudah terdaftar | 1–4 | 1. Buka `/register`<br>2. Isi nama, email terdaftar, password, dan konfirmasi berbeda<br>3. Klik **Daftar**<br>4. Amati pesan validasi | Nama: `Tester Duplikat`<br>Email: `seeker@lokerin.test`<br>Password: `password123`<br>Konfirmasi: `password456` | Muncul "Email sudah terdaftar. Gunakan email lain atau masuk ke akun Anda." dan "Konfirmasi password tidak cocok." | Kedua pesan validasi tampil sesuai | **Pass** |
| SC-REGISTER | TC-REG-04 | Registrasi berhasil sebagai Job Seeker | Positive | Pengguna baru mendaftar sebagai job seeker dan langsung masuk ke dashboard | Pengguna belum login; email belum terdaftar | 1–5 | 1. Buka `/register`<br>2. Isi nama, email baru, password & konfirmasi<br>3. Pilih peran **Job Seeker**<br>4. Klik **Daftar**<br>5. Amati redirect & pesan sukses | Nama: `Selenium Job Seeker`<br>Email: `selenium.seeker.<acak>@lokerin.test`<br>Password: `password123`<br>Peran: Job Seeker | Auto-login, redirect ke `/seeker/dashboard`, dashboard tampil, pesan sukses "…berhasil dibuat." | Redirect ke `/seeker/dashboard`; dashboard tampil; flash "Selamat datang, Selenium Job Seeker! Akun Job Seeker Anda berhasil dibuat." muncul | **Pass** |
| SC-REGISTER | TC-REG-05 | Registrasi berhasil sebagai Employer | Positive | Pengguna baru mendaftar sebagai employer dan langsung masuk ke dashboard | Pengguna belum login; email belum terdaftar | 1–5 | 1. Buka `/register`<br>2. Isi nama, email baru, password & konfirmasi<br>3. Pilih peran **Employer**<br>4. Klik **Daftar**<br>5. Amati redirect & pesan sukses | Nama: `Selenium Employer`<br>Email: `selenium.employer.<acak>@lokerin.test`<br>Password: `password123`<br>Peran: Employer | Auto-login, redirect ke `/employer/dashboard`, empty state profil perusahaan tampil, pesan sukses muncul | Redirect ke `/employer/dashboard`; empty state "Profil perusahaan belum dibuat" tampil; flash sukses muncul | **Pass** |

---

## SC-LOGOUT — Logout (TF-7)

| Scenario ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Steps | Steps Description | Test Data | Expected Result | Actual Result | Status (Pass/Fail) |
|---|---|---|---|---|---|---|---|---|---|---|---|
| SC-LOGOUT | TC-LOGOUT-01 | Logout melalui tombol di dashboard | Positive | Pengguna yang login dapat mengakhiri sesi dengan aman lewat tombol Logout dan konfirmasi | Pengguna sudah login sebagai job seeker dan berada di `/seeker/dashboard` | 1–6 | 1. Login sebagai job seeker<br>2. Pastikan dashboard tampil<br>3. Klik tombol **Logout** di sidebar<br>4. Modal "Keluar dari Lokerin?" muncul<br>5. Klik **"Ya, logout"**<br>6. Akses kembali `/seeker/dashboard` | Email: `seeker@lokerin.test`<br>Password: `password` | Kembali ke beranda; sesi berakhir — halaman yang butuh login diarahkan ke `/login`; halaman publik tetap bisa dibuka | Redirect ke beranda; akses `/seeker/dashboard` diarahkan ke `/login` (sesi mati); halaman login dapat dibuka sebagai guest | **Pass** |
