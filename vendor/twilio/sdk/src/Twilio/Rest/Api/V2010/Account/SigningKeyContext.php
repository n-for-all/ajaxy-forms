<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Api
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Api\V2010\Account;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
class SigningKeyContext extends InstanceContext
{
    /**
     * Initialize the SigningKeyContext
     *
     * @param Version $version Version that contains the resource
     * @param string $accountSid 
     * @param string $sid 
     */
    public function __construct(Version $version, $accountSid, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['accountSid' => $accountSid, 'sid' => $sid];
        $this->uri = '/Accounts/' . \rawurlencode($accountSid) . '/SigningKeys/' . \rawurlencode($sid) . '.json';
    }
    /**
     * Delete the SigningKeyInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->version->delete('DELETE', $this->uri);
    }
    /**
     * Fetch the SigningKeyInstance
     *
     * @return SigningKeyInstance Fetched SigningKeyInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : SigningKeyInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new SigningKeyInstance($this->version, $payload, $this->solution['accountSid'], $this->solution['sid']);
    }
    /**
     * Update the SigningKeyInstance
     *
     * @param array|Options $options Optional Arguments
     * @return SigningKeyInstance Updated SigningKeyInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : SigningKeyInstance
    {
        $options = new Values($options);
        $data = Values::of(['FriendlyName' => $options['friendlyName']]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new SigningKeyInstance($this->version, $payload, $this->solution['accountSid'], $this->solution['sid']);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "{$key}={$value}";
        }
        return '[Twilio.Api.V2010.SigningKeyContext ' . \implode(' ', $context) . ']';
    }
}
