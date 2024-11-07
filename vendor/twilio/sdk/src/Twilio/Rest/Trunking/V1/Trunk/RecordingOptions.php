<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Trunking
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Trunking\V1\Trunk;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class RecordingOptions
{
    /**
     * @param string $mode
     * @param string $trim
     * @return UpdateRecordingOptions Options builder
     */
    public static function update(string $mode = Values::NONE, string $trim = Values::NONE) : UpdateRecordingOptions
    {
        return new UpdateRecordingOptions($mode, $trim);
    }
}
class UpdateRecordingOptions extends Options
{
    /**
     * @param string $mode
     * @param string $trim
     */
    public function __construct(string $mode = Values::NONE, string $trim = Values::NONE)
    {
        $this->options['mode'] = $mode;
        $this->options['trim'] = $trim;
    }
    /**
     * @param string $mode
     * @return $this Fluent Builder
     */
    public function setMode(string $mode) : self
    {
        $this->options['mode'] = $mode;
        return $this;
    }
    /**
     * @param string $trim
     * @return $this Fluent Builder
     */
    public function setTrim(string $trim) : self
    {
        $this->options['trim'] = $trim;
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
        return '[Twilio.Trunking.V1.UpdateRecordingOptions ' . $options . ']';
    }
}
