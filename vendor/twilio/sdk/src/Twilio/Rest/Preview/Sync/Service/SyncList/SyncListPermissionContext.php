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
namespace Isolated\Twilio\Rest\Preview\Sync\Service\SyncList;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\InstanceContext;
use Isolated\Twilio\Serialize;
class SyncListPermissionContext extends InstanceContext
{
    /**
     * Initialize the SyncListPermissionContext
     *
     * @param Version $version Version that contains the resource
     * @param string $serviceSid 
     * @param string $listSid Identifier of the Sync List. Either a SID or a unique name.
     * @param string $identity Arbitrary string identifier representing a user associated with an FPA token, assigned by the developer.
     */
    public function __construct(Version $version, $serviceSid, $listSid, $identity)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['serviceSid' => $serviceSid, 'listSid' => $listSid, 'identity' => $identity];
        $this->uri = '/Services/' . \rawurlencode($serviceSid) . '/Lists/' . \rawurlencode($listSid) . '/Permissions/' . \rawurlencode($identity) . '';
    }
    /**
     * Delete the SyncListPermissionInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete() : bool
    {
        return $this->version->delete('DELETE', $this->uri);
    }
    /**
     * Fetch the SyncListPermissionInstance
     *
     * @return SyncListPermissionInstance Fetched SyncListPermissionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch() : SyncListPermissionInstance
    {
        $payload = $this->version->fetch('GET', $this->uri, [], []);
        return new SyncListPermissionInstance($this->version, $payload, $this->solution['serviceSid'], $this->solution['listSid'], $this->solution['identity']);
    }
    /**
     * Update the SyncListPermissionInstance
     *
     * @param bool $read Boolean flag specifying whether the identity can read the Sync List.
     * @param bool $write Boolean flag specifying whether the identity can create, update and delete Items of the Sync List.
     * @param bool $manage Boolean flag specifying whether the identity can delete the Sync List.
     * @return SyncListPermissionInstance Updated SyncListPermissionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(bool $read, bool $write, bool $manage) : SyncListPermissionInstance
    {
        $data = Values::of(['Read' => Serialize::booleanToString($read), 'Write' => Serialize::booleanToString($write), 'Manage' => Serialize::booleanToString($manage)]);
        $payload = $this->version->update('POST', $this->uri, [], $data);
        return new SyncListPermissionInstance($this->version, $payload, $this->solution['serviceSid'], $this->solution['listSid'], $this->solution['identity']);
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
        return '[Twilio.Preview.Sync.SyncListPermissionContext ' . \implode(' ', $context) . ']';
    }
}
