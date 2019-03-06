<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'selling_price',
        'image_filepath'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
