@if ($products->count() > 0)
    <form action="/checkout/items/add" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="node_id" value="{{ $node->id }}" />
        <input type="hidden" name="delivery_date" value="{{ Request::input('date') }}" />

        <div class="card-block product-list">
            @foreach ($products->sortBy('name') as $product)
                <input type="hidden" name="product[{{ $product->id }}][product_id]" value="{{ $product->id }}" />
                <input type="hidden" name="product[{{ $product->id }}][producer_id]" value="{{ $product->producer()->id }}" />

                @if ($product->isVisible($node->id) === true)
                    <div class="row product-item">
                        <div class="col-3 hidden-xs-down">
                            @if ($product->images()->count())
                                <img src="{{ $product->images()->first()->url('small') }}">
                            @endif
                        </div>

                        <div class="col-12 col-sm-9">

                            @if ($product->variants()->count() > 0)
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <h3><a href="{{ $node->permalink()->url }}{{ $product->permalink()->url }}">{{ $product->name }}</a></h3>
                                        {{ $product->producer()->name }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-7 mb-3">
                                        <div class="form-group">
                                            <select name="product[{{ $product->id}}][variant_id]" class="form-control">
                                                @foreach ($product->variants() as $index => $variant)
                                                    <option value="{{ $variant->id}}">
                                                        {{ $product->name }} - {{ $variant->name }}

                                                        @if ($variant->getPackageAmountUnit())
                                                            - {{ $variant->getPackageAmountUnit() }}
                                                        @endif

                                                        - {{ $variant->getPriceWithUnit() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-5">
                                        <div class="form-group">
                                            <div class="input-group quantity-component">
                                                <span class="input-group-addon decrease"><i class="fa fa-minus-circle"></i></span>
                                                <input type="number" min="0" name="product[{{ $product->id }}][quantity]" class="form-control" value="0" />
                                                <span class="input-group-addon increase"><i class="fa fa-plus-circle"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <h3><a href="{{ $node->permalink()->url }}{{ $product->permalink()->url }}">{{ $product->name }}</a></h3>
                                        {{ $product->producer()->name }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-7 mb-3">
                                        <select name="product_id" class="form-control">
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} - {{ $product->getPriceWithUnit() }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-5">
                                        <div class="form-group">
                                            <div class="input-group quantity-component">
                                                <span class="input-group-addon decrease"><i class="fa fa-minus-circle"></i></span>
                                                <input type="number" min="0" name="product[{{ $product->id }}][quantity]" class="form-control" value="0" />
                                                <span class="input-group-addon increase"><i class="fa fa-plus-circle"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div> <!-- End col. -->
                    </div>
                @endif <!-- End visible. -->
            @endforeach

            <div class="text-right">
                <button class="btn btn-success">{{ trans('public/node.add_to_cart') }}</button>
            </div>
        </div>
    </form>

    <script>
        $(function() {
            $('.variant-info-block-action').on('click', function(event) {
                event.preventDefault();
                $(this).closest('.product-item').find('.variant-info-block').toggleClass('hidden');
            });
        });
    </script>

    <script>
        $('.decrease').on('click', function(event) {
            updateQuantity('decrease', $(this).next());
        });
        $('.increase').on('click', function(event) {
            updateQuantity('increase', $(this).prev());
        });

        var updateQuantity = function(modifier, $input) {
            var currentValue = $.isNumeric($input.val()) ? parseInt($input.val()) : 0;
            var value = modifier === 'increase' ? currentValue + 1 : currentValue - 1;
            value > 0 ? $input.val(value) : $input.val(0);
        }
    </script>
@else
    {{ trans('public/node.no_products') }}
@endif
