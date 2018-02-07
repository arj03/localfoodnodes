<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Order\OrderDateItemLink;

use \DateTime;

class ProductNodeDeliveryLink extends \App\BaseModel
{
    public $timestamps = false;

    protected $with = ['productRelationship', 'nodeRelationship'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'node_id',
        'date',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'product_id' => 'required|integer',
        'node_id' => 'required|integer',
        'date' => 'date',
    ];

    /**
     * Define product relationship.
     *
     * @return Relation
     */
    public function productRelationship()
    {
        return $this->hasOne('App\Product\Product', 'id', 'product_id')->with(['productionsRelationship']);
    }

    /**
     * Get producer.
     *
     * @return Producer
     */
    public function getProduct($with = [])
    {
        if (empty($with)) {
            return $this->productRelationship;
        } else {
            return $this->productRelationship;
        }
    }

    /**
     * Define node relationship.
     *
     * @return Relation
     */
    public function nodeRelationship()
    {
        return $this->hasOne('App\Node\Node', 'id', 'node_id');
    }

    /**
     * Get node.
     *
     * @return Node
     */
    public function getNode()
    {
        return $this->nodeRelationship;
    }

    /**
     * Return date object.
     *
     * @param string $value
     * @return Date
     */
    public function getDateAttribute($value)
    {
        return new DateTime($value);
    }

    /**
     * Return date.
     *
     * @param string $value
     * @return string
     */
    public function date($format)
    {
        return $this->date->format($format);
    }

    /**
     * Get product quantity.
     *
     * $cartQuantity is useful for occasional products, for example a prduct called meat.
     * You produce 10 pieces of this product on one occasion and another 10 on a later date.
     * First date will say 10 in stock, second will say 20. When you add the first ten to the cart there's only ten
     * left to buy, not 20. Pass in 10 as $cartQuantity and everything will be ok.
     *
     * @param Variant $variant Get quantity for a variant.
     * @param int $cartQuantity Number of products already added to cart.
     * @return int
     */
    public function getAvailableQuantity($variant = null, $cartQuantity = null)
    {
        if ($variant) {
            return $this->getAvailableQuantityForVariant($variant, $cartQuantity);
        } else {
            return $this->getAvailableQuantityForProduct($cartQuantity);
        }
    }

    /**
     * Get available quantity for product.
     *
     * @param int $cartQuantity
     * @return int
     */
    public function getAvailableQuantityForProduct($cartQuantity = null)
    {
        $orderDateItemLinks = $this->getOrderDateItemLinks();

        $orderQuantity = 0;

        if ($this->getProduct()->production_type === 'csa') {
            $orderQuantity = $this->getOrderQuantityForCsaProduct();
        } else if (!$orderDateItemLinks->isEmpty()) {
            $orderDateItemLinks->each(function($orderDateItemLink) use (&$orderQuantity) {
                $orderItemQuantity = $orderDateItemLink->quantity;
                $orderQuantity += (int) $orderItemQuantity;
            });
        }

        $productQuantity = $this->getProduct()->getProductionQuantity($this->date, $cartQuantity);

        $quantity = $productQuantity - $orderQuantity;

        return $quantity > 0 ? floor($quantity) : 0;
    }

    /**
     * Get product quantity for a specific date.
     *
     * @param Product $product
     * @param string $date
     * @return int
     */
    public function getAvailableQuantityForVariant($variant, $cartQuantity = null)
    {
        $orderDateItemLinks = $this->getOrderDateItemLinks();

        $orderQuantity = 0; // Default

        if (!$orderDateItemLinks->isEmpty()) {
            if ($this->getProduct()->production_type === 'csa') {
                $orderQuantity = $this->getOrderQuantityForCsaVariant($variant, $orderDateItemLinks);
            } else {
                $variantOrderQuantity = new Collection();

                $orderDateItemLinks->each(function($orderDateItemLink) use (&$variantOrderQuantity) {
                    $orderItem = $orderDateItemLink->getItem();

                    $orderItemQuantity = $orderDateItemLink->quantity;
                    $packageAmount = $orderItem->variant['package_amount'];
                    $orderQuantity = (int) $orderItemQuantity * (int) $packageAmount; // Calculated

                    $variantOrderQuantity->put($orderDateItemLink->id, $orderQuantity);
                });

                $orderQuantity = $variantOrderQuantity->sum();
            }
        }

        $productQuantity = $this->getProduct()->getProductionQuantity($this->date, $cartQuantity) * $this->getProduct()->mainVariant()->package_amount;

        $quantity = $productQuantity - $orderQuantity;
        $quantity = $quantity / (float) $variant->package_amount;

        return $quantity > 0 ? floor($quantity) : 0;
    }

