<?php

/**
 * Dekripsi cookie XSRF-TOKEN (terenkripsi Laravel) menjadi token CSRF mentah.
 *
 * Dipakai oleh test_lokerin.py untuk request POST /logout dari browser,
 * karena token dirotasi saat login dan tidak ada halaman auth yang bisa
 * dirender (dashboard masih menunggu route milik tiket lain).
 *
 * Nilai cookie dikirim lewat environment variable: XSRF_VAL
 * Output: "TOKEN:<token>" bila berhasil, atau "FAIL:<pesan>" bila gagal.
 */

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$value = getenv('XSRF_VAL') ?: '';
$last = 'nilai XSRF_VAL kosong';

foreach ([$value, urldecode($value)] as $candidate) {
    if ($candidate === '') {
        continue;
    }

    foreach ([true, false] as $serialize) {
        try {
            $plain = app('encrypter')->decrypt($candidate, $serialize);

            if (is_string($plain)) {
                echo 'TOKEN:' . Illuminate\Cookie\CookieValuePrefix::remove($plain);
                exit(0);
            }

            $last = 'hasil dekripsi bukan string';
        } catch (Throwable $e) {
            $last = $e->getMessage();
        }
    }
}

echo 'FAIL:' . $last;
