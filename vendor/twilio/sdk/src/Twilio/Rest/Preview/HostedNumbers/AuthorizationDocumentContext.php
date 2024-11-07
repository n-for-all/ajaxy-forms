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
namespace Isolated\Twilio\Rest\Preview\HostedNumbers;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
use Isolated\Twilio\Serialize;
use Isolated\Twilio\Rest\Preview\HostedNumbers\AuthorizationDocument\DependentHostedNumberOrderList;
/**
 * @property DependentHostedNumberOrderList $dependentHostedNumberOrders
 */
class AuthorizationDocumentContext extends InstanceContext
{
    protected $_dependentHostedNumberOrders;
    /**
     * Initialize the AuthorizationDocumentContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid A 34 character string that uniquely identifies this AuthorizationDocument.
     */
    public function __construct(Version $version, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['sid' => $sid];
        $this->uri = '/AuthorizationDocuments/' . \rawurlencode($sid) . '';
    }
    /**
     * Fetch the AuthorizationDocumentInstance
     *
     * @return AuthorizationDocumentInstance Fetched AuthorizationDocumentInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : AuthorizationDocumentInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new AuthorizationDocumentInstance($this->version, $payload, $this->solution['sid']);
    }
    /**
     * Update the AuthorizationDocumentInstance
     *
     * @param array|Options $options Optional Arguments
     * @return AuthorizationDocumentInstance Updated AuthorizationDocumentInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : AuthorizationDocumentInstance
    {
        $options = new Values($options);
        $data = Values::of(['HostedNumberOrderSids' => Serialize::map($options['hostedNumberOrderSids'], function ($e) {
            return $e;
        }), 'AddressSid' => $options['addressSid'], 'Email' => $options['email'], 'CcEmails' => Serialize::map($options['ccEmails'], function ($e) {
            return $e;
        }), 'Status' => $options['status'], 'ContactTitle' => $options['contactTitle'], 'ContactPhoneNumber' => $options['contactPhoneNumber']]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new AuthorizationDocumentInstance($this->version, $payload, $this->solution['sid']);
    }
    /**
     * Access the dependentHostedNumberOrders
     */
    protected function getDependentHostedNumberOrders() : DependentHostedNumberOrderList
    {
        if (!$this->_dependentHostedNumberOrders) {
            $this->_dependentHostedNumberOrders = new DependentHostedNumberOrderList($this->version, $this->solution['sid']);
        }
        return $this->_dependentHostedNumberOrders;
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
        return '[Twilio.Preview.HostedNumbers.AuthorizationDocumentContext ' . \implode(' ', $context) . ']';
    }
}
