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
namespace Isolated\Twilio\Rest\Numbers\V2;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
class BulkHostedNumberOrderContext extends InstanceContext
{
    /**
     * Initialize the BulkHostedNumberOrderContext
     *
     * @param Version $version Version that contains the resource
     * @param string $bulkHostingSid A 34 character string that uniquely identifies this BulkHostedNumberOrder.
     */
    public function __construct(Version $version, $bulkHostingSid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['bulkHostingSid' => $bulkHostingSid];
        $this->uri = '/HostedNumber/Orders/Bulk/' . \rawurlencode($bulkHostingSid) . '';
    }
    /**
     * Fetch the BulkHostedNumberOrderInstance
     *
     * @param array|Options $options Optional Arguments
     * @return BulkHostedNumberOrderInstance Fetched BulkHostedNumberOrderInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []) : BulkHostedNumberOrderInstance
    {
        $options = new Values($options);
        $params = Values::of(['OrderStatus' => $options['orderStatus']]);
        $payload = $this->version->fetch('GET', $this->uri, $params, []);
        return new BulkHostedNumberOrderInstance($this->version, $payload, $this->solution['bulkHostingSid']);
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
        return '[Twilio.Numbers.V2.BulkHostedNumberOrderContext ' . \implode(' ', $context) . ']';
    }
}
