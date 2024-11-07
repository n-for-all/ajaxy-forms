<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Oauth\V1;
class Oauth extends OauthBase
{
    /**
     * @deprecated Use v1->oauth instead.
     */
    protected function getOauth() : \Isolated\Twilio\Rest\Oauth\V1\OauthList
    {
        echo "oauth is deprecated. Use v1->oauth instead.";
        return $this->v1->oauth;
    }
    /**
     * @deprecated Use v1->oauth() instead.
     */
    protected function contextOauth() : \Isolated\Twilio\Rest\Oauth\V1\OauthContext
    {
        echo "oauth() is deprecated. Use v1->oauth() instead.";
        return $this->v1->oauth();
    }
    /**
     * @deprecated Use v1->deviceCode instead.
     */
    protected function getDeviceCode() : \Isolated\Twilio\Rest\Oauth\V1\DeviceCodeList
    {
        echo "deviceCode is deprecated. Use v1->deviceCode instead.";
        return $this->v1->deviceCode;
    }
    /**
     * @deprecated Use v1->openidDiscovery instead.
     */
    protected function getOpenidDiscovery() : \Isolated\Twilio\Rest\Oauth\V1\OpenidDiscoveryList
    {
        echo "openidDiscovery is deprecated. Use v1->openidDiscovery instead.";
        return $this->v1->openidDiscovery;
    }
    /**
     * @deprecated Use v1->openidDiscovery() instead.
     */
    protected function contextOpenidDiscovery() : \Isolated\Twilio\Rest\Oauth\V1\OpenidDiscoveryContext
    {
        echo "openidDiscovery() is deprecated. Use v1->openidDiscovery() instead.";
        return $this->v1->openidDiscovery();
    }
    /**
     * @deprecated Use v1->token instead.
     */
    protected function getToken() : \Isolated\Twilio\Rest\Oauth\V1\TokenList
    {
        echo "token is deprecated. Use v1->token instead.";
        return $this->v1->token;
    }
    /**
     * @deprecated Use v1->userInfo instead.
     */
    protected function getUserInfo() : \Isolated\Twilio\Rest\Oauth\V1\UserInfoList
    {
        echo "userInfo is deprecated. Use v1->userInfo instead.";
        return $this->v1->userInfo;
    }
    /**
     * @deprecated Use v1->userInfo() instead.
     */
    protected function contextUserInfo() : \Isolated\Twilio\Rest\Oauth\V1\UserInfoContext
    {
        echo "userInfo() is deprecated. Use v1->userInfo() instead.";
        return $this->v1->userInfo();
    }
}
