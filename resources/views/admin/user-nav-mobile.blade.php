@if (Auth::user())
    <div class="container-fluid hidden-lg-up">
        <div class="row">
            <nav class="navbar navbar-light user-nav-responsive">
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="/">
                    <img src="/images/nav-logo-dark.png">
                    Local Food Nodes
                </a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a href="/account/user">{{ $user->name }}</a>
                        </li>

                        @if (Auth::user() && Auth::user()->cartItems()->count() > 0)
                            <li class="nav-item">
                                <a href="/checkout">{{ trans('admin/user-nav.cart_and_checkout') }}</a>
                            </li>
                        @endif

                        <div class="nav-item nav-item-divider"></div>

                        @if ($user->nodeAdminLinks()->count() > 0)
                            @foreach ($user->nodeAdminLinks() as $nodeAdminLink)
                                <li class="nav-item">
                                    <a href="/account/node/{{ $nodeAdminLink->getNode()->id }}">
                                        {{ $nodeAdminLink->getNode()->name }}
                                    </a>
                                </li>
                            @endforeach
                            <div class="nav-item nav-item-divider"></div>
                        @endif

                        @if ($user->producerAdminLinks()->count() > 0)
                            @foreach ($user->producerAdminLinks() as $producerAdminLink)
                                <li class="nav-item">
                                    <a href="/account/producer/{{ $producerAdminLink->getProducer()->id }}">
                                        {{ $producerAdminLink->getProducer()->name }}
                                    </a>
                                </li>
                            @endforeach
                            <div class="nav-item nav-item-divider"></div>
                        @endif

                        <li class="nav-item"><a href="/logout">{{ trans('admin/user-nav.logout') }}</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
@endif
