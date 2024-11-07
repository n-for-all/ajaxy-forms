<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Studio\V2;
class Studio extends StudioBase
{
    /**
     * @deprecated Use v2->flows instead.
     */
    protected function getFlows() : \Isolated\Twilio\Rest\Studio\V2\FlowList
    {
        echo "flows is deprecated. Use v2->flows instead.";
        return $this->v2->flows;
    }
    /**
     * @deprecated Use v2->flows(\$sid) instead.
     * @param string $sid The SID that identifies the resource to fetch
     */
    protected function contextFlows(string $sid) : \Isolated\Twilio\Rest\Studio\V2\FlowContext
    {
        echo "flows(\$sid) is deprecated. Use v2->flows(\$sid) instead.";
        return $this->v2->flows($sid);
    }
    /**
     * @deprecated Use v2->flowValidate instead.
     */
    protected function getFlowValidate() : \Isolated\Twilio\Rest\Studio\V2\FlowValidateList
    {
        echo "flowValidate is deprecated. Use v2->flowValidate instead.";
        return $this->v2->flowValidate;
    }
}
