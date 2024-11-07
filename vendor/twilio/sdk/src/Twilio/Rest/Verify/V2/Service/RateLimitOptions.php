<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Verify
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Verify\V2\Service;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class RateLimitOptions
{
    /**
     * @param string $description Description of this Rate Limit
     * @return CreateRateLimitOptions Options builder
     */
    public static function create(string $description = Values::NONE) : CreateRateLimitOptions
    {
        return new CreateRateLimitOptions($description);
    }
    /**
     * @param string $description Description of this Rate Limit
     * @return UpdateRateLimitOptions Options builder
     */
    public static function update(string $description = Values::NONE) : UpdateRateLimitOptions
    {
        return new UpdateRateLimitOptions($description);
    }
}
class CreateRateLimitOptions extends Options
{
    /**
     * @param string $description Description of this Rate Limit
     */
    public function __construct(string $description = Values::NONE)
    {
        $this->options['description'] = $description;
    }
    /**
     * Description of this Rate Limit
     *
     * @param string $description Description of this Rate Limit
     * @return $this Fluent Builder
     */
    public function setDescription(string $description) : self
    {
        $this->options['description'] = $description;
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
        return '[Twilio.Verify.V2.CreateRateLimitOptions ' . $options . ']';
    }
}
class UpdateRateLimitOptions extends Options
{
    /**
     * @param string $description Description of this Rate Limit
     */
    public function __construct(string $description = Values::NONE)
    {
        $this->options['description'] = $description;
    }
    /**
     * Description of this Rate Limit
     *
     * @param string $description Description of this Rate Limit
     * @return $this Fluent Builder
     */
    public function setDescription(string $description) : self
    {
        $this->options['description'] = $description;
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
        return '[Twilio.Verify.V2.UpdateRateLimitOptions ' . $options . ']';
    }
}
