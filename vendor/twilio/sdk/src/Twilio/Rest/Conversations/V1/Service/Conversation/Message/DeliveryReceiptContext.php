<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Conversations
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Conversations\V1\Service\Conversation\Message;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
class DeliveryReceiptContext extends InstanceContext
{
    /**
     * Initialize the DeliveryReceiptContext
     *
     * @param Version $version Version that contains the resource
     * @param string $chatServiceSid The SID of the [Conversation Service](https://www.twilio.com/docs/conversations/api/service-resource) the Message resource is associated with.
     * @param string $conversationSid The unique ID of the [Conversation](https://www.twilio.com/docs/conversations/api/conversation-resource) for this message.
     * @param string $messageSid The SID of the message within a [Conversation](https://www.twilio.com/docs/conversations/api/conversation-resource) the delivery receipt belongs to.
     * @param string $sid A 34 character string that uniquely identifies this resource.
     */
    public function __construct(Version $version, $chatServiceSid, $conversationSid, $messageSid, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['chatServiceSid' => $chatServiceSid, 'conversationSid' => $conversationSid, 'messageSid' => $messageSid, 'sid' => $sid];
        $this->uri = '/Services/' . \rawurlencode($chatServiceSid) . '/Conversations/' . \rawurlencode($conversationSid) . '/Messages/' . \rawurlencode($messageSid) . '/Receipts/' . \rawurlencode($sid) . '';
    }
    /**
     * Fetch the DeliveryReceiptInstance
     *
     * @return DeliveryReceiptInstance Fetched DeliveryReceiptInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : DeliveryReceiptInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new DeliveryReceiptInstance($this->version, $payload, $this->solution['chatServiceSid'], $this->solution['conversationSid'], $this->solution['messageSid'], $this->solution['sid']);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "{$key}={$value}";
        }
        return '[Twilio.Conversations.V1.DeliveryReceiptContext ' . \implode(' ', $context) . ']';
    }
}
