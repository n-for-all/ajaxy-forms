<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Events
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Events\V1\Sink;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Version;
class SinkTestList extends ListResource
{
    /**
     * Construct the SinkTestList
     *
     * @param Version $version Version that contains the resource
     * @param string $sid A 34 character string that uniquely identifies the Sink to be Tested.
     */
    public function __construct(Version $version, string $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['sid' => $sid];
        $this->uri = '/Sinks/' . \rawurlencode($sid) . '/Test';
    }
    /**
     * Create the SinkTestInstance
     *
     * @return SinkTestInstance Created SinkTestInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create() : SinkTestInstance
    {
        $payload = $this->version->create('POST', $this->uri, [], []);
        return new SinkTestInstance($this->version, $payload, $this->solution['sid']);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Events.V1.SinkTestList]';
    }
}
