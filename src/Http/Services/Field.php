<?php

namespace Infinety\TemplyPages\Http\Services;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'component',
        'name',
        'prefixComponent',
        'indexName',
        'name',
        'type',
        'attribute',
        'value',
        'panel',
        'sortable',
        'textAlign',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'prefixComponent' => true,
        'indexName'       => '',
        'attribute'       => '',
        'panel'           => '',
        'sortable'        => false,
        'textAlign'       => 'left',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'extras' => 'array',
    ];

    /**
     * @var array
     */
    protected $appends = ['indexName', 'attribute'];

    /**
     * @param $component
     * @param $name
     * @param $value
     * @param null $panel
     */
    public function construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @return mixed
     */
    protected function getIndexNameAttribute()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    protected function getAttributeAttribute()
    {
        return str_slug($this->name);
    }
}

// [
//     'component'       => 'text-field',
//     'prefixComponent' => true,
//     'indexName'       => 'ISO',
//     'name'            => 'ISO',
//     'attribute'       => 'iso',
//     'value'           => null,
//     'panel'           => 'Panel1',
//     'sortable'        => true,
//     'textAlign'       => 'left',
// ],
