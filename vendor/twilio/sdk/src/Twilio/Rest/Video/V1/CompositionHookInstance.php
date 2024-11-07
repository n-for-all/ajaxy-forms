<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Video
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Video\V1;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $accountSid
 * @property string|null $friendlyName
 * @property bool|null $enabled
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property string|null $sid
 * @property string[]|null $audioSources
 * @property string[]|null $audioSourcesExcluded
 * @property array|null $videoLayout
 * @property string|null $resolution
 * @property bool|null $trim
 * @property string $format
 * @property string|null $statusCallback
 * @property string|null $statusCallbackMethod
 * @property string|null $url
 */
class CompositionHookInstance extends InstanceResource
{
    /**
     * Initialize the CompositionHookInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $sid The SID of the CompositionHook resource to delete.
     */
    public function __construct(Version $version, array $payload, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['accountSid' => Values::array_get($payload, 'account_sid'), 'friendlyName' => Values::array_get($payload, 'friendly_name'), 'enabled' => Values::array_get($payload, 'enabled'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')), 'sid' => Values::array_get($payload, 'sid'), 'audioSources' => Values::array_get($payload, 'audio_sources'), 'audioSourcesExcluded' => Values::array_get($payload, 'audio_sources_excluded'), 'videoLayout' => Values::array_get($payload, 'video_layout'), 'resolution' => Values::array_get($payload, 'resolution'), 'trim' => Values::array_get($payload, 'trim'), 'format' => Values::array_get($payload, 'format'), 'statusCallback' => Values::array_get($payload, 'status_callback'), 'statusCallbackMethod' => Values::array_get($payload, 'status_callback_method'), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return CompositionHookContext Context for this CompositionHookInstance
     */
    protected function proxy() : CompositionHookContext
    {
        if (!$this->context) {
            $this->context = new CompositionHookContext($this->version, $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the CompositionHookInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the CompositionHookInstance
     *
     * @return CompositionHookInstance Fetched CompositionHookInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : CompositionHookInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the CompositionHookInstance
     *
     * @param string $friendlyName A descriptive string that you create to describe the resource. It can be up to  100 characters long and it must be unique within the account.
     * @param array|Options $options Optional Arguments
     * @return CompositionHookInstance Updated CompositionHookInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(string $friendlyName, array $options = []) : CompositionHookInstance
    {
        return $this->proxy()->update($friendlyName, $options);
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
        return '[Twilio.Video.V1.CompositionHookInstance ' . \implode(' ', $context) . ']';
    }
}
