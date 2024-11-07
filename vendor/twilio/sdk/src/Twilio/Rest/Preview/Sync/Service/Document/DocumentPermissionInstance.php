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
namespace Isolated\Twilio\Rest\Preview\Sync\Service\Document;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
/**
 * @property string|null $accountSid
 * @property string|null $serviceSid
 * @property string|null $documentSid
 * @property string|null $identity
 * @property bool|null $read
 * @property bool|null $write
 * @property bool|null $manage
 * @property string|null $url
 */
class DocumentPermissionInstance extends InstanceResource
{
    /**
     * Initialize the DocumentPermissionInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $serviceSid 
     * @param string $documentSid Identifier of the Sync Document. Either a SID or a unique name.
     * @param string $identity Arbitrary string identifier representing a user associated with an FPA token, assigned by the developer.
     */
    public function __construct(Version $version, array $payload, string $serviceSid, string $documentSid, string $identity = null)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['accountSid' => Values::array_get($payload, 'account_sid'), 'serviceSid' => Values::array_get($payload, 'service_sid'), 'documentSid' => Values::array_get($payload, 'document_sid'), 'identity' => Values::array_get($payload, 'identity'), 'read' => Values::array_get($payload, 'read'), 'write' => Values::array_get($payload, 'write'), 'manage' => Values::array_get($payload, 'manage'), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['serviceSid' => $serviceSid, 'documentSid' => $documentSid, 'identity' => $identity ?: $this->properties['identity']];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return DocumentPermissionContext Context for this DocumentPermissionInstance
     */
    protected function proxy() : DocumentPermissionContext
    {
        if (!$this->context) {
            $this->context = new DocumentPermissionContext($this->version, $this->solution['serviceSid'], $this->solution['documentSid'], $this->solution['identity']);
        }
        return $this->context;
    }
    /**
     * Delete the DocumentPermissionInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->proxy()->delete();
    }
    /**
     * Fetch the DocumentPermissionInstance
     *
     * @return DocumentPermissionInstance Fetched DocumentPermissionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : DocumentPermissionInstance
    {
        return $this->proxy()->fetch();
    }
    /**
     * Update the DocumentPermissionInstance
     *
     * @param bool $read Boolean flag specifying whether the identity can read the Sync Document.
     * @param bool $write Boolean flag specifying whether the identity can update the Sync Document.
     * @param bool $manage Boolean flag specifying whether the identity can delete the Sync Document.
     * @return DocumentPermissionInstance Updated DocumentPermissionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(bool $read, bool $write, bool $manage) : DocumentPermissionInstance
    {
        return $this->proxy()->update($read, $write, $manage);
    }
    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->{$method}();
        }
        throw new TwilioException('Unknown property: ' . $name);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "{$key}={$value}";
        }
        return '[Twilio.Preview.Sync.DocumentPermissionInstance ' . \implode(' ', $context) . ']';
    }
}
