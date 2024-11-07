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
namespace Isolated\Twilio\Rest\Verify\V2\Service;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class VerificationCheckOptions
{
    /**
     * @param string $code The 4-10 character string being verified.
     * @param string $to The phone number or [email](https://www.twilio.com/docs/verify/email) to verify. Either this parameter or the `verification_sid` must be specified. Phone numbers must be in [E.164 format](https://www.twilio.com/docs/glossary/what-e164).
     * @param string $verificationSid A SID that uniquely identifies the Verification Check. Either this parameter or the `to` phone number/[email](https://www.twilio.com/docs/verify/email) must be specified.
     * @param string $amount The amount of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     * @param string $payee The payee of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     * @return CreateVerificationCheckOptions Options builder
     */
    public static function create(string $code = Values::NONE, string $to = Values::NONE, string $verificationSid = Values::NONE, string $amount = Values::NONE, string $payee = Values::NONE) : CreateVerificationCheckOptions
    {
        return new CreateVerificationCheckOptions($code, $to, $verificationSid, $amount, $payee);
    }
}
class CreateVerificationCheckOptions extends Options
{
    /**
     * @param string $code The 4-10 character string being verified.
     * @param string $to The phone number or [email](https://www.twilio.com/docs/verify/email) to verify. Either this parameter or the `verification_sid` must be specified. Phone numbers must be in [E.164 format](https://www.twilio.com/docs/glossary/what-e164).
     * @param string $verificationSid A SID that uniquely identifies the Verification Check. Either this parameter or the `to` phone number/[email](https://www.twilio.com/docs/verify/email) must be specified.
     * @param string $amount The amount of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     * @param string $payee The payee of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     */
    public function __construct(string $code = Values::NONE, string $to = Values::NONE, string $verificationSid = Values::NONE, string $amount = Values::NONE, string $payee = Values::NONE)
    {
        $this->options['code'] = $code;
        $this->options['to'] = $to;
        $this->options['verificationSid'] = $verificationSid;
        $this->options['amount'] = $amount;
        $this->options['payee'] = $payee;
    }
    /**
     * The 4-10 character string being verified.
     *
     * @param string $code The 4-10 character string being verified.
     * @return $this Fluent Builder
     */
    public function setCode(string $code) : self
    {
        $this->options['code'] = $code;
        return $this;
    }
    /**
     * The phone number or [email](https://www.twilio.com/docs/verify/email) to verify. Either this parameter or the `verification_sid` must be specified. Phone numbers must be in [E.164 format](https://www.twilio.com/docs/glossary/what-e164).
     *
     * @param string $to The phone number or [email](https://www.twilio.com/docs/verify/email) to verify. Either this parameter or the `verification_sid` must be specified. Phone numbers must be in [E.164 format](https://www.twilio.com/docs/glossary/what-e164).
     * @return $this Fluent Builder
     */
    public function setTo(string $to) : self
    {
        $this->options['to'] = $to;
        return $this;
    }
    /**
     * A SID that uniquely identifies the Verification Check. Either this parameter or the `to` phone number/[email](https://www.twilio.com/docs/verify/email) must be specified.
     *
     * @param string $verificationSid A SID that uniquely identifies the Verification Check. Either this parameter or the `to` phone number/[email](https://www.twilio.com/docs/verify/email) must be specified.
     * @return $this Fluent Builder
     */
    public function setVerificationSid(string $verificationSid) : self
    {
        $this->options['verificationSid'] = $verificationSid;
        return $this;
    }
    /**
     * The amount of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     *
     * @param string $amount The amount of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     * @return $this Fluent Builder
     */
    public function setAmount(string $amount) : self
    {
        $this->options['amount'] = $amount;
        return $this;
    }
    /**
     * The payee of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     *
     * @param string $payee The payee of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     * @return $this Fluent Builder
     */
    public function setPayee(string $payee) : self
    {
        $this->options['payee'] = $payee;
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
        return '[Twilio.Verify.V2.CreateVerificationCheckOptions ' . $options . ']';
    }
}
