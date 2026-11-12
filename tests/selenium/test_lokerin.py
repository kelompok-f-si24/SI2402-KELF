"""
Selenium E2E - Lokerin (SI2402-KELF)
=====================================
Menguji 2 tiket milik FA:
  TF-5 : register akun sesuai peran (job seeker / employer)
  TF-7 : logout aman lewat tombol UI di dashboard (akhiri sesi)

Cara pakai (dari folder project):
    python tests\\selenium\\test_lokerin.py                 # jalankan semua test
    python tests\\selenium\\test_lokerin.py TestTF5Register # jalankan sebagian
    $env:HEADLESS='1'; python tests\\selenium\\test_lokerin.py   # mode tanpa jendela browser

Catatan:
- Script menjalankan `php artisan serve` sendiri di port 8090 (dimatikan otomatis
  di akhir). Untuk membuka web manual pakai: http://localhost:8000
- Butuh Chrome terinstall + internet (Selenium Manager mengunduh chromedriver
  sekali saja).
- Menu sidebar otomatis menyembunyikan fitur yang route/controller-nya belum ada
  (milik tiket lain) — test memakai menu yang tersedia.
"""

import os
import subprocess
import sys
import time
import unittest
import urllib.request
import uuid
from pathlib import Path

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import WebDriverWait

PROJECT = Path(__file__).resolve().parents[2]
PHP = r"C:\laragon\bin\php\php-8.4.27-Win32-vs17-x64\php.exe"
PORT = int(os.environ.get("LOKERIN_PORT", "8090"))
BASE = f"http://127.0.0.1:{PORT}"
WAIT = 15

_server_proc = None


# ---------------------------------------------------------------------------
# Server & browser helpers
# ---------------------------------------------------------------------------

def server_up():
    try:
        with urllib.request.urlopen(BASE + "/", timeout=3) as resp:
            return resp.status == 200
    except Exception:
        return False


def ensure_server():
    """Start `php artisan serve` di port tes jika belum ada yang jalan."""
    global _server_proc
    if server_up():
        return

    _server_proc = subprocess.Popen(
        [PHP, "artisan", "serve", "--host=127.0.0.1", f"--port={PORT}"],
        cwd=str(PROJECT),
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL,
    )

    deadline = time.time() + 30
    while time.time() < deadline:
        if server_up():
            return
        time.sleep(0.5)

    raise RuntimeError(
        f"Server {BASE} gagal start. Coba manual: php artisan serve --port={PORT}"
    )


def stop_server():
    if _server_proc is None:
        return
    subprocess.run(
        ["taskkill", "/F", "/T", "/PID", str(_server_proc.pid)],
        stdout=subprocess.DEVNULL,
        stderr=subprocess.DEVNULL,
    )


def make_driver():
    opts = Options()
    opts.add_argument("--window-size=1400,950")
    opts.add_argument("--no-first-run")
    opts.add_argument("--no-default-browser-check")
    if os.environ.get("HEADLESS"):
        opts.add_argument("--headless=new")
    driver = webdriver.Chrome(options=opts)
    driver.set_page_load_timeout(30)
    return driver


def wait_url(driver, fragment, timeout=WAIT):
    """Tunggu sampai URL browser mengandung teks tertentu."""
    WebDriverWait(driver, timeout).until(
        lambda d: fragment in d.current_url,
        f"Timeout: URL tidak pernah berisi '{fragment}' (aktual: {driver.current_url})",
    )


def wait_text(driver, text, timeout=WAIT):
    """Tunggu sampai teks muncul di halaman."""
    WebDriverWait(driver, timeout).until(
        lambda d: text in d.page_source,
        f"Timeout: teks '{text}' tidak muncul di halaman",
    )


# ---------------------------------------------------------------------------
# Alur aplikasi
# ---------------------------------------------------------------------------

def isi_form_register(driver, name, email, password, role):
    """Isi form register + pilih radio peran (radio sr-only, klik via JS)."""
    driver.find_element(By.NAME, "name").send_keys(name)
    driver.find_element(By.NAME, "email").send_keys(email)
    driver.find_element(By.NAME, "password").send_keys(password)
    driver.find_element(By.NAME, "password_confirmation").send_keys(password)
    radio = driver.find_element(
        By.CSS_SELECTOR, f'input[name="role"][value="{role}"]'
    )
    driver.execute_script("arguments[0].click()", radio)


def submit(driver):
    driver.find_element(By.CSS_SELECTOR, 'button[type="submit"]').click()


def email_unik(prefix):
    return f"selenium.{prefix}.{uuid.uuid4().hex[:8]}@lokerin.test"


# ===========================================================================
# TF-5 : Register
# ===========================================================================

