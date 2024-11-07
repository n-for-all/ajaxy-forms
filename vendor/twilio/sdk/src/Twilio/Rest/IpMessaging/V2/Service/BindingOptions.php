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
namespace Isolated\Twilio\Rest\IpMessaging\V2\Service;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class BindingOptions
{
    /**
     * @param string $bindingType 
     * @param string[] $identity 
     * @return ReadBindingOptions Options builder
     */
    public static function read(array $bindingType = Values::ARRAY_NONE, array $identity = Values::ARRAY_NONE) : ReadBindingOptions
    {
        return new ReadBindingOptions($bindingType, $identity);
    }
}
class ReadBindingOptions extends Options
{
    /**
     * @param string $bindingType 
     * @param string[] $identity 
     */
    public function __construct(array $bindingType = Values::ARRAY_NONE, array $identity = Values::ARRAY_NONE)
    {
        $this->options['bindingType'] = $bindingType;
        $this->options['identity'] = $identity;
    }
    /**
     * 
     *
     * @param string $bindingType 
     * @return $this Fluent Builder
     */
    public function setBindingType(array $bindingType) : self
    {
        $this->options['bindingType'] = $bindingType;
        return $this;
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
        return '[Twilio.IpMessaging.V2.ReadBindingOptions ' . $options . ']';
    }
}
