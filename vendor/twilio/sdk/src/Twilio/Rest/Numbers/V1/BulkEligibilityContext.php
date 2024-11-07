<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Numbers
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Numbers\V1;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
class BulkEligibilityContext extends InstanceContext
{
    /**
     * Initialize the BulkEligibilityContext
     *
     * @param Version $version Version that contains the resource
     * @param string $requestId The SID of the bulk eligibility check that you want to know about.
     */
    public function __construct(Version $version, $requestId)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['requestId' => $requestId];
        $this->uri = '/HostedNumber/Eligibility/Bulk/' . \rawurlencode($requestId) . '';
    }
    /**
     * Fetch the BulkEligibilityInstance
     *
     * @return BulkEligibilityInstance Fetched BulkEligibilityInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : BulkEligibilityInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new BulkEligibilityInstance($this->version, $payload, $this->solution['requestId']);
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
        return '[Twilio.Numbers.V1.BulkEligibilityContext ' . \implode(' ', $context) . ']';
    }
}
