<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Serverless
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Serverless\V1\Service\TwilioFunction\FunctionVersion;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
class FunctionVersionContentContext extends InstanceContext
{
    /**
     * Initialize the FunctionVersionContentContext
     *
     * @param Version $version Version that contains the resource
     * @param string $serviceSid The SID of the Service to fetch the Function Version content from.
     * @param string $functionSid The SID of the Function that is the parent of the Function Version content to fetch.
     * @param string $sid The SID of the Function Version content to fetch.
     */
    public function __construct(Version $version, $serviceSid, $functionSid, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['serviceSid' => $serviceSid, 'functionSid' => $functionSid, 'sid' => $sid];
        $this->uri = '/Services/' . \rawurlencode($serviceSid) . '/Functions/' . \rawurlencode($functionSid) . '/Versions/' . \rawurlencode($sid) . '/Content';
    }
    /**
     * Fetch the FunctionVersionContentInstance
     *
     * @return FunctionVersionContentInstance Fetched FunctionVersionContentInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : FunctionVersionContentInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new FunctionVersionContentInstance($this->version, $payload, $this->solution['serviceSid'], $this->solution['functionSid'], $this->solution['sid']);
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
        return '[Twilio.Serverless.V1.FunctionVersionContentContext ' . \implode(' ', $context) . ']';
    }
}
