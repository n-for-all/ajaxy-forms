<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Preview
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Preview\DeployedDevices\Fleet;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $sid
 * @property string|null $url
 * @property string|null $friendlyName
 * @property string|null $fleetSid
 * @property string|null $accountSid
 * @property string|null $deviceSid
 * @property string|null $secret
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 */
class KeyInstance extends InstanceResource
{
    /**
     * Initialize the KeyInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $fleetSid 
     * @param string $sid Provides a 34 character string that uniquely identifies the requested Key credential resource.
     */
    public function __construct(Version $version, array $payload, string $fleetSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => Values::array_get($payload, 'sid'), 'url' => Values::array_get($payload, 'url'), 'friendlyName' => Values::array_get($payload, 'friendly_name'), 'fleetSid' => Values::array_get($payload, 'fleet_sid'), 'accountSid' => Values::array_get($payload, 'account_sid'), 'deviceSid' => Values::array_get($payload, 'device_sid'), 'secret' => Values::array_get($payload, 'secret'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated'))];
        $this->solution = ['fleetSid' => $fleetSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return KeyContext Context for this KeyInstance
     */
    protected function proxy() : KeyContext
    {
        if (!$this->context) {
            $this->context = new KeyContext($this->version, $this->solution['fleetSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the KeyInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the KeyInstance
     *
     * @return KeyInstance Fetched KeyInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : KeyInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the KeyInstance
     *
     * @param array|Options $options Optional Arguments
     * @return KeyInstance Updated KeyInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : KeyInstance
    {
        return $this->proxy()->update($options);
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
        return '[Twilio.Preview.DeployedDevices.KeyInstance ' . \implode(' ', $context) . ']';
    }
}
