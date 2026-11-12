# 🧪 Panduan Test Selenium — Lokerin (SI2402-KELF)

Script ini menguji 2 tiket milik **FA** secara end-to-end memakai browser sungguhan (Chrome):

| Tiket | Fitur | Jumlah test |
|---|---|---|
| **TF-5** | Register akun sesuai peran (Job Seeker / Employer) | 5 test |
| **TF-7** | Logout — mengakhiri sesi dengan aman | 1 test |
| | **Total** | **6 test** |

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
# jalankan semua test (6 test)
python tests\selenium\test_lokerin.py

# jalankan satu kelas saja
python tests\selenium\test_lokerin.py TestTF5Register
python tests\selenium\test_lokerin.py TestTF7Logout

# mode headless (tanpa jendela browser terbuka)
$env:HEADLESS='1'
python tests\selenium\test_lokerin.py
```

**Hasil yang diharapkan:**

```
test_01_form_daftar_tampil ... ok
test_02_validasi_form_kosong ... ok
test_03_validasi_email_duplikat_dan_password_tidak_cocok ... ok
test_04_daftar_sebagai_job_seeker ... ok
test_05_daftar_sebagai_employer ... ok
test_01_login_lalu_logout_mengakhiri_sesi ... ok

----------------------------------------------------------------------
Ran 6 tests in ~37s

OK
```

- **`OK`** = semua lulus ✅
- **`FAILED`** = ada yang gagal — lihat pesan error di bawahnya (menunjuk baris di `test_lokerin.py`)

> 💡 Saat test berjalan, jendela Chrome akan terbuka sendiri. Itu normal — script memang memakai browser sungguhan supaya tes merepresentasikan pengguna asli.

---

## 3. Alur Test

### TF-5 — Register (`TestTF5Register`)

| # | Test | Yang diverifikasi |
|---|---|---|
| 1 | `test_01_form_daftar_tampil` | Halaman `/register` tampil: field nama, email, password, konfirmasi, **2 radio peran** (admin **tidak** bisa dipilih), link ke login |
| 2 | `test_02_validasi_form_kosong` | Submit kosong → muncul *"Nama wajib diisi."*, *"Email wajib diisi."*, *"Password wajib diisi."* dan tetap di halaman register |
| 3 | `test_03_validasi_email_duplikat_dan_password_tidak_cocok` | Email sudah terdaftar + password beda → *"Email sudah terdaftar"*, *"Konfirmasi password tidak cocok."* |
| 4 | `test_04_daftar_sebagai_job_seeker` | Daftar sebagai **Job Seeker** → otomatis login → redirect ke `/seeker/dashboard` |
| 5 | `test_05_daftar_sebagai_employer` | Daftar sebagai **Employer** → otomatis login → redirect ke `/employer/dashboard` |

### TF-7 — Logout (`TestTF7Logout`)

| # | Test | Yang diverifikasi |
|---|---|---|
| 1 | `test_01_login_lalu_logout_mengakhiri_sesi` | Login (akun demo) → **POST `/logout`** dengan token CSRF → halaman publik tetap bisa dibuka → mengakses halaman yang butuh login **dialihkan ke `/login`** (bukti sesi benar-benar mati) |

---

## 4. File di Folder Ini

```
tests/selenium/
├── README.md           ← file ini
├── test_lokerin.py     ← script utama (Python + Selenium, framework unittest)
└── decrypt_xsrp.php    ← helper: mendekripsi cookie XSRF-TOKEN jadi token CSRF
```

**Kenapa ada `decrypt_xsrp.php`?**
Laravel **memutar (mengganti) token CSRF setiap kali user login** (fitur keamanan
anti session-fixation). Token lama dari form login jadi tidak berlaku. Script PHP
ini mendekripsi cookie `XSRF-TOKEN` (yang selalu berisi token terbaru) memakai
`APP_KEY` aplikasi, sehingga request `POST /logout` dari browser diterima server.

---

## 5. Konfigurasi

| Variabel env | Default | Fungsi |
|---|---|---|
| `HEADLESS` | *(off)* | Set `1` = jalankan Chrome tanpa jendela |
| `LOKERIN_PORT` | `8090` | Port server tes — script otomatis menjalankan `php artisan serve` di port ini dan mematikannya di akhir |

Server tes **terpisah** dari server manual kamu (`http://localhost:8000`), jadi aman dijalankan berbarengan.

---

## 6. Catatan & Troubleshooting

- **Dashboard error 500 setelah register/login** — itu *bukan* bug test. Route
  `seeker.cv.show`, `seeker.applications.*`, `employer.applicants.*` milik tiket
  teman belum ada. Karena itu test TF-5 memverifikasi **redirect sesuai peran**,
  dan test TF-7 mengirim POST logout dari halaman publik (bukan tombol di
  dashboard). Begitu route teman merge, tombol UI dashboard bisa diuji.
- **`Login has been throttled` / 429** — login dibatasi 5x/menit (bawaan Laravel).
  Tunggu sebentar, lalu jalankan ulangi.
- **Test membuat akun baru** setiap run (`selenium.seeker.*@lokerin.test`) —
  aman untuk development; hapus kapan saja lewat admin/SQL kalau ingin rapi.
- **ChromeDriver gagal diunduh** — pastikan ada internet; kalau perlu, hapus cache
  Selenium Manager di `%LOCALAPPDATA%\SeleniumManager`.
- **`Connection refused` saat start** — port 8090 kemungkinan dipakai program
  lain; jalankan dengan port lain: `$env:LOKERIN_PORT='8091'` lalu ulangi.
