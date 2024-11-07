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
abstract class PluginOptions
{
    /**
     * @param string $friendlyName The Flex Plugin's friendly name.
     * @param string $description A descriptive string that you create to describe the plugin resource. It can be up to 500 characters long
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     * @return CreatePluginOptions Options builder
     */
    public static function create(string $friendlyName = Values::NONE, string $description = Values::NONE, string $flexMetadata = Values::NONE) : CreatePluginOptions
    {
        return new CreatePluginOptions($friendlyName, $description, $flexMetadata);
    }
    /**
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     * @return FetchPluginOptions Options builder
     */
    public static function fetch(string $flexMetadata = Values::NONE) : FetchPluginOptions
    {
        return new FetchPluginOptions($flexMetadata);
    }
    /**
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     * @return ReadPluginOptions Options builder
     */
    public static function read(string $flexMetadata = Values::NONE) : ReadPluginOptions
    {
        return new ReadPluginOptions($flexMetadata);
    }
    /**
     * @param string $friendlyName The Flex Plugin's friendly name.
     * @param string $description A descriptive string that you update to describe the plugin resource. It can be up to 500 characters long
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     * @return UpdatePluginOptions Options builder
     */
    public static function update(string $friendlyName = Values::NONE, string $description = Values::NONE, string $flexMetadata = Values::NONE) : UpdatePluginOptions
    {
        return new UpdatePluginOptions($friendlyName, $description, $flexMetadata);
    }
}
class CreatePluginOptions extends Options
{
    /**
     * @param string $friendlyName The Flex Plugin's friendly name.
     * @param string $description A descriptive string that you create to describe the plugin resource. It can be up to 500 characters long
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     */
    public function __construct(string $friendlyName = Values::NONE, string $description = Values::NONE, string $flexMetadata = Values::NONE)
    {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['description'] = $description;
        $this->options['flexMetadata'] = $flexMetadata;
    }
    /**
     * The Flex Plugin's friendly name.
     *
     * @param string $friendlyName The Flex Plugin's friendly name.
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName) : self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }
    /**
     * A descriptive string that you create to describe the plugin resource. It can be up to 500 characters long
     *
     * @param string $description A descriptive string that you create to describe the plugin resource. It can be up to 500 characters long
     * @return $this Fluent Builder
     */
    public function setDescription(string $description) : self
    {
        $this->options['description'] = $description;
        return $this;
    }
    /**
     * The Flex-Metadata HTTP request header
     *
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     * @return $this Fluent Builder
     */
    public function setFlexMetadata(string $flexMetadata) : self
    {
        $this->options['flexMetadata'] = $flexMetadata;
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
        return '[Twilio.FlexApi.V1.CreatePluginOptions ' . $options . ']';
    }
}
class FetchPluginOptions extends Options
{
    /**
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     */
    public function __construct(string $flexMetadata = Values::NONE)
    {
        $this->options['flexMetadata'] = $flexMetadata;
    }
    /**
     * The Flex-Metadata HTTP request header
     *
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     * @return $this Fluent Builder
     */
    public function setFlexMetadata(string $flexMetadata) : self
    {
        $this->options['flexMetadata'] = $flexMetadata;
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
        return '[Twilio.FlexApi.V1.FetchPluginOptions ' . $options . ']';
    }
}
class ReadPluginOptions extends Options
{
    /**
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     */
    public function __construct(string $flexMetadata = Values::NONE)
    {
        $this->options['flexMetadata'] = $flexMetadata;
    }
    /**
     * The Flex-Metadata HTTP request header
     *
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     * @return $this Fluent Builder
     */
    public function setFlexMetadata(string $flexMetadata) : self
    {
        $this->options['flexMetadata'] = $flexMetadata;
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
        return '[Twilio.FlexApi.V1.ReadPluginOptions ' . $options . ']';
    }
}
class UpdatePluginOptions extends Options
{
    /**
     * @param string $friendlyName The Flex Plugin's friendly name.
     * @param string $description A descriptive string that you update to describe the plugin resource. It can be up to 500 characters long
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     */
    public function __construct(string $friendlyName = Values::NONE, string $description = Values::NONE, string $flexMetadata = Values::NONE)
    {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['description'] = $description;
        $this->options['flexMetadata'] = $flexMetadata;
    }
    /**
     * The Flex Plugin's friendly name.
     *
     * @param string $friendlyName The Flex Plugin's friendly name.
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName) : self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }
    /**
     * A descriptive string that you update to describe the plugin resource. It can be up to 500 characters long
     *
     * @param string $description A descriptive string that you update to describe the plugin resource. It can be up to 500 characters long
     * @return $this Fluent Builder
     */
    public function setDescription(string $description) : self
    {
        $this->options['description'] = $description;
        return $this;
    }
    /**
     * The Flex-Metadata HTTP request header
     *
     * @param string $flexMetadata The Flex-Metadata HTTP request header
     * @return $this Fluent Builder
     */
    public function setFlexMetadata(string $flexMetadata) : self
    {
        $this->options['flexMetadata'] = $flexMetadata;
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
        return '[Twilio.FlexApi.V1.UpdatePluginOptions ' . $options . ']';
    }
}
