<?php

namespace App\Product;

use Illuminate\Support\Collection;

class Product extends \App\BaseModel
{
    protected $appends = ['productionType'];

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $validationRules = [
        'producer_id' => 'required|integer',
        'name' => 'required',
        'info' => 'required',
        'price_unit' => 'required',
        'price' => 'required',
        'is_hidden' => '',
        'deadline' => 'integer',
        'payment_info' => ''
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'producer_id',
        'name',
        'info',
        'price_unit',
        'price',
        'is_hidden',
        'deadline',
        'payment_info'
    ];

    /**
     * Lifecycle events.
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($product) {
            $product->variants()->each->delete();
            $product->productions()->each->delete();
            $product->deliveryLinks()->each->delete();
            $product->tags()->each->delete();
            $product->permalink()->delete();
        });

        static::created(function($product) {
            \App\Permalink::create(['entity_type' => 'product', 'entity_id' => $product->id, 'slug' => $product->name]);
        });

        static::updated(function($product) {
            $permalink = $product->permalink();
            $permalink->slug = $product->name;
            $permalink->save();
        });
    }

    /**
     * Get productions.
     */
    public function producer()
    {
        return $this->belongsTo('App\Producer\Producer')->first();
    }

    /**
     * Define product relationship with productions.
     */
    public function productionsRelationship()
    {
        return $this->hasMany('App\Product\Production\ProductProduction');
    }

    /**
     * Get productions.
     */
    public function productions()
    {
        return $this->productionsRelationship;
    }

    /**
     * Define product relationship with production adjustments.
     */
    public function productionWeekAdjustments()
    {
        return $this->hasMany('App\Product\Production\WeekAdjustment')->get();
    }

    /**
     * Get production adjustment.
     *
     * @param int $year
     * @param int $week
     * @return WeekAdjustment
     */
    public function productionWeekAdjustment($year, $week)
    {
        return $this->productionWeekAdjustments()->where('year', $year)->where('week', $week)->first();
    }

    /**
     * Get product adjustment quantity.
     *
     * @param int $year
     * @param int $week
     * @return int|null
     */
    public function productionWeekAdjustmentQuantity($year, $week)
    {
        $productionWeekAdjustment = $this->productionWeekAdjustment($year, $week);

        if ($productionWeekAdjustment) {
            return $productionWeekAdjustment->quantity;
        } else {
            return null;
        }
    }

    /**
     * Define product relationship with production adjustments.
     */
    public function productionDeliveryAdjustments()
    {
        return $this->hasMany('App\Product\Production\DeliveryAdjustment')->get();
    }

    /**
     * Get production adjustment.
     *
     * @param int $year
     * @param int $week
     * @return WeekAdjustment
     */
    public function productionDeliveryAdjustment(\DateTime $date)
    {
        return $this->productionDeliveryAdjustments()->where('date', $date->format('Y-m-d'))->first();
    }

    /**
     * Get product adjustment quantity.
     *
     * @param int $year
     * @param int $week
     * @return int|null
     */
    public function productionDeliveryAdjustmentQuantity(\DateTime $date)
    {
        $productionDeliveryAdjustment = $this->productionDeliveryAdjustment($date);

        if ($productionDeliveryAdjustment) {
            return $productionDeliveryAdjustment->quantity;
        } else {
            return null;
        }
    }

    /**
     * Get product variants.
     */
    public function productVariants()
    {
        return $this->hasMany('App\Product\ProductVariant')->get();
    }

    /**
     * Get variants.
     *
     * @return Collection
     */
    public function variants($includeMainVariant = true)
    {
        return $this->productVariants()->sortBy('package_amount');
    }

    /**
     * Get specific variant.
     *
     * @param int $variantId
     * @return ProductVariant
     */
    public function variant($variantId)
    {
        return $this->productVariants()->where('id', $variantId)->first();
    }

    /**
     * Get products main variant.
     *
     * @return ProductVariant
     */
    public function mainVariant()
    {
        $mainVariant = $this->productVariants()->where('main_variant', 1)->first();

        // Temp fix to avoid crash if main variant flag is missing.
        if (!$mainVariant) {
            $mainVariant = $this->productVariants()->first();
        }

        return $mainVariant;
    }

    /**
     * Get smallest variant.
     *
     * @return ProductVariant
     */
    public function smallestVariant()
    {
        return $this->productVariants()->first();
    }

    /**
     * Return all variant names as concat string.
     *
     * @return string
     */
    public function variantsAsString()
    {
        return $this->variants()->pluck('name')->implode(', ');
    }

    /**
     * Return all variant names as concat string.
     *
     * @return string
     */
    public function variantCount()
    {
        return $this->variants->count();
    }

    /**
     * Define product relationship with node delivery links.
     */
    public function deliveryLinksRelationship()
    {
        // Adjust for product booking deadline
        if ($this->deadline) {
            $currenctDate = new \DateTime(date('Y-m-d'));
            $deadlineModifyString = '+' . $this->deadline . ' days';
            $firstBookableDate = $currenctDate->modify($deadlineModifyString)->format('Y-m-d');
        } else {
            $firstBookableDate = date('Y-m-d');
        }

        return $this->hasMany('App\Product\ProductNodeDeliveryLink')->where('date', '>=', $firstBookableDate)->orderBy('date');
    }

