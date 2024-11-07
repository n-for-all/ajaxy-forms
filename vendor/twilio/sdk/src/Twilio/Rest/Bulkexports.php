<?php

namespace Isolated\Twilio\Rest;

use Isolated\Twilio\Rest\Bulkexports\V1;
class Bulkexports extends BulkexportsBase
{
    /**
     * @deprecated Use v1->exports instead.
     */
    protected function getExports() : \Isolated\Twilio\Rest\Bulkexports\V1\ExportList
    {
        echo "exports is deprecated. Use v1->exports instead.";
        return $this->v1->exports;
    }
    /**
     * @deprecated Use v1->exports(\$resourceType) instead.
     * @param string $resourceType The type of communication – Messages, Calls,
     *                             Conferences, and Participants
     */
    protected function contextExports(string $resourceType) : \Isolated\Twilio\Rest\Bulkexports\V1\ExportContext
    {
        echo "exports(\$resourceType) is deprecated. Use v1->exports(\$resourceType) instead.";
        return $this->v1->exports($resourceType);
    }
    /**
     * @deprecated Use v1->exportConfiguration instead.
     */
    protected function getExportConfiguration() : \Isolated\Twilio\Rest\Bulkexports\V1\ExportConfigurationList
    {
        echo "exportConfiguration is deprecated. Use v1->exportConfiguration instead.";
        return $this->v1->exportConfiguration;
    }
    /**
     * @deprecated Use v1->exportConfiguration(\$resourceType) instead.
     * @param string $resourceType The type of communication – Messages, Calls,
     *                             Conferences, and Participants
     */
    protected function contextExportConfiguration(string $resourceType) : \Isolated\Twilio\Rest\Bulkexports\V1\ExportConfigurationContext
    {
        echo "rexportConfiguration(\$resourceType) is deprecated. Use v1->exportConfiguration(\$resourceType) instead.";
        return $this->v1->exportConfiguration($resourceType);
    }
}
