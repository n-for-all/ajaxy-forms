<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Trusthub
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Trusthub\V1;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
/**
 * @property string|null $inquiryId
 * @property string|null $inquirySessionToken
 * @property string|null $registrationId
 * @property string|null $url
 */
class ComplianceRegistrationInquiriesInstance extends InstanceResource
{
    /**
     * Initialize the ComplianceRegistrationInquiriesInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $registrationId The unique RegistrationId matching the Regulatory Compliance Inquiry that should be resumed or resubmitted. This value will have been returned by the initial Regulatory Compliance Inquiry creation call.
     */
    public function __construct(Version $version, array $payload, string $registrationId = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['inquiryId' => Values::array_get($payload, 'inquiry_id'), 'inquirySessionToken' => Values::array_get($payload, 'inquiry_session_token'), 'registrationId' => Values::array_get($payload, 'registration_id'), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['registrationId' => $registrationId ?: $this->properties['registrationId']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return ComplianceRegistrationInquiriesContext Context for this ComplianceRegistrationInquiriesInstance
     */
    protected function proxy() : ComplianceRegistrationInquiriesContext
    {
        if (!$this->context) {
            $this->context = new ComplianceRegistrationInquiriesContext($this->version, $this->solution['registrationId']);
        }
        return $this->context;
    }
    /**
     * Update the ComplianceRegistrationInquiriesInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ComplianceRegistrationInquiriesInstance Updated ComplianceRegistrationInquiriesInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []) : ComplianceRegistrationInquiriesInstance
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
        return '[Twilio.Trusthub.V1.ComplianceRegistrationInquiriesInstance ' . \implode(' ', $context) . ']';
    }
}
