<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Ip_messaging
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\IpMessaging\V2\Service\Channel;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class InviteOptions
{
    /**
     * @param string $roleSid 
     * @return CreateInviteOptions Options builder
     */
    public static function create(string $roleSid = Values::NONE) : CreateInviteOptions
    {
        return new CreateInviteOptions($roleSid);
    }
    /**
     * @param string[] $identity 
     * @return ReadInviteOptions Options builder
     */
    public static function read(array $identity = Values::ARRAY_NONE) : ReadInviteOptions
    {
        return new ReadInviteOptions($identity);
    }
}
class CreateInviteOptions extends Options
{
    /**
     * @param string $roleSid 
     */
    public function __construct(string $roleSid = Values::NONE)
    {
        $this->options['roleSid'] = $roleSid;
    }
    /**
     * 
     *
     * @param string $roleSid 
     * @return $this Fluent Builder
     */
    public function setRoleSid(string $roleSid) : self
    {
        $this->options['roleSid'] = $roleSid;
        return $this;
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.IpMessaging.V2.CreateInviteOptions ' . $options . ']';
    }
}
class ReadInviteOptions extends Options
{
    /**
     * @param string[] $identity 
     */
    public function __construct(array $identity = Values::ARRAY_NONE)
    {
        $this->options['identity'] = $identity;
    }
    /**
     * 
     *
     * @param string[] $identity 
     * @return $this Fluent Builder
     */
    public function setIdentity(array $identity) : self
    {
        $this->options['identity'] = $identity;
        return $this;
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.IpMessaging.V2.ReadInviteOptions ' . $options . ']';
    }
}
