<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Insights
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Insights\V1;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class RoomOptions
{
    /**
     * @param string $roomType Type of room. Can be `go`, `peer_to_peer`, `group`, or `group_small`.
     * @param string $codec Codecs used by participants in the room. Can be `VP8`, `H264`, or `VP9`.
     * @param string $roomName Room friendly name.
     * @param \DateTime $createdAfter Only read rooms that started on or after this ISO 8601 timestamp.
     * @param \DateTime $createdBefore Only read rooms that started before this ISO 8601 timestamp.
     * @return ReadRoomOptions Options builder
     */
    public static function read(array $roomType = Values::ARRAY_NONE, array $codec = Values::ARRAY_NONE, string $roomName = Values::NONE, \DateTime $createdAfter = null, \DateTime $createdBefore = null) : ReadRoomOptions
    {
        return new ReadRoomOptions($roomType, $codec, $roomName, $createdAfter, $createdBefore);
    }
}
class ReadRoomOptions extends Options
{
    /**
     * @param string $roomType Type of room. Can be `go`, `peer_to_peer`, `group`, or `group_small`.
     * @param string $codec Codecs used by participants in the room. Can be `VP8`, `H264`, or `VP9`.
     * @param string $roomName Room friendly name.
     * @param \DateTime $createdAfter Only read rooms that started on or after this ISO 8601 timestamp.
     * @param \DateTime $createdBefore Only read rooms that started before this ISO 8601 timestamp.
     */
    public function __construct(array $roomType = Values::ARRAY_NONE, array $codec = Values::ARRAY_NONE, string $roomName = Values::NONE, \DateTime $createdAfter = null, \DateTime $createdBefore = null)
    {
        $this->options['roomType'] = $roomType;
        $this->options['codec'] = $codec;
        $this->options['roomName'] = $roomName;
        $this->options['createdAfter'] = $createdAfter;
        $this->options['createdBefore'] = $createdBefore;
    }
    /**
     * Type of room. Can be `go`, `peer_to_peer`, `group`, or `group_small`.
     *
     * @param string $roomType Type of room. Can be `go`, `peer_to_peer`, `group`, or `group_small`.
     * @return $this Fluent Builder
     */
    public function setRoomType(array $roomType) : self
    {
        $this->options['roomType'] = $roomType;
        return $this;
    }
    /**
     * Codecs used by participants in the room. Can be `VP8`, `H264`, or `VP9`.
     *
     * @param string $codec Codecs used by participants in the room. Can be `VP8`, `H264`, or `VP9`.
     * @return $this Fluent Builder
     */
    public function setCodec(array $codec) : self
    {
        $this->options['codec'] = $codec;
        return $this;
    }
    /**
     * Room friendly name.
     *
     * @param string $roomName Room friendly name.
     * @return $this Fluent Builder
     */
    public function setRoomName(string $roomName) : self
    {
        $this->options['roomName'] = $roomName;
        return $this;
    }
    /**
     * Only read rooms that started on or after this ISO 8601 timestamp.
     *
     * @param \DateTime $createdAfter Only read rooms that started on or after this ISO 8601 timestamp.
     * @return $this Fluent Builder
     */
    public function setCreatedAfter(\DateTime $createdAfter) : self
    {
        $this->options['createdAfter'] = $createdAfter;
        return $this;
    }
    /**
     * Only read rooms that started before this ISO 8601 timestamp.
     *
     * @param \DateTime $createdBefore Only read rooms that started before this ISO 8601 timestamp.
     * @return $this Fluent Builder
     */
    public function setCreatedBefore(\DateTime $createdBefore) : self
    {
        $this->options['createdBefore'] = $createdBefore;
        return $this;
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Insights.V1.ReadRoomOptions ' . $options . ']';
    }
}
