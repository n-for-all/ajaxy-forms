<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Microvisor
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Microvisor\V1\App;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
/**
 * @property string|null $appSid
 * @property string|null $hash
 * @property string|null $encodedBytes
 * @property string|null $url
 */
class AppManifestInstance extends InstanceResource
{
    /**
     * Initialize the AppManifestInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $appSid A 34-character string that uniquely identifies this App.
     */
    public function __construct(Version $version, array $payload, string $appSid)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['appSid' => Values::array_get($payload, 'app_sid'), 'hash' => Values::array_get($payload, 'hash'), 'encodedBytes' => Values::array_get($payload, 'encoded_bytes'), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['appSid' => $appSid];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return AppManifestContext Context for this AppManifestInstance
     */
    protected function proxy() : AppManifestContext
    {
        if (!$this->context) {
            $this->context = new AppManifestContext($this->version, $this->solution['appSid']);
        }
        return $this->context;
    }
    /**
     * Fetch the AppManifestInstance
     *
     * @return AppManifestInstance Fetched AppManifestInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : AppManifestInstance
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
        return '[Twilio.Microvisor.V1.AppManifestInstance ' . \implode(' ', $context) . ']';
    }
}
