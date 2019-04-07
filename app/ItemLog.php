<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemLog extends Model
{
    protected $fillable = [
        'item_id',
        'quantity',
        'causer_type',
        'causer_id',
        'reason'
    ];

    public function loggable()
    {
        return $this->morphTo();
    }
}
