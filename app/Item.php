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
        'reorder_level',
        'image_filepath'
    ];

    protected $casts = [
        'selling_price' => 'double',
        'reorder_level' => 'double',
    ];

    protected $appends = [
        'slug'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }

    public function logs()
    {
        return $this->morphMany(ItemLog::class, 'loggable', 'causer_type', 'causer_id')->latest();
    }

    public function getBalanceAttribute() : float
    {
        if ( ! $this->relationLoaded('logs')) {
            return 0;
        }

        return $this->logs->sum('quantity');
    }

    public function likers()
    {
        return $this->belongsToMany(Item::class, 'favorites', 'item_id', 'user_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attributes_items')->withPivot(['value']);
    }
}
