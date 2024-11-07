<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Voice
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Voice\V1;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $accountSid
 * @property string|null $sid
 * @property string|null $friendlyName
 * @property string|null $voiceUrl
 * @property string|null $voiceMethod
 * @property string|null $voiceFallbackUrl
 * @property string|null $voiceFallbackMethod
 * @property string|null $statusCallbackUrl
 * @property string|null $statusCallbackMethod
 * @property bool|null $cnamLookupEnabled
 * @property string|null $connectionPolicySid
 * @property string|null $fromDomainSid
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property string|null $url
 */
class ByocTrunkInstance extends InstanceResource
{
    /**
     * Initialize the ByocTrunkInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $sid The Twilio-provided string that uniquely identifies the BYOC Trunk resource to delete.
     */
    public function __construct(Version $version, array $payload, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['accountSid' => Values::array_get($payload, 'account_sid'), 'sid' => Values::array_get($payload, 'sid'), 'friendlyName' => Values::array_get($payload, 'friendly_name'), 'voiceUrl' => Values::array_get($payload, 'voice_url'), 'voiceMethod' => Values::array_get($payload, 'voice_method'), 'voiceFallbackUrl' => Values::array_get($payload, 'voice_fallback_url'), 'voiceFallbackMethod' => Values::array_get($payload, 'voice_fallback_method'), 'statusCallbackUrl' => Values::array_get($payload, 'status_callback_url'), 'statusCallbackMethod' => Values::array_get($payload, 'status_callback_method'), 'cnamLookupEnabled' => Values::array_get($payload, 'cnam_lookup_enabled'), 'connectionPolicySid' => Values::array_get($payload, 'connection_policy_sid'), 'fromDomainSid' => Values::array_get($payload, 'from_domain_sid'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return ByocTrunkContext Context for this ByocTrunkInstance
     */
    protected function proxy() : ByocTrunkContext
    {
        if (!$this->context) {
            $this->context = new ByocTrunkContext($this->version, $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the ByocTrunkInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the ByocTrunkInstance
     *
     * @return ByocTrunkInstance Fetched ByocTrunkInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : ByocTrunkInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the ByocTrunkInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ByocTrunkInstance Updated ByocTrunkInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : ByocTrunkInstance
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
        return '[Twilio.Voice.V1.ByocTrunkInstance ' . \implode(' ', $context) . ']';
    }
}
