@if (config('services.recaptcha.enabled', true))
    <div>
        <div class="g-recaptcha overflow-hidden rounded-lg" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        @error('g-recaptcha-response')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    @pushOnce('scripts')
        <script src="https://www.google.com/recaptcha/api.js?hl=id" async defer></script>
    @endPushOnce
@endif
