<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Numbers\V2;
class Numbers extends NumbersBase
{
    /**
     * @deprecated Use v2->regulatoryCompliance instead.
     */
    protected function getRegulatoryCompliance() : \Isolated\Twilio\Rest\Numbers\V2\RegulatoryComplianceList
    {
        echo "regulatoryCompliance is deprecated. Use v2->regulatoryCompliance instead.";
        return $this->v2->regulatoryCompliance;
    }
}
