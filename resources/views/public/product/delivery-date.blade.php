@if ($product->variants()->count() > 0)
    <div class="day">
        <!-- CSA is always all dates. This makes all dates pre-selected and disabled -->
        @if ($product->productionType === 'csa')
            <input type="checkbox" id="{{ $deliveryLink->date('Y-m-d') }}" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" {{ $deliveryLink->getCheckboxAttributes(Request::all(), $variant) }} checked disabled />
        @else
            <input type="checkbox" id="{{ $deliveryLink->date('Y-m-d') }}" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" {{ $deliveryLink->getCheckboxAttributes(Request::all(), $variant) }} />
        @endif

        <label for="{{ $deliveryLink->date('Y-m-d') }}">
            <div class="date">{{ $deliveryLink->date('j') }}</div>
            @foreach($product->variants() as $variant)
                <div class="variant-quantity variant-quantity-{{ $variant->id }}" style="display: none;">
                    {{ $deliveryLink->getAvailableQuantity($variant) }} {{ trans('public/product.in_stock') }}
                </div>
            @endforeach
        </label>

        <!-- Disabled fields doesn't submit therefor we have a hidden field with date value for CSA -->
        @if ($product->productionType === 'csa')
            <input type="hidden" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" />
        @endif
    </div>
@else
    <div class="day">
        <!-- CSA is always all dates. This makes all dates pre-selected and disabled -->
        @if ($product->productionType === 'csa')
            <input type="checkbox" id="{{ $deliveryLink->date('Y-m-d') }}" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" checked disabled />
        @else
            <input type="checkbox" id="{{ $deliveryLink->date('Y-m-d') }}" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" />
        @endif

        <label for="{{ $deliveryLink->date('Y-m-d') }}">
            <div class="date">{{ $deliveryLink->date('j') }}</div>
            <div>{{ $deliveryLink->getAvailableQuantity() }} {{ trans('public/product.in_stock') }}</div>
        </label>

        <!-- Disabled fields doesn't submit therefor we have a hidden field with date value for CSA -->
        @if ($product->productionType === 'csa')
            <input type="hidden" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" />
        @endif
    </div>
@endif
