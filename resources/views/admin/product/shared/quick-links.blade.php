<div class="row">
    <ul class="product-quick-links col-12 col-xl-8">
        <li class="{{ Request::is('*/edit') ? 'active' : '' }}">
            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/edit">
                <i class="fa fa-check-circle completed"></i>
                <!-- <i class="fa fa-cube"></i> -->
                {{ trans('admin/producer.product') }}
            </a>
        </li>
        @if ($product->variants()->count() > 0)
            <li class="{{ Request::is('*/variants') ? 'active' : '' }}">
                <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/variants">
                    <i class="fa fa-check-circle completed"></i>
                    <!-- <i class="fa fa-cubes"></i> -->
                    {{ trans('admin/producer.variants') }}
                </a>
            </li>
        @endif
        <li class="{{ Request::is('*/production') ? 'active' : '' }}">
            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production">
                @if ($product->productions()->count() > 0)
                    <i class="fa fa-check-circle completed"></i>
                @else
                    <i class="fa fa-industry"></i>
                @endif
                {{ trans('admin/producer.production') }}
            </a>
        </li>
        <li class="{{ Request::is('*/deliveries') ? 'active' : '' }}">
            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/deliveries">
                @if ($product->deliveryLinks()->count() > 0)
                    <i class="fa fa-check-circle completed"></i>
                @else
                    <i class="fa fa-calendar"></i>
                @endif
                {{ trans('admin/producer.delivery_dates') }}
            </a>
        </li>
    </ul>
</div>
