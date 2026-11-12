# 🧪 Panduan Test Selenium — Lokerin (SI2402-KELF)

Script ini menguji 2 tiket milik **FA** secara end-to-end memakai browser sungguhan (Chrome):

| Tiket | Fitur | Jumlah test |
|---|---|---|
| **TF-5** | Register akun sesuai peran (Job Seeker / Employer) | 5 test |
| **TF-7** | Logout — klik tombol UI di dashboard, sesi berakhir aman | 1 test |
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
test_01_logout_lewat_tombol_ui ... ok

----------------------------------------------------------------------
Ran 6 tests in ~30s

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
| 4 | `test_04_daftar_sebagai_job_seeker` | Daftar sebagai **Job Seeker** → auto-login → **dashboard tampil sempurna** + pesan sukses *"…berhasil dibuat"* |
| 5 | `test_05_daftar_sebagai_employer` | Daftar sebagai **Employer** → auto-login → dashboard employer tampil (empty state profil perusahaan) + pesan sukses |

### TF-7 — Logout (`TestTF7Logout`)

| # | Test | Yang diverifikasi |
|---|---|---|
| 1 | `test_01_logout_lewat_tombol_ui` | Login (akun demo) → dashboard tampil → **klik tombol "Logout" di sidebar** → modal *"Keluar dari Lokerin?"* muncul → klik **"Ya, logout"** → kembali ke beranda → mengakses halaman login-required **dialihkan ke `/login`** (bukti sesi benar-benar mati) |

---

## 4. File di Folder Ini

```
tests/selenium/
├── README.md           ← file ini
└── test_lokerin.py     ← script utama (Python + Selenium, framework unittest)
```

---

## 5. Konfigurasi

| Variabel env | Default | Fungsi |
|---|---|---|
| `HEADLESS` | *(off)* | Set `1` = jalankan Chrome tanpa jendela |
| `LOKERIN_PORT` | `8090` | Port server tes — script otomatis menjalankan `php artisan serve` di port ini dan mematikannya di akhir |

Server tes **terpisah** dari server manual kamu (`http://localhost:8000`), jadi aman dijalankan berbarengan.

---

## 6. Catatan & Troubleshooting

- **Menu sidebar otomatis menyesuaikan** — menu yang route/controller-nya belum ada
  (fitur milik tiket lain: Skill, Pengalaman, CV, Lamaran, Pelamar, menu admin)
  disembunyikan otomatis. Menu muncul sendiri begitu teman merge kode + route mereka.
  Karena itu test memakai menu yang tersedia (Dashboard, Cari Lowongan, Profil, Pendidikan).
- **`Login has been throttled` / 429** — login dibatasi 5x/menit (bawaan Laravel).
  Tunggu sebentar, lalu jalankan ulangi.
- **Test membuat akun baru** setiap run (`selenium.seeker.*@lokerin.test`) —
  aman untuk development; hapus kapan saja lewat admin/SQL kalau mau rapi.
- **ChromeDriver gagal diunduh** — pastikan ada internet; kalau perlu, hapus cache
  Selenium Manager di `%LOCALAPPDATA%\SeleniumManager`.
- **`Connection refused` saat start** — port 8090 kemungkinan dipakai program
  lain; jalankan dengan port lain: `$env:LOKERIN_PORT='8091'` lalu ulangi.
- **Test PHPUnit terkait** — jalankan `php artisan test` (terpisah dari Selenium).
  `SeekerApplicationTest` error *"Class App\Models\Cv not found"* itu **normal**:
  fitur CV/lamaran memang belum diimplementasi (tiket teman).
