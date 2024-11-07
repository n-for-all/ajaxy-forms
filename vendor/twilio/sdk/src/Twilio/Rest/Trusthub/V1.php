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
namespace Isolated\Twilio\Rest\Trusthub;

use Isolated\Twilio\Domain;
use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceContext;
use Isolated\Twilio\Rest\Trusthub\V1\ComplianceInquiriesList;
use Isolated\Twilio\Rest\Trusthub\V1\ComplianceRegistrationInquiriesList;
use Isolated\Twilio\Rest\Trusthub\V1\ComplianceTollfreeInquiriesList;
use Isolated\Twilio\Rest\Trusthub\V1\CustomerProfilesList;
use Isolated\Twilio\Rest\Trusthub\V1\EndUserList;
use Isolated\Twilio\Rest\Trusthub\V1\EndUserTypeList;
use Isolated\Twilio\Rest\Trusthub\V1\PoliciesList;
use Isolated\Twilio\Rest\Trusthub\V1\SupportingDocumentList;
use Isolated\Twilio\Rest\Trusthub\V1\SupportingDocumentTypeList;
use Isolated\Twilio\Rest\Trusthub\V1\TrustProductsList;
use Isolated\Twilio\Version;
/**
 * @property ComplianceInquiriesList $complianceInquiries
 * @property ComplianceRegistrationInquiriesList $complianceRegistrationInquiries
 * @property ComplianceTollfreeInquiriesList $complianceTollfreeInquiries
 * @property CustomerProfilesList $customerProfiles
 * @property EndUserList $endUsers
 * @property EndUserTypeList $endUserTypes
 * @property PoliciesList $policies
 * @property SupportingDocumentList $supportingDocuments
 * @property SupportingDocumentTypeList $supportingDocumentTypes
 * @property TrustProductsList $trustProducts
 * @method \Twilio\Rest\Trusthub\V1\CustomerProfilesContext customerProfiles(string $sid)
 * @method \Twilio\Rest\Trusthub\V1\EndUserContext endUsers(string $sid)
 * @method \Twilio\Rest\Trusthub\V1\EndUserTypeContext endUserTypes(string $sid)
 * @method \Twilio\Rest\Trusthub\V1\PoliciesContext policies(string $sid)
 * @method \Twilio\Rest\Trusthub\V1\SupportingDocumentContext supportingDocuments(string $sid)
 * @method \Twilio\Rest\Trusthub\V1\SupportingDocumentTypeContext supportingDocumentTypes(string $sid)
 * @method \Twilio\Rest\Trusthub\V1\TrustProductsContext trustProducts(string $sid)
 */
class V1 extends Version
{
    protected $_complianceInquiries;
    protected $_complianceRegistrationInquiries;
    protected $_complianceTollfreeInquiries;
    protected $_customerProfiles;
    protected $_endUsers;
    protected $_endUserTypes;
    protected $_policies;
    protected $_supportingDocuments;
    protected $_supportingDocumentTypes;
    protected $_trustProducts;
    /**
     * Construct the V1 version of Trusthub
     *
     * @param Domain $domain Domain that contains the version
     */
    public function __construct(Domain $domain)
    {
        parent::__construct($domain);
        $this->version = 'v1';
    }
    protected function getComplianceInquiries() : ComplianceInquiriesList
    {
        if (!$this->_complianceInquiries) {
            $this->_complianceInquiries = new ComplianceInquiriesList($this);
        }
        return $this->_complianceInquiries;
    }
    protected function getComplianceRegistrationInquiries() : ComplianceRegistrationInquiriesList
    {
        if (!$this->_complianceRegistrationInquiries) {
            $this->_complianceRegistrationInquiries = new ComplianceRegistrationInquiriesList($this);
        }
        return $this->_complianceRegistrationInquiries;
    }
    protected function getComplianceTollfreeInquiries() : ComplianceTollfreeInquiriesList
    {
        if (!$this->_complianceTollfreeInquiries) {
            $this->_complianceTollfreeInquiries = new ComplianceTollfreeInquiriesList($this);
        }
        return $this->_complianceTollfreeInquiries;
    }
    protected function getCustomerProfiles() : CustomerProfilesList
    {
        if (!$this->_customerProfiles) {
            $this->_customerProfiles = new CustomerProfilesList($this);
        }
        return $this->_customerProfiles;
    }
    protected function getEndUsers() : EndUserList
    {
        if (!$this->_endUsers) {
            $this->_endUsers = new EndUserList($this);
        }
        return $this->_endUsers;
    }
    protected function getEndUserTypes() : EndUserTypeList
    {
        if (!$this->_endUserTypes) {
            $this->_endUserTypes = new EndUserTypeList($this);
        }
        return $this->_endUserTypes;
    }
    protected function getPolicies() : PoliciesList
    {
        if (!$this->_policies) {
            $this->_policies = new PoliciesList($this);
        }
        return $this->_policies;
    }
    protected function getSupportingDocuments() : SupportingDocumentList
    {
        if (!$this->_supportingDocuments) {
            $this->_supportingDocuments = new SupportingDocumentList($this);
        }
        return $this->_supportingDocuments;
    }
    protected function getSupportingDocumentTypes() : SupportingDocumentTypeList
    {
        if (!$this->_supportingDocumentTypes) {
            $this->_supportingDocumentTypes = new SupportingDocumentTypeList($this);
        }
        return $this->_supportingDocumentTypes;
    }
    protected function getTrustProducts() : TrustProductsList
    {
        if (!$this->_trustProducts) {
            $this->_trustProducts = new TrustProductsList($this);
        }
        return $this->_trustProducts;
    }
    /**
     * Magic getter to lazy load root resources
     *
     * @param string $name Resource to return
     * @return \Twilio\ListResource The requested resource
     * @throws TwilioException For unknown resource
     */
    public function __get(string $name)
    {
        $method = 'get' . \ucfirst($name);
        if (\method_exists($this, $method)) {
            return $this->{$method}();
        }
        throw new TwilioException('Unknown resource ' . $name);
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
        return '[Twilio.Trusthub.V1]';
    }
}
