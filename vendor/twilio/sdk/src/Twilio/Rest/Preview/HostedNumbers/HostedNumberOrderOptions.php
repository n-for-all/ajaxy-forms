<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Preview
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Preview\HostedNumbers;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
abstract class HostedNumberOrderOptions
{
    /**
     * @param string $accountSid This defaults to the AccountSid of the authorization the user is using. This can be provided to specify a subaccount to add the HostedNumberOrder to.
     * @param string $friendlyName A 64 character string that is a human readable text that describes this resource.
     * @param string $uniqueName Optional. Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     * @param string[] $ccEmails Optional. A list of emails that the LOA document for this HostedNumberOrder will be carbon copied to.
     * @param string $smsUrl The URL that Twilio should request when somebody sends an SMS to the phone number. This will be copied onto the IncomingPhoneNumber resource.
     * @param string $smsMethod The HTTP method that should be used to request the SmsUrl. Must be either `GET` or `POST`.  This will be copied onto the IncomingPhoneNumber resource.
     * @param string $smsFallbackUrl A URL that Twilio will request if an error occurs requesting or executing the TwiML defined by SmsUrl. This will be copied onto the IncomingPhoneNumber resource.
     * @param string $smsFallbackMethod The HTTP method that should be used to request the SmsFallbackUrl. Must be either `GET` or `POST`. This will be copied onto the IncomingPhoneNumber resource.
     * @param string $statusCallbackUrl Optional. The Status Callback URL attached to the IncomingPhoneNumber resource.
     * @param string $statusCallbackMethod Optional. The Status Callback Method attached to the IncomingPhoneNumber resource.
     * @param string $smsApplicationSid Optional. The 34 character sid of the application Twilio should use to handle SMS messages sent to this number. If a `SmsApplicationSid` is present, Twilio will ignore all of the SMS urls above and use those set on the application.
     * @param string $addressSid Optional. A 34 character string that uniquely identifies the Address resource that represents the address of the owner of this phone number.
     * @param string $email Optional. Email of the owner of this phone number that is being hosted.
     * @param string $verificationType
     * @param string $verificationDocumentSid Optional. The unique sid identifier of the Identity Document that represents the document for verifying ownership of the number to be hosted. Required when VerificationType is phone-bill.
     * @return CreateHostedNumberOrderOptions Options builder
     */
    public static function create(string $accountSid = Values::NONE, string $friendlyName = Values::NONE, string $uniqueName = Values::NONE, array $ccEmails = Values::ARRAY_NONE, string $smsUrl = Values::NONE, string $smsMethod = Values::NONE, string $smsFallbackUrl = Values::NONE, string $smsFallbackMethod = Values::NONE, string $statusCallbackUrl = Values::NONE, string $statusCallbackMethod = Values::NONE, string $smsApplicationSid = Values::NONE, string $addressSid = Values::NONE, string $email = Values::NONE, string $verificationType = Values::NONE, string $verificationDocumentSid = Values::NONE) : CreateHostedNumberOrderOptions
    {
        return new CreateHostedNumberOrderOptions($accountSid, $friendlyName, $uniqueName, $ccEmails, $smsUrl, $smsMethod, $smsFallbackUrl, $smsFallbackMethod, $statusCallbackUrl, $statusCallbackMethod, $smsApplicationSid, $addressSid, $email, $verificationType, $verificationDocumentSid);
    }
    /**
     * @param string $status The Status of this HostedNumberOrder. One of `received`, `pending-verification`, `verified`, `pending-loa`, `carrier-processing`, `testing`, `completed`, `failed`, or `action-required`.
     * @param string $phoneNumber An E164 formatted phone number hosted by this HostedNumberOrder.
     * @param string $incomingPhoneNumberSid A 34 character string that uniquely identifies the IncomingPhoneNumber resource created by this HostedNumberOrder.
     * @param string $friendlyName A human readable description of this resource, up to 64 characters.
     * @param string $uniqueName Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     * @return ReadHostedNumberOrderOptions Options builder
     */
    public static function read(string $status = Values::NONE, string $phoneNumber = Values::NONE, string $incomingPhoneNumberSid = Values::NONE, string $friendlyName = Values::NONE, string $uniqueName = Values::NONE) : ReadHostedNumberOrderOptions
    {
        return new ReadHostedNumberOrderOptions($status, $phoneNumber, $incomingPhoneNumberSid, $friendlyName, $uniqueName);
    }
    /**
     * @param string $friendlyName A 64 character string that is a human readable text that describes this resource.
     * @param string $uniqueName Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     * @param string $email Email of the owner of this phone number that is being hosted.
     * @param string[] $ccEmails Optional. A list of emails that LOA document for this HostedNumberOrder will be carbon copied to.
     * @param string $status
     * @param string $verificationCode A verification code that is given to the user via a phone call to the phone number that is being hosted.
     * @param string $verificationType
     * @param string $verificationDocumentSid Optional. The unique sid identifier of the Identity Document that represents the document for verifying ownership of the number to be hosted. Required when VerificationType is phone-bill.
     * @param string $extension Digits to dial after connecting the verification call.
     * @param int $callDelay The number of seconds, between 0 and 60, to delay before initiating the verification call. Defaults to 0.
     * @return UpdateHostedNumberOrderOptions Options builder
     */
    public static function update(string $friendlyName = Values::NONE, string $uniqueName = Values::NONE, string $email = Values::NONE, array $ccEmails = Values::ARRAY_NONE, string $status = Values::NONE, string $verificationCode = Values::NONE, string $verificationType = Values::NONE, string $verificationDocumentSid = Values::NONE, string $extension = Values::NONE, int $callDelay = Values::INT_NONE) : UpdateHostedNumberOrderOptions
    {
        return new UpdateHostedNumberOrderOptions($friendlyName, $uniqueName, $email, $ccEmails, $status, $verificationCode, $verificationType, $verificationDocumentSid, $extension, $callDelay);
    }
}
class CreateHostedNumberOrderOptions extends Options
{
    /**
     * @param string $accountSid This defaults to the AccountSid of the authorization the user is using. This can be provided to specify a subaccount to add the HostedNumberOrder to.
     * @param string $friendlyName A 64 character string that is a human readable text that describes this resource.
     * @param string $uniqueName Optional. Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     * @param string[] $ccEmails Optional. A list of emails that the LOA document for this HostedNumberOrder will be carbon copied to.
     * @param string $smsUrl The URL that Twilio should request when somebody sends an SMS to the phone number. This will be copied onto the IncomingPhoneNumber resource.
     * @param string $smsMethod The HTTP method that should be used to request the SmsUrl. Must be either `GET` or `POST`.  This will be copied onto the IncomingPhoneNumber resource.
     * @param string $smsFallbackUrl A URL that Twilio will request if an error occurs requesting or executing the TwiML defined by SmsUrl. This will be copied onto the IncomingPhoneNumber resource.
     * @param string $smsFallbackMethod The HTTP method that should be used to request the SmsFallbackUrl. Must be either `GET` or `POST`. This will be copied onto the IncomingPhoneNumber resource.
     * @param string $statusCallbackUrl Optional. The Status Callback URL attached to the IncomingPhoneNumber resource.
     * @param string $statusCallbackMethod Optional. The Status Callback Method attached to the IncomingPhoneNumber resource.
     * @param string $smsApplicationSid Optional. The 34 character sid of the application Twilio should use to handle SMS messages sent to this number. If a `SmsApplicationSid` is present, Twilio will ignore all of the SMS urls above and use those set on the application.
     * @param string $addressSid Optional. A 34 character string that uniquely identifies the Address resource that represents the address of the owner of this phone number.
     * @param string $email Optional. Email of the owner of this phone number that is being hosted.
     * @param string $verificationType
     * @param string $verificationDocumentSid Optional. The unique sid identifier of the Identity Document that represents the document for verifying ownership of the number to be hosted. Required when VerificationType is phone-bill.
     */
    public function __construct(string $accountSid = Values::NONE, string $friendlyName = Values::NONE, string $uniqueName = Values::NONE, array $ccEmails = Values::ARRAY_NONE, string $smsUrl = Values::NONE, string $smsMethod = Values::NONE, string $smsFallbackUrl = Values::NONE, string $smsFallbackMethod = Values::NONE, string $statusCallbackUrl = Values::NONE, string $statusCallbackMethod = Values::NONE, string $smsApplicationSid = Values::NONE, string $addressSid = Values::NONE, string $email = Values::NONE, string $verificationType = Values::NONE, string $verificationDocumentSid = Values::NONE)
    {
        $this->options['accountSid'] = $accountSid;
        $this->options['friendlyName'] = $friendlyName;
        $this->options['uniqueName'] = $uniqueName;
        $this->options['ccEmails'] = $ccEmails;
        $this->options['smsUrl'] = $smsUrl;
        $this->options['smsMethod'] = $smsMethod;
        $this->options['smsFallbackUrl'] = $smsFallbackUrl;
        $this->options['smsFallbackMethod'] = $smsFallbackMethod;
        $this->options['statusCallbackUrl'] = $statusCallbackUrl;
        $this->options['statusCallbackMethod'] = $statusCallbackMethod;
        $this->options['smsApplicationSid'] = $smsApplicationSid;
        $this->options['addressSid'] = $addressSid;
        $this->options['email'] = $email;
        $this->options['verificationType'] = $verificationType;
        $this->options['verificationDocumentSid'] = $verificationDocumentSid;
    }
    /**
     * This defaults to the AccountSid of the authorization the user is using. This can be provided to specify a subaccount to add the HostedNumberOrder to.
     *
     * @param string $accountSid This defaults to the AccountSid of the authorization the user is using. This can be provided to specify a subaccount to add the HostedNumberOrder to.
     * @return $this Fluent Builder
     */
    public function setAccountSid(string $accountSid) : self
    {
        $this->options['accountSid'] = $accountSid;
        return $this;
    }
    /**
     * A 64 character string that is a human readable text that describes this resource.
     *
     * @param string $friendlyName A 64 character string that is a human readable text that describes this resource.
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName) : self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }
    /**
     * Optional. Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     *
     * @param string $uniqueName Optional. Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     * @return $this Fluent Builder
     */
    public function setUniqueName(string $uniqueName) : self
    {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }
    /**
     * Optional. A list of emails that the LOA document for this HostedNumberOrder will be carbon copied to.
     *
     * @param string[] $ccEmails Optional. A list of emails that the LOA document for this HostedNumberOrder will be carbon copied to.
     * @return $this Fluent Builder
     */
    public function setCcEmails(array $ccEmails) : self
    {
        $this->options['ccEmails'] = $ccEmails;
        return $this;
    }
    /**
     * The URL that Twilio should request when somebody sends an SMS to the phone number. This will be copied onto the IncomingPhoneNumber resource.
     *
     * @param string $smsUrl The URL that Twilio should request when somebody sends an SMS to the phone number. This will be copied onto the IncomingPhoneNumber resource.
     * @return $this Fluent Builder
     */
    public function setSmsUrl(string $smsUrl) : self
    {
        $this->options['smsUrl'] = $smsUrl;
        return $this;
    }
    /**
     * The HTTP method that should be used to request the SmsUrl. Must be either `GET` or `POST`.  This will be copied onto the IncomingPhoneNumber resource.
     *
     * @param string $smsMethod The HTTP method that should be used to request the SmsUrl. Must be either `GET` or `POST`.  This will be copied onto the IncomingPhoneNumber resource.
     * @return $this Fluent Builder
     */
    public function setSmsMethod(string $smsMethod) : self
    {
        $this->options['smsMethod'] = $smsMethod;
        return $this;
    }
    /**
     * A URL that Twilio will request if an error occurs requesting or executing the TwiML defined by SmsUrl. This will be copied onto the IncomingPhoneNumber resource.
     *
     * @param string $smsFallbackUrl A URL that Twilio will request if an error occurs requesting or executing the TwiML defined by SmsUrl. This will be copied onto the IncomingPhoneNumber resource.
     * @return $this Fluent Builder
     */
    public function setSmsFallbackUrl(string $smsFallbackUrl) : self
    {
        $this->options['smsFallbackUrl'] = $smsFallbackUrl;
        return $this;
    }
    /**
     * The HTTP method that should be used to request the SmsFallbackUrl. Must be either `GET` or `POST`. This will be copied onto the IncomingPhoneNumber resource.
     *
     * @param string $smsFallbackMethod The HTTP method that should be used to request the SmsFallbackUrl. Must be either `GET` or `POST`. This will be copied onto the IncomingPhoneNumber resource.
     * @return $this Fluent Builder
     */
    public function setSmsFallbackMethod(string $smsFallbackMethod) : self
    {
        $this->options['smsFallbackMethod'] = $smsFallbackMethod;
        return $this;
    }
    /**
     * Optional. The Status Callback URL attached to the IncomingPhoneNumber resource.
     *
     * @param string $statusCallbackUrl Optional. The Status Callback URL attached to the IncomingPhoneNumber resource.
     * @return $this Fluent Builder
     */
    public function setStatusCallbackUrl(string $statusCallbackUrl) : self
    {
        $this->options['statusCallbackUrl'] = $statusCallbackUrl;
        return $this;
    }
    /**
     * Optional. The Status Callback Method attached to the IncomingPhoneNumber resource.
     *
     * @param string $statusCallbackMethod Optional. The Status Callback Method attached to the IncomingPhoneNumber resource.
     * @return $this Fluent Builder
     */
    public function setStatusCallbackMethod(string $statusCallbackMethod) : self
    {
        $this->options['statusCallbackMethod'] = $statusCallbackMethod;
        return $this;
    }
    /**
     * Optional. The 34 character sid of the application Twilio should use to handle SMS messages sent to this number. If a `SmsApplicationSid` is present, Twilio will ignore all of the SMS urls above and use those set on the application.
     *
     * @param string $smsApplicationSid Optional. The 34 character sid of the application Twilio should use to handle SMS messages sent to this number. If a `SmsApplicationSid` is present, Twilio will ignore all of the SMS urls above and use those set on the application.
     * @return $this Fluent Builder
     */
    public function setSmsApplicationSid(string $smsApplicationSid) : self
    {
        $this->options['smsApplicationSid'] = $smsApplicationSid;
        return $this;
    }
    /**
     * Optional. A 34 character string that uniquely identifies the Address resource that represents the address of the owner of this phone number.
     *
     * @param string $addressSid Optional. A 34 character string that uniquely identifies the Address resource that represents the address of the owner of this phone number.
     * @return $this Fluent Builder
     */
    public function setAddressSid(string $addressSid) : self
    {
        $this->options['addressSid'] = $addressSid;
        return $this;
    }
    /**
     * Optional. Email of the owner of this phone number that is being hosted.
     *
     * @param string $email Optional. Email of the owner of this phone number that is being hosted.
     * @return $this Fluent Builder
     */
    public function setEmail(string $email) : self
    {
        $this->options['email'] = $email;
        return $this;
    }
    /**
     * @param string $verificationType
     * @return $this Fluent Builder
     */
    public function setVerificationType(string $verificationType) : self
    {
        $this->options['verificationType'] = $verificationType;
        return $this;
    }
    /**
     * Optional. The unique sid identifier of the Identity Document that represents the document for verifying ownership of the number to be hosted. Required when VerificationType is phone-bill.
     *
     * @param string $verificationDocumentSid Optional. The unique sid identifier of the Identity Document that represents the document for verifying ownership of the number to be hosted. Required when VerificationType is phone-bill.
     * @return $this Fluent Builder
     */
    public function setVerificationDocumentSid(string $verificationDocumentSid) : self
    {
        $this->options['verificationDocumentSid'] = $verificationDocumentSid;
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
        return '[Twilio.Preview.HostedNumbers.CreateHostedNumberOrderOptions ' . $options . ']';
    }
}
class ReadHostedNumberOrderOptions extends Options
{
    /**
     * @param string $status The Status of this HostedNumberOrder. One of `received`, `pending-verification`, `verified`, `pending-loa`, `carrier-processing`, `testing`, `completed`, `failed`, or `action-required`.
     * @param string $phoneNumber An E164 formatted phone number hosted by this HostedNumberOrder.
     * @param string $incomingPhoneNumberSid A 34 character string that uniquely identifies the IncomingPhoneNumber resource created by this HostedNumberOrder.
     * @param string $friendlyName A human readable description of this resource, up to 64 characters.
     * @param string $uniqueName Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     */
    public function __construct(string $status = Values::NONE, string $phoneNumber = Values::NONE, string $incomingPhoneNumberSid = Values::NONE, string $friendlyName = Values::NONE, string $uniqueName = Values::NONE)
    {
        $this->options['status'] = $status;
        $this->options['phoneNumber'] = $phoneNumber;
        $this->options['incomingPhoneNumberSid'] = $incomingPhoneNumberSid;
        $this->options['friendlyName'] = $friendlyName;
        $this->options['uniqueName'] = $uniqueName;
    }
    /**
     * The Status of this HostedNumberOrder. One of `received`, `pending-verification`, `verified`, `pending-loa`, `carrier-processing`, `testing`, `completed`, `failed`, or `action-required`.
     *
     * @param string $status The Status of this HostedNumberOrder. One of `received`, `pending-verification`, `verified`, `pending-loa`, `carrier-processing`, `testing`, `completed`, `failed`, or `action-required`.
     * @return $this Fluent Builder
     */
    public function setStatus(string $status) : self
    {
        $this->options['status'] = $status;
        return $this;
    }
    /**
     * An E164 formatted phone number hosted by this HostedNumberOrder.
     *
     * @param string $phoneNumber An E164 formatted phone number hosted by this HostedNumberOrder.
     * @return $this Fluent Builder
     */
    public function setPhoneNumber(string $phoneNumber) : self
    {
        $this->options['phoneNumber'] = $phoneNumber;
        return $this;
    }
    /**
     * A 34 character string that uniquely identifies the IncomingPhoneNumber resource created by this HostedNumberOrder.
     *
     * @param string $incomingPhoneNumberSid A 34 character string that uniquely identifies the IncomingPhoneNumber resource created by this HostedNumberOrder.
     * @return $this Fluent Builder
     */
    public function setIncomingPhoneNumberSid(string $incomingPhoneNumberSid) : self
    {
        $this->options['incomingPhoneNumberSid'] = $incomingPhoneNumberSid;
        return $this;
    }
    /**
     * A human readable description of this resource, up to 64 characters.
     *
     * @param string $friendlyName A human readable description of this resource, up to 64 characters.
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName) : self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }
    /**
     * Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     *
     * @param string $uniqueName Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     * @return $this Fluent Builder
     */
    public function setUniqueName(string $uniqueName) : self
    {
        $this->options['uniqueName'] = $uniqueName;
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
        return '[Twilio.Preview.HostedNumbers.ReadHostedNumberOrderOptions ' . $options . ']';
    }
}
class UpdateHostedNumberOrderOptions extends Options
{
    /**
     * @param string $friendlyName A 64 character string that is a human readable text that describes this resource.
     * @param string $uniqueName Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     * @param string $email Email of the owner of this phone number that is being hosted.
     * @param string[] $ccEmails Optional. A list of emails that LOA document for this HostedNumberOrder will be carbon copied to.
     * @param string $status
     * @param string $verificationCode A verification code that is given to the user via a phone call to the phone number that is being hosted.
     * @param string $verificationType
     * @param string $verificationDocumentSid Optional. The unique sid identifier of the Identity Document that represents the document for verifying ownership of the number to be hosted. Required when VerificationType is phone-bill.
     * @param string $extension Digits to dial after connecting the verification call.
     * @param int $callDelay The number of seconds, between 0 and 60, to delay before initiating the verification call. Defaults to 0.
     */
    public function __construct(string $friendlyName = Values::NONE, string $uniqueName = Values::NONE, string $email = Values::NONE, array $ccEmails = Values::ARRAY_NONE, string $status = Values::NONE, string $verificationCode = Values::NONE, string $verificationType = Values::NONE, string $verificationDocumentSid = Values::NONE, string $extension = Values::NONE, int $callDelay = Values::INT_NONE)
    {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['uniqueName'] = $uniqueName;
        $this->options['email'] = $email;
        $this->options['ccEmails'] = $ccEmails;
        $this->options['status'] = $status;
        $this->options['verificationCode'] = $verificationCode;
        $this->options['verificationType'] = $verificationType;
        $this->options['verificationDocumentSid'] = $verificationDocumentSid;
        $this->options['extension'] = $extension;
        $this->options['callDelay'] = $callDelay;
    }
    /**
     * A 64 character string that is a human readable text that describes this resource.
     *
     * @param string $friendlyName A 64 character string that is a human readable text that describes this resource.
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName) : self
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }
    /**
     * Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     *
     * @param string $uniqueName Provides a unique and addressable name to be assigned to this HostedNumberOrder, assigned by the developer, to be optionally used in addition to SID.
     * @return $this Fluent Builder
     */
    public function setUniqueName(string $uniqueName) : self
    {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }
    /**
     * Email of the owner of this phone number that is being hosted.
     *
     * @param string $email Email of the owner of this phone number that is being hosted.
     * @return $this Fluent Builder
     */
    public function setEmail(string $email) : self
    {
        $this->options['email'] = $email;
        return $this;
    }
    /**
     * Optional. A list of emails that LOA document for this HostedNumberOrder will be carbon copied to.
     *
     * @param string[] $ccEmails Optional. A list of emails that LOA document for this HostedNumberOrder will be carbon copied to.
     * @return $this Fluent Builder
     */
    public function setCcEmails(array $ccEmails) : self
    {
        $this->options['ccEmails'] = $ccEmails;
        return $this;
    }
    /**
     * @param string $status
     * @return $this Fluent Builder
     */
    public function setStatus(string $status) : self
    {
        $this->options['status'] = $status;
        return $this;
    }
    /**
     * A verification code that is given to the user via a phone call to the phone number that is being hosted.
     *
     * @param string $verificationCode A verification code that is given to the user via a phone call to the phone number that is being hosted.
     * @return $this Fluent Builder
     */
    public function setVerificationCode(string $verificationCode) : self
    {
        $this->options['verificationCode'] = $verificationCode;
        return $this;
    }
    /**
     * @param string $verificationType
     * @return $this Fluent Builder
     */
    public function setVerificationType(string $verificationType) : self
    {
        $this->options['verificationType'] = $verificationType;
        return $this;
    }
    /**
     * Optional. The unique sid identifier of the Identity Document that represents the document for verifying ownership of the number to be hosted. Required when VerificationType is phone-bill.
     *
     * @param string $verificationDocumentSid Optional. The unique sid identifier of the Identity Document that represents the document for verifying ownership of the number to be hosted. Required when VerificationType is phone-bill.
     * @return $this Fluent Builder
     */
    public function setVerificationDocumentSid(string $verificationDocumentSid) : self
    {
        $this->options['verificationDocumentSid'] = $verificationDocumentSid;
        return $this;
    }
    /**
     * Digits to dial after connecting the verification call.
     *
     * @param string $extension Digits to dial after connecting the verification call.
     * @return $this Fluent Builder
     */
    public function setExtension(string $extension) : self
    {
        $this->options['extension'] = $extension;
        return $this;
    }
    /**
     * The number of seconds, between 0 and 60, to delay before initiating the verification call. Defaults to 0.
     *
     * @param int $callDelay The number of seconds, between 0 and 60, to delay before initiating the verification call. Defaults to 0.
     * @return $this Fluent Builder
     */
    public function setCallDelay(int $callDelay) : self
    {
        $this->options['callDelay'] = $callDelay;
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
        return '[Twilio.Preview.HostedNumbers.UpdateHostedNumberOrderOptions ' . $options . ']';
    }
}
