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
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
/**
 * @property string|null $segmentId
 * @property string|null $externalId
 * @property string|null $queue
 * @property string|null $externalContact
 * @property string|null $externalSegmentLinkId
 * @property string|null $date
 * @property string|null $accountId
 * @property string|null $externalSegmentLink
 * @property string|null $agentId
 * @property string|null $agentPhone
 * @property string|null $agentName
 * @property string|null $agentTeamName
 * @property string|null $agentTeamNameInHierarchy
 * @property string|null $agentLink
 * @property string|null $customerPhone
 * @property string|null $customerName
 * @property string|null $customerLink
 * @property string|null $segmentRecordingOffset
 * @property array|null $media
 * @property array|null $assessmentType
 * @property array|null $assessmentPercentage
 * @property string|null $url
 */
class InsightsSegmentsInstance extends InstanceResource
{
    /**
     * Initialize the InsightsSegmentsInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     */
    public function __construct(Version $version, array $payload)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['segmentId' => Values::array_get($payload, 'segment_id'), 'externalId' => Values::array_get($payload, 'external_id'), 'queue' => Values::array_get($payload, 'queue'), 'externalContact' => Values::array_get($payload, 'external_contact'), 'externalSegmentLinkId' => Values::array_get($payload, 'external_segment_link_id'), 'date' => Values::array_get($payload, 'date'), 'accountId' => Values::array_get($payload, 'account_id'), 'externalSegmentLink' => Values::array_get($payload, 'external_segment_link'), 'agentId' => Values::array_get($payload, 'agent_id'), 'agentPhone' => Values::array_get($payload, 'agent_phone'), 'agentName' => Values::array_get($payload, 'agent_name'), 'agentTeamName' => Values::array_get($payload, 'agent_team_name'), 'agentTeamNameInHierarchy' => Values::array_get($payload, 'agent_team_name_in_hierarchy'), 'agentLink' => Values::array_get($payload, 'agent_link'), 'customerPhone' => Values::array_get($payload, 'customer_phone'), 'customerName' => Values::array_get($payload, 'customer_name'), 'customerLink' => Values::array_get($payload, 'customer_link'), 'segmentRecordingOffset' => Values::array_get($payload, 'segment_recording_offset'), 'media' => Values::array_get($payload, 'media'), 'assessmentType' => Values::array_get($payload, 'assessment_type'), 'assessmentPercentage' => Values::array_get($payload, 'assessment_percentage'), 'url' => Values::array_get($payload, 'url')];
        $this->solution = [];
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
        return '[Twilio.FlexApi.V1.InsightsSegmentsInstance]';
    }
}
