@foreach($nodes as $node)
    <div class="card">
        <div class="card-header toggle">
            {{ $node->name }} - {{ trans('admin/product.select_delivery_dates') }}
            <i class="fa fa-chevron-down toggle"></i>
        </div>
        <div class="card-block">
            <div class="row calendar product-calendar">
                @foreach ($node->getDeliveryDatesByMonths() as $firstDateOfMonth => $deliveryDates)
                    <div class="col col-3">
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
                                        id="{{ $deliveryDate }}"
                                        name="delivery_dates[{{ $node->id }}][]"
                                        value="{{ $deliveryDate }}"
                                        {{ $product->deliveryLink($node->id, $deliveryDate) ? 'checked' : '' }}>
                                        <label for="{{ $deliveryDate }}">
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
