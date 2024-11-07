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
namespace Isolated\Twilio\Rest\Api\V2010\Account\Call;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $accountSid
 * @property string|null $apiVersion
 * @property string|null $callSid
 * @property string|null $conferenceSid
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property \DateTime|null $startTime
 * @property string|null $duration
 * @property string|null $sid
 * @property string|null $price
 * @property string|null $uri
 * @property array|null $encryptionDetails
 * @property string|null $priceUnit
 * @property string $status
 * @property int|null $channels
 * @property string $source
 * @property int|null $errorCode
 * @property string|null $track
 */
class RecordingInstance extends InstanceResource
{
    /**
     * Initialize the RecordingInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid The SID of the [Account](https://www.twilio.com/docs/iam/api/account) that will create the resource.
     * @param string $callSid The SID of the [Call](https://www.twilio.com/docs/voice/api/call-resource) to associate the resource with.
     * @param string $sid The Twilio-provided string that uniquely identifies the Recording resource to delete.
     */
    public function __construct(Version $version, array $payload, string $accountSid, string $callSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['accountSid' => Values::array_get($payload, 'account_sid'), 'apiVersion' => Values::array_get($payload, 'api_version'), 'callSid' => Values::array_get($payload, 'call_sid'), 'conferenceSid' => Values::array_get($payload, 'conference_sid'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')), 'startTime' => Deserialize::dateTime(Values::array_get($payload, 'start_time')), 'duration' => Values::array_get($payload, 'duration'), 'sid' => Values::array_get($payload, 'sid'), 'price' => Values::array_get($payload, 'price'), 'uri' => Values::array_get($payload, 'uri'), 'encryptionDetails' => Values::array_get($payload, 'encryption_details'), 'priceUnit' => Values::array_get($payload, 'price_unit'), 'status' => Values::array_get($payload, 'status'), 'channels' => Values::array_get($payload, 'channels'), 'source' => Values::array_get($payload, 'source'), 'errorCode' => Values::array_get($payload, 'error_code'), 'track' => Values::array_get($payload, 'track')];
        $this->solution = ['accountSid' => $accountSid, 'callSid' => $callSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return RecordingContext Context for this RecordingInstance
     */
    protected function proxy() : RecordingContext
    {
        if (!$this->context) {
            $this->context = new RecordingContext($this->version, $this->solution['accountSid'], $this->solution['callSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the RecordingInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the RecordingInstance
     *
     * @return RecordingInstance Fetched RecordingInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : RecordingInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the RecordingInstance
     *
     * @param string $status
     * @param array|Options $options Optional Arguments
     * @return RecordingInstance Updated RecordingInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(string $status, array $options = []) : RecordingInstance
    {
        return $this->proxy()->update($status, $options);
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
        return '[Twilio.Api.V2010.RecordingInstance ' . \implode(' ', $context) . ']';
    }
}
