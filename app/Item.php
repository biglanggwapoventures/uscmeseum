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
        'purchase_cost',
        'selling_price',
        'reorder_level',
        'image_filepath'
    ];

    protected $casts = [
        'purchase_cost' => 'double',
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

    public static function bestSellers()
    {
        return \DB::table('items')
                  ->selectRaw('items.id, IFNULL(SUM(od.quantity), 0) AS total_ordered')
                  ->leftJoin('order_details AS od', 'od.item_id', '=', 'items.id')
                  ->groupBy('items.id')
                  ->having('total_ordered', '>', 0)
                  ->orderBy('total_ordered', 'desc')
                  ->limit(4)
                  ->get()
                  ->pluck('total_ordered', 'id')
                  ->all();
    }
}