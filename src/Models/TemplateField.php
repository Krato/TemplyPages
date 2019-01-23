<?php

namespace Infinety\TemplyPages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infinety\TemplyPages\Http\Services\CreateField;
use Infinety\TemplyPages\Http\Traits\JsonData;
use Infinety\TemplyPages\Models\Template;
use Kalnoy\Nestedset\NodeTrait;

class TemplateField extends Model
{
    use NodeTrait, JsonData;

    /**
     * @var string
     */
    protected $table = 'fields';

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'component', 'template_id', 'parent_id', 'data',
    ];

    /**
     * @var array
     */
    protected $appends = ['field', 'type', 'panel', 'extras', 'dataArray'];

    /**
     * Template refered
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get parent field
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TemplateField::class, 'parent_id');
    }

    /**
     * Get children TemplateField
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(TemplateField::class, 'parent_id');
    }

    /**
     * @return mixed
     */
    public function getFieldAttribute()
    {
        $fieldInfo = new CreateField('Form', $this->uuid, $this->component, $this->data, null);

        return $fieldInfo();
    }

    /**
     * @return mixed
     */
    public function getTypeAttribute()
    {
        return $this->getCustomData('type', 'section');
    }

    /**
     * @return mixed
     */
    public function getDataArrayAttribute()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getExtrasAttribute()
    {
        return $this->data;
    }

    /**
     * Returns panel name
     * @return mixed
     */
    public function getPanelAttribute()
    {
        if ($this->ancestors()->count() > 0) {
            return $this->ancestors()->whereIsRoot()->first()->id;
        }

        return $this->id;
    }
}
