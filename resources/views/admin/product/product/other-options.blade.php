<div class="card">
    <div class="card-header">
        {{ trans('admin/product.other_options') }}
    </div>
    <div class="card-block">
        <div class="form-group">
            <label for="payment-info">
                {{ trans('admin/product.product_payment_info') }}
            </label>
            <textarea class="form-control" id="payment-info" name="payment_info" rows="3" placeholder="{{ trans('admin/product.product_payment_info_placeholder') }}">{{ $product->payment_info }}</textarea>
        </div>

        <div class="form-check">
            <fieldset class="form-group">
                <legend class="col-form-legend">{{ trans('admin/product.visibility') }}</legend>
                <label class="form-check-label">
                    <input class="form-check-input" name="is_hidden" type="hidden" value="0" />
                    <input class="form-check-input" name="is_hidden" type="checkbox" value="1" {{ $product->is_hidden == 1 ? 'checked' : '' }} />
                    {{ trans('admin/product.hide_from_store') }}
                </label>
            </fieldset>
        </div>
        <div class="row">
            <div class="form-group col-12 col-lg-6">
                <label class="control-label" for="deadline">{{ trans('admin/product.booking_deadline') }}</label>
                <div class="input-group">
                    <input type="number" min="0" name="deadline" class="form-control" id="deadline" placeholder="0" value="{{ $product->deadline or '' }}">
                    <span class="input-group-addon">{{ trans('admin/product.days') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
