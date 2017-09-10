<form action="/checkout/item/add" method="post" class="order-form">
    <div class="card-block">
        {{ csrf_field() }}
        <input type="hidden" name="node_id" value="{{ $node->id }}" />
        <input type="hidden" name="product_id" value="{{ $product->id }}" />

        <div class="form-group">
            @if ($product->variants()->count() > 0)
                <label class="form-control-label">
                    {{ trans('public/node.select_variant') }}
                    @include('admin.field-error', ['field' => 'variant_id'])
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
                    @include('admin.field-error', ['field' => 'product_id'])
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

    <div class="card-block">
        <div class="form-group">
            <label class="form-control-label" for="quantity">
                {{ trans('public/node.how_many') }}
                @include('admin.field-error', ['field' => 'quantity'])
            </label>
            <div class="input-group">
                <input type="number" min="0" name="quantity" class="form-control" id="quantity" placeholder="{{ trans('public/node.placeholder_qty') }}" />
            </div>
        </div>
    </div>

    <div class="card-block">
        <div class="form-group">
            <!-- days before filter måste fungera -->
            <!-- Hidden field for delivery date -->
        </div>
    </div>

    <div class="card-block">
        @if (!Auth::check())
            <button type="submit" class="btn btn-success pull-left" disabled>{{ trans('public/node.login_needed') }}</button>
        @else
            <button type="submit" class="btn btn-success pull-left">{{ trans('public/node.add_to_cart') }}</button>
        @endif
    </div>
</form>
