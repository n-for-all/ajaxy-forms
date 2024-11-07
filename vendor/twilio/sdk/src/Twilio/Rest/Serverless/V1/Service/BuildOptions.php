<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Serverless
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */
namespace Isolated\Twilio\Rest\Serverless\V1\Service;

use Isolated\Twilio\Options;
use Isolated\Twilio\Values;
use Isolated\Twilio\Version;
abstract class BuildOptions
{
    /**
     * @param string[] $assetVersions The list of Asset Version resource SIDs to include in the Build.
     * @param string[] $functionVersions The list of the Function Version resource SIDs to include in the Build.
     * @param string $dependencies A list of objects that describe the Dependencies included in the Build. Each object contains the `name` and `version` of the dependency.
     * @param string $runtime The Runtime version that will be used to run the Build resource when it is deployed.
     * @return CreateBuildOptions Options builder
     */
    public static function create(array $assetVersions = Values::ARRAY_NONE, array $functionVersions = Values::ARRAY_NONE, string $dependencies = Values::NONE, string $runtime = Values::NONE) : CreateBuildOptions
    {
        return new CreateBuildOptions($assetVersions, $functionVersions, $dependencies, $runtime);
    }
}
class CreateBuildOptions extends Options
{
    /**
     * @param string[] $assetVersions The list of Asset Version resource SIDs to include in the Build.
     * @param string[] $functionVersions The list of the Function Version resource SIDs to include in the Build.
     * @param string $dependencies A list of objects that describe the Dependencies included in the Build. Each object contains the `name` and `version` of the dependency.
     * @param string $runtime The Runtime version that will be used to run the Build resource when it is deployed.
     */
    public function __construct(array $assetVersions = Values::ARRAY_NONE, array $functionVersions = Values::ARRAY_NONE, string $dependencies = Values::NONE, string $runtime = Values::NONE)
    {
        $this->options['assetVersions'] = $assetVersions;
        $this->options['functionVersions'] = $functionVersions;
        $this->options['dependencies'] = $dependencies;
        $this->options['runtime'] = $runtime;
    }
    /**
     * The list of Asset Version resource SIDs to include in the Build.
     *
     * @param string[] $assetVersions The list of Asset Version resource SIDs to include in the Build.
     * @return $this Fluent Builder
     */
    public function setAssetVersions(array $assetVersions) : self
    {
        $this->options['assetVersions'] = $assetVersions;
        return $this;
    }
    /**
     * The list of the Function Version resource SIDs to include in the Build.
     *
     * @param string[] $functionVersions The list of the Function Version resource SIDs to include in the Build.
     * @return $this Fluent Builder
     */
    public function setFunctionVersions(array $functionVersions) : self
    {
        $this->options['functionVersions'] = $functionVersions;
        return $this;
    }
    /**
     * A list of objects that describe the Dependencies included in the Build. Each object contains the `name` and `version` of the dependency.
     *
     * @param string $dependencies A list of objects that describe the Dependencies included in the Build. Each object contains the `name` and `version` of the dependency.
     * @return $this Fluent Builder
     */
    public function setDependencies(string $dependencies) : self
    {
        $this->options['dependencies'] = $dependencies;
        return $this;
    }
    /**
     * The Runtime version that will be used to run the Build resource when it is deployed.
     *
     * @param string $runtime The Runtime version that will be used to run the Build resource when it is deployed.
     * @return $this Fluent Builder
     */
    public function setRuntime(string $runtime) : self
    {
        $this->options['runtime'] = $runtime;
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
        return '[Twilio.Serverless.V1.CreateBuildOptions ' . $options . ']';
    }
}
