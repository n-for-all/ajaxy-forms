<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Frontline
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\FrontlineApi\V1;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
use Isolated\Twilio\Serialize;
class UserContext extends InstanceContext
{
    /**
     * Initialize the UserContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid The SID of the User resource to fetch. This value can be either the `sid` or the `identity` of the User resource to fetch.
     */
    public function __construct(Version $version, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['sid' => $sid];
        $this->uri = '/Users/' . \rawurlencode($sid) . '';
    }
    /**
     * Fetch the UserInstance
     *
     * @return UserInstance Fetched UserInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : UserInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new UserInstance($this->version, $payload, $this->solution['sid']);
    }
    /**
     * Update the UserInstance
     *
     * @param array|Options $options Optional Arguments
     * @return UserInstance Updated UserInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : UserInstance
    {
        $options = new Values($options);
        $data = Values::of(['FriendlyName' => $options['friendlyName'], 'Avatar' => $options['avatar'], 'State' => $options['state'], 'IsAvailable' => Serialize::booleanToString($options['isAvailable'])]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new UserInstance($this->version, $payload, $this->solution['sid']);
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
        return '[Twilio.FrontlineApi.V1.UserContext ' . \implode(' ', $context) . ']';
    }
}
