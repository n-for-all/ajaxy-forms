<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Flex
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\FlexApi\V1;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $sid
 * @property string|null $accountSid
 * @property string|null $configurationSid
 * @property \DateTime|null $dateCreated
 * @property string|null $url
 */
class PluginReleaseInstance extends InstanceResource
{
    /**
     * Initialize the PluginReleaseInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $sid The SID of the Flex Plugin Release resource to fetch.
     */
    public function __construct(Version $version, array $payload, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => Values::array_get($payload, 'sid'), 'accountSid' => Values::array_get($payload, 'account_sid'), 'configurationSid' => Values::array_get($payload, 'configuration_sid'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return PluginReleaseContext Context for this PluginReleaseInstance
     */
    protected function proxy() : PluginReleaseContext
    {
        if (!$this->context) {
            $this->context = new PluginReleaseContext($this->version, $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Fetch the PluginReleaseInstance
     *
     * @param array|Options $options Optional Arguments
     * @return PluginReleaseInstance Fetched PluginReleaseInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []) : PluginReleaseInstance
    {
        return $this->proxy()->fetch($options);
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
        return '[Twilio.FlexApi.V1.PluginReleaseInstance ' . \implode(' ', $context) . ']';
    }
}
