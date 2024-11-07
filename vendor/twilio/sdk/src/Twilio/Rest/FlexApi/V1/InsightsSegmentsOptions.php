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

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class InsightsSegmentsOptions
{
    /**
     * @param string $segmentId To unique id of the segment
     * @param string[] $reservationId The list of reservation Ids
     * @param string $authorization The Authorization HTTP request header
     * @return ReadInsightsSegmentsOptions Options builder
     */
    public static function read(string $segmentId = Values::NONE, array $reservationId = Values::ARRAY_NONE, string $authorization = Values::NONE) : ReadInsightsSegmentsOptions
    {
        return new ReadInsightsSegmentsOptions($segmentId, $reservationId, $authorization);
    }
}
class ReadInsightsSegmentsOptions extends Options
{
    /**
     * @param string $segmentId To unique id of the segment
     * @param string[] $reservationId The list of reservation Ids
     * @param string $authorization The Authorization HTTP request header
     */
    public function __construct(string $segmentId = Values::NONE, array $reservationId = Values::ARRAY_NONE, string $authorization = Values::NONE)
    {
        $this->options['segmentId'] = $segmentId;
        $this->options['reservationId'] = $reservationId;
        $this->options['authorization'] = $authorization;
    }
    /**
     * To unique id of the segment
     *
     * @param string $segmentId To unique id of the segment
     * @return $this Fluent Builder
     */
    public function setSegmentId(string $segmentId) : self
    {
        $this->options['segmentId'] = $segmentId;
        return $this;
    }
    /**
     * The list of reservation Ids
     *
     * @param string[] $reservationId The list of reservation Ids
     * @return $this Fluent Builder
     */
    public function setReservationId(array $reservationId) : self
    {
        $this->options['reservationId'] = $reservationId;
        return $this;
    }
    /**
     * The Authorization HTTP request header
     *
     * @param string $authorization The Authorization HTTP request header
     * @return $this Fluent Builder
     */
    public function setAuthorization(string $authorization) : self
    {
        $this->options['authorization'] = $authorization;
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
        return '[Twilio.FlexApi.V1.ReadInsightsSegmentsOptions ' . $options . ']';
    }
}
