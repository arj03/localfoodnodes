<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">{{ trans('admin/product.edit_variant') }}</div>
            <div class="card-body">
                {{ csrf_field() }}
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="form-group">
                    <label class="form-control-label" for="name">
                        {{ trans('admin/product.name') }}
                        @include('account.field-error', ['field' => 'name'])
                    </label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="{{ trans('admin/product.variant_name_example') }}" value="{{ $variant->name }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="package_amount">
                        {{ trans('admin/product.amount_per_package') }}
                        @include('account.field-error', ['field' => 'package_amount'])
                    </label>
                    <div class="input-group">
                        <input type="text" name="package_amount" class="form-control" id="package_amount" placeholder="{{ trans('admin/product.amount_per_package') }}" value="{{ $variant->package_amount }}">
                        @if ($product->package_unit)
                            <span class="input-group-addon">{{ trans_choice('units.' . $product->package_unit, 2) }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="price">
                        {{ trans('admin/product.price') }}
                        @include('account.field-error', ['field' => 'price'])
                    </label>
                    <input type="number" min="0" name="price" class="form-control" id="price" placeholder="{{ trans('admin/product.price') }}" value="{{ $variant->price }}">
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        @include('account.product.variants.how-does-it-work')
    </div>
</div>
