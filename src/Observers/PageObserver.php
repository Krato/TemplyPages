<?php

namespace Infinety\TemplyPages\Observers;

use App\Models\Tenant\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PageObserver
{

    /**
     * @var mixed
     */
    protected $page;

    /**
     * @var mixed
     */
    protected $fields;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $dirtyData;

    /**
     * Listen to the User creating event
     *
     * @param Page $page
     */
    public function creating(Page $page)
    {
        if ($page->template_id == 1) {
            $page->type = 2;
        }
    }

    /**
     * Listen to the User updating event.
     *
     * @param  \Infinety\TemplyPages\Models\Page  $page
     * @return void
     */
    public function updating(Page $page)
    {
        $this->page = $page;

        if ($this->page->isDirty('deleted_at')) {
            return true;
        }

        if ($this->page->isDirty('template_type')) {
            return true;
        }

        if ($this->page->type == 2) {
            return true;
        }

        // throw \Illuminate\Validation\ValidationException::withMessages([
        //     'error' => ['Esto es un mensaje de error'],
        // ]);

        $this->getFields();
        $this->checkRequired();
        $this->checkFileFields();

        $page->data = $this->dirtyData->merge($this->data);

        return true;
    }

    /**
     * Get the fields and dirty data
     *
     * @return  void
     */
    private function getFields()
    {
        $fieldsArray = json_decode(json_encode($this->page->template->fields), true);
        $data = json_decode($this->page->data, true);

        $pageOld = Page::find($this->page->id);
        $dirtyData = json_decode($pageOld->data, true);

        $this->fields = collect($fieldsArray, true)->recursive();
        $this->data = collect($data, true)->recursive();
        $this->dirtyData = collect($dirtyData, true)->recursive();
    }

    /**
     * Check the required fields
     *
     * @return  void
     */
    private function checkRequired()
    {
        $required = $this->fields->where('data.required', true)->all();

        $toValidate = collect([]);
        if (count($required) > 0) {
            foreach ($required as $item) {
                if (!$this->dirtyData->has($item['field']['key'])) {
                    $toValidate->put($item['field']['key'], 'required');
                }
            }
            $validate = Validator::make($this->data->toArray(), $toValidate->toArray())->validate();
        }
    }

    /**
     * Check for file fields
     *
     * @return  void
     */
    private function checkFileFields()
    {
        $fileFields = $this->fields->where('component', 'file-field')->all();
        $info = [];
        foreach ($fileFields as $item) {
            $fieldItem = $this->data->get($item['field']['key']);

            if ($fieldItem) {
                $extension = pathinfo($fieldItem['name'], PATHINFO_EXTENSION);

                $fileName = md5($fieldItem['name']).'.'.$extension;
                $fileName = 'pages'.'/'.$fileName;
                $binaryData = array_last(explode(',', $fieldItem['data']));
                $binaryData = base64_decode($binaryData);

                if (isset($item['data']['disk'])) {
                    $path = Storage::disk($item['data']['disk'])->put($fileName, $binaryData);
                    $url = Storage::disk($item['data']['disk'])->url($fileName);
                } else {
                    $path = Storage::disk('public')->put($fileName, $binaryData);
                    $url = Storage::disk('public')->url($fileName);
                }

                $info[$item['field']['key']] = [
                    'name' => $fileName,
                    'type' => $fieldItem['type'],
                    'size' => $fieldItem['size'],
                    'url'  => $url,
                ];

                $this->data = $this->data->merge($info);

                if ($this->dirtyData->has($item['field']['key'])) {
                    $this->removeFile($item, $this->dirtyData->get($item['field']['key']));
                }

                $this->dirtyData->forget($item['field']['key']);
            }
        }
    }

    /**
     * Removes the old file if exists
     *
     * @param $item
     * @param $file
     */
    private function removeFile($item, $file)
    {
        if (isset($item['field']['data']['disk'])) {
            Storage::disk($item['field']['data']['disk'])->delete($file['name']);
        } else {
            Storage::disk('public')->delete($file['name']);
        }
    }
}
