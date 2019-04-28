<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'is_required',
        'is_unique'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_unique'   => 'boolean',
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function values()
    {
        return $this->hasMany(Value::class, 'attribute_id');
    }
}
