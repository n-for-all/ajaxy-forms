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
namespace Isolated\Twilio\Rest\Taskrouter\V1\Workspace\TaskQueue;

use Isolated\Twilio\ListResource;
use Isolated\Twilio\Version;
class TaskQueueCumulativeStatisticsList extends ListResource
{
    /**
     * Construct the TaskQueueCumulativeStatisticsList
     *
     * @param Version $version Version that contains the resource
     * @param string $workspaceSid The SID of the Workspace with the TaskQueue to fetch.
     * @param string $taskQueueSid The SID of the TaskQueue for which to fetch statistics.
     */
    public function __construct(Version $version, string $workspaceSid, string $taskQueueSid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['workspaceSid' => $workspaceSid, 'taskQueueSid' => $taskQueueSid];
    }
    /**
     * Constructs a TaskQueueCumulativeStatisticsContext
     */
    public function getContext() : TaskQueueCumulativeStatisticsContext
    {
        return new TaskQueueCumulativeStatisticsContext($this->version, $this->solution['workspaceSid'], $this->solution['taskQueueSid']);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Taskrouter.V1.TaskQueueCumulativeStatisticsList]';
    }
}
