<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Messaging
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Messaging\V1;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
use Isolated\Twilio\Serialize;
use Isolated\Twilio\Rest\Messaging\V1\Service\AlphaSenderList;
use Isolated\Twilio\Rest\Messaging\V1\Service\PhoneNumberList;
use Isolated\Twilio\Rest\Messaging\V1\Service\UsAppToPersonUsecaseList;
use Isolated\Twilio\Rest\Messaging\V1\Service\ChannelSenderList;
use Isolated\Twilio\Rest\Messaging\V1\Service\ShortCodeList;
use Isolated\Twilio\Rest\Messaging\V1\Service\UsAppToPersonList;
/**
 * @property AlphaSenderList $alphaSenders
 * @property PhoneNumberList $phoneNumbers
 * @property UsAppToPersonUsecaseList $usAppToPersonUsecases
 * @property ChannelSenderList $channelSenders
 * @property ShortCodeList $shortCodes
 * @property UsAppToPersonList $usAppToPerson
 * @method \Twilio\Rest\Messaging\V1\Service\ShortCodeContext shortCodes(string $sid)
 * @method \Twilio\Rest\Messaging\V1\Service\UsAppToPersonContext usAppToPerson(string $sid)
 * @method \Twilio\Rest\Messaging\V1\Service\PhoneNumberContext phoneNumbers(string $sid)
 * @method \Twilio\Rest\Messaging\V1\Service\AlphaSenderContext alphaSenders(string $sid)
 * @method \Twilio\Rest\Messaging\V1\Service\ChannelSenderContext channelSenders(string $sid)
 */
class ServiceContext extends InstanceContext
{
    protected $_alphaSenders;
    protected $_phoneNumbers;
    protected $_usAppToPersonUsecases;
    protected $_channelSenders;
    protected $_shortCodes;
    protected $_usAppToPerson;
    /**
     * Initialize the ServiceContext
     *
     * @param Version $version Version that contains the resource
     * @param string $sid The SID of the Service resource to delete.
     */
    public function __construct(Version $version, $sid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['sid' => $sid];
        $this->uri = '/Services/' . \rawurlencode($sid) . '';
    }
    /**
     * Delete the ServiceInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->version->delete('DELETE', $this->uri);
    }
    /**
     * Fetch the ServiceInstance
     *
     * @return ServiceInstance Fetched ServiceInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : ServiceInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new ServiceInstance($this->version, $payload, $this->solution['sid']);
    }
    /**
     * Update the ServiceInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ServiceInstance Updated ServiceInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : ServiceInstance
    {
        $options = new Values($options);
        $data = Values::of(['FriendlyName' => $options['friendlyName'], 'InboundRequestUrl' => $options['inboundRequestUrl'], 'InboundMethod' => $options['inboundMethod'], 'FallbackUrl' => $options['fallbackUrl'], 'FallbackMethod' => $options['fallbackMethod'], 'StatusCallback' => $options['statusCallback'], 'StickySender' => Serialize::booleanToString($options['stickySender']), 'MmsConverter' => Serialize::booleanToString($options['mmsConverter']), 'SmartEncoding' => Serialize::booleanToString($options['smartEncoding']), 'ScanMessageContent' => $options['scanMessageContent'], 'FallbackToLongCode' => Serialize::booleanToString($options['fallbackToLongCode']), 'AreaCodeGeomatch' => Serialize::booleanToString($options['areaCodeGeomatch']), 'ValidityPeriod' => $options['validityPeriod'], 'SynchronousValidation' => Serialize::booleanToString($options['synchronousValidation']), 'Usecase' => $options['usecase'], 'UseInboundWebhookOnNumber' => Serialize::booleanToString($options['useInboundWebhookOnNumber'])]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new ServiceInstance($this->version, $payload, $this->solution['sid']);
    }
    /**
     * Access the alphaSenders
     */
    protected function getAlphaSenders() : AlphaSenderList
    {
        if (!$this->_alphaSenders) {
            $this->_alphaSenders = new AlphaSenderList($this->version, $this->solution['sid']);
        }
        return $this->_alphaSenders;
    }
    /**
     * Access the phoneNumbers
     */
    protected function getPhoneNumbers() : PhoneNumberList
    {
        if (!$this->_phoneNumbers) {
            $this->_phoneNumbers = new PhoneNumberList($this->version, $this->solution['sid']);
        }
        return $this->_phoneNumbers;
    }
    /**
     * Access the usAppToPersonUsecases
     */
    protected function getUsAppToPersonUsecases() : UsAppToPersonUsecaseList
    {
        if (!$this->_usAppToPersonUsecases) {
            $this->_usAppToPersonUsecases = new UsAppToPersonUsecaseList($this->version, $this->solution['sid']);
        }
        return $this->_usAppToPersonUsecases;
    }
    /**
     * Access the channelSenders
     */
    protected function getChannelSenders() : ChannelSenderList
    {
        if (!$this->_channelSenders) {
            $this->_channelSenders = new ChannelSenderList($this->version, $this->solution['sid']);
        }
        return $this->_channelSenders;
    }
    /**
     * Access the shortCodes
     */
    protected function getShortCodes() : ShortCodeList
    {
        if (!$this->_shortCodes) {
            $this->_shortCodes = new ShortCodeList($this->version, $this->solution['sid']);
        }
        return $this->_shortCodes;
    }
    /**
     * Access the usAppToPerson
     */
    protected function getUsAppToPerson() : UsAppToPersonList
    {
        if (!$this->_usAppToPerson) {
            $this->_usAppToPerson = new UsAppToPersonList($this->version, $this->solution['sid']);
        }
        return $this->_usAppToPerson;
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
        return '[Twilio.Messaging.V1.ServiceContext ' . \implode(' ', $context) . ']';
    }
}