    /**
     * Get delivery links.
     *
     * @param int $nodeId
     * @param array $dates
     * @return Collection
     */
    public function deliveryLinks($nodeId = null, Collection $dates = null)
    {
        $deliveryLinks = $this->deliveryLinksRelationship;

        if ($nodeId) {
            $deliveryLinks = $deliveryLinks->where('node_id', $nodeId);
        }

        if (!empty($dates)) {
            $deliveryLinks = $deliveryLinks->filter(function($item, $key) use ($dates) {
                return $dates->contains($item->date('Y-m-d'));
            });
        }

        return $deliveryLinks;
    }

    /**
     * Get specific delivery link.
     *
     * @param int $nodeId
     * @param string $date
     * @return ProductNodeDeliveryLink
     */
    public function deliveryLink($nodeId, $date)
    {
        return $this->deliveryLinks($nodeId)->first(function($deliveryLink) use ($date) {
            return $deliveryLink->date->format('Y-m-d') === $date;
        });
    }

    /**
     * Get delivery links by months.
     *
     * @return Collection
     */
    public function getDeliveryLinksByMonths($nodeId)
    {
        return $this->deliveryLinks($nodeId)->groupBy(function($deliveryLink) {
            return $deliveryLink->date->format('m');
        });
    }

    /**
     * Get tags.
     */
    public function tags()
    {
        return $this->hasMany('App\Product\ProductTag')->get();
    }

    /**
     * Get specific tag.
     */
    public function tag($tag)
    {
        return $this->tags()->where('tag', $tag)->first();
    }

    /**
     * Get permalink.
     */
    public function permalink()
    {
        return $this->hasOne('App\Permalink', 'entity_id')->where('entity_type', 'product')->first();
    }

    /**
     * Get images.
     */
    public function images()
    {
        return $this->hasMany('App\Image\Image', 'entity_id')->where('entity_type', 'product')->get()->sortBy('sort');
    }

    /**
     * Get specific image.
     *
     * @param int $imageId
     * @return Image
     */
    public function image($imageId)
    {
        return $this->images()->where('id', $imageId)->first();
    }

    /**
     * Return all variant names as concat string.
     *
     * @return string
     */
    public function priceRange($minOrMax)
    {
        if ($minOrMax === 'min') {
            return $this->variants()->min('price');
        } else if ($minOrMax === 'max') {
            return $this->variants()->max('price');
        }
    }

    /**
     * Get production type based on the productions defined for this product.
     *
     * @return string
     */
    public function getProductionTypeAttribute()
    {
        $type = null;

        if ($this->productions() && $this->productions()->count() > 0) {
            $type = $this->productions()->first()->type;
        }

        return $type;
    }

    /**
     * [getProductionQuantity description]
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    public function getProductionQuantity(\DateTime $date = null, $cartQuantity = null)
    {
        $quantity = 0;

        if ($this->productionType === 'csa') {
            $quantity = $this->productions()->first()->quantity;
        } else if ($this->productionType === 'weekly') {
            $quantity = $this->productions()->first()->quantity;

            if ($date) {
                $productionWeekAdjustment = $this->productionWeekAdjustment($date->format('Y'), $date->format('W'));
                if ($productionWeekAdjustment) {
                    $quantity += $productionWeekAdjustment->quantity;
                }
            }
        } else if ($this->productionType === 'occasional') {
            $occasionalQuantity = 0;

            // Sum production quantities up to the delivery date
            $this->productions()->each(function($production) use (&$occasionalQuantity, $date) {
                if (!$date) {
                    $date = new \DateTime();
                }

                if ($production->date->getTimestamp() <= $date->getTimestamp()) {
                    $occasionalQuantity += $production->quantity;
                }
            });

            $quantity = $occasionalQuantity;

            if ($cartQuantity) {
                $quantity -= $cartQuantity;
            }
        }

        return $quantity;
    }

    /**
     * Check if product has all the needed data to be visible.
     *
     * @return boolean
     */
    public function isVisible($nodeId = null)
    {
        $isVisible = true;
        $errors = new Collection();

        if ($this->is_hidden === 1) {
            $isVisible = false;
            $errors->push('Product is hidden.');
        }

        if ($this->productions()->count() <= 0) {
            $isVisible = false;
            $errors->push('Product has no quantity.');
        }

        if ($this->deliveryLinks($nodeId)->count() <= 0) {
            $isVisible = false;
            $errors->push('Product has no delivery dates.');
        }

        if ($isVisible) {
            return true;
        } else {
            return $errors->implode(' ');
        }
    }

    /**
     * Get info to be stored with order.
     *
     * @return array
     */
    public function getInfoForOrder()
    {
        return [
            'id' => $this->id,
            'producer_id' => $this->producer_id,
            'name' => $this->name,
            'price_unit' => $this->price_unit,
            'price' => $this->price,
            'package_unit' => $this->package_unit,
            'is_hidden' => $this->hidden,
            'deadline' => $this->deadline,
            'payment_info' => $this->payment_info,
            'production_type' => $this->production_type,
        ];
    }
}
