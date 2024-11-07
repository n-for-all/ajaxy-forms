<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Routes
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Routes\V2;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
class SipDomainContext extends InstanceContext
{
    /**
     * Initialize the SipDomainContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sipDomain 
     */
    public function __construct(Version $version, $sipDomain)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['sipDomain' => $sipDomain];
        $this->uri = '/SipDomains/' . \rawurlencode($sipDomain) . '';
    }
    /**
     * Fetch the SipDomainInstance
     *
     * @return SipDomainInstance Fetched SipDomainInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : SipDomainInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new SipDomainInstance($this->version, $payload, $this->solution['sipDomain']);
    }
    /**
     * Update the SipDomainInstance
     *
     * @param array|Options $options Optional Arguments
     * @return SipDomainInstance Updated SipDomainInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : SipDomainInstance
    {
        $options = new Values($options);
        $data = Values::of(['VoiceRegion' => $options['voiceRegion'], 'FriendlyName' => $options['friendlyName']]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new SipDomainInstance($this->version, $payload, $this->solution['sipDomain']);
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
        return '[Twilio.Routes.V2.SipDomainContext ' . \implode(' ', $context) . ']';
    }
}
