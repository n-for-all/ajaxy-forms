<?php

namespace Ajaxy\Forms\Inc;

/**
 * A submitted field as the action templates see it.
 *
 * Printing it gives the value as it reads, while .value, .value_label,
 * .label and .type stay available, so {{data['email']}} and
 * {{data['email'].value}} both work.
 */
class TemplateField implements \ArrayAccess, \JsonSerializable
{
    public $type = '';
    public $value = '';
    public $value_label = '';
    public $label = '';

    public function __construct(array $field)
    {
        $this->type = $field['type'] ?? '';
        $this->value = $field['value'] ?? '';
        $this->value_label = $field['value_label'] ?? $this->value;
        $this->label = $field['label'] ?? '';
    }

    /**
     * Wrap the parsed submission so that the templates can read it either way
     *
     * @param array $data
     *
     * @return array
     */
    public static function from_data($data)
    {
        $fields = [];
        foreach ((array)$data as $name => $field) {
            $fields[$name] = \is_array($field) && isset($field['value']) ? new self($field) : $field;
        }

        return $fields;
    }

    public function __toString()
    {
        return self::stringify($this->value_label);
    }

    private static function stringify($value)
    {
        if (\is_array($value)) {
            return \implode(', ', \array_map([self::class, 'stringify'], $value));
        }

        if (\is_bool($value)) {
            return $value ? '1' : '0';
        }

        return (string)$value;
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return \in_array($offset, ['type', 'value', 'value_label', 'label'], true);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->{$offset} : null;
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $this->{$offset} = $value;
        }
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset) {}

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'value' => $this->value,
            'value_label' => $this->value_label,
            'label' => $this->label,
        ];
    }
}
