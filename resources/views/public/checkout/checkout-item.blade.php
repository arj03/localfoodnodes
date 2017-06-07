    <div class="cart-item">
        <div class="hidden-sm-down">
            <div class="row cart-item-inner">
                <div class="col-sm-2">
                    @if ($cartDateItemLink->getItem()->getProduct() && $cartDateItemLink->getItem()->getProduct()->images()->count() > 0)
                        <img src="{{ $cartDateItemLink->getItem()->getProduct()->images()->first()->url('medium') }}">
                    @else
                        <img src="/images/product-image-placeholder.jpg">
                    @endif
                </div>

                <div class="col-12 col-sm-7">
                    <h3>{{ $cartDateItemLink->getItem()->getName() }}</h3>
                    <div>
                        @if ($cartDateItemLink->getItem()->getProducer())
                            <a href="{{ $cartDateItemLink->getItem()->getProducer()->permalink()->url }}">{{ $cartDateItemLink->getItem()->producer['name'] }}</a>
                        @else
                            {{ $cartDateItemLink->getItem()->producer['name'] }}
                        @endif
                        -
                        @if ($cartDateItemLink->getItem()->getNode())
                            <a href="{{ $cartDateItemLink->getItem()->getNode()->permalink()->url }}">{{ $cartDateItemLink->getItem()->node['name'] }}</a>
                        @else
                            {{ $cartDateItemLink->getItem()->node['name'] }}
                        @endif
                    </div>
                    @if ($cartDateItemLink->getItem()->message)
                        <p class="text-muted">{{ trans('public/checkout.message') }}: {{ $cartDateItemLink->getItem()->message }}</p>
                    @endif
                    <a href="/checkout/item/{{ $cartDateItemLink->id }}/remove">{{ trans('public/checkout.remove') }}</a>
                </div>

                <div class="col-12 col-sm-3 text-right">
                    <h3>{{ $cartDateItemLink->getPrice() }} {{ $cartDateItemLink->getItem()->producer['currency'] }}</h3>
                    <form action="/checkout/item/{{ $cartDateItemLink->id }}/update" method="post">
                        {{ csrf_field() }}
                        <div class="input-group">
                           <input type="number" min="0" class="form-control quantity-input" name="quantity" value="{{ $cartDateItemLink->quantity }}" placeholder="Qty">
                           <span class="input-group-addon">{{ trans('units.' . $cartDateItemLink->getItem()->product['price_unit']) }}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="hidden-md-up">
            <div class="row cart-item-inner">
                <div class="col-8">
                    <h3>{{ $cartDateItemLink->getItem()->getName() }}</h3>
                    <div>
                        <a href="{{ $cartDateItemLink->getItem()->getProducer()->permalink()->url }}">
                            {{ $cartDateItemLink->getItem()->producer['name'] }}
                        </a>
                        -
                        <a href="{{ $cartDateItemLink->getItem()->getNode()->permalink()->url }}">
                            {{ $cartDateItemLink->getItem()->node['name'] }}
                        </a>
                    </div>
                    @if ($cartDateItemLink->getItem()->message)
                        <p class="text-muted">{{ trans('public/checkout.message') }}: {{ $cartDateItemLink->getItem()->message }}</p>
                    @endif
                    <a href="/checkout/item/{{ $cartDateItemLink->id }}/remove">{{ trans('public/checkout.remove') }}</a>
                </div>
                <div class="col-4 text-right">
                    <h3>{{ $cartDateItemLink->getPrice() }} {{ $cartDateItemLink->getItem()->producer['currency'] }}</h3>
                    <form action="/checkout/item/{{ $cartDateItemLink->id }}/update" method="post">
                        {{ csrf_field() }}
                        <div class="input-group">
                           <input type="number" min="0" class="form-control quantity-input" name="quantity" value="{{ $cartDateItemLink->quantity }}" placeholder="Qty">
                           <span class="input-group-addon">{{ trans('units.' . $cartDateItemLink->getItem()->product['price_unit']) }}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
