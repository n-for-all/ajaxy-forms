<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Trunking
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Trunking\V1\Trunk;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Stream;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
use Isolated\Twilio\Serialize;
class OriginationUrlList extends ListResource
{
    /**
     * Construct the OriginationUrlList
     *
     * @param Version $version Version that contains the resource
     * @param string $trunkSid The SID of the Trunk to associate the resource with.
     */
    public function __construct(Version $version, string $trunkSid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['trunkSid' => $trunkSid];
        $this->uri = '/Trunks/' . \rawurlencode($trunkSid) . '/OriginationUrls';
    }
    /**
     * Create the OriginationUrlInstance
     *
     * @param int $weight The value that determines the relative share of the load the URI should receive compared to other URIs with the same priority. Can be an integer from 1 to 65535, inclusive, and the default is 10. URLs with higher values receive more load than those with lower ones with the same priority.
     * @param int $priority The relative importance of the URI. Can be an integer from 0 to 65535, inclusive, and the default is 10. The lowest number represents the most important URI.
     * @param bool $enabled Whether the URL is enabled. The default is `true`.
     * @param string $friendlyName A descriptive string that you create to describe the resource. It can be up to 64 characters long.
     * @param string $sipUrl The SIP address you want Twilio to route your Origination calls to. This must be a `sip:` schema.
     * @return OriginationUrlInstance Created OriginationUrlInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create(int $weight, int $priority, bool $enabled, string $friendlyName, string $sipUrl) : OriginationUrlInstance
    {
        $data = Values::of(['Weight' => $weight, 'Priority' => $priority, 'Enabled' => Serialize::booleanToString($enabled), 'FriendlyName' => $friendlyName, 'SipUrl' => $sipUrl]);
        $payload = $this->version->create('POST', $this->uri, [], $data);
        return new OriginationUrlInstance($this->version, $payload, $this->solution['trunkSid']);
    }
    /**
     * Reads OriginationUrlInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return OriginationUrlInstance[] Array of results
     */
    public function read(int $limit = null, $pageSize = null) : array
    {
        return \iterator_to_array($this->stream($limit, $pageSize), \false);
    }
    /**
     * Streams OriginationUrlInstance records from the API as a generator stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return Stream stream of results
     */
    public function stream(int $limit = null, $pageSize = null) : Stream
    {
        $limits = $this->version->readLimits($limit, $pageSize);
        $page = $this->page($limits['pageSize']);
        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }
    /**
     * Retrieve a single page of OriginationUrlInstance records from the API.
     * Request is executed immediately
     *
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return OriginationUrlPage Page of OriginationUrlInstance
     */
    public function page($pageSize = Values::NONE, string $pageToken = Values::NONE, $pageNumber = Values::NONE) : OriginationUrlPage
    {
        $params = Values::of(['PageToken' => $pageToken, 'Page' => $pageNumber, 'PageSize' => $pageSize]);
        $response = $this->version->page('GET', $this->uri, $params);
        return new OriginationUrlPage($this->version, $response, $this->solution);
    }
    /**
     * Retrieve a specific page of OriginationUrlInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return OriginationUrlPage Page of OriginationUrlInstance
     */
    public function getPage(string $targetUrl) : OriginationUrlPage
    {
        $response = $this->version->getDomain()->getClient()->request('GET', $targetUrl);
        return new OriginationUrlPage($this->version, $response, $this->solution);
    }
    /**
     * Constructs a OriginationUrlContext
     *
     * @param string $sid The unique string that we created to identify the OriginationUrl resource to delete.
     */
    public function getContext(string $sid) : OriginationUrlContext
    {
        return new OriginationUrlContext($this->version, $this->solution['trunkSid'], $sid);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Trunking.V1.OriginationUrlList]';
    }
}