class TestTF5Register(unittest.TestCase):
    """TF-5: Sebagai pengguna baru, saya ingin mendaftarkan akun agar dapat
    menggunakan Lokerin sesuai peran saya."""

    def setUp(self):
        self.driver = make_driver()

    def tearDown(self):
        self.driver.quit()

    def test_01_form_daftar_tampil(self):
        d = self.driver
        d.get(BASE + "/register")

        self.assertIn("Buat Akun Baru", d.page_source)
        for field in ("name", "email", "password", "password_confirmation"):
            self.assertTrue(d.find_elements(By.NAME, field), f"field '{field}' tidak ada")

        radios = d.find_elements(By.CSS_SELECTOR, 'input[name="role"]')
        self.assertEqual(len(radios), 2, "harus ada 2 pilihan peran")
        nilai = [r.get_attribute("value") for r in radios]
        self.assertIn("job_seeker", nilai)
        self.assertIn("employer", nilai)
        self.assertNotIn("admin", nilai, "peran admin tidak boleh bisa dipilih")

        self.assertTrue(d.find_elements(By.LINK_TEXT, "Masuk"), "link ke login tidak ada")

    def test_02_validasi_form_kosong(self):
        d = self.driver
        d.get(BASE + "/register")

        # atribut required HTML5 memblokir submit kosong di sisi browser;
        # lepas dulu supaya validasi server (Laravel) yang diuji
        d.execute_script(
            "document.querySelectorAll('input[required]').forEach("
            "i => i.removeAttribute('required'));"
        )
        submit(d)

        wait_text(d, "Nama wajib diisi.")
        self.assertIn("Email wajib diisi.", d.page_source)
        self.assertIn("Password wajib diisi.", d.page_source)
        self.assertIn("/register", d.current_url, "harus tetap di halaman register")

    def test_03_validasi_email_duplikat_dan_password_tidak_cocok(self):
        d = self.driver
        d.get(BASE + "/register")
        d.find_element(By.NAME, "name").send_keys("Tester Duplikat")
        d.find_element(By.NAME, "email").send_keys("seeker@lokerin.test")  # sudah terdaftar
        d.find_element(By.NAME, "password").send_keys("password123")
        d.find_element(By.NAME, "password_confirmation").send_keys("password456")  # beda
        submit(d)

        wait_text(d, "Email sudah terdaftar")
        self.assertIn("Konfirmasi password tidak cocok.", d.page_source)

    def test_04_daftar_sebagai_job_seeker(self):
        d = self.driver
        email = email_unik("seeker")
        d.get(BASE + "/register")
        isi_form_register(d, "Selenium Job Seeker", email, "password123", "job_seeker")
        submit(d)

        # redirect ke dashboard sesuai peran, halaman tampil sempurna,
        # dan muncul pesan sukses registrasi
        wait_url(d, "/seeker/dashboard")
        wait_text(d, "Kelengkapan Profil")
        self.assertIn("Selenium Job Seeker", d.page_source)  # nama di header/flash
        self.assertIn("berhasil dibuat", d.page_source)  # flash sukses
        self.assertNotIn("/register", d.current_url, "gagal daftar, masih di form")

    def test_05_daftar_sebagai_employer(self):
        d = self.driver
        email = email_unik("employer")
        d.get(BASE + "/register")
        isi_form_register(d, "Selenium Employer", email, "password123", "employer")
        submit(d)

        # redirect ke dashboard employer + empty state profil perusahaan
        wait_url(d, "/employer/dashboard")
        wait_text(d, "Profil perusahaan belum dibuat")
        self.assertIn("Selenium Employer", d.page_source)
        self.assertIn("berhasil dibuat", d.page_source)
        self.assertNotIn("/register", d.current_url, "gagal daftar, masih di form")


# ===========================================================================
# TF-7 : Logout
# ===========================================================================

class TestTF7Logout(unittest.TestCase):
    """TF-7: Sebagai pengguna yang login, saya ingin logout agar dapat
    mengakhiri sesi dengan aman."""

    def setUp(self):
        self.driver = make_driver()

    def tearDown(self):
        self.driver.quit()

    def test_01_logout_lewat_tombol_ui(self):
        d = self.driver

        # 1. login sebagai job seeker (akun demo)
        d.get(BASE + "/login")
        self.assertIn("Selamat Datang Kembali", d.page_source)
        d.find_element(By.NAME, "email").send_keys("seeker@lokerin.test")
        d.find_element(By.NAME, "password").send_keys("password")
        submit(d)
        wait_url(d, "/seeker/dashboard")

        # 2. dashboard tampil sempurna → klik tombol Logout di sidebar
        wait_text(d, "Kelengkapan Profil")
        self.assertIn("Job Seeker", d.page_source)  # role tampil di header
        d.find_element(By.CSS_SELECTOR, 'button[data-modal-open="logout-modal"]').click()

        # 3. modal konfirmasi muncul → klik "Ya, logout"
        confirm = WebDriverWait(d, WAIT).until(
            EC.visibility_of_element_located(
                (By.XPATH, "//div[@id='logout-modal']//button[@type='submit']")
            ),
            "Modal konfirmasi logout tidak muncul",
        )
        self.assertIn("Keluar dari Lokerin?", d.page_source)
        confirm.click()

        # 4. berhasil logout → kembali ke beranda
        WebDriverWait(d, WAIT).until(
            lambda x: x.current_url.rstrip("/") == BASE,
            f"Logout gagal, URL akhir: {d.current_url}",
        )
        self.assertIn("LOKERIN", d.page_source.upper())

        # 5. sesi benar-benar mati → halaman yang butuh login diarahkan ke login
        d.get(BASE + "/seeker/dashboard")
        wait_url(d, "/login")
        self.assertIn("/login", d.current_url, "sesi masih hidup setelah logout!")

        # 6. halaman publik tetap bisa dibuka sebagai guest
        d.get(BASE + "/login")
        self.assertIn("Selamat Datang Kembali", d.page_source)


# ===========================================================================

def main():
    ensure_server()
    try:
        program = unittest.main(verbosity=2, exit=False, module=__name__)
        sys.exit(0 if program.result.wasSuccessful() else 1)
    finally:
        stop_server()


if __name__ == "__main__":
    main()
