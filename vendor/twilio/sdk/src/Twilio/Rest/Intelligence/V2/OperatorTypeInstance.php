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
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $name
 * @property string|null $sid
 * @property string|null $friendlyName
 * @property string|null $description
 * @property string|null $docsLink
 * @property string $outputType
 * @property string[]|null $supportedLanguages
 * @property string $provider
 * @property string $availability
 * @property bool|null $configurable
 * @property array|null $configSchema
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property string|null $url
 */
class OperatorTypeInstance extends InstanceResource
{
    /**
     * Initialize the OperatorTypeInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $sid A 34 character string that uniquely identifies this Operator Type.
     */
    public function __construct(Version $version, array $payload, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['name' => Values::array_get($payload, 'name'), 'sid' => Values::array_get($payload, 'sid'), 'friendlyName' => Values::array_get($payload, 'friendly_name'), 'description' => Values::array_get($payload, 'description'), 'docsLink' => Values::array_get($payload, 'docs_link'), 'outputType' => Values::array_get($payload, 'output_type'), 'supportedLanguages' => Values::array_get($payload, 'supported_languages'), 'provider' => Values::array_get($payload, 'provider'), 'availability' => Values::array_get($payload, 'availability'), 'configurable' => Values::array_get($payload, 'configurable'), 'configSchema' => Values::array_get($payload, 'config_schema'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return OperatorTypeContext Context for this OperatorTypeInstance
     */
    protected function proxy() : OperatorTypeContext
    {
        if (!$this->context) {
            $this->context = new OperatorTypeContext($this->version, $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Fetch the OperatorTypeInstance
     *
     * @return OperatorTypeInstance Fetched OperatorTypeInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : OperatorTypeInstance
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
        return '[Twilio.Intelligence.V2.OperatorTypeInstance ' . \implode(' ', $context) . ']';
    }
}
