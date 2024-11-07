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
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Serialize;
class SubscribeRulesList extends ListResource
{
    /**
     * Construct the SubscribeRulesList
     *
     * @param Version $version Version that contains the resource
     * @param string $roomSid The SID of the Room resource where the subscribe rules to fetch apply.
     * @param string $participantSid The SID of the Participant resource with the subscribe rules to fetch.
     */
    public function __construct(Version $version, string $roomSid, string $participantSid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['roomSid' => $roomSid, 'participantSid' => $participantSid];
        $this->uri = '/Rooms/' . \rawurlencode($roomSid) . '/Participants/' . \rawurlencode($participantSid) . '/SubscribeRules';
    }
    /**
     * Fetch the SubscribeRulesInstance
     *
     * @return SubscribeRulesInstance Fetched SubscribeRulesInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : SubscribeRulesInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new SubscribeRulesInstance($this->version, $payload, $this->solution['roomSid'], $this->solution['participantSid']);
    }
    /**
     * Update the SubscribeRulesInstance
     *
     * @param array|Options $options Optional Arguments
     * @return SubscribeRulesInstance Updated SubscribeRulesInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : SubscribeRulesInstance
    {
        $options = new Values($options);
        $data = Values::of(['Rules' => Serialize::jsonObject($options['rules'])]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new SubscribeRulesInstance($this->version, $payload, $this->solution['roomSid'], $this->solution['participantSid']);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Video.V1.SubscribeRulesList]';
    }
}
