<?php

namespace Infinety\TemplyPages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infinety\TemplyPages\Http\Services\CreateField;
use Infinety\TemplyPages\Models\TemplateField;

class Template extends Model
{

    /**
     * @var array
     */
    protected $with = ['fields'];

    /**
     * @var array
     */
    protected $fieldsArray = [];

    /**
     * TemplateField of template
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(TemplateField::class);
    }

    /**
     * TemplateField of template
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(GroupTemplates::class, 'group_id');
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields->toTree();
    }

    /**
     * @return mixed
     */
    public function getFieldsToPage(array $values)
    {
        $tree = $this->fields->toTree();

        $sections = $this->formatFields('form', $tree->toArray(), null, $values);

        return ['sections' => $sections, 'values' => $values];
    }

    /**
     * @param array $values
     */
    public function getFieldsToPageWithValues(array $values)
    {
        $tree = $this->fields->toTree();
        $this->fieldsArray = collect([]);
        $this->fieldsKeys($tree->toArray());

        $sections = $this->formatFields('detail', $tree->toArray(), null, $values);

        return ['sections' => $sections, 'values' => $values];
    }

    /**
     * @param $data
     */
    private function fieldsKeys($data)
    {
        $index = 0;
        foreach ($data as $section) {
            foreach ($section['children'] as $field) {
                if (isset($field['type']) and $field['type'] == 'repeater') {
                    $repeatedTimes = (int) $field['field']['repeatTimes'];
                    $this->repeaterField($field['children'], $index, $repeatedTimes);
                } else {
                    $this->fieldsArray->push($field['uuid']);
                }
            }
        }
    }

    /**
     * @param $field
     * @param $parentIndex
     */
    private function repeaterField($fields, $parentIndex, $repeatedTimes)
    {
        $repeatedTimes = $repeatedTimes + 1;
        for ($number = 1; $number < $repeatedTimes; $number++) {
            $indexRepeater = 0;

            foreach ($fields as $children) {
                if (isset($children['type']) and $children['type'] == 'repeater') {
                    $repeat = (int) $children['field']['repeatTimes'];
                    $this->repeaterField($children['children'], $number, $repeat);
                } else {
                    $fieldNewKey = $this->getNewKey($parentIndex, $number, $indexRepeater, $children);
                    $this->fieldsArray->push($fieldNewKey);
                }

                $indexRepeater++;
            }
        }
    }

    /**
     * @param $parentIndex
     * @param $number
     * @param $indexRepeater
     * @param $field
     */
    private function getNewKey($parentIndex, $number, $indexRepeater, $field)
    {
        if ($parentIndex != 0) {
            return $field['uuid'].'_'.$parentIndex.'_'.$number;
        }

        return $field['uuid'].'_'.$number.'_'.($indexRepeater + 1);
    }

    /**
     * @param $root
     */
    private function formatFields($pageType, $root, $parent = null, $values = null)
    {
        $data = [];
        foreach ($root as $field) {
            $data[$field['id']]['name'] = $field['name'];
            $data[$field['id']]['errors'] = [];

            if (isset($field['children']) && count($field['children']) > 0) {
                $data[$field['id']]['children'] = $this->formatFields($pageType, $field['children'], $field['name'], $values);
            }

            $fieldData = $this->createField($pageType, $field, $parent, $values);
            $data[$field['id']]['field'] = $fieldData;
        }

        return $data;
    }

    /**
     * @param $field
     * @param $section
     */
    private function createField($pageType, $field, $section, $values)
    {
        $fieldInfo = new CreateField($pageType, $field['uuid'], $field['component'], $field['data'], $section, $values);

        return $fieldInfo();
    }
}
