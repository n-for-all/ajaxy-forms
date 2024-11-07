<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Intelligence
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Intelligence\V2;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
class PrebuiltOperatorContext extends InstanceContext
{
    /**
     * Initialize the PrebuiltOperatorContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid A 34 character string that uniquely identifies this Pre-built Operator.
     */
    public function __construct(Version $version, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['sid' => $sid];
        $this->uri = '/Operators/PreBuilt/' . \rawurlencode($sid) . '';
    }
    /**
     * Fetch the PrebuiltOperatorInstance
     *
     * @return PrebuiltOperatorInstance Fetched PrebuiltOperatorInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : PrebuiltOperatorInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new PrebuiltOperatorInstance($this->version, $payload, $this->solution['sid']);
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
        return '[Twilio.Intelligence.V2.PrebuiltOperatorContext ' . \implode(' ', $context) . ']';
    }
}
