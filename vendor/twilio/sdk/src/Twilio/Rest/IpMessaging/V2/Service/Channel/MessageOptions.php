<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Ip_messaging
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\IpMessaging\V2\Service\Channel;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class MessageOptions
{
    /**
     * @param string $from 
     * @param string $attributes 
     * @param \DateTime $dateCreated 
     * @param \DateTime $dateUpdated 
     * @param string $lastUpdatedBy 
     * @param string $body 
     * @param string $mediaSid 
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     * @return CreateMessageOptions Options builder
     */
    public static function create(string $from = Values::NONE, string $attributes = Values::NONE, \DateTime $dateCreated = null, \DateTime $dateUpdated = null, string $lastUpdatedBy = Values::NONE, string $body = Values::NONE, string $mediaSid = Values::NONE, string $xTwilioWebhookEnabled = Values::NONE) : CreateMessageOptions
    {
        return new CreateMessageOptions($from, $attributes, $dateCreated, $dateUpdated, $lastUpdatedBy, $body, $mediaSid, $xTwilioWebhookEnabled);
    }
    /**
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     * @return DeleteMessageOptions Options builder
     */
    public static function delete(string $xTwilioWebhookEnabled = Values::NONE) : DeleteMessageOptions
    {
        return new DeleteMessageOptions($xTwilioWebhookEnabled);
    }
    /**
     * @param string $order 
     * @return ReadMessageOptions Options builder
     */
    public static function read(string $order = Values::NONE) : ReadMessageOptions
    {
        return new ReadMessageOptions($order);
    }
    /**
     * @param string $body 
     * @param string $attributes 
     * @param \DateTime $dateCreated 
     * @param \DateTime $dateUpdated 
     * @param string $lastUpdatedBy 
     * @param string $from 
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     * @return UpdateMessageOptions Options builder
     */
    public static function update(string $body = Values::NONE, string $attributes = Values::NONE, \DateTime $dateCreated = null, \DateTime $dateUpdated = null, string $lastUpdatedBy = Values::NONE, string $from = Values::NONE, string $xTwilioWebhookEnabled = Values::NONE) : UpdateMessageOptions
    {
        return new UpdateMessageOptions($body, $attributes, $dateCreated, $dateUpdated, $lastUpdatedBy, $from, $xTwilioWebhookEnabled);
    }
}
class CreateMessageOptions extends Options
{
    /**
     * @param string $from 
     * @param string $attributes 
     * @param \DateTime $dateCreated 
     * @param \DateTime $dateUpdated 
     * @param string $lastUpdatedBy 
     * @param string $body 
     * @param string $mediaSid 
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     */
    public function __construct(string $from = Values::NONE, string $attributes = Values::NONE, \DateTime $dateCreated = null, \DateTime $dateUpdated = null, string $lastUpdatedBy = Values::NONE, string $body = Values::NONE, string $mediaSid = Values::NONE, string $xTwilioWebhookEnabled = Values::NONE)
    {
        $this->options['from'] = $from;
        $this->options['attributes'] = $attributes;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateUpdated'] = $dateUpdated;
        $this->options['lastUpdatedBy'] = $lastUpdatedBy;
        $this->options['body'] = $body;
        $this->options['mediaSid'] = $mediaSid;
        $this->options['xTwilioWebhookEnabled'] = $xTwilioWebhookEnabled;
    }
    /**
     * 
     *
     * @param string $from 
     * @return $this Fluent Builder
     */
    public function setFrom(string $from) : self
    {
        $this->options['from'] = $from;
        return $this;
    }
    /**
     * 
     *
     * @param string $attributes 
     * @return $this Fluent Builder
     */
    public function setAttributes(string $attributes) : self
    {
        $this->options['attributes'] = $attributes;
        return $this;
    }
    /**
     * 
     *
     * @param \DateTime $dateCreated 
     * @return $this Fluent Builder
     */
    public function setDateCreated(\DateTime $dateCreated) : self
    {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }
    /**
     * 
     *
     * @param \DateTime $dateUpdated 
     * @return $this Fluent Builder
     */
    public function setDateUpdated(\DateTime $dateUpdated) : self
    {
        $this->options['dateUpdated'] = $dateUpdated;
        return $this;
    }
    /**
     * 
     *
     * @param string $lastUpdatedBy 
     * @return $this Fluent Builder
     */
    public function setLastUpdatedBy(string $lastUpdatedBy) : self
    {
        $this->options['lastUpdatedBy'] = $lastUpdatedBy;
        return $this;
    }
    /**
     * 
     *
     * @param string $body 
     * @return $this Fluent Builder
     */
    public function setBody(string $body) : self
    {
        $this->options['body'] = $body;
        return $this;
    }
    /**
     * 
     *
     * @param string $mediaSid 
     * @return $this Fluent Builder
     */
    public function setMediaSid(string $mediaSid) : self
    {
        $this->options['mediaSid'] = $mediaSid;
        return $this;
    }
    /**
     * The X-Twilio-Webhook-Enabled HTTP request header
     *
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     * @return $this Fluent Builder
     */
    public function setXTwilioWebhookEnabled(string $xTwilioWebhookEnabled) : self
    {
        $this->options['xTwilioWebhookEnabled'] = $xTwilioWebhookEnabled;
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
        return '[Twilio.IpMessaging.V2.CreateMessageOptions ' . $options . ']';
    }
}
class DeleteMessageOptions extends Options
{
    /**
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     */
    public function __construct(string $xTwilioWebhookEnabled = Values::NONE)
    {
        $this->options['xTwilioWebhookEnabled'] = $xTwilioWebhookEnabled;
    }
    /**
     * The X-Twilio-Webhook-Enabled HTTP request header
     *
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     * @return $this Fluent Builder
     */
    public function setXTwilioWebhookEnabled(string $xTwilioWebhookEnabled) : self
    {
        $this->options['xTwilioWebhookEnabled'] = $xTwilioWebhookEnabled;
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
        return '[Twilio.IpMessaging.V2.DeleteMessageOptions ' . $options . ']';
    }
}
class ReadMessageOptions extends Options
{
    /**
     * @param string $order 
     */
    public function __construct(string $order = Values::NONE)
    {
        $this->options['order'] = $order;
    }
    /**
     * 
     *
     * @param string $order 
     * @return $this Fluent Builder
     */
    public function setOrder(string $order) : self
    {
        $this->options['order'] = $order;
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
        return '[Twilio.IpMessaging.V2.ReadMessageOptions ' . $options . ']';
    }
}
class UpdateMessageOptions extends Options
{
    /**
     * @param string $body 
     * @param string $attributes 
     * @param \DateTime $dateCreated 
     * @param \DateTime $dateUpdated 
     * @param string $lastUpdatedBy 
     * @param string $from 
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     */
    public function __construct(string $body = Values::NONE, string $attributes = Values::NONE, \DateTime $dateCreated = null, \DateTime $dateUpdated = null, string $lastUpdatedBy = Values::NONE, string $from = Values::NONE, string $xTwilioWebhookEnabled = Values::NONE)
    {
        $this->options['body'] = $body;
        $this->options['attributes'] = $attributes;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateUpdated'] = $dateUpdated;
        $this->options['lastUpdatedBy'] = $lastUpdatedBy;
        $this->options['from'] = $from;
        $this->options['xTwilioWebhookEnabled'] = $xTwilioWebhookEnabled;
    }
    /**
     * 
     *
     * @param string $body 
     * @return $this Fluent Builder
     */
    public function setBody(string $body) : self
    {
        $this->options['body'] = $body;
        return $this;
    }
    /**
     * 
     *
     * @param string $attributes 
     * @return $this Fluent Builder
     */
    public function setAttributes(string $attributes) : self
    {
        $this->options['attributes'] = $attributes;
        return $this;
    }
    /**
     * 
     *
     * @param \DateTime $dateCreated 
     * @return $this Fluent Builder
     */
    public function setDateCreated(\DateTime $dateCreated) : self
    {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }
    /**
     * 
     *
     * @param \DateTime $dateUpdated 
     * @return $this Fluent Builder
     */
    public function setDateUpdated(\DateTime $dateUpdated) : self
    {
        $this->options['dateUpdated'] = $dateUpdated;
        return $this;
    }
    /**
     * 
     *
     * @param string $lastUpdatedBy 
     * @return $this Fluent Builder
     */
    public function setLastUpdatedBy(string $lastUpdatedBy) : self
    {
        $this->options['lastUpdatedBy'] = $lastUpdatedBy;
        return $this;
    }
    /**
     * 
     *
     * @param string $from 
     * @return $this Fluent Builder
     */
    public function setFrom(string $from) : self
    {
        $this->options['from'] = $from;
        return $this;
    }
    /**
     * The X-Twilio-Webhook-Enabled HTTP request header
     *
     * @param string $xTwilioWebhookEnabled The X-Twilio-Webhook-Enabled HTTP request header
     * @return $this Fluent Builder
     */
    public function setXTwilioWebhookEnabled(string $xTwilioWebhookEnabled) : self
    {
        $this->options['xTwilioWebhookEnabled'] = $xTwilioWebhookEnabled;
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
        return '[Twilio.IpMessaging.V2.UpdateMessageOptions ' . $options . ']';
    }
}
