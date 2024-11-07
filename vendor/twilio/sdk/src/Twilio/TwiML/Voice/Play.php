<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace Isolated\Twilio\TwiML\Voice;

use Isolated\Twilio\TwiML\TwiML;
class Play extends TwiML
{
    /**
     * Play constructor.
     *
     * @param string $url Media URL
     * @param array $attributes Optional attributes
     */
    public function __construct($url = null, $attributes = [])
    {
        parent::__construct('Play', $url, $attributes);
    }
    /**
     * Add Loop attribute.
     *
     * @param int $loop Times to loop media
     */
    public function setLoop($loop) : self
    {
        return $this->setAttribute('loop', $loop);
    }
    /**
     * Add Digits attribute.
     *
     * @param string $digits Play DTMF tones for digits
     */
    public function setDigits($digits) : self
    {
        return $this->setAttribute('digits', $digits);
    }
}
