<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'selling_price',
        'image_filepath'
    ];

    protected $appends = [
        'slug'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }
}
