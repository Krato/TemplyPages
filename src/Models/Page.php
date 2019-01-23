<?php

namespace Infinety\TemplyPages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Infinety\TemplyPages\Http\Traits\JsonData;
use Infinety\TemplyPages\Models\Template;

class Page extends Model
{
    use SoftDeletes, JsonData;

    // /**
    //  * @var array
    //  */
    // protected $casts = [
    //     'data' => 'array',
    // ];

    /**
     * @var array
     */
    protected $with = ['template'];

    /**
     * @var array
     */
    protected $appends = ['isCustom', 'dataArray', 'url'];

    /**
     * TemplateField of template
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @param $name
     */
    public function field($name = null)
    {
        if ($name === null) {
            return null;
        }

        return $this->getCustomData($name);
    }

    public function getIsCustomAttribute()
    {
        return ($this->type == 1) ? false : true;
    }

    public function getDataArrayAttribute()
    {
        return json_decode($this->data, true);
    }

    public function getUrlAttribute()
    {
        return 'http://temply.on/link-to-page/';
    }
}
