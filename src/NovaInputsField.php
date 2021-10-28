<?php

namespace ZetRider\NovaInputsField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use ZetRider\NovaInputsField\Model\FieldType;
use ZetRider\NovaInputsField\Model\FieldTemplate;

class NovaInputsField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-inputs-field';

    /**
     * Field templates
     *
     * @var array
     */
    public $templates = [];

    /** @inheritDoc */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }
    }

    /** @inheritDoc */
    protected function resolveAttribute($resource, $attribute)
    {
        $value = data_get($resource, str_replace('->', '.', $attribute));

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (!is_array($value)) {
            $value = [];
        }

        $value = $this->fillTemplateData($value);

        return $value;
    }

    /**
     * Fill data
     * @param array $value
     * @return array
     */
    private function fillTemplateData(array $value): array
    {
        foreach ($value as $key => $data) {

            if (!is_array($data)) {
                $data = [];
            }

            foreach ($this->templates as $template) {
                $this->validateTemplateData($template, $data);
            }

            $value[$key] = $data;
        }
        return $value;
    }

    /**
     * Validate template data
     *
     * @param FieldTemplate $template
     * @param array $data
     * @return $this
     */
    private function validateTemplateData(FieldTemplate $template, array &$data)
    {
        $this->validateTemplateKeyData($template, $data);
        $this->validateDataKeyTemplate($template, $data);
        $this->validateTemplateValueData($template, $data);

        return $this;
    }

    /**
     * Insert template key to array when is not exists
     *
     * @param FieldTemplate $template
     * @param array $data
     * @return $this
     */
    private function validateTemplateKeyData(FieldTemplate $template, array &$data)
    {
        if (!array_key_exists($template->key(), $data)) {
            $data[$template->key()] = '';
        }

        return $this;
    }

    /**
     * Unset excess template key
     *
     * @param FieldTemplate $template
     * @param array $data
     * @return $this
     */
    private function validateDataKeyTemplate(FieldTemplate $template, array &$data)
    {
        foreach ($data as $key => $values) {
            if (!array_key_exists($key, $this->templates)) {
                unset($data[$key]);
            }
        }

        if (!is_array($data)) {
            $data = [];
        }

        return $this;
    }

    /**
     * Check template data type
     *
     * @param FieldTemplate $template
     * @param array $data
     * @return $this
     */
    private function validateTemplateValueData(FieldTemplate $template, array &$data)
    {
        if ($template->isMultiple() === true and is_array($data[$template->key()]) === false) {
            $data[$template->key()] = [];
        }

        return $this;
    }

    /**
     * Sets input's template
     *
     * @param string $key
     * @param string $type
     * @param array $attributes
     * @param array $options
     * @return $this
     **/
    private function template($key, $type, $attributes = [], $options = [])
    {
        $this->templates[$key] = new FieldTemplate($key, $type, $attributes, $options);
        return $this->withMeta(['templates' => $this->templates]);
    }

    /**
     * Set input
     *
     * @param string $key
     * @param array $attributes
     * @return $this
     */
    public function input(string $key, $attributes = [])
    {
        return $this->template($key, FieldType::INPUT, $attributes);
    }

    /**
     * Set checkbox
     *
     * @param string $key
     * @param array $attributes
     * @return $this
     */
    public function checkbox(string $key, $attributes = [], $options = [])
    {
        return $this->template($key, FieldType::CHECKBOX, $attributes, $options);
    }

    /**
     * Set radio
     *
     * @param string $key
     * @param array $attributes
     * @return $this
     */
    public function radio(string $key, $attributes = [], $options = [])
    {
        return $this->template($key, FieldType::RADIO, $attributes, $options);
    }

    /**
     * Set select
     *
     * @param string $key
     * @param array $attributes
     * @return $this
     */
    public function select(string $key, $attributes = [], $options = [])
    {
        return $this->template($key, FieldType::SELECT, $attributes, $options);
    }
}
