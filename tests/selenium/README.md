# 🧪 Panduan Test Selenium — Lokerin (SI2402-KELF)

Script ini menguji alur autentikasi secara end-to-end memakai browser sungguhan (Chrome):

| Alur | Fitur | Jumlah test |
|---|---|---|
| **LOGIN** | Form login, login sukses per peran, password salah | 4 test |
| **TF-5 (Register)** | Daftar akun sesuai peran (Job Seeker / Employer) + validasi | 5 test |
| **TF-7 (Logout)** | Klik tombol Logout di dashboard, sesi berakhir aman | 1 test |
| | **Total** | **10 test** |

📄 Dokumen test case (Scenario ID / Case ID / Expected / Actual / Status) ada di
**[TEST-CASES.md](TEST-CASES.md)** dan versi Excel di **[test-cases.csv](test-cases.csv)**.

---

## 1. Prasyarat

- ✅ **Python 3.13** (sudah ada di Laragon: `C:\laragon\bin\python`)
- ✅ **Selenium** — cek dengan: `python -c "import selenium; print(selenium.__version__)"`
  Kalau belum: `pip install selenium`
- ✅ **Google Chrome** terinstall
- ✅ **PHP 8.4** + `vendor/` sudah di-`composer install`
- ✅ **Internet** — saat jalankan pertama kali, Selenium Manager otomatis mengunduh *chromedriver* yang cocok dengan Chrome kamu (sekali saja)

---

## 2. Cara Menjalankan

Dari **folder project** (`SI2402-KELF`), buka terminal lalu:

```powershell
# jalankan semua test (10 test)
python tests\selenium\test_lokerin.py

# jalankan satu kelas saja
python tests\selenium\test_lokerin.py TestLogin
python tests\selenium\test_lokerin.py TestTF5Register
python tests\selenium\test_lokerin.py TestTF7Logout

# mode headless (tanpa jendela browser terbuka)
$env:HEADLESS='1'
python tests\selenium\test_lokerin.py
```

**Hasil yang diharapkan:**

```
test_01_form_login_tampil ... ok
test_02_login_sukses_sebagai_job_seeker ... ok
test_03_login_gagal_password_salah ... ok
test_04_login_sukses_sebagai_employer ... ok
test_01_form_daftar_tampil ... ok
test_02_validasi_form_kosong ... ok
test_03_validasi_email_duplikat_dan_password_tidak_cocok ... ok
test_04_daftar_sebagai_job_seeker ... ok
test_05_daftar_sebagai_employer ... ok
test_01_logout_lewat_tombol_ui ... ok

----------------------------------------------------------------------
Ran 10 tests in ~90s

OK
```

- **`OK`** = semua lulus ✅
- **`FAILED`** = ada yang gagal — lihat pesan error di bawahnya (menunjuk baris di `test_lokerin.py`)

> 💡 Saat test berjalan, jendela Chrome akan terbuka sendiri. Itu normal — script memang memakai browser sungguhan supaya tes merepresentasikan pengguna asli.

---

## 3. Alur Test

### LOGIN (`TestLogin`)

| # | Test | Yang diverifikasi |
|---|---|---|
| 1 | `test_01_form_login_tampil` | Halaman `/login`: judul "Selamat Datang Kembali", field email & password (wajib), checkbox "Ingat saya", link "Daftar gratis" |
| 2 | `test_02_login_sukses_sebagai_job_seeker` | Login `seeker@lokerin.test` → redirect `/seeker/dashboard`, dashboard tampil, label peran "Job Seeker" |
| 3 | `test_03_login_gagal_password_salah` | Password salah → tetap di `/login` + pesan "Email atau password salah." |
| 4 | `test_04_login_sukses_sebagai_employer` | Login `employer@lokerin.test` → redirect `/employer/dashboard` + empty state profil perusahaan |

### TF-5 — Register (`TestTF5Register`)

