<?php

namespace Tests\Feature;

use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RecaptchaTest extends TestCase
{
    public function test_login_page_renders_recaptcha_widget_when_enabled(): void
    {
        Config::set('services.recaptcha.enabled', true);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('g-recaptcha')
            ->assertSee('recaptcha/api.js');
    }

    public function test_registration_page_renders_recaptcha_widget_when_enabled(): void
    {
        Config::set('services.recaptcha.enabled', true);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('g-recaptcha')
            ->assertSee('recaptcha/api.js');
    }

    public function test_recaptcha_rule_fails_when_response_is_blank(): void
    {
        Config::set('services.recaptcha.enabled', true);

        // Sama seperti LoginRequest/RegisterRequest: rule dipasangkan dengan
        // 'required' karena rule non-implicit dilewati Laravel untuk nilai kosong.
        $validator = Validator::make(
            ['g-recaptcha-response' => ''],
            ['g-recaptcha-response' => ['required', new Recaptcha]],
            ['g-recaptcha-response.required' => 'Silakan centang verifikasi reCAPTCHA "Saya bukan robot".']
        );

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('Silakan centang verifikasi reCAPTCHA', $validator->errors()->first('g-recaptcha-response'));
    }

    public function test_recaptcha_rule_passes_when_google_returns_success(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret-key');

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true], 200),
        ]);

        $validator = Validator::make(
            ['g-recaptcha-response' => 'valid-token'],
            ['g-recaptcha-response' => [new Recaptcha]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_recaptcha_rule_fails_when_google_returns_failure(): void
    {
        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret-key');

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false], 200),
        ]);

        $validator = Validator::make(
            ['g-recaptcha-response' => 'invalid-token'],
            ['g-recaptcha-response' => [new Recaptcha]]
        );

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString('Verifikasi reCAPTCHA gagal', $validator->errors()->first('g-recaptcha-response'));
    }

    public function test_recaptcha_rule_is_skipped_when_disabled(): void
    {
        Config::set('services.recaptcha.enabled', false);

        $validator = Validator::make(
            ['g-recaptcha-response' => ''],
            ['g-recaptcha-response' => [new Recaptcha]]
        );

        $this->assertTrue($validator->passes());
    }
}
