"""
Selenium E2E - Lokerin (SI2402-KELF)
=====================================
Menguji 2 tiket milik FA:
  TF-5 : register akun sesuai peran (job seeker / employer)
  TF-7 : logout aman (mengakhiri sesi)

Cara pakai (dari folder project):
    python tests\\selenium\\test_lokerin.py                 # jalankan semua test
    python tests\\selenium\\test_lokerin.py TestTF5Register # jalankan sebagian
    $env:HEADLESS='1'; python tests\\selenium\\test_lokerin.py   # mode tanpa jendela browser

Catatan:
- Script menjalankan `php artisan serve` sendiri di port 8090 (dimatikan otomatis
  di akhir). Untuk membuka web manual pakai: http://localhost:8000
- Butuh Chrome terinstall + internet (Selenium Manager mengunduh chromedriver
  sekali saja).
- Setelah register, user diarahkan ke dashboard sesuai peran. Halaman dashboard
  masih error 500 karena route fitur milik tiket lain (seeker.cv.show,
  employer.applicants.*) belum ada - jadi test TF-5 memverifikasi REDIRECT
  sesuai peran, bukan isi halaman dashboard.
- Karena tombol Logout ada di dashboard, test TF-7 mengirim POST /logout
  dari halaman publik dengan token CSRF segar (cookie XSRF-TOKEN didekripsi
  oleh decrypt_xsrp.php). Begitu dashboard teman-merge, tombol UI bisa diuji.
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
from selenium.webdriver.support.ui import WebDriverWait

PROJECT = Path(__file__).resolve().parents[2]
PHP = r"C:\laragon\bin\php\php-8.4.27-Win32-vs17-x64\php.exe"
DECRYPT_HELPER = Path(__file__).with_name("decrypt_xsrp.php")
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


def decrypt_xsrf(driver):
    """Ambil token CSRF segar dari cookie XSRF-TOKEN (didekripsi via PHP)."""
    cookie = driver.get_cookie("XSRF-TOKEN")
    assert cookie, "Cookie XSRF-TOKEN tidak ditemukan"

    env = os.environ.copy()
    env["XSRF_VAL"] = cookie["value"]
    out = subprocess.check_output(
        [PHP, str(DECRYPT_HELPER)],
        env=env,
        cwd=str(PROJECT),
        text=True,
        timeout=60,
    ).strip()

    for line in out.splitlines():
        if line.startswith("TOKEN:"):
            return line[len("TOKEN:"):]

    raise AssertionError(f"Dekripsi XSRF-TOKEN gagal: {out}")


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

        # redirect ke dashboard sesuai peran (isi halaman: menunggu route tiket lain)
        wait_url(d, "/seeker/dashboard")
        self.assertIn("/seeker/dashboard", d.current_url)
        self.assertNotIn("/register", d.current_url, "gagal daftar, masih di form")

    def test_05_daftar_sebagai_employer(self):
        d = self.driver
        email = email_unik("employer")
        d.get(BASE + "/register")
        isi_form_register(d, "Selenium Employer", email, "password123", "employer")
        submit(d)

        wait_url(d, "/employer/dashboard")
        self.assertIn("/employer/dashboard", d.current_url)
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

    def test_01_login_lalu_logout_mengakhiri_sesi(self):
        d = self.driver

        # 1. login sebagai job seeker (akun demo)
        d.get(BASE + "/login")
        self.assertIn("Selamat Datang Kembali", d.page_source)
        d.find_element(By.NAME, "email").send_keys("seeker@lokerin.test")
        d.find_element(By.NAME, "password").send_keys("password")
        submit(d)
        wait_url(d, "/seeker/dashboard")  # auto-login -> dashboard peran

        # 2. pindah ke halaman publik (dashboard masih menunggu route tiket lain)
        d.get(BASE + "/")
        self.assertIn("LOKERIN", d.page_source.upper())

        # 3. POST /logout dengan token CSRF segar (cookie XSRF-TOKEN didekripsi)
        token = decrypt_xsrf(d)
        d.execute_script(
            """
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = arguments[0];
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
            """,
            token,
        )
        time.sleep(2)  # tunggu POST + redirect ke beranda selesai

        # 4. bukti sesi mati: halaman yang butuh login diarahkan ke login
        d.get(BASE + "/seeker/dashboard")
        wait_url(d, "/login")
        self.assertIn("/login", d.current_url, "sesi masih hidup setelah logout!")

        # 5. halaman publik tetap bisa dibuka sebagai guest
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
