<?php

namespace Ajaxy\Forms\Admin\Inc;

class Helper
{
    /**
     * Converts base name to input name with parent
     *
     * @date 2024-05-21
     *
     * @param string $base_name
     * @param string $parent
     *
     * @return string
     */
    public static function convert_input_name($base_name, $parent = 'form_action')
    {
        if (!$base_name || trim($base_name) == "") {
            return '';
        }
        // Regular expression pattern (allows nested brackets)
        $pattern = "/^([a-z\_]+)(?:(\[.*\]))*$/i";

        // Use preg_match to check if the string matches the pattern
        if (preg_match($pattern, $base_name, $matches)) {
            // Extract captured groups (base action and values)
            $baseAction = $matches[1];
            $name = sprintf('%s[%s]', $parent, $baseAction);

            if (isset($matches[2]) && !empty($matches[2])) {
                $name .= $matches[2];
            }
            return $name;
        }
        return $parent;
    }

    /**
     * Converts an associative array to HTML attributes
     *
     * @date 2024-05-21
     *
     * @param array $attributes
     *
     * @return string
     */

    public static function convert_array_to_attributes($attributes)
    {
        // Validate input as associative array
        if (!is_array($attributes) || array_keys($attributes) == range(0, count($attributes) - 1)) {
            throw new \Exception('Input must be an associative array');
        }

        $htmlAttributes = [];
        foreach ($attributes as $key => $value) {
            $key = \strtolower($key);
            if (!\in_array($key, ["required", "selected", "multiple"]) && ((\is_string($value) && (empty($value) || \trim($value) == "") || \is_null($value)))) {
                continue;
            }

            if (\in_array($key, ["required", "selected", "multiple"])) {
                if ($value == true || $value == "1") {
                    $value = true;
                } else {
                    continue;
                }
            }
            // Handle boolean attributes (without value in JS, empty string in PHP)
            if (is_bool($value)) {
                $value = $value ? $key : ''; // Set empty string for true booleans
            }
            // Escape special characters for security
            $escapedValue = htmlspecialchars((string)$value, ENT_QUOTES);

            $htmlAttributes[] = "$key=\"$escapedValue\"";
        }

        return implode(' ', $htmlAttributes);
    }
}
