<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Microvisor\V1;
class Microvisor extends MicrovisorBase
{
    /**
     * @deprecated Use v1->apps instead.
     */
    protected function getApps() : \Isolated\Twilio\Rest\Microvisor\V1\AppList
    {
        echo "apps is deprecated. Use v1->apps instead.";
        return $this->v1->apps;
    }
    /**
     * @deprecated Use v1->apps(\$sid) instead.
     * @param string $sid A string that uniquely identifies this App.
     */
    protected function contextApps(string $sid) : \Isolated\Twilio\Rest\Microvisor\V1\AppContext
    {
        echo "apps(\$sid) is deprecated. Use v1->apps(\$sid) instead.";
        return $this->v1->apps($sid);
    }
    /**
     * @deprecated Use v1->devices instead.
     */
    protected function getDevices() : \Isolated\Twilio\Rest\Microvisor\V1\DeviceList
    {
        echo "devices is deprecated. Use v1->devices instead.";
        return $this->v1->devices;
    }
    /**
     * @deprecated Use v1->devices(\$sid) instead.
     * @param string $sid A string that uniquely identifies this Device.
     */
    protected function contextDevices(string $sid) : \Isolated\Twilio\Rest\Microvisor\V1\DeviceContext
    {
        echo "devices(\$sid) is deprecated. Use v1->devices(\$sid) instead.";
        return $this->v1->devices($sid);
    }
}
