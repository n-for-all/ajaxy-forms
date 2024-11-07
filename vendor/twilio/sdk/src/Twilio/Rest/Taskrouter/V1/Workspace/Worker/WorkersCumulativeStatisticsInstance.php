<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Taskrouter
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Taskrouter\V1\Workspace\Worker;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\InstanceResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Deserialize;
/**
 * @property string|null $accountSid
 * @property \DateTime|null $startTime
 * @property \DateTime|null $endTime
 * @property array[]|null $activityDurations
 * @property int|null $reservationsCreated
 * @property int|null $reservationsAccepted
 * @property int|null $reservationsRejected
 * @property int|null $reservationsTimedOut
 * @property int|null $reservationsCanceled
 * @property int|null $reservationsRescinded
 * @property string|null $workspaceSid
 * @property string|null $url
 */
class WorkersCumulativeStatisticsInstance extends InstanceResource
{
    /**
     * Initialize the WorkersCumulativeStatisticsInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $workspaceSid The SID of the Workspace with the resource to fetch.
     */
    public function __construct(Version $version, array $payload, string $workspaceSid)
    {
        parent::__construct($version);
        // Marshaled Properties
        $this->properties = ['accountSid' => Values::array_get($payload, 'account_sid'), 'startTime' => Deserialize::dateTime(Values::array_get($payload, 'start_time')), 'endTime' => Deserialize::dateTime(Values::array_get($payload, 'end_time')), 'activityDurations' => Values::array_get($payload, 'activity_durations'), 'reservationsCreated' => Values::array_get($payload, 'reservations_created'), 'reservationsAccepted' => Values::array_get($payload, 'reservations_accepted'), 'reservationsRejected' => Values::array_get($payload, 'reservations_rejected'), 'reservationsTimedOut' => Values::array_get($payload, 'reservations_timed_out'), 'reservationsCanceled' => Values::array_get($payload, 'reservations_canceled'), 'reservationsRescinded' => Values::array_get($payload, 'reservations_rescinded'), 'workspaceSid' => Values::array_get($payload, 'workspace_sid'), 'url' => Values::array_get($payload, 'url')];
        $this->solution = ['workspaceSid' => $workspaceSid];
    }
    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return WorkersCumulativeStatisticsContext Context for this WorkersCumulativeStatisticsInstance
     */
    protected function proxy() : WorkersCumulativeStatisticsContext
    {
        if (!$this->context) {
            $this->context = new WorkersCumulativeStatisticsContext($this->version, $this->solution['workspaceSid']);
        }
        return $this->context;
    }
    /**
     * Fetch the WorkersCumulativeStatisticsInstance
     *
     * @param array|Options $options Optional Arguments
     * @return WorkersCumulativeStatisticsInstance Fetched WorkersCumulativeStatisticsInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []) : WorkersCumulativeStatisticsInstance
    {
        return $this->proxy()->fetch($options);
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
        return '[Twilio.Taskrouter.V1.WorkersCumulativeStatisticsInstance ' . \implode(' ', $context) . ']';
    }
}
