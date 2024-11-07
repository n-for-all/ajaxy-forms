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
abstract class AssessmentsOptions
{
    /**
     * @param string $authorization The Authorization HTTP request header
     * @return CreateAssessmentsOptions Options builder
     */
    public static function create(string $authorization = Values::NONE) : CreateAssessmentsOptions
    {
        return new CreateAssessmentsOptions($authorization);
    }
    /**
     * @param string $segmentId The id of the segment.
     * @param string $authorization The Authorization HTTP request header
     * @return ReadAssessmentsOptions Options builder
     */
    public static function read(string $segmentId = Values::NONE, string $authorization = Values::NONE) : ReadAssessmentsOptions
    {
        return new ReadAssessmentsOptions($segmentId, $authorization);
    }
    /**
     * @param string $authorization The Authorization HTTP request header
     * @return UpdateAssessmentsOptions Options builder
     */
    public static function update(string $authorization = Values::NONE) : UpdateAssessmentsOptions
    {
        return new UpdateAssessmentsOptions($authorization);
    }
}
class CreateAssessmentsOptions extends Options
{
    /**
     * @param string $authorization The Authorization HTTP request header
     */
    public function __construct(string $authorization = Values::NONE)
    {
        $this->options['authorization'] = $authorization;
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
        return '[Twilio.FlexApi.V1.CreateAssessmentsOptions ' . $options . ']';
    }
}
class ReadAssessmentsOptions extends Options
{
    /**
     * @param string $segmentId The id of the segment.
     * @param string $authorization The Authorization HTTP request header
     */
    public function __construct(string $segmentId = Values::NONE, string $authorization = Values::NONE)
    {
        $this->options['segmentId'] = $segmentId;
        $this->options['authorization'] = $authorization;
    }
    /**
     * The id of the segment.
     *
     * @param string $segmentId The id of the segment.
     * @return $this Fluent Builder
     */
    public function setSegmentId(string $segmentId) : self
    {
        $this->options['segmentId'] = $segmentId;
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
        return '[Twilio.FlexApi.V1.ReadAssessmentsOptions ' . $options . ']';
    }
}
class UpdateAssessmentsOptions extends Options
{
    /**
     * @param string $authorization The Authorization HTTP request header
     */
    public function __construct(string $authorization = Values::NONE)
    {
        $this->options['authorization'] = $authorization;
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
        return '[Twilio.FlexApi.V1.UpdateAssessmentsOptions ' . $options . ']';
    }
}
