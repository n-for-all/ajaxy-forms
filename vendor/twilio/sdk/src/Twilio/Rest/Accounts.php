<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Accounts\V1;
class Accounts extends AccountsBase
{
    /**
     * @deprecated Use v1->authTokenPromotion instead
     */
    protected function getAuthTokenPromotion() : \Isolated\Twilio\Rest\Accounts\V1\AuthTokenPromotionList
    {
        echo "authTokenPromotion is deprecated. Use v1->authTokenPromotion instead.";
        return $this->v1->authTokenPromotion;
    }
    /**
     * @deprecated Use v1->authTokenPromotion() instead.
     */
    protected function contextAuthTokenPromotion() : \Isolated\Twilio\Rest\Accounts\V1\AuthTokenPromotionContext
    {
        echo "authTokenPromotion() is deprecated. Use v1->authTokenPromotion() instead.";
        return $this->v1->authTokenPromotion();
    }
    /**
     * @deprecated Use v1->credentials instead.
     */
    protected function getCredentials() : \Isolated\Twilio\Rest\Accounts\V1\CredentialList
    {
        echo "credentials is deprecated. Use v1->credentials instead.";
        return $this->v1->credentials;
    }
    /**
     * @deprecated Use v1->secondaryAuthToken instead.
     */
    protected function getSecondaryAuthToken() : \Isolated\Twilio\Rest\Accounts\V1\SecondaryAuthTokenList
    {
        echo "secondaryAuthToken is deprecated. Use v1->secondaryAuthToken instead.";
        return $this->v1->secondaryAuthToken;
    }
    /**
     * @deprecated Use v1->secondaryAuthToken() instead.
     */
    protected function contextSecondaryAuthToken() : \Isolated\Twilio\Rest\Accounts\V1\SecondaryAuthTokenContext
    {
        echo "secondaryAuthToken() is deprecated. Use v1->secondaryAuthToken() instead.";
        return $this->v1->secondaryAuthToken();
    }
}
