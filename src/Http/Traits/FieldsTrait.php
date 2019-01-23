<?php

namespace Infinety\TemplyPages\Http\Traits;

use Infinety\TemplyPages\Models\Template;
use Infinety\TemplyPages\Models\TemplateField;
use Webpatser\Uuid\Uuid;

trait FieldsTrait
{
    /**
     * @param $fields
     * @param $parentID
     */
    public function saveFieldsItems(Template $template, array $fields, $parent = null, $lastItem = null)
    {
        foreach ($fields as $field) {
            $data = [];
            if ($field['component'] == 'section') {
                $parent = null;
            }

            if (isset($field['extras'])) {
                $data = $field['extras'];
            }

            $templateField = $this->saveSingleField($template, $field, $lastItem, $data, $parent);

            if (isset($field['children']) && count($field['children'])) {
                $this->saveFieldsItems($template, $field['children'], $templateField, $templateField);
            }
        }

        return true;
    }

    /**
     * Save single field
     *
     * @param   Template  $template
     * @param   array     $info
     * @param   array     $data
     * @param   integer   $parentId
     * @param   lastItem  $position
     *
     * @return  \Infinety\TemplyPages\Models\TemplateField
     */
    public function saveSingleField(Template $template, array $info, $lastItem, array $data = [], $parent = null)
    {
        $data = $this->setMoreInfo($data, $info);

        $field = new TemplateField();
        $field->uuid = $info['uuid'] ?? $this->generateUuid();
        $field->name = $info['name'];
        $field->component = $info['component'];
        $field->data = $data;
        $field->template_id = $template->id;

        if ($lastItem != null) {
            $field->afterNode($lastItem);
        }

        if ($parent != null) {
            $field->appendToNode($parent)->save();
        } else {
            $field->saveAsRoot();
        }

        return $field;
    }

    /**
     * @param $data
     * @param $info
     */
    private function setMoreInfo($data, $info)
    {
        if (isset($info['type'])) {
            $data['type'] = $info['type'];
        }

        if (!isset($data['name']) && isset($info['name'])) {
            $data['name'] = $info['name'];
        }

        return $data;
    }

    private function generateUuid()
    {
        return Uuid::generate()->string;
    }
}
