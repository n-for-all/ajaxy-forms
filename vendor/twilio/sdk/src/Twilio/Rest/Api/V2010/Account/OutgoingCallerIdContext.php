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
class OutgoingCallerIdContext extends InstanceContext
{
    /**
     * Initialize the OutgoingCallerIdContext
     *
     * @param Version $version Version that contains the resource
     * @param string $accountSid The SID of the [Account](https://www.twilio.com/docs/iam/api/account) that created the OutgoingCallerId resources to delete.
     * @param string $sid The Twilio-provided string that uniquely identifies the OutgoingCallerId resource to delete.
     */
    public function __construct(Version $version, $accountSid, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['accountSid' => $accountSid, 'sid' => $sid];
        $this->uri = '/Accounts/' . \rawurlencode($accountSid) . '/OutgoingCallerIds/' . \rawurlencode($sid) . '.json';
    }
    /**
     * Delete the OutgoingCallerIdInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->version->delete('DELETE', $this->uri);
    }
    /**
     * Fetch the OutgoingCallerIdInstance
     *
     * @return OutgoingCallerIdInstance Fetched OutgoingCallerIdInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : OutgoingCallerIdInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new OutgoingCallerIdInstance($this->version, $payload, $this->solution['accountSid'], $this->solution['sid']);
    }
    /**
     * Update the OutgoingCallerIdInstance
     *
     * @param array|Options $options Optional Arguments
     * @return OutgoingCallerIdInstance Updated OutgoingCallerIdInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : OutgoingCallerIdInstance
    {
        $options = new Values($options);
        $data = Values::of(['FriendlyName' => $options['friendlyName']]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new OutgoingCallerIdInstance($this->version, $payload, $this->solution['accountSid'], $this->solution['sid']);
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
        return '[Twilio.Api.V2010.OutgoingCallerIdContext ' . \implode(' ', $context) . ']';
    }
}
