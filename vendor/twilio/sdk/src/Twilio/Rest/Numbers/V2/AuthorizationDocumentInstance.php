<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Numbers
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Numbers\V2;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
use Isolated\Twilio\Rest\Numbers\V2\AuthorizationDocument\DependentHostedNumberOrderList;
/**
 * @property string|null $sid
 * @property string|null $addressSid
 * @property string $status
 * @property string|null $email
 * @property string[]|null $ccEmails
 * @property \DateTime|null $dateCreated
 * @property \DateTime|null $dateUpdated
 * @property string|null $url
 * @property array|null $links
 */
class AuthorizationDocumentInstance extends InstanceResource
{
    protected $_dependentHostedNumberOrders;
    /**
     * Initialize the AuthorizationDocumentInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $sid A 34 character string that uniquely identifies this AuthorizationDocument.
     */
    public function __construct(Version $version, array $payload, string $sid = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['sid' => Values::array_get($payload, 'sid'), 'addressSid' => Values::array_get($payload, 'address_sid'), 'status' => Values::array_get($payload, 'status'), 'email' => Values::array_get($payload, 'email'), 'ccEmails' => Values::array_get($payload, 'cc_emails'), 'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')), 'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')), 'url' => Values::array_get($payload, 'url'), 'links' => Values::array_get($payload, 'links')];
        $this->solution = ['sid' => $sid ?: $this->properties['sid']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return AuthorizationDocumentContext Context for this AuthorizationDocumentInstance
     */
    protected function proxy() : AuthorizationDocumentContext
    {
        if (!$this->context) {
            $this->context = new AuthorizationDocumentContext($this->version, $this->solution['sid']);
        }
        return $this->context;
    }
    /**
     * Delete the AuthorizationDocumentInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the AuthorizationDocumentInstance
     *
     * @return AuthorizationDocumentInstance Fetched AuthorizationDocumentInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : AuthorizationDocumentInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Access the dependentHostedNumberOrders
     */
    protected function getDependentHostedNumberOrders() : DependentHostedNumberOrderList
    {
        return $this->proxy()->dependentHostedNumberOrders;
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
        return '[Twilio.Numbers.V2.AuthorizationDocumentInstance ' . \implode(' ', $context) . ']';
    }
}
