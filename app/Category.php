<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'image_filepath'
    ];

    public function products()
    {
        return $this->hasMany(Item::class);
    }
}
