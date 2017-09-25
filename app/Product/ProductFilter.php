<?php

namespace App\Product;

use Illuminate\Support\Collection;
use App\Product\ProductNodeDeliveryLink;
use Illuminate\Support\Facades\DB;

class ProductFilter
{
    /**
     * @var Collection
     */
    private $products;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $date;

    /**
     * Constructor.
     *
     * @param Collection $products
     * @param Request $request
     */
    public function __construct($products, $request)
    {
        $this->products = $products;
        $this->request = $request;
        $this->date = $request->has('date') ? $request->get('date') : date('Y-m-d');
    }

    /**
     * All available product tags.
     *
     * @return array
     */
    public static function tags($translate = false) {
        return collect([
            'artisan_food' => trans('public/tags.artisan_food'),
            'beer' => trans('public/tags.beer'),
            'beverage' => trans('public/tags.beverage'),
            'csa' => trans('public/tags.csa'),
            'dairy_cheese' => trans('public/tags.dairy_cheese'),
            'eggs' => trans('public/tags.eggs'),
            'fish' => trans('public/tags.fish'),
            'fruit_berries' => trans('public/tags.fruit_berries'),
            'grain_flour' => trans('public/tags.grain_flour'),
            'honey' => trans('public/tags.honey'),
            'icecream' => trans('public/tags.icecream'),
            'jam_marmelade' => trans('public/tags.jam_marmelade'),
            'meat' => trans('public/tags.meat'),
            'mushroom' => trans('public/tags.mushroom'),
            'other' => trans('public/tags.other'),
            'pickled' => trans('public/tags.pickled'),
            'potatoes' => trans('public/tags.potatoes'),
            'poultry' => trans('public/tags.poultry'),
            'sample' => trans('public/tags.sample'),
            'seeds' => trans('public/tags.seeds'),
            'vegetable' => trans('public/tags.vegetable'),
        ])->sort();
    }

    /**
     * Filter products on date.
     *
     * @return ProductFilter
     */
    public function filterDate($nodeId = null)
    {
        if ($this->request->has('date')) {
            if ($this->request->has('node_id') && !$nodeId) {
                $nodeId = $this->request->get('node_id');
            }

            $date = new \DateTime($this->request->get('date'));

            $query = DB::table('product_node_delivery_links')
            ->join('products', 'products.id', '=', 'product_node_delivery_links.product_id')
            ->select('products.id');

            if ($nodeId) {
                $query->where('product_node_delivery_links.node_id', '=', $nodeId);
            }

            $query->where('product_node_delivery_links.date', '=', $date->format('Y-m-d'))
            ->whereRaw('(
                DATE(DATE_ADD(product_node_delivery_links.date, INTERVAL -products.deadline DAY)) > NOW()
                OR products.deadline IS NULL
            )');

            $productIds = $query->get()->pluck('id')->unique();

            $this->products = $this->products->whereIn('id', $productIds);
        }

        return $this;
    }

    /**
     * Filter product on visibility
     *
     * @return ProductFilter
     */
    public function filterVisibility()
    {
        $this->products = $this->products->filter(function($product) {
            return $product->isVisible() === true;
        });

        return $this;
    }

    /**
     * Filter produts on tags.
     *
     * @return ProductFilter
     */
    public function filterTags()
    {
        $selectedTags = $this->getSelectedTags();
        if ($selectedTags->count() > 0) {
            $this->products = $this->products->whereIn('id', ProductTag::whereIn('tag', $selectedTags)->get()->map->product_id);
        }

        return $this;
    }

    /**
     * Return filter date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Return filter month.
     *
     * @return string
     */
    public function getMonthDate()
    {
        return date('Y-m-01', strtotime($this->date));
    }

    /**
     * Get filtered products.
     *
     * @return [type] [description]
     */
    public function get()
    {
        return $this->products;
    }

    /**
     * Get all tags with additional filter data from request.
     *
     * @param Request $request
     * @return Collection
     */
    public function getTagFilter($request)
    {
        $tags = new Collection();

        foreach (self::tags() as $key => $tag) {
            // In loop to it's resetted for every tag
            $selectedTags = $this->getSelectedTags($request);

            if ($selectedTags->contains($key)) {
                $active = true;
                $selectedTags = $selectedTags->reject(function ($selectedTag) use ($key) {
                    return $selectedTag === $key;
                });
            } else {
                $active = false;
                $selectedTags->push($key);
            }

            $url = $this->buildQuery('tag', $selectedTags->implode(','));

            $tags->put($tag, [
                'active' => $active,
                'url' => '?' . $url
            ]);
        }

        return $tags;
    }

    /**
     * Get selected tags from request.
     *
     * @param Request $request
     * @return Collection
     */
    private function getSelectedTags($request = null)
    {
        if (!$request && !$this->request) {
            return new Collection();
        }

        if (!$request) {
            $request = $this->request;
        }

        return $request->has('tag') ? collect(explode(',', $request->get('tag'))) : new Collection();
    }

    /**
     * Get query parts.
     *
     * @param string $resetKey
     * @return sting
     */
    private function getQueryParts($resetKey)
    {
        $queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;

        $query = [];
        if ($queryString) {
            $queryParts = explode('&', $queryString);

            foreach ($queryParts as $part) {
                if (strpos($part, '=')) {
                    list($key, $value) = explode('=', $part);
                    $query[$key] = $value;
                }
            }

            if (isset($query[$resetKey])) {
                unset($query[$resetKey]);
            }
        }

        return $query;
    }

    /**
     * Build query.
     *
     * @param string $key
     * @param string $param
     * @return string
     */
    private function buildQuery($key, $param)
    {
        $queryParts = $this->getQueryParts($key);

        if (!empty($param)) {
            $queryParts[$key] = $param;
        }

        return http_build_query($queryParts);
    }
}
