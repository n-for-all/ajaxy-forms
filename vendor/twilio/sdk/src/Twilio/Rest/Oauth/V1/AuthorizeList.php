<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Oauth
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Oauth\V1;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
class AuthorizeList extends ListResource
{
    /**
     * Construct the AuthorizeList
     *
     * @param Version $version Version that contains the resource
     */
    public function __construct(Version $version)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = [];
        $this->uri = '/authorize';
    }
    /**
     * Fetch the AuthorizeInstance
     *
     * @param array|Options $options Optional Arguments
     * @return AuthorizeInstance Fetched AuthorizeInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []) : AuthorizeInstance
    {
        $options = new Values($options);
        $params = Values::of(['ResponseType' => $options['responseType'], 'ClientId' => $options['clientId'], 'RedirectUri' => $options['redirectUri'], 'Scope' => $options['scope'], 'State' => $options['state']]);
        $payload = $this->version->fetch('GET', $this->uri, $params, []);
        return new AuthorizeInstance($this->version, $payload);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Oauth.V1.AuthorizeList]';
    }
}
