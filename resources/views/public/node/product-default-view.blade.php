<div class="card-body">
    @if ($products->count() > 0)
        <div class="card-deck">
            @foreach ($products as $index => $product)
                <a class="card product-card" href="{{ $node->permalink()->url }}{{ $product->permalink()->url }}">
                    @if ($product->images()->count() > 0)
                        <img class="card-image-top" src="{{ $product->images()->first()->url('small') }}" alt="{{ $product->name }}">
                    @else
                        <img class="card-image-top" src="/images/product-image-placeholder.jpg" alt="Default product image">
                    @endif

                    <div class="card-body">
                        <div class="producer-info">
                            <div class="name">{{ $product->producer()->name }}</div>
                        </div>
                        <div class="title">
                            {{ $product->name }}
                        </div>
                        <div class="price">
                            @if (!$product->isInStock($node->id))
                               <span class="text-danger">{{ trans('public/product.sold_out') }}</span>
                            @elseif ($product->variants()->count() > 0)
                                {{ trans('public/node.from') }} {{ $product->smallestVariant()->getPriceWithUnit() }}
                            @else
                                {{ $product->getPriceWithUnit() }}
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <i class="fa fa-map-marker"></i> {{ $product->producer()->getDistance($node) }} {{ trans('public/node.km') }}
                        <div class="tags">
                            @foreach ($product->tags() as $tag)
                                <div class="badge badge-default">{{ trans('public/tags.' . $tag->tag) }}</div>
                            @endforeach
                        </div>
                    </div>
                </a>

                @if ($index > 0 && ($index + 1) % 3 === 0)
                    </div><div class="card-deck">
                @endif
            @endforeach

            <!-- Fill out with empty cards -->
            @for ($i = 0; $i < (3 - ($products->count() % 3)); $i++)
                <div class="card-body"></div>
            @endfor
        </div>
    @else
        {{ trans('public/node.no_products') }}
    @endif
</div>
