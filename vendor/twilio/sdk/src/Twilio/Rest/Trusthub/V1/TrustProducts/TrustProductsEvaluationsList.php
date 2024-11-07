<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Trusthub
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Trusthub\V1\TrustProducts;

use Isolated\Twilio\Exceptions\TwilioException;
use Isolated\Twilio\ListResource;
use Isolated\Twilio\Stream;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
class TrustProductsEvaluationsList extends ListResource
{
    /**
     * Construct the TrustProductsEvaluationsList
     *
     * @param Version $version Version that contains the resource
     * @param string $trustProductSid The unique string that we created to identify the trust_product resource.
     */
    public function __construct(Version $version, string $trustProductSid)
    {
        parent::__construct($version);
        // Path Solution
        $this->solution = ['trustProductSid' => $trustProductSid];
        $this->uri = '/TrustProducts/' . \rawurlencode($trustProductSid) . '/Evaluations';
    }
    /**
     * Create the TrustProductsEvaluationsInstance
     *
     * @param string $policySid The unique string of a policy that is associated to the customer_profile resource.
     * @return TrustProductsEvaluationsInstance Created TrustProductsEvaluationsInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create(string $policySid) : TrustProductsEvaluationsInstance
    {
        $data = Values::of(['PolicySid' => $policySid]);
        $payload = $this->version->create('POST', $this->uri, [], $data);
        return new TrustProductsEvaluationsInstance($this->version, $payload, $this->solution['trustProductSid']);
    }
    /**
     * Reads TrustProductsEvaluationsInstance records from the API as a list.
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
     * @return TrustProductsEvaluationsInstance[] Array of results
     */
    public function read(int $limit = null, $pageSize = null) : array
    {
        return \iterator_to_array($this->stream($limit, $pageSize), \false);
    }
    /**
     * Streams TrustProductsEvaluationsInstance records from the API as a generator stream.
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
     * Retrieve a single page of TrustProductsEvaluationsInstance records from the API.
     * Request is executed immediately
     *
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return TrustProductsEvaluationsPage Page of TrustProductsEvaluationsInstance
     */
    public function page($pageSize = Values::NONE, string $pageToken = Values::NONE, $pageNumber = Values::NONE) : TrustProductsEvaluationsPage
    {
        $params = Values::of(['PageToken' => $pageToken, 'Page' => $pageNumber, 'PageSize' => $pageSize]);
        $response = $this->version->page('GET', $this->uri, $params);
        return new TrustProductsEvaluationsPage($this->version, $response, $this->solution);
    }
    /**
     * Retrieve a specific page of TrustProductsEvaluationsInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return TrustProductsEvaluationsPage Page of TrustProductsEvaluationsInstance
     */
    public function getPage(string $targetUrl) : TrustProductsEvaluationsPage
    {
        $response = $this->version->getDomain()->getClient()->request('GET', $targetUrl);
        return new TrustProductsEvaluationsPage($this->version, $response, $this->solution);
    }
    /**
     * Constructs a TrustProductsEvaluationsContext
     *
     * @param string $sid The unique string that identifies the Evaluation resource.
     */
    public function getContext(string $sid) : TrustProductsEvaluationsContext
    {
        return new TrustProductsEvaluationsContext($this->version, $this->solution['trustProductSid'], $sid);
    }
    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString() : string
    {
        return '[Twilio.Trusthub.V1.TrustProductsEvaluationsList]';
    }
}
