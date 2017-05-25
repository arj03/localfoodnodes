<div class="card">
    <div class="card-header">{{ trans('public/product.order') }}</div>
    <div class="card-block">
        {{ trans('public/product.nav_to_node') }}.
        <ul class="mt-3">
            @foreach ($producer->nodeLinks() as $nodeLink)
                @foreach ($producer->products() as $product)
                    @if ($product->deliveryLinks($nodeLink->node_id)->count() > 0)
                        <li>
                            <a href="{{ $nodeLink->getNode()->permalink()->url }}{{ $product->permalink()->url }}">
                                {{ $nodeLink->getNode()->name }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endforeach
        </ul>
    </div>
</div>
