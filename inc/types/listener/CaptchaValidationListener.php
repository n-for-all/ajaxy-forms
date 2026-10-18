<?php

namespace Ajaxy\Forms\Inc\Types\Listener;

use Isolated\Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Isolated\Symfony\Component\Form\FormError;
use Isolated\Symfony\Component\Form\FormEvent;
use Isolated\Symfony\Component\Form\FormEvents;

/**
 * Shared server side verification for the captcha widgets.
 *
 * Every provider posts a single use token under its own field name and
 * verifies it against its own endpoint, the rest of the flow is identical.
 */
abstract class CaptchaValidationListener implements EventSubscriberInterface
{
    protected $options;

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
        if (!$this->validate()) {
            $event->getForm()->addError(new FormError($this->options['invalid_message']));
        }
    }

    /**
     * The provider verification endpoint
     *
     * @return string
     */
    abstract protected function get_endpoint();

    /**
     * The name of the field holding the token in the submitted data
     *
     * @return string
     */
    abstract protected function get_response_field();

    /**
     * Verify the submitted token against the provider
     *
     * @return boolean
     */
    public function validate()
    {
        $field = $this->get_response_field();
        if (empty($_POST[$field]) || !\is_string($_POST[$field]) || empty($this->options['secret'])) {
            return false;
        }

        // The superglobal is already unslashed by Plugin::handle_request(), and
        // the token is only ever handed back to the provider, never rendered,
        // so it is passed through untouched rather than escaped out of shape.
        $token = \trim($_POST[$field]);
        if ('' === $token) {
            return false;
        }

        $response = \wp_remote_post($this->get_endpoint(), [
            'timeout' => 10,
            'body' => [
                'secret' => $this->options['secret'],
                'response' => $token,
                'remoteip' => $this->get_remote_ip(),
            ]
        ]);

        if (\is_wp_error($response)) {
            \error_log(sprintf('Ajaxy Forms: captcha verification failed, %s', $response->get_error_message()));
            return false;
        }

        $body = \wp_remote_retrieve_body($response);
        if (empty($body)) {
            return false;
        }

        $result = \json_decode($body, true);

        return isset($result['success']) && true === $result['success'];
    }

    /**
     * The address of the visitor submitting the form
     *
     * @return string
     */
    protected function get_remote_ip()
    {
        if (empty($_SERVER['REMOTE_ADDR'])) {
            return '';
        }

        $ip = \filter_var(\wp_unslash($_SERVER['REMOTE_ADDR']), FILTER_VALIDATE_IP);

        return $ip ? $ip : '';
    }
}
