<?php

namespace Ajaxy\Forms\Inc\Types\Listener;

class ReCaptchaValidationListener extends CaptchaValidationListener
{
    /**
     * @inheritDoc
     */
    protected function get_endpoint()
    {
        return 'https://www.google.com/recaptcha/api/siteverify';
    }

    /**
     * @inheritDoc
     */
    protected function get_response_field()
    {
        return 'g-recaptcha-response';
    }

    /**
     * Verify the submitted token against Google
     *
     * @deprecated Use validate() instead
     *
     * @return boolean
     */
    public function validateReCaptcha()
    {
        return $this->validate();
    }
}
