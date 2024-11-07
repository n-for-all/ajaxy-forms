<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Api
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Api\V2010\Account;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $accountSid
 * @property string|null $apiVersion
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property string|null $friendlyName
 * @property string|null $messageStatusCallback
 * @property string|null $sid
 * @property string|null $smsFallbackMethod
 * @property string|null $smsFallbackUrl
 * @property string|null $smsMethod
 * @property string|null $smsStatusCallback
 * @property string|null $smsUrl
 * @property string|null $statusCallback
 * @property string|null $statusCallbackMethod
 * @property string|null $uri
 * @property bool|null $voiceCallerIdLookup
 * @property string|null $voiceFallbackMethod
 * @property string|null $voiceFallbackUrl
 * @property string|null $voiceMethod
 * @property string|null $voiceUrl
 * @property bool|null $publicApplicationConnectEnabled
 */
class ApplicationInstance extends InstanceResource
{
    /**
     * Initialize the ApplicationInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid The SID of the [Account](https://www.twilio.com/docs/iam/api/account) that will create the resource.
     * @param string $sid The Twilio-provided string that uniquely identifies the Application resource to delete.
     */
    public function __construct(Version $version, array $payload, string $accountSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['accountSid' => Values::array_get($payload, 'account_sid'), 'apiVersion' => Values::array_get($payload, 'api_version'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')), 'friendlyName' => Values::array_get($payload, 'friendly_name'), 'messageStatusCallback' => Values::array_get($payload, 'message_status_callback'), 'sid' => Values::array_get($payload, 'sid'), 'smsFallbackMethod' => Values::array_get($payload, 'sms_fallback_method'), 'smsFallbackUrl' => Values::array_get($payload, 'sms_fallback_url'), 'smsMethod' => Values::array_get($payload, 'sms_method'), 'smsStatusCallback' => Values::array_get($payload, 'sms_status_callback'), 'smsUrl' => Values::array_get($payload, 'sms_url'), 'statusCallback' => Values::array_get($payload, 'status_callback'), 'statusCallbackMethod' => Values::array_get($payload, 'status_callback_method'), 'uri' => Values::array_get($payload, 'uri'), 'voiceCallerIdLookup' => Values::array_get($payload, 'voice_caller_id_lookup'), 'voiceFallbackMethod' => Values::array_get($payload, 'voice_fallback_method'), 'voiceFallbackUrl' => Values::array_get($payload, 'voice_fallback_url'), 'voiceMethod' => Values::array_get($payload, 'voice_method'), 'voiceUrl' => Values::array_get($payload, 'voice_url'), 'publicApplicationConnectEnabled' => Values::array_get($payload, 'public_application_connect_enabled')];
        $this->solution = ['accountSid' => $accountSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return ApplicationContext Context for this ApplicationInstance
     */
    protected function proxy() : ApplicationContext
    {
        if (!$this->context) {
            $this->context = new ApplicationContext($this->version, $this->solution['accountSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the ApplicationInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the ApplicationInstance
     *
     * @return ApplicationInstance Fetched ApplicationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : ApplicationInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the ApplicationInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ApplicationInstance Updated ApplicationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : ApplicationInstance
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
        return '[Twilio.Api.V2010.ApplicationInstance ' . \implode(' ', $context) . ']';
    }
}
