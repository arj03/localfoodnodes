<div class="card">
    <div class="card-header">Order</div>
    <div class="card-block">
        <form action="/checkout/item/add" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="node_id" value="{{ $node->id }}" />
            <input type="hidden" name="product_id" value="{{ $product->id }}" />

            <div class="form-group">
                @if ($product->variants()->count() > 0)
                    <label class="form-control-label">Select variant</label>
                    @foreach ($product->variants() as $variant)
                        <div class="form-check">
                            <label class="form-check-label w-100">
                                <input class="form-check-input variant" type="radio" name="variant_id" value="{{ $variant->id}}">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div>{{ $product->name}} - {{ $variant->name }}</div>
                                        @if ($product->package_unit)
                                            <div>{{ $variant->package_amount}} {{ $product->package_unit }}</div>
                                        @endif
                                    </div>
                                    <div style="white-space: nowrap">{{ $variant->price }} {{ $producer->currency }}</div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                @else
                    <label class="form-control-label">Product</label>
                    <div class="form-check">
                        <label class="form-check-label w-100">
                            <input class="form-check-input" type="radio" name="product_id" value="{{ $product->id }}" checked>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div>{{ $product->name}}</div>
                                </div>
                                <div style="white-space: nowrap">{{ $product->price }} {{ $producer->currency }}</div>
                            </div>
                        </label>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-control-label" for="quantity">How many at each pick-up?</label>
                <input type="number" min="0" name="quantity" class="form-control" id="quantity" placeholder="Order Quantity" />
            </div>

            <div class="form-group">
                <label class="form-control-label">Select pickup dates</label>
                @if ($product->getDeliveryLinksByMonths($node->id)->count() > 0)
                    <div class="row calendar product-calendar">
                        @foreach ($product->getDeliveryLinksByMonths($node->id) as $month => $deliveryLinks)
                            <div class="col col-6">
                                <div class="month">
                                    <div class="month-header">
                                        <b>{{ date('F Y', mktime(null, null, null, $month)) }}</b>
                                        @if ($product->productionType !== 'csa')
                                            <i class="fa fa-check-square select-all-dates-action"></i>
                                        @endif
                                    </div>
                                    <div class="days">
                                        @foreach ($deliveryLinks as $deliveryLink)
                                            @include('public.node.delivery-date')
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if (!Auth::check())
                <button type="submit" class="btn btn-success pull-left" disabled>Login to buy product</button>
            @else
                <button type="submit" class="btn btn-success pull-left">Add to cart</button>
            @endif
        </form>
    </div>
</div>
