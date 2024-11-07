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
class PortingPortInContext extends InstanceContext
{
    /**
     * Initialize the PortingPortInContext
     *
     * @param Version $version Version that contains the resource
     * @param string $portInRequestSid The SID of the Port In request. This is a unique identifier of the port in request.
     */
    public function __construct(Version $version, $portInRequestSid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['portInRequestSid' => $portInRequestSid];
        $this->uri = '/Porting/PortIn/' . \rawurlencode($portInRequestSid) . '';
    }
    /**
     * Delete the PortingPortInInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->version->delete('DELETE', $this->uri);
    }
    /**
     * Fetch the PortingPortInInstance
     *
     * @return PortingPortInInstance Fetched PortingPortInInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : PortingPortInInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new PortingPortInInstance($this->version, $payload, $this->solution['portInRequestSid']);
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
        return '[Twilio.Numbers.V1.PortingPortInContext ' . \implode(' ', $context) . ']';
    }
}
