<?php

namespace Ajaxy\Forms\Inc\Types\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ReCaptchaValidationListener implements EventSubscriberInterface
{
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit'
        ];
    }

    public function onPostSubmit(FormEvent $event)
    {
        $valid = $this->validateReCaptcha();
        if (!$valid) {
            $event->getForm()->addError(new FormError($this->options['invalid_message']));
        }
    }

    public function validateReCaptcha()
    {
        if (isset($_POST['g-recaptcha-response'])) {
            $params = sprintf( '?secret=%s&response=%s', $this->options['secret'], \sanitize_text_field($_POST[ 'g-recaptcha-response' ]) );
            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify'.$params);


            if (is_wp_error($response)) {
                return false;
            }

            $body = wp_remote_retrieve_body($response);
            if (empty($body)) {
                return false;
            }
            $result = json_decode($body);
            if (empty($result)) {
                return false;
            }
            if (! isset($result->success)) {
                return false;
            }
            return $result->success;
        }
    }
}
