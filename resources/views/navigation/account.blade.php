<div class="container-fluid navbar-wrapper">
    <div class="container">
        <nav class="navbar navbar-light navbar-expand-md">
            <a class="navbar-brand" href="/">
                <img src="/images/nav-logo-dark.png">
            </a>

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#main-nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="main-nav">
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Sök utlämningsplats">
                </form>

                <ul class="navbar-nav ml-auto">
                    <!-- Cart -->
                    @if (Auth::user() && Auth::user()->cartItems()->count() > 0)
                        <li class="nav-item {{ Request::is('checkout') ? 'active' : '' }}">
                            <a class="nav-link" href="/checkout">{{ trans('admin/user-nav.cart_and_checkout') }}</a>
                        </li>
                    @endif

                    <!-- Node -->
                    @if ($user->nodeAdminLinks()->count() > 0)
                        @if ($user->nodeAdminLinks()->count() === 1)
                            <li class="nav-item {{ Request::is('account/node/*') ? 'active' : '' }}">
                                <a class="nav-link" href="/account/node/{{ $user->nodeAdminLinks()->first()->getNode()->id }}">
                                    <i class="fa fa-location-arrow"></i>
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown {{ Request::is('account/node/*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" id="main-nav-user" data-toggle="dropdown">
                                    <i class="fa fa-location-arrow"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @foreach ($user->nodeAdminLinks() as $nodeAdminLink)
                                        <a class="dropdown-item" href="/account/node/{{ $nodeAdminLink->getNode()->id }}">
                                            {{ $nodeAdminLink->getNode()->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                        @endif
                    @else
                        <li class="nav-item {{ Request::is('account/node/*') ? 'active' : '' }}">
                            <a class="nav-link" href="/account/node/create">
                                <i class="fa fa-location-arrow"></i>
                            </a>
                        </li>
                    @endif

                    <!-- Producer -->
                    @if ($user->producerAdminLinks()->count() > 0)
                        @if ($user->producerAdminLinks()->count() === 1)
                            <li class="nav-item {{ Request::is('account/producer/*') ? 'active' : '' }}">
                                <a class="nav-link" href="/account/producer/{{ $user->producerAdminLinks()->first()->getProducer()->id }}">
                                    <i class="fa fa-male"></i>
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown {{ Request::is('account/producer/*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" id="main-nav-user" data-toggle="dropdown">
                                    <i class="fa fa-male"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @foreach ($user->producerAdminLinks() as $producerAdminLink)
                                        <a class="dropdown-item" href="/account/producer/{{ $producerAdminLink->getNode()->id }}">
                                            {{ $producerAdminLink->getNode()->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                        @endif
                    @else
                        <li class="nav-item {{ Request::is('account/producer/*') ? 'active' : '' }}">
                            <a class="nav-link" href="/account/producer/create">
                                <i class="fa fa-male"></i>
                            </a>
                        </li>
                    @endif

                    <!-- User -->
                    <li class="nav-item dropdown {{ Request::is('account/user') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="/account/user" id="main-nav-user" data-toggle="dropdown">
                            <i class="fa fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="/account/user">Account</a>
                            <a class="dropdown-item" href="/logout">{{ trans('admin/user-nav.logout') }}</a>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</div>
