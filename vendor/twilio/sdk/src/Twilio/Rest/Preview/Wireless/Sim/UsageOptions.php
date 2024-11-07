<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Preview
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Preview\Wireless\Sim;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class UsageOptions
{
    /**
     * @param string $end 
     * @param string $start 
     * @return FetchUsageOptions Options builder
     */
    public static function fetch(string $end = Values::NONE, string $start = Values::NONE) : FetchUsageOptions
    {
        return new FetchUsageOptions($end, $start);
    }
}
class FetchUsageOptions extends Options
{
    /**
     * @param string $end 
     * @param string $start 
     */
    public function __construct(string $end = Values::NONE, string $start = Values::NONE)
    {
        $this->options['end'] = $end;
        $this->options['start'] = $start;
    }
    /**
     * 
     *
     * @param string $end 
     * @return $this Fluent Builder
     */
    public function setEnd(string $end) : self
    {
        $this->options['end'] = $end;
        return $this;
    }
    /**
     * 
     *
     * @param string $start 
     * @return $this Fluent Builder
     */
    public function setStart(string $start) : self
    {
        $this->options['start'] = $start;
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
        return '[Twilio.Preview.Wireless.FetchUsageOptions ' . $options . ']';
    }
}