| # | Test | Yang diverifikasi |
|---|---|---|
| 1 | `test_01_form_daftar_tampil` | Halaman `/register`: field nama, email, password, konfirmasi, **2 radio peran** (admin **tidak** bisa dipilih), link login |
| 2 | `test_02_validasi_form_kosong` | Submit kosong → *"Nama wajib diisi."*, *"Email wajib diisi."*, *"Password wajib diisi."* dan tetap di halaman register |
| 3 | `test_03_validasi_email_duplikat_dan_password_tidak_cocok` | Email sudah terdaftar + password beda → *"Email sudah terdaftar"*, *"Konfirmasi password tidak cocok."* |
| 4 | `test_04_daftar_sebagai_job_seeker` | Daftar Job Seeker → auto-login → dashboard tampil + pesan sukses *"…berhasil dibuat"* |
| 5 | `test_05_daftar_sebagai_employer` | Daftar Employer → dashboard employer tampil (empty state) + pesan sukses |

### TF-7 — Logout (`TestTF7Logout`)

| # | Test | Yang diverifikasi |
|---|---|---|
| 1 | `test_01_logout_lewat_tombol_ui` | Login → dashboard → **klik tombol "Logout"** → modal *"Keluar dari Lokerin?"* → klik **"Ya, logout"** → kembali ke beranda → halaman login-required dialihkan ke `/login` (sesi mati) |

---

## 4. File di Folder Ini

```
tests/selenium/
├── README.md           ← file ini
├── TEST-CASES.md       ← dokumen test case (Scenario ID, Case ID, Expected/Actual, Status)
├── test-cases.csv      ← versi CSV/Excel dari dokumen test case
└── test_lokerin.py     ← script utama (Python + Selenium, framework unittest)
```

---

## 5. Konfigurasi

| Variabel env | Default | Fungsi |
|---|---|---|
| `HEADLESS` | *(off)* | Set `1` = jalankan Chrome tanpa jendela |
| `LOKERIN_PORT` | `8090` | Port server tes — script otomatis menjalankan `php artisan serve` di port ini dan mematikannya di akhir |

Server tes **terpisah** dari server manual kamu (`http://localhost:8000`), jadi aman dijalankan berbarengan.

⚠️ Server tes dijalankan dengan `RECAPTCHA_ENABLED=false` supaya hasil deterministik
(widget reCAPTCHA Google tidak diuji otomatis). Di `http://localhost:8000`, reCAPTCHA
tetap aktif normal seperti biasa.

---

## 6. Catatan & Troubleshooting

- **Menu sidebar otomatis menyesuaikan** — menu yang route/controller-nya belum ada
  (fitur milik tiket lain: CV, Pelamar, Pengguna admin) disembunyikan otomatis dan
  muncul sendiri begitu teman merge kode + route mereka.
- **`Login has been throttled` / 429** — login dibatasi 5x/menit (bawaan Laravel).
  Satu run penuh memakai 4 POST login, jadi kalau baru saja menjalankan test,
  tunggu ±1 menit sebelum run berikutnya.
- **Test membuat akun baru** setiap run (`selenium.seeker.*@lokerin.test`) —
  aman untuk development; bersihkan dengan:
  ```powershell
  & 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe' -u root kerja -e "DELETE FROM users WHERE email LIKE 'selenium.%@lokerin.test';"
  ```
- **ChromeDriver gagal diunduh** — pastikan ada internet; kalau perlu, hapus cache
  Selenium Manager di `%LOCALAPPDATA%\SeleniumManager`.
- **`Connection refused` saat start** — port 8090 kemungkinan dipakai program
  lain; jalankan dengan port lain: `$env:LOKERIN_PORT='8091'` lalu ulangi.
- **Test PHPUnit terkait** — jalankan `php artisan test` (terpisah dari Selenium).
  `SeekerApplicationTest` error *"Class App\Models\Cv not found"* itu **normal**:
  fitur CV/lamaran memang belum diimplementasi (tiket teman).
