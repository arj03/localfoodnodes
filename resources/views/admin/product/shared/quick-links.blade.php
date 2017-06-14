<div class="row">
    <ul class="product-quick-links col-12 col-xl-8">
        <li class="{{ Request::is('*/edit') ? 'active' : '' }}">
            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/edit">
                <i class="fa fa-check completed"></i>
                {{ trans('admin/producer.product') }}
            </a>
        </li>
        <li class="{{ Request::is('*/production') ? 'active' : '' }}">
            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production">
                @if ($product->productions()->count() > 0)
                    <i class="fa fa-check completed"></i>
                @else
                    <i class="fa fa-sun-o"></i>
                @endif
                {{ trans('admin/producer.production') }}
            </a>
        </li>
        <li class="{{ Request::is('*/deliveries') ? 'active' : '' }}">
            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/deliveries">
                @if ($product->deliveryLinks()->count() > 0)
                    <i class="fa fa-check completed"></i>
                @else
                    <i class="fa fa-calendar"></i>
                @endif
                {{ trans('admin/producer.delivery_dates') }}
            </a>
        </li>
        <li class="{{ Request::is('*/variants') ? 'active' : '' }}">
            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/variants">
                @if ($product->variants()->count() > 0)
                    <i class="fa fa-check completed"></i>
                @else
                    <i class="fa fa-cubes"></i>
                @endif
                {{ trans('admin/producer.variants') }}
            </a>
        </li>
    </ul>
</div>
