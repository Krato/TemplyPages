<?php

namespace Infinety\TemplyPages\Http\Services;

use DateTimeZone;
use Illuminate\Support\Facades\Storage;

class CreateField
{
    /**
     * @var mixed
     */
    protected $pageType = null;

    /**
     * @var mixed
     */
    protected $uuid;

    /**
     * @var mixed
     */
    public $name;

    /**
     * @var mixed
     */
    protected $component;

    /**
     * @var mixed
     */
    protected $section;

    /**
     * @var mixed
     */
    protected $values;

    /**
     * @var  array
     */
    protected $jsonData;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param $name
     * @param $component
     * @param $value
     */
    public function __construct($pageType, $uuid, $component, $jsonData, $section = null, $values = null)
    {
        $this->pageType = $pageType;
        $this->uuid = $uuid;
        $this->component = $component;
        $this->jsonData = $jsonData;
        $this->name = $jsonData['name'];
        $this->section = $section;
        $this->values = $values;
        $this->createData();
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->data;
    }

    private function createData()
    {
        $this->data = [
            'key'             => $this->uuid,
            'attribute'       => $this->getKey(),
            'component'       => $this->component,
            'vue'             => $this->getCorrectVueComponent('form-text-field'),
            'indexName'       => $this->name,
            'name'            => $this->name,
            'panel'           => $this->section,
            'prefixComponent' => true,
            'sortable'        => true,
            'textAlign'       => 'left',
            'value'           => null,
            'helpText'        => $this->getHelp(),
        ];

        $this->checkExtraInfo();
        $this->checkValueinfo();
    }

    private function getKey()
    {
        return camel_case(str_slug($this->name));
    }

    public function getAttribute()
    {
        return str_slug($this->name);
    }

    /**
     * @return mixed
     */
    public function getHelp()
    {
        if (isset($this->jsonData['help'])) {
            return $this->jsonData['help'];
        }

        return null;
    }

    private function checkExtraInfo()
    {
        if (str_contains($this->component, 'avatar') ||
            str_contains($this->component, 'image') ||
            str_contains($this->component, 'file')) {
            $this->fileFields();
        }

        if (str_contains($this->component, 'code')) {
            $this->codeField();
        }
        if (str_contains($this->component, 'country')) {
            $this->codeField();
        }

        if (str_contains($this->component, 'textarea')) {
            $this->textAreaField();
        }

        if (str_contains($this->component, 'currency')) {
            $this->currencyField();
        }

        if (str_contains($this->component, 'date')) {
            $this->dateField();
        }

        if (str_contains($this->component, 'datetime')) {
            $this->dateTimeField();
        }

        if (str_contains($this->component, 'timezone')) {
            $this->timeZoneField();
        }

        if (str_contains($this->component, 'trix')) {
            $this->trixField();
        }

        if (str_contains($this->component, 'markdown')) {
            $this->markdownField();
        }

        if (str_contains($this->component, 'number')) {
            $this->numberField();
        }

        if (str_contains($this->component, 'place')) {
            $this->placeField();
        }

        if (str_contains($this->component, 'select')) {
            $this->selectField();
        }

        if (str_contains($this->component, 'boolean')) {
            $this->booleanField();
        }

        if (str_contains($this->component, 'repeater')) {
            $this->repeaterField();
        }
    }

    private function checkValueinfo()
    {
        if (isset($this->values[$this->data['key']])) {
            $value = $this->values[$this->data['key']];

            if ($this->data['component'] == 'file-field') {
                $url = Storage::disk($this->data['disk'])->url($value['name']);

                if (str_contains($this->jsonData['type'], 'avatar') || str_contains($this->jsonData['type'], 'image')) {
                    $this->data['thumbnailUrl'] = $url;
                    $this->data['previewUrl'] = $url;
                }

                $this->data['value'] = $value['name'];
            } else {
                $this->data['value'] = $value;
            }
        }
    }

    /**
     * @param $vueComponentName
     * @return mixed
     */
    private function getCorrectVueComponent($vueComponentName)
    {
        if ($this->pageType == 'detail') {
            return str_replace('form', 'detail', $vueComponentName);
        }

        return $vueComponentName;
    }

    /**
     * Set extra info for avatars
     */
    private function fileFields()
    {
        $disk = 'public';

        $newData = [
            'type'         => $this->jsonData['type'],
            'component'    => 'file-field',
            'vue'          => $this->getCorrectVueComponent('form-file-field'),
            'deletable'    => true,
            'downloadable' => false,
            'deletable'    => false,
            'previewUrl'   => null,
            'thumbnailUrl' => null,
            'disk'         => $disk,
            'textAlign'    => 'center',
        ];

        $this->data = array_merge($this->data, $newData);
    }

