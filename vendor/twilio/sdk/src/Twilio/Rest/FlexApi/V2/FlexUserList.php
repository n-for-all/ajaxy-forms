<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Flex
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\FlexApi\V2;

use Isolated\Twilio\ListResource;
use Isolated\Twilio\Version;
class FlexUserList extends ListResource
{
    /**
     * Construct the FlexUserList
     *
     * @param Version $version Version that contains the resource
     */
    public function __construct(Version $version)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = [];
    }
    /**
     * Constructs a FlexUserContext
     *
     * @param string $instanceSid The unique ID created by Twilio to identify a Flex instance.
     *
     * @param string $flexUserSid The unique id for the flex user to be retrieved.
     */
    public function getContext(string $instanceSid, string $flexUserSid) : FlexUserContext
    {
        return new FlexUserContext($this->version, $instanceSid, $flexUserSid);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.FlexApi.V2.FlexUserList]';
    }
}
