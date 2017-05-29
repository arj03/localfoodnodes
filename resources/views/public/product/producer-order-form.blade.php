<div class="card">
    <div class="card-header">{{ trans('public/product.order') }}</div>
    <div class="card-block">
        {{ trans('public/product.nav_to_node') }}.
        <ul class="mt-3">
            @foreach ($producer->getNodeLinksWhereProductIsSold($product->id) as $nodeLink)
                <li>
                    <a href="{{ $nodeLink->getNode()->permalink()->url }}{{ $product->permalink()->url }}">
                        {{ $nodeLink->getNode()->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
