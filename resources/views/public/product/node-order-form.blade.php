<div class="card">
    <div class="card-header">{{ trans('public/node.order') }}</div>
    <div class="card-block order-block">
        <form action="/checkout/item/add" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="node_id" value="{{ $node->id }}" />
            <input type="hidden" name="product_id" value="{{ $product->id }}" />

            <div class="form-group">
                @if ($product->variants()->count() > 0)
                    <label class="form-control-label">
                        {{ trans('public/node.select_variant') }}
                        @include('admin.field-error', ['field' => 'variant_id'])
                    </label>
                    @foreach ($product->variants() as $variant)
                        <div class="form-check">
                            <label class="form-check-label w-100">
                                <input class="form-check-input variant" type="radio" name="variant_id" value="{{ $variant->id}}">
                                {{ $product->name}} - {{ $variant->name }}
                                ({{ $variant->package_amount }} {{ trans_choice('units.' . $product->package_unit, $variant->package_amount) }})
                                <div class="price">
                                    {{ $variant->price }} {{ $producer->currency }}
                                    @if ($product->package_unit)
                                        / {{ trans_choice('units.' . $product->price_unit, $variant->package_amount) }}
                                    @endif
                                </div>
                            </label>
                        </div>
                    @endforeach
                @else
                    <label class="form-control-label">
                        {{ trans('public/node.product') }}
                        @include('admin.field-error', ['field' => 'product_id'])
                    </label>
                    <div class="form-check">
                        <label class="form-check-label w-100">
                            <input class="form-check-input" type="radio" name="product_id" value="{{ $product->id }}" checked>
                            <div>
                                {{ $product->name }}
                                @if ($product->price_unit !== 'product')
                                    ({{ $product->getPriceUnit() }})
                                @endif
                                <div class="price">{{ $product->price }} {{ $producer->currency }} / {{ trans_choice('units.' . $product->price_unit, 1) }}</div>
                            </div>
                        </label>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-control-label" for="quantity">
                    {{ trans('public/node.how_many') }}
                    @include('admin.field-error', ['field' => 'quantity'])
                </label>
                <div class="input-group">
                    <input type="number" min="0" name="quantity" class="form-control" id="quantity" placeholder="{{ trans('public/node.placeholder_qty') }}" />
                </div>
            </div>

            <div class="form-group">
                <label class="form-control-label">
                    {{ trans('public/node.select_pickup') }}
                    @include('admin.field-error', ['field' => 'delivery_dates'])
                </label>
                @if ($product->getDeliveryLinksByMonths($node->id)->count() > 0)
                    <div class="row calendar product-calendar">
                        @foreach ($product->getDeliveryLinksByMonths($node->id) as $monthDate => $deliveryLinks)
                            <div class="col col-6">
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
                                            @include('public.node.delivery-date')
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="exampleTextarea">Message to producer</label>
                <textarea class="form-control" name="message" rows="3" placeholder="Add extra information to your order"></textarea>
            </div>

            @if (!Auth::check())
                <button type="submit" class="btn btn-success pull-left" disabled>{{ trans('public/node.login') }}</button>
            @else
                <button type="submit" class="btn btn-success pull-left">{{ trans('public/node.add_to_cart') }}</button>
            @endif
        </form>
    </div>
</div>
