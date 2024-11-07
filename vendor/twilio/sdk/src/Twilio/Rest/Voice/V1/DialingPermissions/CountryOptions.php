<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Voice
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Voice\V1\DialingPermissions;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class CountryOptions
{
    /**
     * @param string $isoCode Filter to retrieve the country permissions by specifying the [ISO country code](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
     * @param string $continent Filter to retrieve the country permissions by specifying the continent
     * @param string $countryCode Filter the results by specified [country codes](https://www.itu.int/itudoc/itu-t/ob-lists/icc/e164_763.html)
     * @param bool $lowRiskNumbersEnabled Filter to retrieve the country permissions with dialing to low-risk numbers enabled. Can be: `true` or `false`.
     * @param bool $highRiskSpecialNumbersEnabled Filter to retrieve the country permissions with dialing to high-risk special service numbers enabled. Can be: `true` or `false`
     * @param bool $highRiskTollfraudNumbersEnabled Filter to retrieve the country permissions with dialing to high-risk [toll fraud](https://www.twilio.com/blog/how-to-protect-your-account-from-toll-fraud-with-voice-dialing-geo-permissions-html) numbers enabled. Can be: `true` or `false`.
     * @return ReadCountryOptions Options builder
     */
    public static function read(string $isoCode = Values::NONE, string $continent = Values::NONE, string $countryCode = Values::NONE, bool $lowRiskNumbersEnabled = Values::BOOL_NONE, bool $highRiskSpecialNumbersEnabled = Values::BOOL_NONE, bool $highRiskTollfraudNumbersEnabled = Values::BOOL_NONE) : ReadCountryOptions
    {
        return new ReadCountryOptions($isoCode, $continent, $countryCode, $lowRiskNumbersEnabled, $highRiskSpecialNumbersEnabled, $highRiskTollfraudNumbersEnabled);
    }
}
class ReadCountryOptions extends Options
{
    /**
     * @param string $isoCode Filter to retrieve the country permissions by specifying the [ISO country code](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
     * @param string $continent Filter to retrieve the country permissions by specifying the continent
     * @param string $countryCode Filter the results by specified [country codes](https://www.itu.int/itudoc/itu-t/ob-lists/icc/e164_763.html)
     * @param bool $lowRiskNumbersEnabled Filter to retrieve the country permissions with dialing to low-risk numbers enabled. Can be: `true` or `false`.
     * @param bool $highRiskSpecialNumbersEnabled Filter to retrieve the country permissions with dialing to high-risk special service numbers enabled. Can be: `true` or `false`
     * @param bool $highRiskTollfraudNumbersEnabled Filter to retrieve the country permissions with dialing to high-risk [toll fraud](https://www.twilio.com/blog/how-to-protect-your-account-from-toll-fraud-with-voice-dialing-geo-permissions-html) numbers enabled. Can be: `true` or `false`.
     */
    public function __construct(string $isoCode = Values::NONE, string $continent = Values::NONE, string $countryCode = Values::NONE, bool $lowRiskNumbersEnabled = Values::BOOL_NONE, bool $highRiskSpecialNumbersEnabled = Values::BOOL_NONE, bool $highRiskTollfraudNumbersEnabled = Values::BOOL_NONE)
    {
        $this->options['isoCode'] = $isoCode;
        $this->options['continent'] = $continent;
        $this->options['countryCode'] = $countryCode;
        $this->options['lowRiskNumbersEnabled'] = $lowRiskNumbersEnabled;
        $this->options['highRiskSpecialNumbersEnabled'] = $highRiskSpecialNumbersEnabled;
        $this->options['highRiskTollfraudNumbersEnabled'] = $highRiskTollfraudNumbersEnabled;
    }
    /**
     * Filter to retrieve the country permissions by specifying the [ISO country code](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
     *
     * @param string $isoCode Filter to retrieve the country permissions by specifying the [ISO country code](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
     * @return $this Fluent Builder
     */
    public function setIsoCode(string $isoCode) : self
    {
        $this->options['isoCode'] = $isoCode;
        return $this;
    }
    /**
     * Filter to retrieve the country permissions by specifying the continent
     *
     * @param string $continent Filter to retrieve the country permissions by specifying the continent
     * @return $this Fluent Builder
     */
    public function setContinent(string $continent) : self
    {
        $this->options['continent'] = $continent;
        return $this;
    }
    /**
     * Filter the results by specified [country codes](https://www.itu.int/itudoc/itu-t/ob-lists/icc/e164_763.html)
     *
     * @param string $countryCode Filter the results by specified [country codes](https://www.itu.int/itudoc/itu-t/ob-lists/icc/e164_763.html)
     * @return $this Fluent Builder
     */
    public function setCountryCode(string $countryCode) : self
    {
        $this->options['countryCode'] = $countryCode;
        return $this;
    }
    /**
     * Filter to retrieve the country permissions with dialing to low-risk numbers enabled. Can be: `true` or `false`.
     *
     * @param bool $lowRiskNumbersEnabled Filter to retrieve the country permissions with dialing to low-risk numbers enabled. Can be: `true` or `false`.
     * @return $this Fluent Builder
     */
    public function setLowRiskNumbersEnabled(bool $lowRiskNumbersEnabled) : self
    {
        $this->options['lowRiskNumbersEnabled'] = $lowRiskNumbersEnabled;
        return $this;
    }
    /**
     * Filter to retrieve the country permissions with dialing to high-risk special service numbers enabled. Can be: `true` or `false`
     *
     * @param bool $highRiskSpecialNumbersEnabled Filter to retrieve the country permissions with dialing to high-risk special service numbers enabled. Can be: `true` or `false`
     * @return $this Fluent Builder
     */
    public function setHighRiskSpecialNumbersEnabled(bool $highRiskSpecialNumbersEnabled) : self
    {
        $this->options['highRiskSpecialNumbersEnabled'] = $highRiskSpecialNumbersEnabled;
        return $this;
    }
    /**
     * Filter to retrieve the country permissions with dialing to high-risk [toll fraud](https://www.twilio.com/blog/how-to-protect-your-account-from-toll-fraud-with-voice-dialing-geo-permissions-html) numbers enabled. Can be: `true` or `false`.
     *
     * @param bool $highRiskTollfraudNumbersEnabled Filter to retrieve the country permissions with dialing to high-risk [toll fraud](https://www.twilio.com/blog/how-to-protect-your-account-from-toll-fraud-with-voice-dialing-geo-permissions-html) numbers enabled. Can be: `true` or `false`.
     * @return $this Fluent Builder
     */
    public function setHighRiskTollfraudNumbersEnabled(bool $highRiskTollfraudNumbersEnabled) : self
    {
        $this->options['highRiskTollfraudNumbersEnabled'] = $highRiskTollfraudNumbersEnabled;
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
        return '[Twilio.Voice.V1.ReadCountryOptions ' . $options . ']';
    }
}
