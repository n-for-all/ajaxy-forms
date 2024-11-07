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
namespace Isolated\Twilio\Rest\Preview\Sync;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class ServiceOptions
{
    /**
     * @param string $friendlyName 
     * @param string $webhookUrl 
     * @param bool $reachabilityWebhooksEnabled 
     * @param bool $aclEnabled 
     * @return CreateServiceOptions Options builder
     */
    public static function create(string $friendlyName = Values::NONE, string $webhookUrl = Values::NONE, bool $reachabilityWebhooksEnabled = Values::BOOL_NONE, bool $aclEnabled = Values::BOOL_NONE) : CreateServiceOptions
    {
        return new CreateServiceOptions($friendlyName, $webhookUrl, $reachabilityWebhooksEnabled, $aclEnabled);
    }
    /**
     * @param string $webhookUrl 
     * @param string $friendlyName 
     * @param bool $reachabilityWebhooksEnabled 
     * @param bool $aclEnabled 
     * @return UpdateServiceOptions Options builder
     */
    public static function update(string $webhookUrl = Values::NONE, string $friendlyName = Values::NONE, bool $reachabilityWebhooksEnabled = Values::BOOL_NONE, bool $aclEnabled = Values::BOOL_NONE) : UpdateServiceOptions
    {
        return new UpdateServiceOptions($webhookUrl, $friendlyName, $reachabilityWebhooksEnabled, $aclEnabled);
    }
}
class CreateServiceOptions extends Options
{
    /**
     * @param string $friendlyName 
     * @param string $webhookUrl 
     * @param bool $reachabilityWebhooksEnabled 
     * @param bool $aclEnabled 
     */
    public function __construct(string $friendlyName = Values::NONE, string $webhookUrl = Values::NONE, bool $reachabilityWebhooksEnabled = Values::BOOL_NONE, bool $aclEnabled = Values::BOOL_NONE)
    {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['webhookUrl'] = $webhookUrl;
        $this->options['reachabilityWebhooksEnabled'] = $reachabilityWebhooksEnabled;
        $this->options['aclEnabled'] = $aclEnabled;
    }
    /**
     * 
     *
     * @param string $friendlyName 
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName) : self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }
    /**
     * 
     *
     * @param string $webhookUrl 
     * @return $this Fluent Builder
     */
    public function setWebhookUrl(string $webhookUrl) : self
    {
        $this->options['webhookUrl'] = $webhookUrl;
        return $this;
    }
    /**
     * 
     *
     * @param bool $reachabilityWebhooksEnabled 
     * @return $this Fluent Builder
     */
    public function setReachabilityWebhooksEnabled(bool $reachabilityWebhooksEnabled) : self
    {
        $this->options['reachabilityWebhooksEnabled'] = $reachabilityWebhooksEnabled;
        return $this;
    }
    /**
     * 
     *
     * @param bool $aclEnabled 
     * @return $this Fluent Builder
     */
    public function setAclEnabled(bool $aclEnabled) : self
    {
        $this->options['aclEnabled'] = $aclEnabled;
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
        return '[Twilio.Preview.Sync.CreateServiceOptions ' . $options . ']';
    }
}
class UpdateServiceOptions extends Options
{
    /**
     * @param string $webhookUrl 
     * @param string $friendlyName 
     * @param bool $reachabilityWebhooksEnabled 
     * @param bool $aclEnabled 
     */
    public function __construct(string $webhookUrl = Values::NONE, string $friendlyName = Values::NONE, bool $reachabilityWebhooksEnabled = Values::BOOL_NONE, bool $aclEnabled = Values::BOOL_NONE)
    {
        $this->options['webhookUrl'] = $webhookUrl;
        $this->options['friendlyName'] = $friendlyName;
        $this->options['reachabilityWebhooksEnabled'] = $reachabilityWebhooksEnabled;
        $this->options['aclEnabled'] = $aclEnabled;
    }
    /**
     * 
     *
     * @param string $webhookUrl 
     * @return $this Fluent Builder
     */
    public function setWebhookUrl(string $webhookUrl) : self
    {
        $this->options['webhookUrl'] = $webhookUrl;
        return $this;
    }
    /**
     * 
     *
     * @param string $friendlyName 
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName) : self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }
    /**
     * 
     *
     * @param bool $reachabilityWebhooksEnabled 
     * @return $this Fluent Builder
     */
    public function setReachabilityWebhooksEnabled(bool $reachabilityWebhooksEnabled) : self
    {
        $this->options['reachabilityWebhooksEnabled'] = $reachabilityWebhooksEnabled;
        return $this;
    }
    /**
     * 
     *
     * @param bool $aclEnabled 
     * @return $this Fluent Builder
     */
    public function setAclEnabled(bool $aclEnabled) : self
    {
        $this->options['aclEnabled'] = $aclEnabled;
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
        return '[Twilio.Preview.Sync.UpdateServiceOptions ' . $options . ']';
    }
}
