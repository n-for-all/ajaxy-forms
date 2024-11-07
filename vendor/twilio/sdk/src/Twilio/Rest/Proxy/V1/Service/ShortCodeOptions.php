<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Proxy
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Proxy\V1\Service;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class ShortCodeOptions
{
    /**
     * @param bool $isReserved Whether the short code should be reserved and not be assigned to a participant using proxy pool logic. See [Reserved Phone Numbers](https://www.twilio.com/docs/proxy/reserved-phone-numbers) for more information.
     * @return UpdateShortCodeOptions Options builder
     */
    public static function update(bool $isReserved = Values::BOOL_NONE) : UpdateShortCodeOptions
    {
        return new UpdateShortCodeOptions($isReserved);
    }
}
class UpdateShortCodeOptions extends Options
{
    /**
     * @param bool $isReserved Whether the short code should be reserved and not be assigned to a participant using proxy pool logic. See [Reserved Phone Numbers](https://www.twilio.com/docs/proxy/reserved-phone-numbers) for more information.
     */
    public function __construct(bool $isReserved = Values::BOOL_NONE)
    {
        $this->options['isReserved'] = $isReserved;
    }
    /**
     * Whether the short code should be reserved and not be assigned to a participant using proxy pool logic. See [Reserved Phone Numbers](https://www.twilio.com/docs/proxy/reserved-phone-numbers) for more information.
     *
     * @param bool $isReserved Whether the short code should be reserved and not be assigned to a participant using proxy pool logic. See [Reserved Phone Numbers](https://www.twilio.com/docs/proxy/reserved-phone-numbers) for more information.
     * @return $this Fluent Builder
     */
    public function setIsReserved(bool $isReserved) : self
    {
        $this->options['isReserved'] = $isReserved;
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
        return '[Twilio.Proxy.V1.UpdateShortCodeOptions ' . $options . ']';
    }
}
