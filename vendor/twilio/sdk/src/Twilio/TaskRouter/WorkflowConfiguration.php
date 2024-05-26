<?php

namespace Twilio\TaskRouter;

/**
 * Twilio TaskRouter Workflow Builder
 *
 * @author Justin Witz <jwitz@twilio.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 */
class WorkflowConfiguration implements \JsonSerializable {
    public $filters;
    public $default_filter;

    public function __construct(array $filters, $default_filter = null) {
        $this->filters = $filters;
        $this->default_filter = $default_filter;
    }

    public function toJSON() {
        return \wp_json_encode($this);
    }

    public static function parse(string $json) {
        return \json_decode($json);
    }

    public static function fromJson(string $json): WorkflowConfiguration {
        $configJSON = self::parse($json);
        $default_filter = $configJSON->task_routing->default_filter;
        $filters = [];
        foreach ($configJSON->task_routing->filters as $filter) {
            // friendly_name and filter_friendly_name should map to same variable
            $friendly_name = $filter->filter_friendly_name ?? $filter->friendly_name;
            $filter = new WorkflowRule($filter->expression, $filter->targets, $friendly_name);
            $filters[] = $filter;
        }
        return new WorkflowConfiguration($filters, $default_filter);
    }

    public function jsonSerialize(): array {
        $json = [];
        $task_routing = [];
        $task_routing['filters'] = $this->filters;
        $task_routing['default_filter'] = $this->default_filter;
        $json['task_routing'] = $task_routing;
        return $json;
    }
}