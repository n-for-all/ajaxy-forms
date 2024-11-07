<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Supersim
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Supersim\V1\NetworkAccessProfile;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
/**
 * @property string|null $sid
 * @property string|null $networkAccessProfileSid
 * @property string|null $friendlyName
 * @property string|null $isoCountry
 * @property array[]|null $identifiers
 * @property string|null $url
 */
class NetworkAccessProfileNetworkInstance extends InstanceResource
{
    /**
     * Initialize the NetworkAccessProfileNetworkInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $networkAccessProfileSid The unique string that identifies the Network Access Profile resource.
     * @param string $sid The SID of the Network resource to be removed from the Network Access Profile resource.
     */
    public function __construct(Version $version, array $payload, string $networkAccessProfileSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => Values::array_get($payload, 'sid'), 'networkAccessProfileSid' => Values::array_get($payload, 'network_access_profile_sid'), 'friendlyName' => Values::array_get($payload, 'friendly_name'), 'isoCountry' => Values::array_get($payload, 'iso_country'), 'identifiers' => Values::array_get($payload, 'identifiers'), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['networkAccessProfileSid' => $networkAccessProfileSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return NetworkAccessProfileNetworkContext Context for this NetworkAccessProfileNetworkInstance
     */
    protected function proxy() : NetworkAccessProfileNetworkContext
    {
        if (!$this->context) {
            $this->context = new NetworkAccessProfileNetworkContext($this->version, $this->solution['networkAccessProfileSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the NetworkAccessProfileNetworkInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the NetworkAccessProfileNetworkInstance
     *
     * @return NetworkAccessProfileNetworkInstance Fetched NetworkAccessProfileNetworkInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : NetworkAccessProfileNetworkInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->{$method}();
        }
        throw new TwilioException('Unknown property: ' . $name);
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
        return '[Twilio.Supersim.V1.NetworkAccessProfileNetworkInstance ' . \implode(' ', $context) . ']';
    }
}
