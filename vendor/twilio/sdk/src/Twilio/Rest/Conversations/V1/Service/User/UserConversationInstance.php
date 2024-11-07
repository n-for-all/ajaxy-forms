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
namespace Isolated\Twilio\Rest\Conversations\V1\Service\User;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $accountSid
 * @property string|null $chatServiceSid
 * @property string|null $conversationSid
 * @property int|null $unreadMessagesCount
 * @property int|null $lastReadMessageIndex
 * @property string|null $participantSid
 * @property string|null $userSid
 * @property string|null $friendlyName
 * @property string $conversationState
 * @property array|null $timers
 * @property string|null $attributes
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property string|null $createdBy
 * @property string $notificationLevel
 * @property string|null $uniqueName
 * @property string|null $url
 * @property array|null $links
 */
class UserConversationInstance extends InstanceResource
{
    /**
     * Initialize the UserConversationInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $chatServiceSid The SID of the [Conversation Service](https://www.twilio.com/docs/conversations/api/service-resource) the Conversation resource is associated with.
     * @param string $userSid The unique SID identifier of the [User resource](https://www.twilio.com/docs/conversations/api/user-resource). This value can be either the `sid` or the `identity` of the User resource.
     * @param string $conversationSid The unique SID identifier of the Conversation. This value can be either the `sid` or the `unique_name` of the [Conversation resource](https://www.twilio.com/docs/conversations/api/conversation-resource).
     */
    public function __construct(Version $version, array $payload, string $chatServiceSid, string $userSid, string $conversationSid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['accountSid' => Values::array_get($payload, 'account_sid'), 'chatServiceSid' => Values::array_get($payload, 'chat_service_sid'), 'conversationSid' => Values::array_get($payload, 'conversation_sid'), 'unreadMessagesCount' => Values::array_get($payload, 'unread_messages_count'), 'lastReadMessageIndex' => Values::array_get($payload, 'last_read_message_index'), 'participantSid' => Values::array_get($payload, 'participant_sid'), 'userSid' => Values::array_get($payload, 'user_sid'), 'friendlyName' => Values::array_get($payload, 'friendly_name'), 'conversationState' => Values::array_get($payload, 'conversation_state'), 'timers' => Values::array_get($payload, 'timers'), 'attributes' => Values::array_get($payload, 'attributes'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')), 'createdBy' => Values::array_get($payload, 'created_by'), 'notificationLevel' => Values::array_get($payload, 'notification_level'), 'uniqueName' => Values::array_get($payload, 'unique_name'), 'url' => Values::array_get($payload, 'url'), 'links' => Values::array_get($payload, 'links')];
        $this->solution = ['chatServiceSid' => $chatServiceSid, 'userSid' => $userSid, 'conversationSid' => $conversationSid ?: $this->properties['conversationSid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return UserConversationContext Context for this UserConversationInstance
     */
    protected function proxy() : UserConversationContext
    {
        if (!$this->context) {
            $this->context = new UserConversationContext($this->version, $this->solution['chatServiceSid'], $this->solution['userSid'], $this->solution['conversationSid']);
        }
        return $this->context;
    }
    /**
     * Delete the UserConversationInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the UserConversationInstance
     *
     * @return UserConversationInstance Fetched UserConversationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : UserConversationInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the UserConversationInstance
     *
     * @param array|Options $options Optional Arguments
     * @return UserConversationInstance Updated UserConversationInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : UserConversationInstance
    {
        return $this->proxy()->update($options);
    }
    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->{$method}();
        }
        throw new TwilioException('Unknown property: ' . $name);
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
        return '[Twilio.Conversations.V1.UserConversationInstance ' . \implode(' ', $context) . ']';
    }
}
