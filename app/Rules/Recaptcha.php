<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Lewati verifikasi jika fitur reCAPTCHA dinonaktifkan
        if (! config('services.recaptcha.enabled', true)) {
            return;
        }

        if (blank($value)) {
            $fail('Silakan centang verifikasi reCAPTCHA "Saya bukan robot".');
            return;
        }

        $secretKey = config('services.recaptcha.secret_key');

        if (blank($secretKey)) {
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful() || ! $response->json('success')) {
                $fail('Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
            }
        } catch (\Throwable $e) {
            report($e);
            $fail('Gagal memverifikasi reCAPTCHA karena kendala koneksi. Silakan coba lagi.');
        }
    }
}
