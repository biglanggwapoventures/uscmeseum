<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Order
 * @property int    $user_id
 * @property string $delivery_address
 * @property string $remarks
 * @property string $order_status
 * @property string $order_status_remarks
 *
 * @package App
 */
class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'delivery_address',
        'remarks',
        'order_status',
        'order_status_remarks',
    ];

    /**
     * Arbitrary fields
     *
     * @var array
     */
    protected $appends = [
        'total_amount'
    ];


    /**
     * Date fields
     *
     * @var array
     */
    protected $date = [
        'created_at',
        'updated_at',
    ];


    /**
     * The associated OrderDetail model
     *
     * @return HasMany
     */
    public function orderDetails() : HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * The associated User model
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer() : BelongsTo
    {
        return $this->user();
    }

    /**
     * Calculates the value for the arbitrary field `amount`
     *
     * @return float
     */
    public function getTotalAmountAttribute() : float
    {
        /**
         * If the associated order details
         * are not loaded then we cannot calculate
         * the amount so we return 0
         */
        if ( ! $this->relationLoaded('orderDetails')) {
            return 0;
        }

        return $this->orderDetails->sum('amount');
    }

    public function status ($status)
    {
        return strtolower($status) === strtolower($this->order_status);
    }


}
