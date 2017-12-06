<div class="card">
    <div class="card-header">{{ trans('public/node.order') }}</div>
    <form action="/checkout/item/add" method="post" class="order-form">
        <div class="card-body">
            {{ csrf_field() }}
            <input type="hidden" name="node_id" value="{{ $node->id }}" />
            <input type="hidden" name="product_id" value="{{ $product->id }}" />

            <div class="form-group">
                @if ($product->variants()->count() > 0)
                    <label class="form-control-label">
                        {{ trans('public/node.select_variant') }}
                        @include('account.field-error', ['field' => 'variant_id'])
                    </label>
                    @foreach ($product->variants() as $index => $variant)
                        <div class="form-check">
                            <label class="form-check-label w-100">
                                <input class="form-check-input variant" type="radio" name="variant_id" value="{{ $variant->id}}" {{ $index === 0 ? 'checked' : '' }}>
                                {{ $product->name}} - {{ $variant->name }}
                                {{ $variant->getPackageAmountUnit() }}
                                <div class="price">
                                    {{ $variant->getPriceWithUnit() }}
                                </div>
                            </label>
                        </div>
                    @endforeach
                @else
                    <label class="form-control-label">
                        {{ trans('public/node.product') }}
                        @include('account.field-error', ['field' => 'product_id'])
                    </label>
                    <div class="form-check">
                        <label class="form-check-label w-100">
                            <input class="form-check-input" type="radio" name="product_id" value="{{ $product->id }}" checked>
                            <div>
                                {{ $product->name }}
                                @if ($product->price_unit !== 'product')
                                    {{ $product->getPackageAmountUnit() }}
                                @endif
                                <div class="price">{{ $product->getPriceWithUnit() }}</div>
                            </div>
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="form-control-label" for="quantity">
                    {{ trans('public/node.how_many') }}
                    @include('account.field-error', ['field' => 'quantity'])
                </label>
                <div class="input-group">
                    <input type="number" min="0" name="quantity" class="form-control" id="quantity" placeholder="{{ trans('public/node.placeholder_qty') }}" />
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="form-control-label">
                    {{ trans('public/node.select_pickup') }}
                    @include('account.field-error', ['field' => 'delivery_dates'])
                    @if ($product->deadline > 0)
                        <div class="text-muted">{{ trans('public/product.book_days_before', ['deadline' => $product->deadline]) }}</div>
                    @endif
                </label>

                @if ($product->getDeliveryLinksByMonths($node->id)->count() > 0)
                    <div class="row calendar product-calendar">
                        @foreach ($product->getDeliveryLinksByMonths($node->id) as $monthDate => $deliveryLinks)
                            <div class="col-6 col-md-4 col-lg-12 col-xl-6 mt-3">
                                <div class="month">
                                    <div class="month-header">
                                        <b>
                                            {{ substr(ucfirst(trans('months.' . date('F', strtotime($monthDate)))), 0, 3) }}
                                            {{ date('Y', strtotime($monthDate)) }}
                                        </b>
                                        @if ($product->productionType !== 'csa')
                                            <i class="fa fa-check-square select-all-dates-action"></i>
                                        @endif
                                    </div>
                                    <div class="days">
                                        @foreach ($deliveryLinks as $deliveryLink)
                                            @include('public.product.delivery-date')
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>{{ trans('public/product.no_bookable_dates') }}</p>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="form-group">
                <label for="exampleTextarea">{{ trans('public/product.message_producer') }}</label>
                <textarea class="form-control" name="message" rows="3" placeholder="{{ trans('public/product.message_producer_placeholder') }}"></textarea>
            </div>
        </div>

        <div class="card-body">
            @if (!Auth::check())
                <a href="/login" class="btn btn-success pull-left">{{ trans('public/node.login_needed') }}</a>
            @else
                <button type="submit" class="btn btn-success pull-left">{{ trans('public/node.add_to_cart') }}</button>
            @endif
        </div>
    </form>
</div>
