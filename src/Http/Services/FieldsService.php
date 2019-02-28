<?php

namespace Infinety\TemplyPages\Http\Services;

use App\Models\Tenant\Template;
use Infinety\TemplyPages\Http\Services\Field;

class FieldsService
{

    /**
     * @return mixed
     */
    public function getFields($resourceId)
    {
        return [
            'fields'       => $this->templateFields($resourceId),
            'staticFields' => [
                'General'      => [
                    $this->textField(),
                    $this->textareaField(),
                    $this->selectField(),
                    $this->numberField(),
                ],
                'Editors'      => [
                    $this->markdownField(),
                    $this->trixField(),
                    $this->redactorField(),
                ],
                'Files'        => [
                    $this->fileField(),
                    $this->avatarField(),
                    $this->imageField(),
                ],
                'Dates'        => [
                    $this->dateField(),
                    $this->dateTimeField(),
                    $this->timezoneField(),
                ],
                'Other fields' => [
                    $this->booleanField(),
                    $this->codeField(),
                    $this->countryField(),
                    $this->currencyField(),
                    $this->passwordField(),
                    $this->placeField(),
                ],
                'Options'      => [
                    $this->repeaterField(),
                ],
            ],
        ];
    }

    /**
     * @param $templateId
     * @return mixed
     */
    public function templateFields($templateId)
    {
        $template = Template::find($templateId);

        return $template->getFields();
    }

    /**
     * File field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function avatarField()
    {
        return new Field(['id' => 2, 'type' => 'avatar', 'component' => 'file-field', 'name' => 'Avatar']);
    }

    /**
     * File field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function booleanField()
    {
        return new Field(['id' => 3, 'type' => 'boolean', 'component' => 'boolean-field', 'name' => 'Boolean']);
    }

    /**
     * File field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function codeField()
    {
        return new Field(['id' => 4, 'type' => 'code', 'component' => 'code-field', 'name' => 'Code']);
    }

    /**
     * Country field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function countryField()
    {
        return new Field(['id' => 5, 'type' => 'country', 'component' => 'country-field', 'name' => 'Country']);
    }

    /**
     * Currency field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function currencyField()
    {
        return new Field(['id' => 6, 'type' => 'currency', 'component' => 'currency-field', 'name' => 'Currency']);
    }

    /**
     * Currency field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function dateField()
    {
        return new Field(['id' => 7, 'type' => 'date', 'component' => 'date-field', 'name' => 'Date']);
    }

    /**
     * Currency field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function dateTimeField()
    {
        return new Field(['id' => 8, 'type' => 'datetime', 'component' => 'datetime-field', 'name' => 'DateTime']);
    }

    /**
     * File field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function fileField()
    {
        return new Field(['id' => 9, 'type' => 'file', 'component' => 'file-field', 'name' => 'File']);
    }

    /**
     * Image field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function imageField()
    {
        return new Field(['id' => 10, 'type' => 'image', 'component' => 'file-field', 'name' => 'Image']);
    }

    /**
     * Markdown field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function markdownField()
    {
        return new Field(['id' => 11, 'type' => 'markdown', 'component' => 'markdown-field', 'name' => 'Markdown']);
    }

    /**
     * Number field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function numberField()
    {
        return new Field(['id' => 12, 'type' => 'number', 'component' => 'number-field', 'name' => 'Number']);
    }

    /**
     * Password field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function passwordField()
    {
        return new Field(['id' => 13, 'type' => 'password', 'component' => 'password-field', 'name' => 'Password']);
    }

    /**
     * Place field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function placeField()
    {
        return new Field(['id' => 14, 'type' => 'place', 'component' => 'place-field', 'name' => 'Place']);
    }

    /**
     * Select field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function selectField()
    {
        return new Field(['id' => 15, 'type' => 'select', 'component' => 'select-field', 'name' => 'Select']);
    }

    /**
     * Text field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function textField()
    {
        return new Field(['id' => 1, 'type' => 'text', 'component' => 'text-field', 'name' => 'Text']);
    }

    /**
     * Textarea field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function textareaField()
    {
        return new Field(['id' => 16, 'type' => 'textarea', 'component' => 'textarea-field', 'name' => 'Textarea']);
    }

    /**
     * Timezone field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function timezoneField()
    {
        return new Field(['id' => 17, 'type' => 'timezone', 'component' => 'timezone-field', 'name' => 'Timezone']);
    }

    /**
     * Timezone field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function trixField()
    {
        return new Field(['id' => 18, 'type' => 'trix', 'component' => 'trix-field', 'name' => 'Trix']);
    }

    /**
     * Redactor field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function redactorField()
    {
        return new Field(['id' => 18, 'type' => 'redactor', 'component' => 'redactor-field', 'name' => 'Redactor']);
    }

    /**
     * Timezone field
     *
     * @return  \Infinety\TemplyPages\Http\Services\Field
     */
    private function repeaterField()
    {
        return new Field(['id' => 19, 'type' => 'repeater', 'component' => 'repeater-field', 'name' => 'Repeater']);
    }
}
