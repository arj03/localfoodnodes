<?php

namespace App\Order;

use App\Order\Order;
use App\Order\OrderItem;
use App\Order\OrderItemDate;

use \DateTime;

class OrderStatus extends \App\BaseModel
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'order_item_id' => 'required',
        'status' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_item_id',
        'status',
        'active', // Not db field
        'key', // Not db field
    ];

    /**
     * Get created at as a DateTime object.
     *
     * @param string $value
     * @return DateTime
     */
    public function getCreatedAtAttribute($value)
    {
        return new DateTime($value);
    }

    /**
     * Get status key.
     *
     * @return string
     */
    public function getKeyAttribute()
    {
        return $this->status;
    }

    /**
     * Get html class.
     *
     * @return string
     */
    public function getHtmlClass()
    {
        if (!$this->active) {
            return 'badge badge-secondary';
        } else if ($this->status === 'cancelled') {
            return 'badge badge-danger';
        } else {
            return 'badge badge-success';
        }
    }

    /**
     * Get order status translated string.
     *
     * @return string
     */
    public function __toString()
    {
        return trans('admin/order-statuses.' . $this->status);
    }
}
