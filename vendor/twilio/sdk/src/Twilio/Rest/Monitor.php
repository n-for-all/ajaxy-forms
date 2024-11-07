<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Monitor\V1;
class Monitor extends MonitorBase
{
    /**
     * @deprecated Use v1->alerts instead.
     */
    protected function getAlerts() : \Isolated\Twilio\Rest\Monitor\V1\AlertList
    {
        echo "alerts is deprecated. Use v1->alerts instead.";
        return $this->v1->alerts;
    }
    /**
     * @deprecated Use v1->alerts(\$sid) instead.
     * @param string $sid The SID that identifies the resource to fetch
     */
    protected function contextAlerts(string $sid) : \Isolated\Twilio\Rest\Monitor\V1\AlertContext
    {
        echo "alerts(\$sid) is deprecated. Use v1->alerts(\$sid) instead.";
        return $this->v1->alerts($sid);
    }
    /**
     * @deprecated Use v1->events instead.
     */
    protected function getEvents() : \Isolated\Twilio\Rest\Monitor\V1\EventList
    {
        echo "events is deprecated. Use v1->events instead.";
        return $this->v1->events;
    }
    /**
     * @deprecated Use v1->events(\$sid) instead.
     * @param string $sid The SID that identifies the resource to fetch
     */
    protected function contextEvents(string $sid) : \Isolated\Twilio\Rest\Monitor\V1\EventContext
    {
        echo "events(\$sid) is deprecated. Use v1->events(\$sid) instead.";
        return $this->v1->events($sid);
    }
}
