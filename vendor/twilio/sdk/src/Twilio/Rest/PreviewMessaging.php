<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\PreviewMessaging\V1;
class PreviewMessaging extends PreviewMessagingBase
{
    /**
     * @deprecated Use v1->oauth instead.
     */
    protected function getMessages() : \Isolated\Twilio\Rest\PreviewMessaging\V1\MessageList
    {
        return $this->v1->messages;
    }
    /**
     * @deprecated Use v1->oauth() instead.
     */
    protected function getBroadcasts() : \Isolated\Twilio\Rest\PreviewMessaging\V1\BroadcastList
    {
        return $this->v1->broadcasts;
    }
}
