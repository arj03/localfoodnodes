@foreach($nodes as $index => $node)
    <div class="card">
        <div class="card-header toggle">
            {{ $node->name }} - {{ trans('admin/product.select_delivery_dates') }}
            @if ($index === 0)
                <i class="fa fa-chevron-up toggle"></i>
            @else
                <i class="fa fa-chevron-down toggle"></i>
            @endif
        </div>
        <div class="card-block">
            <div class="row calendar product-calendar">
                @foreach ($node->getDeliveryDatesByMonths($product) as $firstDateOfMonth => $deliveryDates)
                    <div class="col-6 col-sm-3 mt-3">
                        <div class="month">
                            <div class="month-header">
                                <b>
                                    {{ substr(ucfirst(trans('months.' . date('F', strtotime($firstDateOfMonth)))), 0, 3) }}
                                    {{ date('Y', strtotime($firstDateOfMonth)) }}
                                </b>
                                <i class="fa fa-check-square select-all-dates-action"></i>
                            </div>
                            <div class="days">
                                @foreach ($deliveryDates as $deliveryDate)
                                    <div class="day">
                                        <input
                                        type="checkbox"
                                        id="{{ $node->id }}-{{ $deliveryDate }}"
                                        name="delivery_dates[{{ $node->id }}][]"
                                        value="{{ $deliveryDate }}"
                                        {{ $product->deliveryLink($node->id, $deliveryDate) ? 'checked' : '' }}>
                                        <label for="{{ $node->id }}-{{ $deliveryDate }}">
                                            <div class="date">{{ date('d', strtotime($deliveryDate)) }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
