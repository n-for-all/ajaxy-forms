<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Studio
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Studio\V2\Flow;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class ExecutionOptions
{
    /**
     * @param array $parameters JSON data that will be added to the Flow's context and that can be accessed as variables inside your Flow. For example, if you pass in `Parameters={\\\"name\\\":\\\"Zeke\\\"}`, a widget in your Flow can reference the variable `{{flow.data.name}}`, which returns \\\"Zeke\\\". Note: the JSON value must explicitly be passed as a string, not as a hash object. Depending on your particular HTTP library, you may need to add quotes or URL encode the JSON string.
     * @return CreateExecutionOptions Options builder
     */
    public static function create(array $parameters = Values::ARRAY_NONE) : CreateExecutionOptions
    {
        return new CreateExecutionOptions($parameters);
    }
    /**
     * @param \DateTime $dateCreatedFrom Only show Execution resources starting on or after this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time, given as `YYYY-MM-DDThh:mm:ss-hh:mm`.
     * @param \DateTime $dateCreatedTo Only show Execution resources starting before this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time, given as `YYYY-MM-DDThh:mm:ss-hh:mm`.
     * @return ReadExecutionOptions Options builder
     */
    public static function read(\DateTime $dateCreatedFrom = null, \DateTime $dateCreatedTo = null) : ReadExecutionOptions
    {
        return new ReadExecutionOptions($dateCreatedFrom, $dateCreatedTo);
    }
}
class CreateExecutionOptions extends Options
{
    /**
     * @param array $parameters JSON data that will be added to the Flow's context and that can be accessed as variables inside your Flow. For example, if you pass in `Parameters={\\\"name\\\":\\\"Zeke\\\"}`, a widget in your Flow can reference the variable `{{flow.data.name}}`, which returns \\\"Zeke\\\". Note: the JSON value must explicitly be passed as a string, not as a hash object. Depending on your particular HTTP library, you may need to add quotes or URL encode the JSON string.
     */
    public function __construct(array $parameters = Values::ARRAY_NONE)
    {
        $this->options['parameters'] = $parameters;
    }
    /**
     * JSON data that will be added to the Flow's context and that can be accessed as variables inside your Flow. For example, if you pass in `Parameters={\\\"name\\\":\\\"Zeke\\\"}`, a widget in your Flow can reference the variable `{{flow.data.name}}`, which returns \\\"Zeke\\\". Note: the JSON value must explicitly be passed as a string, not as a hash object. Depending on your particular HTTP library, you may need to add quotes or URL encode the JSON string.
     *
     * @param array $parameters JSON data that will be added to the Flow's context and that can be accessed as variables inside your Flow. For example, if you pass in `Parameters={\\\"name\\\":\\\"Zeke\\\"}`, a widget in your Flow can reference the variable `{{flow.data.name}}`, which returns \\\"Zeke\\\". Note: the JSON value must explicitly be passed as a string, not as a hash object. Depending on your particular HTTP library, you may need to add quotes or URL encode the JSON string.
     * @return $this Fluent Builder
     */
    public function setParameters(array $parameters) : self
    {
        $this->options['parameters'] = $parameters;
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
        return '[Twilio.Studio.V2.CreateExecutionOptions ' . $options . ']';
    }
}
class ReadExecutionOptions extends Options
{
    /**
     * @param \DateTime $dateCreatedFrom Only show Execution resources starting on or after this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time, given as `YYYY-MM-DDThh:mm:ss-hh:mm`.
     * @param \DateTime $dateCreatedTo Only show Execution resources starting before this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time, given as `YYYY-MM-DDThh:mm:ss-hh:mm`.
     */
    public function __construct(\DateTime $dateCreatedFrom = null, \DateTime $dateCreatedTo = null)
    {
        $this->options['dateCreatedFrom'] = $dateCreatedFrom;
        $this->options['dateCreatedTo'] = $dateCreatedTo;
    }
    /**
     * Only show Execution resources starting on or after this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time, given as `YYYY-MM-DDThh:mm:ss-hh:mm`.
     *
     * @param \DateTime $dateCreatedFrom Only show Execution resources starting on or after this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time, given as `YYYY-MM-DDThh:mm:ss-hh:mm`.
     * @return $this Fluent Builder
     */
    public function setDateCreatedFrom(\DateTime $dateCreatedFrom) : self
    {
        $this->options['dateCreatedFrom'] = $dateCreatedFrom;
        return $this;
    }
    /**
     * Only show Execution resources starting before this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time, given as `YYYY-MM-DDThh:mm:ss-hh:mm`.
     *
     * @param \DateTime $dateCreatedTo Only show Execution resources starting before this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time, given as `YYYY-MM-DDThh:mm:ss-hh:mm`.
     * @return $this Fluent Builder
     */
    public function setDateCreatedTo(\DateTime $dateCreatedTo) : self
    {
        $this->options['dateCreatedTo'] = $dateCreatedTo;
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
        return '[Twilio.Studio.V2.ReadExecutionOptions ' . $options . ']';
    }
}
