@if ($product->variants()->count() > 0)
    <div class="day">
        <input type="checkbox" id="{{ $deliveryLink->date('Y-m-d') }}" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" {{ $deliveryLink->getCheckboxAttributes(Request::all(), $variant) }} />
        <label for="{{ $deliveryLink->date('Y-m-d') }}">
            <div class="date">{{ $deliveryLink->date('j') }}</div>
            @foreach($product->variants() as $variant)
                <div class="variant-quantity variant-quantity-{{ $variant->id }}" style="display: none;">
                    {{ $deliveryLink->getAvailableQuantity($variant) }} in stock
                </div>
            @endforeach
        </label>

        <!-- if csa disable checkbox, only avaiable if buying all -->
        @if ($product->productionType === 'csa')
            <input type="hidden" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" />
        @endif
    </div>
@else
    <div class="day">
        <input type="checkbox" id="{{ $deliveryLink->date('Y-m-d') }}" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" />
        <label for="{{ $deliveryLink->date('Y-m-d') }}">
            <div class="date">{{ $deliveryLink->date('j') }}</div>
            <div>{{ $deliveryLink->getAvailableQuantity() }} in stock</div>
        </label>

        <!-- if csa disable checkbox, only avaiable if buying all -->
        @if ($product->productionType === 'csa')
            <input type="hidden" name="delivery_dates[]" value="{{ $deliveryLink->date('Y-m-d') }}" />
        @endif
    </div>
@endif
