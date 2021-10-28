<?php

namespace ZetRider\NovaInputsField\Model;

use JsonSerializable;

final class FieldTemplate implements JsonSerializable
{
    private string $key;
    private string $type;
    private array $attributes;
    private array $options;

    public function __construct(string $key, string $type, array $attributes = [], array $options = [])
    {
        $this->key = $key;
        $this->type = $type;
        $this->attributes = $attributes;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return boolean
     */
    public function isMultiple(): bool
    {
        if (in_array($this->type, [FieldType::CHECKBOX]) or array_key_exists('multiple', $this->attributes)) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'type' => $this->type,
            'attributes' => $this->attributes,
            'options' => $this->options,
            'multiple' => $this->isMultiple(),
        ];
    }
}
