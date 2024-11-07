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
namespace Isolated\Twilio\Rest\Video\V1\Room\Participant;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $sid
 * @property string|null $participantSid
 * @property string|null $roomSid
 * @property string|null $name
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property bool|null $enabled
 * @property string $kind
 * @property string|null $url
 */
class PublishedTrackInstance extends InstanceResource
{
    /**
     * Initialize the PublishedTrackInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $roomSid The SID of the Room resource where the Track resource to fetch is published.
     * @param string $participantSid The SID of the Participant resource with the published track to fetch.
     * @param string $sid The SID of the RoomParticipantPublishedTrack resource to fetch.
     */
    public function __construct(Version $version, array $payload, string $roomSid, string $participantSid, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => Values::array_get($payload, 'sid'), 'participantSid' => Values::array_get($payload, 'participant_sid'), 'roomSid' => Values::array_get($payload, 'room_sid'), 'name' => Values::array_get($payload, 'name'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')), 'enabled' => Values::array_get($payload, 'enabled'), 'kind' => Values::array_get($payload, 'kind'), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['roomSid' => $roomSid, 'participantSid' => $participantSid, 'sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return PublishedTrackContext Context for this PublishedTrackInstance
     */
    protected function proxy() : PublishedTrackContext
    {
        if (!$this->context) {
            $this->context = new PublishedTrackContext($this->version, $this->solution['roomSid'], $this->solution['participantSid'], $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Fetch the PublishedTrackInstance
     *
     * @return PublishedTrackInstance Fetched PublishedTrackInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : PublishedTrackInstance
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
        return '[Twilio.Video.V1.PublishedTrackInstance ' . \implode(' ', $context) . ']';
    }
}