    /**
     * Set extra info for code
     */
    private function codeField()
    {
        $mode = false;
        if (isset($this->jsonData['language'])) {
            if ($this->jsonData['language'] == 'json') {
                $mode = 'application/json';
            } else {
                $mode = $this->jsonData['language'];
            }
        }

        $newData = [
            'mode' => $mode,
            'vue'  => $this->getCorrectVueComponent('form-code-field'),
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function countryField()
    {
        $newData = [
            'component' => 'select-field',
            'vue'       => $this->getCorrectVueComponent('form-select-field'),
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function textAreaField()
    {
        $newData = [
            'component' => 'textarea-field',
            'vue'       => $this->getCorrectVueComponent('form-textarea-field'),
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function currencyField()
    {
        $newData = [
            'component' => $this->getCorrectVueComponent('text-field'),
            'type'      => 'number',
            'step'      => '0.01',
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function dateField()
    {
        $newData = [
            'component' => 'date',
            'vue'       => $this->getCorrectVueComponent('form-date'),
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function dateTimeField()
    {
        $newData = [
            'component' => 'date-time',
            'vue'       => $this->getCorrectVueComponent('form-date-time'),
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function timeZoneField()
    {
        $newData = [
            'component' => 'select-field',
            'vue'       => $this->getCorrectVueComponent('form-select-field'),
        ];

        $newData['options'] = $this->options(collect(DateTimeZone::listIdentifiers(DateTimeZone::ALL))->mapWithKeys(function ($timezone) {
            return [$timezone => $timezone];
        })->all());

        $this->data = array_merge($this->data, $newData);
    }

    private function trixField()
    {
        $newData = [
            'component' => 'trix-field',
            'vue'       => $this->getCorrectVueComponent('form-trix-field'),
            'withFiles' => (isset($this->jsonData['disk']) && !empty($this->jsonData['disk'])) ? true : false,
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function markdownField()
    {
        $newData = [
            'component' => 'markdown-field',
            'vue'       => $this->getCorrectVueComponent('form-markdown-field'),
            'withFiles' => (isset($this->jsonData['disk']) && !empty($this->jsonData['disk'])) ? true : false,
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function numberField()
    {
        $newData = [
            'component' => 'text-field',
            'type'      => 'number',
        ];

        if (isset($this->jsonData['min'])) {
            $newData['min'] = $this->jsonData['min'];
        }

        if (isset($this->jsonData['max'])) {
            $newData['max'] = $this->jsonData['max'];
        }

        if (isset($this->jsonData['step'])) {
            $newData['step'] = $this->jsonData['step'];
        }

        $this->data = array_merge($this->data, $newData);
    }

    private function placeField()
    {
        $countries = [];

        if (count($this->jsonData['countries']) > 0) {
            $countries = explode(',', $this->jsonData['countries']);
        }

        $newData = [
            'component'         => 'place-field',
            'vue'               => $this->getCorrectVueComponent('form-place-field'),
            'city'              => 'city',
            'countries'         => $countries,
            'country'           => 'country',
            'postalCode'        => 'postalCode',
            'secondAddressLine' => 'secondAddressLine',
            'state'             => 'state',
        ];

        if (isset($this->jsonData['onlyCities']) && $this->jsonData['onlyCities'] == true) {
            $newData['placeType'] = 'city';
        }

        $this->data = array_merge($this->data, $newData);
    }

    private function selectField()
    {
        $options = [];

        if (count($this->jsonData['options']) > 0) {
            $options = $this->options($this->jsonData['options']);
        }

        $newData = [
            'component' => 'select-field',
            'vue'       => $this->getCorrectVueComponent('form-select-field'),
            'options'   => $options,
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function booleanField()
    {
        $newData = [
            'component' => 'boolean-field',
            'vue'       => $this->getCorrectVueComponent('form-boolean-field'),
        ];

        $this->data = array_merge($this->data, $newData);
    }

    private function repeaterField()
    {
        $newData = [
            'component'   => 'repeater',
            'vue'         => 'repeater',
            'repeatTimes' => (int) $this->jsonData['repeatTimes'],
        ];

        $this->data = array_merge($this->data, $newData);
    }

    /**
     * Set the options for the select menu.
     *
     * @param  array  $options
     * @return $this
     */
    private function options($options)
    {
        return collect($options ?? [])->map(function ($label, $value) {
            return ['label' => $label, 'value' => $value];
        })->values()->all();
    }
}
