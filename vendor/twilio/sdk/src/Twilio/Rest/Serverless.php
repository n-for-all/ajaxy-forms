<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Serverless\V1;
class Serverless extends ServerlessBase
{
    /**
     * @deprecated Use v1->services instead.
     */
    protected function getServices() : \Isolated\Twilio\Rest\Serverless\V1\ServiceList
    {
        echo "services is deprecated. Use v1->services instead.";
        return $this->v1->services;
    }
    /**
     * @deprecated Use v1->services(\$sid) instead.
     * @param string $sid The SID of the Service resource to fetch
     */
    protected function contextServices(string $sid) : \Isolated\Twilio\Rest\Serverless\V1\ServiceContext
    {
        echo "services(\$sid) is deprecated. Use v1->services(\$sid) instead.";
        return $this->v1->services($sid);
    }
}
