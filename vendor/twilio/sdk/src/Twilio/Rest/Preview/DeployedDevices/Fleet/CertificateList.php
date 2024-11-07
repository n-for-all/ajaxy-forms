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
namespace Isolated\Twilio\Rest\Preview\DeployedDevices\Fleet;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Options;
use Isolated\Twilio\Stream;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
class CertificateList extends ListResource
{
    /**
     * Construct the CertificateList
     *
     * @param Version $version Version that contains the resource
     * @param string $fleetSid 
     */
    public function __construct(Version $version, string $fleetSid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['fleetSid' => $fleetSid];
        $this->uri = '/Fleets/' . \rawurlencode($fleetSid) . '/Certificates';
    }
    /**
     * Create the CertificateInstance
     *
     * @param string $certificateData Provides a URL encoded representation of the public certificate in PEM format.
     * @param array|Options $options Optional Arguments
     * @return CertificateInstance Created CertificateInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create(string $certificateData, array $options = []) : CertificateInstance
    {
        $options = new Values($options);
        $data = Values::of(['CertificateData' => $certificateData, 'FriendlyName' => $options['friendlyName'], 'DeviceSid' => $options['deviceSid']]);
        $payload = $this->version->create('POST', $this->uri, [], $data);
        return new CertificateInstance($this->version, $payload, $this->solution['fleetSid']);
    }
    /**
     * Reads CertificateInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param array|Options $options Optional Arguments
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return CertificateInstance[] Array of results
     */
    public function read(array $options = [], int $limit = null, $pageSize = null) : array
    {
        return \iterator_to_array($this->stream($options, $limit, $pageSize), \false);
    }
    /**
     * Streams CertificateInstance records from the API as a generator stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param array|Options $options Optional Arguments
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
    public function stream(array $options = [], int $limit = null, $pageSize = null) : Stream
    {
        $limits = $this->version->readLimits($limit, $pageSize);
        $page = $this->page($options, $limits['pageSize']);
        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }
    /**
     * Retrieve a single page of CertificateInstance records from the API.
     * Request is executed immediately
     *
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return CertificatePage Page of CertificateInstance
     */
    public function page(array $options = [], $pageSize = Values::NONE, string $pageToken = Values::NONE, $pageNumber = Values::NONE) : CertificatePage
    {
        $options = new Values($options);
        $params = Values::of(['DeviceSid' => $options['deviceSid'], 'PageToken' => $pageToken, 'Page' => $pageNumber, 'PageSize' => $pageSize]);
        $response = $this->version->page('GET', $this->uri, $params);
        return new CertificatePage($this->version, $response, $this->solution);
    }
    /**
     * Retrieve a specific page of CertificateInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return CertificatePage Page of CertificateInstance
     */
    public function getPage(string $targetUrl) : CertificatePage
    {
        $response = $this->version->getDomain()->getClient()->request('GET', $targetUrl);
        return new CertificatePage($this->version, $response, $this->solution);
    }
    /**
     * Constructs a CertificateContext
     *
     * @param string $sid Provides a 34 character string that uniquely identifies the requested Certificate credential resource.
     */
    public function getContext(string $sid) : CertificateContext
    {
        return new CertificateContext($this->version, $this->solution['fleetSid'], $sid);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Preview.DeployedDevices.CertificateList]';
    }
}
