<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */
namespace Isolated\Twilio\TwiML\Voice;

use Isolated\Twilio\TwiML\TwiML;
class Identity extends TwiML
{
    /**
     * Identity constructor.
     *
     * @param string $clientIdentity Identity of the client to dial
     */
    public function __construct($clientIdentity)
    {
        parent::__construct('Identity', $clientIdentity);
    }
}
