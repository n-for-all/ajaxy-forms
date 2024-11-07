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
namespace Isolated\Twilio\Rest\Serverless\V1;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class ServiceOptions
{
    /**
     * @param bool $includeCredentials Whether to inject Account credentials into a function invocation context. The default value is `true`.
     * @param bool $uiEditable Whether the Service's properties and subresources can be edited via the UI. The default value is `false`.
     * @return CreateServiceOptions Options builder
     */
    public static function create(bool $includeCredentials = Values::BOOL_NONE, bool $uiEditable = Values::BOOL_NONE) : CreateServiceOptions
    {
        return new CreateServiceOptions($includeCredentials, $uiEditable);
    }
    /**
     * @param bool $includeCredentials Whether to inject Account credentials into a function invocation context.
     * @param string $friendlyName A descriptive string that you create to describe the Service resource. It can be a maximum of 255 characters.
     * @param bool $uiEditable Whether the Service resource's properties and subresources can be edited via the UI. The default value is `false`.
     * @return UpdateServiceOptions Options builder
     */
    public static function update(bool $includeCredentials = Values::BOOL_NONE, string $friendlyName = Values::NONE, bool $uiEditable = Values::BOOL_NONE) : UpdateServiceOptions
    {
        return new UpdateServiceOptions($includeCredentials, $friendlyName, $uiEditable);
    }
}
class CreateServiceOptions extends Options
{
    /**
     * @param bool $includeCredentials Whether to inject Account credentials into a function invocation context. The default value is `true`.
     * @param bool $uiEditable Whether the Service's properties and subresources can be edited via the UI. The default value is `false`.
     */
    public function __construct(bool $includeCredentials = Values::BOOL_NONE, bool $uiEditable = Values::BOOL_NONE)
    {
        $this->options['includeCredentials'] = $includeCredentials;
        $this->options['uiEditable'] = $uiEditable;
    }
    /**
     * Whether to inject Account credentials into a function invocation context. The default value is `true`.
     *
     * @param bool $includeCredentials Whether to inject Account credentials into a function invocation context. The default value is `true`.
     * @return $this Fluent Builder
     */
    public function setIncludeCredentials(bool $includeCredentials) : self
    {
        $this->options['includeCredentials'] = $includeCredentials;
        return $this;
    }
    /**
     * Whether the Service's properties and subresources can be edited via the UI. The default value is `false`.
     *
     * @param bool $uiEditable Whether the Service's properties and subresources can be edited via the UI. The default value is `false`.
     * @return $this Fluent Builder
     */
    public function setUiEditable(bool $uiEditable) : self
    {
        $this->options['uiEditable'] = $uiEditable;
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
        return '[Twilio.Serverless.V1.CreateServiceOptions ' . $options . ']';
    }
}
class UpdateServiceOptions extends Options
{
    /**
     * @param bool $includeCredentials Whether to inject Account credentials into a function invocation context.
     * @param string $friendlyName A descriptive string that you create to describe the Service resource. It can be a maximum of 255 characters.
     * @param bool $uiEditable Whether the Service resource's properties and subresources can be edited via the UI. The default value is `false`.
     */
    public function __construct(bool $includeCredentials = Values::BOOL_NONE, string $friendlyName = Values::NONE, bool $uiEditable = Values::BOOL_NONE)
    {
        $this->options['includeCredentials'] = $includeCredentials;
        $this->options['friendlyName'] = $friendlyName;
        $this->options['uiEditable'] = $uiEditable;
    }
    /**
     * Whether to inject Account credentials into a function invocation context.
     *
     * @param bool $includeCredentials Whether to inject Account credentials into a function invocation context.
     * @return $this Fluent Builder
     */
    public function setIncludeCredentials(bool $includeCredentials) : self
    {
        $this->options['includeCredentials'] = $includeCredentials;
        return $this;
    }
    /**
     * A descriptive string that you create to describe the Service resource. It can be a maximum of 255 characters.
     *
     * @param string $friendlyName A descriptive string that you create to describe the Service resource. It can be a maximum of 255 characters.
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName) : self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }
    /**
     * Whether the Service resource's properties and subresources can be edited via the UI. The default value is `false`.
     *
     * @param bool $uiEditable Whether the Service resource's properties and subresources can be edited via the UI. The default value is `false`.
     * @return $this Fluent Builder
     */
    public function setUiEditable(bool $uiEditable) : self
    {
        $this->options['uiEditable'] = $uiEditable;
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
        return '[Twilio.Serverless.V1.UpdateServiceOptions ' . $options . ']';
    }
}
