<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Serverless
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Serverless\V1\Service\Environment;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class VariableOptions
{
    /**
     * @param string $key A string by which the Variable resource can be referenced. It can be a maximum of 128 characters.
     * @param string $value A string that contains the actual value of the Variable. It can be a maximum of 450 bytes in size.
     * @return UpdateVariableOptions Options builder
     */
    public static function update(string $key = Values::NONE, string $value = Values::NONE) : UpdateVariableOptions
    {
        return new UpdateVariableOptions($key, $value);
    }
}
class UpdateVariableOptions extends Options
{
    /**
     * @param string $key A string by which the Variable resource can be referenced. It can be a maximum of 128 characters.
     * @param string $value A string that contains the actual value of the Variable. It can be a maximum of 450 bytes in size.
     */
    public function __construct(string $key = Values::NONE, string $value = Values::NONE)
    {
        $this->options['key'] = $key;
        $this->options['value'] = $value;
    }
    /**
     * A string by which the Variable resource can be referenced. It can be a maximum of 128 characters.
     *
     * @param string $key A string by which the Variable resource can be referenced. It can be a maximum of 128 characters.
     * @return $this Fluent Builder
     */
    public function setKey(string $key) : self
    {
        $this->options['key'] = $key;
        return $this;
    }
    /**
     * A string that contains the actual value of the Variable. It can be a maximum of 450 bytes in size.
     *
     * @param string $value A string that contains the actual value of the Variable. It can be a maximum of 450 bytes in size.
     * @return $this Fluent Builder
     */
    public function setValue(string $value) : self
    {
        $this->options['value'] = $value;
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
        return '[Twilio.Serverless.V1.UpdateVariableOptions ' . $options . ']';
    }
}
