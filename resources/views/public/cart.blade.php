@if (Auth::user() && Auth::user()->cartItems()->count() > 0)
    <div class="cart">
        <a class="{{ Request::is('checkout') ? 'active' : '' }}" href="/checkout">
            <i class="fa fa-shopping-cart"></i>
            <div>{{ Auth::user()->cartItems()->count() }} {{ trans_choice('public/cart.in_cart', Auth::user()->cartItems()->count() ) }}</div>
            <i class="fa fa-chevron-right"></i>
        </a>
    </div>
@endif
