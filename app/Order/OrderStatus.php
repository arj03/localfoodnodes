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
        'entity_id' => 'required',
        'entity_type' => 'required',
        'status' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity_id',
        'entity_type',
        'status'
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

    public function getEntity()
    {
        if ($this->entity_type === 'order') {
            return Order::find($this->entity_id);
        } else if ($this->entity_type === 'order_item') {
            return OrderItem::find($this->entity_id);
        } else if ($this->entity_type === 'order_item_date') {
            return OrderItemDate::find($this->entity_id);
        }
    }

    public function getStatusString()
    {
        $readableType = ucfirst(str_replace('_', ' ', $this->entity_type));
        return $readableType . ' ' . $this->status . ' ' . $this->created_at->format('Y-m-d');
    }
}
