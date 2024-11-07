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
namespace Isolated\Twilio\Rest\Numbers\V2\RegulatoryCompliance\Bundle;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $sid
 * @property string|null $accountSid
 * @property string|null $regulationSid
 * @property string|null $bundleSid
 * @property string $status
 * @property array[]|null $results
 * @property \DateTime|null $dateCreated
 * @property string|null $url
 */
class EvaluationInstance extends InstanceResource
{
    /**
     * Initialize the EvaluationInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $bundleSid The unique string that identifies the Bundle resource.
     * @param string $sid The unique string that identifies the Evaluation resource.
     */
    public function __construct(Version $version, array $payload, string $bundleSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => Values::array_get($payload, 'sid'), 'accountSid' => Values::array_get($payload, 'account_sid'), 'regulationSid' => Values::array_get($payload, 'regulation_sid'), 'bundleSid' => Values::array_get($payload, 'bundle_sid'), 'status' => Values::array_get($payload, 'status'), 'results' => Values::array_get($payload, 'results'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['bundleSid' => $bundleSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return EvaluationContext Context for this EvaluationInstance
     */
    protected function proxy() : EvaluationContext
    {
        if (!$this->context) {
            $this->context = new EvaluationContext($this->version, $this->solution['bundleSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Fetch the EvaluationInstance
     *
     * @return EvaluationInstance Fetched EvaluationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : EvaluationInstance
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
        return '[Twilio.Numbers.V2.EvaluationInstance ' . \implode(' ', $context) . ']';
    }
}
