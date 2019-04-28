<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'selling_price',
        'cost'
    ];

    /**
     * Arbitrary fields
     *
     * @var array
     */
    protected $appends = [
        'amount'
    ];

    /**
     * The associated Item model
     *
     * @return BelongsTo
     */
    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * The associated Order model
     *
     * @return BelongsTo
     */
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Calculates the value for the arbitrary field `amount`
     *
     * @return float
     */
    public function getAmountAttribute() : float
    {
        return $this->quantity * $this->selling_price;
    }

    public function logs()
    {
        return $this->morphMany(ItemLog::class, 'loggable', 'causer_type', 'causer_id');
    }

    public function decrementItem() : ItemLog
    {
        return $this->logs()->create([
            'item_id'  => $this->item_id,
            'quantity' => ($this->quantity * -1),
            'reason'   => "Customer Order # {$this->order_id}"
        ]);
    }


}