    /**
     * Get order items.
     *
     * @return Collection
     */
    private function getOrderDateItemLinks()
    {
        $orderQuery = DB::table('order_date_item_links')
        ->leftJoin('order_items', 'order_items.id', '=', 'order_date_item_links.order_item_id')
        ->leftJoin('order_dates', 'order_dates.id', '=', 'order_date_item_links.order_date_id')
        ->select('order_date_item_links.id')
        ->where('order_items.product_id', $this->getProduct()->id);

        // Quantity is not per variants, the variant quantity if only calculated
        // All orders must be loaded for current quantity to be correct.
        // Don't do: ->where('variant_id', $variant->id)

        if ($this->getProduct()->productionType === 'weekly') {
            // Orders on weekly products only decrease the quantity for the specific date.
            $weekStart = date('Y-m-d', strtotime('monday this week', $this->date->getTimestamp()));
            $weekEnd = date('Y-m-d', strtotime('sunday this week', $this->date->getTimestamp()));

            $orderDateItemLinkIds = $orderQuery
            ->where('order_dates.date', '>=', $weekStart)
            ->where('order_dates.date', '<=', $weekEnd)
            ->get();

        } else if ($this->getProduct()->productionType === 'occasional' || $this->getProduct()->productionType === 'csa') {
            // Orders on occasional products and csa products decrease quantity for all dates.
            $orderDateItemLinkIds = $orderQuery->get();
        }

        $orderDateItemLinkIds = $orderDateItemLinkIds->map(function($orderDateItemLinkId) {
            return $orderDateItemLinkId->id;
        });

        $orderDateItemLinks = null;
        if ($orderDateItemLinkIds) {
            $orderDateItemLinks = OrderDateItemLink::whereIn('id', $orderDateItemLinkIds->toArray())->get();
        }

        return $orderDateItemLinks;
    }

    /**
     * CSA products are a bit special since one order includes multiple dates.
     * We don't want to sum the quantity for all dates, we only need one.
     *
     * @return int $orderQuantity
     */
    private function getOrderQuantityForCsaProduct()
    {
        $orderQuantity = DB::table('order_date_item_links')
        ->leftJoin('order_items', 'order_items.id', '=', 'order_date_item_links.order_item_id')
        ->leftJoin('order_dates', 'order_dates.id', '=', 'order_date_item_links.order_date_id')
        ->select(['order_dates.date', DB::raw('COUNT(order_date_item_links.quantity) AS quantity')])
        ->where('order_items.product_id', $this->getProduct()->id)
        ->groupBy('order_dates.date')
        ->orderBy('quantity', 'desc')
        ->pluck('quantity')
        ->first();

        return $orderQuantity;
    }

    /**
     * CSA products are a bit special since one order includes multiple dates.
     * We don't want to sum the quantity for all dates, we only need one.
     *
     * @return int order quantity
     */
    private function getOrderQuantityForCsaVariant($variant, $orderDateItemLinks)
    {
        $groupedOrderDateItemLinks = new Collection();

        $orderDateItemLinks->each(function($orderDateItemLink) use (&$groupedOrderDateItemLinks) {
            $orderDateId = $orderDateItemLink->order_date_id;

            $alreadyAddedOrderDateItemLink = $orderDateItemLink;

            // If a orderDateItemLink with the same order_date_id has already
            // been added we count up quantity for that date. We need to calculate
            // the lowest common denominator for the addition to be correct.
            if ($groupedOrderDateItemLinks->has($orderDateId)) {
                $alreadyAddedOrderDateItemLink = $groupedOrderDateItemLinks->get($orderDateId);

                $orderItem = $orderDateItemLink->getItem();
                $orderItemQuantity = $orderDateItemLink->quantity;
                $packageAmount = $orderItem->variant['package_amount'];
                $orderQuantity = (int) $orderItemQuantity * (int) $packageAmount; // Calculated

                $alreadyAddedOrderDateItemLink->quantity += $orderQuantity;
            }

            $groupedOrderDateItemLinks->put($orderDateId, $alreadyAddedOrderDateItemLink);
        });

        // Sort by quantity and return largest number
        $largest = $groupedOrderDateItemLinks->sortByDesc('quantity')->first();
        return $largest->quantity;
    }

    /**
     * View helper for adding attributes to order date input fields.
     *
     * @param Product $product
     * @param int $quantity
     * @return string
     */
    public function getCheckboxAttributes($requestData, $variant = null) {
        $attributes = [];

        $disabled = ($this->getAvailableQuantity($variant) <= 0) ? true : false;
        $disabled = ($this->getProduct()->type === 'csa') ? true : $disabled;

        if ($disabled) {
            $attributes[] = 'disabled';
        }

        $selectedDates = isset($requestData['delivery_dates']) ? $requestData['delivery_dates'] : [];

        $checked = is_array($selectedDates) ? in_array($this->date('Y-m-d'), $selectedDates) : false;
        $checked = ($this->getProduct()->type === 'csa') ? true : $checked;

        if ($checked) {
            $attributes[] = 'checked';
        }

        return join(' ', $attributes);
    }
}
