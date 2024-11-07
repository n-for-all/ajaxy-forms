<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Verify
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Verify\V2;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class VerificationAttemptOptions
{
    /**
     * @param \DateTime $dateCreatedAfter Datetime filter used to consider only Verification Attempts created after this datetime on the summary aggregation. Given as GMT in ISO 8601 formatted datetime string: yyyy-MM-dd'T'HH:mm:ss'Z.
     * @param \DateTime $dateCreatedBefore Datetime filter used to consider only Verification Attempts created before this datetime on the summary aggregation. Given as GMT in ISO 8601 formatted datetime string: yyyy-MM-dd'T'HH:mm:ss'Z.
     * @param string $channelDataTo Destination of a verification. It is phone number in E.164 format.
     * @param string $country Filter used to query Verification Attempts sent to the specified destination country.
     * @param string $channel Filter used to query Verification Attempts by communication channel. Valid values are `SMS` and `CALL`
     * @param string $verifyServiceSid Filter used to query Verification Attempts by verify service. Only attempts of the provided SID will be returned.
     * @param string $verificationSid Filter used to return all the Verification Attempts of a single verification. Only attempts of the provided verification SID will be returned.
     * @param string $status Filter used to query Verification Attempts by conversion status. Valid values are `UNCONVERTED`, for attempts that were not converted, and `CONVERTED`, for attempts that were confirmed.
     * @return ReadVerificationAttemptOptions Options builder
     */
    public static function read(\DateTime $dateCreatedAfter = null, \DateTime $dateCreatedBefore = null, string $channelDataTo = Values::NONE, string $country = Values::NONE, string $channel = Values::NONE, string $verifyServiceSid = Values::NONE, string $verificationSid = Values::NONE, string $status = Values::NONE) : ReadVerificationAttemptOptions
    {
        return new ReadVerificationAttemptOptions($dateCreatedAfter, $dateCreatedBefore, $channelDataTo, $country, $channel, $verifyServiceSid, $verificationSid, $status);
    }
}
class ReadVerificationAttemptOptions extends Options
{
    /**
     * @param \DateTime $dateCreatedAfter Datetime filter used to consider only Verification Attempts created after this datetime on the summary aggregation. Given as GMT in ISO 8601 formatted datetime string: yyyy-MM-dd'T'HH:mm:ss'Z.
     * @param \DateTime $dateCreatedBefore Datetime filter used to consider only Verification Attempts created before this datetime on the summary aggregation. Given as GMT in ISO 8601 formatted datetime string: yyyy-MM-dd'T'HH:mm:ss'Z.
     * @param string $channelDataTo Destination of a verification. It is phone number in E.164 format.
     * @param string $country Filter used to query Verification Attempts sent to the specified destination country.
     * @param string $channel Filter used to query Verification Attempts by communication channel. Valid values are `SMS` and `CALL`
     * @param string $verifyServiceSid Filter used to query Verification Attempts by verify service. Only attempts of the provided SID will be returned.
     * @param string $verificationSid Filter used to return all the Verification Attempts of a single verification. Only attempts of the provided verification SID will be returned.
     * @param string $status Filter used to query Verification Attempts by conversion status. Valid values are `UNCONVERTED`, for attempts that were not converted, and `CONVERTED`, for attempts that were confirmed.
     */
    public function __construct(\DateTime $dateCreatedAfter = null, \DateTime $dateCreatedBefore = null, string $channelDataTo = Values::NONE, string $country = Values::NONE, string $channel = Values::NONE, string $verifyServiceSid = Values::NONE, string $verificationSid = Values::NONE, string $status = Values::NONE)
    {
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
        $this->options['channelDataTo'] = $channelDataTo;
        $this->options['country'] = $country;
        $this->options['channel'] = $channel;
        $this->options['verifyServiceSid'] = $verifyServiceSid;
        $this->options['verificationSid'] = $verificationSid;
        $this->options['status'] = $status;
    }
    /**
     * Datetime filter used to consider only Verification Attempts created after this datetime on the summary aggregation. Given as GMT in ISO 8601 formatted datetime string: yyyy-MM-dd'T'HH:mm:ss'Z.
     *
     * @param \DateTime $dateCreatedAfter Datetime filter used to consider only Verification Attempts created after this datetime on the summary aggregation. Given as GMT in ISO 8601 formatted datetime string: yyyy-MM-dd'T'HH:mm:ss'Z.
     * @return $this Fluent Builder
     */
    public function setDateCreatedAfter(\DateTime $dateCreatedAfter) : self
    {
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
        return $this;
    }
    /**
     * Datetime filter used to consider only Verification Attempts created before this datetime on the summary aggregation. Given as GMT in ISO 8601 formatted datetime string: yyyy-MM-dd'T'HH:mm:ss'Z.
     *
     * @param \DateTime $dateCreatedBefore Datetime filter used to consider only Verification Attempts created before this datetime on the summary aggregation. Given as GMT in ISO 8601 formatted datetime string: yyyy-MM-dd'T'HH:mm:ss'Z.
     * @return $this Fluent Builder
     */
    public function setDateCreatedBefore(\DateTime $dateCreatedBefore) : self
    {
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
        return $this;
    }
    /**
     * Destination of a verification. It is phone number in E.164 format.
     *
     * @param string $channelDataTo Destination of a verification. It is phone number in E.164 format.
     * @return $this Fluent Builder
     */
    public function setChannelDataTo(string $channelDataTo) : self
    {
        $this->options['channelDataTo'] = $channelDataTo;
        return $this;
    }
    /**
     * Filter used to query Verification Attempts sent to the specified destination country.
     *
     * @param string $country Filter used to query Verification Attempts sent to the specified destination country.
     * @return $this Fluent Builder
     */
    public function setCountry(string $country) : self
    {
        $this->options['country'] = $country;
        return $this;
    }
    /**
     * Filter used to query Verification Attempts by communication channel. Valid values are `SMS` and `CALL`
     *
     * @param string $channel Filter used to query Verification Attempts by communication channel. Valid values are `SMS` and `CALL`
     * @return $this Fluent Builder
     */
    public function setChannel(string $channel) : self
    {
        $this->options['channel'] = $channel;
        return $this;
    }
    /**
     * Filter used to query Verification Attempts by verify service. Only attempts of the provided SID will be returned.
     *
     * @param string $verifyServiceSid Filter used to query Verification Attempts by verify service. Only attempts of the provided SID will be returned.
     * @return $this Fluent Builder
     */
    public function setVerifyServiceSid(string $verifyServiceSid) : self
    {
        $this->options['verifyServiceSid'] = $verifyServiceSid;
        return $this;
    }
    /**
     * Filter used to return all the Verification Attempts of a single verification. Only attempts of the provided verification SID will be returned.
     *
     * @param string $verificationSid Filter used to return all the Verification Attempts of a single verification. Only attempts of the provided verification SID will be returned.
     * @return $this Fluent Builder
     */
    public function setVerificationSid(string $verificationSid) : self
    {
        $this->options['verificationSid'] = $verificationSid;
        return $this;
    }
    /**
     * Filter used to query Verification Attempts by conversion status. Valid values are `UNCONVERTED`, for attempts that were not converted, and `CONVERTED`, for attempts that were confirmed.
     *
     * @param string $status Filter used to query Verification Attempts by conversion status. Valid values are `UNCONVERTED`, for attempts that were not converted, and `CONVERTED`, for attempts that were confirmed.
     * @return $this Fluent Builder
     */
    public function setStatus(string $status) : self
    {
        $this->options['status'] = $status;
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
        return '[Twilio.Verify.V2.ReadVerificationAttemptOptions ' . $options . ']';
    }
}
