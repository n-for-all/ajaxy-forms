<?php

namespace Ajaxy\Forms\Inc\Types\Listener;

class TurnstileValidationListener extends CaptchaValidationListener
{
    /**
     * @inheritDoc
     */
    protected function get_endpoint()
    {
        return 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    }

    /**
     * @inheritDoc
     */
    protected function get_response_field()
    {
        return 'cf-turnstile-response';
    }
}
