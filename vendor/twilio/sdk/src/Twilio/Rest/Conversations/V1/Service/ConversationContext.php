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
namespace Isolated\Twilio\Rest\Conversations\V1\Service;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
use Isolated\Twilio\Serialize;
use Isolated\Twilio\Rest\Conversations\V1\Service\Conversation\WebhookList;
use Isolated\Twilio\Rest\Conversations\V1\Service\Conversation\ParticipantList;
use Isolated\Twilio\Rest\Conversations\V1\Service\Conversation\MessageList;
/**
 * @property WebhookList $webhooks
 * @property ParticipantList $participants
 * @property MessageList $messages
 * @method \Twilio\Rest\Conversations\V1\Service\Conversation\ParticipantContext participants(string $sid)
 * @method \Twilio\Rest\Conversations\V1\Service\Conversation\MessageContext messages(string $sid)
 * @method \Twilio\Rest\Conversations\V1\Service\Conversation\WebhookContext webhooks(string $sid)
 */
class ConversationContext extends InstanceContext
{
    protected $_webhooks;
    protected $_participants;
    protected $_messages;
    /**
     * Initialize the ConversationContext
     *
     * @param Version $version Version that contains the resource
     * @param string $chatServiceSid The SID of the [Conversation Service](https://www.twilio.com/docs/conversations/api/service-resource) the Conversation resource is associated with.
     * @param string $sid A 34 character string that uniquely identifies this resource. Can also be the `unique_name` of the Conversation.
     */
    public function __construct(Version $version, $chatServiceSid, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['chatServiceSid' => $chatServiceSid, 'sid' => $sid];
        $this->uri = '/Services/' . \rawurlencode($chatServiceSid) . '/Conversations/' . \rawurlencode($sid) . '';
    }
    /**
     * Delete the ConversationInstance
     *
     * @param array|Options $options Optional Arguments
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete(array $options = []) : bool
    {
        $options = new Values($options);
        $headers = Values::of(['X-Twilio-Webhook-Enabled' => $options['xTwilioWebhookEnabled']]);
        return $this->version->delete('DELETE', $this->uri, [], [], $headers);
    }
    /**
     * Fetch the ConversationInstance
     *
     * @return ConversationInstance Fetched ConversationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : ConversationInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new ConversationInstance($this->version, $payload, $this->solution['chatServiceSid'], $this->solution['sid']);
    }
    /**
     * Update the ConversationInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ConversationInstance Updated ConversationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : ConversationInstance
    {
        $options = new Values($options);
        $data = Values::of(['FriendlyName' => $options['friendlyName'], 'DateCreated' => Serialize::iso8601DateTime($options['dateCreated']), 'DateUpdated' => Serialize::iso8601DateTime($options['dateUpdated']), 'Attributes' => $options['attributes'], 'MessagingServiceSid' => $options['messagingServiceSid'], 'State' => $options['state'], 'Timers.Inactive' => $options['timersInactive'], 'Timers.Closed' => $options['timersClosed'], 'UniqueName' => $options['uniqueName'], 'Bindings.Email.Address' => $options['bindingsEmailAddress'], 'Bindings.Email.Name' => $options['bindingsEmailName']]);
        $headers = Values::of(['X-Twilio-Webhook-Enabled' => $options['xTwilioWebhookEnabled']]);
        $payload = $this->version->update('POST', $this->uri, [], $data, $headers);
        return new ConversationInstance($this->version, $payload, $this->solution['chatServiceSid'], $this->solution['sid']);
    }
    /**
     * Access the webhooks
     */
    protected function getWebhooks() : WebhookList
    {
        if (!$this->_webhooks) {
            $this->_webhooks = new WebhookList($this->version, $this->solution['chatServiceSid'], $this->solution['sid']);
        }
        return $this->_webhooks;
    }
    /**
     * Access the participants
     */
    protected function getParticipants() : ParticipantList
    {
        if (!$this->_participants) {
            $this->_participants = new ParticipantList($this->version, $this->solution['chatServiceSid'], $this->solution['sid']);
        }
        return $this->_participants;
    }
    /**
     * Access the messages
     */
    protected function getMessages() : MessageList
    {
        if (!$this->_messages) {
            $this->_messages = new MessageList($this->version, $this->solution['chatServiceSid'], $this->solution['sid']);
        }
        return $this->_messages;
    }
    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get(string $name) : ListResource
    {
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->{$method}();
        }
        throw new TwilioException('Unknown subresource ' . $name);
    }
    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call(string $name, array $arguments) : InstanceContext
    {
        $property = $this->{$name};
        if (\method_exists($property, 'getContext')) {
            return \call_user_func_array(array($property, 'getContext'), $arguments);
        }
        throw new TwilioException('Resource does not have a context');
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
        return '[Twilio.Conversations.V1.ConversationContext ' . \implode(' ', $context) . ']';
    }
}
