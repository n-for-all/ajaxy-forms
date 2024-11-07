<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Lookups
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Lookups\V2;

use Isolated\Twilio\ListResource;
use Isolated\Twilio\Version;
class PhoneNumberList extends ListResource
{
    /**
     * Construct the PhoneNumberList
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
     * Constructs a PhoneNumberContext
     *
     * @param string $phoneNumber The phone number to lookup in E.164 or national format. Default country code is +1 (North America).
     */
    public function getContext(string $phoneNumber) : PhoneNumberContext
    {
        return new PhoneNumberContext($this->version, $phoneNumber);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Lookups.V2.PhoneNumberList]';
    }
}
