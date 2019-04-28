<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table = 'attributes_items';

    protected $fillable = [
        'attribute_id',
        'item_id',
        'value'
    ];


}
