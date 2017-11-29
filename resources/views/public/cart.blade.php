@if (Auth::user() && Auth::user()->cartItems()->count() > 0)
    <div class="cart">
        <a class="btn btn-success" href="/checkout">
            <i class="fa fa-shopping-cart"></i>
            <div>{{ Auth::user()->cartItems()->count() }} {{ trans_choice('public/cart.in_cart', Auth::user()->cartItems()->count() ) }}</div>
        </a>
    </div>
@endif
