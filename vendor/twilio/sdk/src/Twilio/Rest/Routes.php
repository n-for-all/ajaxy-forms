<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Routes\V2;
class Routes extends RoutesBase
{
    /**
     * @deprecated Use v2->phoneNumbers instead.
     */
    protected function getPhoneNumbers() : \Isolated\Twilio\Rest\Routes\V2\PhoneNumberList
    {
        echo "phoneNumbers is deprecated. Use v2->phoneNumbers instead.";
        return $this->v2->phoneNumbers;
    }
    /**
     * @deprecated  Use v2->phoneNumbers(\$phoneNumber) instead.
     * @param string $phoneNumber The phone number
     */
    protected function contextPhoneNumbers(string $phoneNumber) : \Isolated\Twilio\Rest\Routes\V2\PhoneNumberContext
    {
        echo "phoneNumbers(\$phoneNumber) is deprecated. Use v2->phoneNumbers(\$phoneNumber) instead.";
        return $this->v2->phoneNumbers($phoneNumber);
    }
    /**
     * @deprecated Use v2->sipDomains instead.
     */
    protected function getSipDomains() : \Isolated\Twilio\Rest\Routes\V2\SipDomainList
    {
        echo "sipDomains is deprecated. Use v2->sipDomains instead.";
        return $this->v2->sipDomains;
    }
    /**
     * @deprecated Use v2->sipDomains(\$sipDomain) instead.
     * @param string $sipDomain The sip_domain
     */
    protected function contextSipDomains(string $sipDomain) : \Isolated\Twilio\Rest\Routes\V2\SipDomainContext
    {
        echo "sipDomains(\$sipDomain) is deprecated. Use v2->sipDomains(\$sipDomain) instead.";
        return $this->v2->sipDomains($sipDomain);
    }
    /**
     * @deprecated Use v2->trunks instead.
     */
    protected function getTrunks() : \Isolated\Twilio\Rest\Routes\V2\TrunkList
    {
        echo "trunks is deprecated. Use v2->trunks instead.";
        return $this->v2->trunks;
    }
    /**
     * @deprecated Use v2->trunks(\$sipTrunkDomain instead.
     * @param string $sipTrunkDomain The SIP Trunk
     */
    protected function contextTrunks(string $sipTrunkDomain) : \Isolated\Twilio\Rest\Routes\V2\TrunkContext
    {
        echo "trunks(\$sipTrunkDomain) is deprecated. Use v2->trunks(\$sipTrunkDomain instead.";
        return $this->v2->trunks($sipTrunkDomain);
    }
}
