<div class="container-fluid mb-5">
    <div class="row">
        <nav class="navbar navbar-light navbar-expand-md">
            <a class="navbar-brand" href="/">
                <img src="/images/nav-logo-dark.png">
                Local Food Nodes
            </a>

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="main-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Find nodes</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/account/user">{{ $user->name }}</a>
                    </li>

                    @if (Auth::user() && Auth::user()->cartItems()->count() > 0)
                        <li class="nav-item">
                            <a class="nav-link" href="/checkout">{{ trans('admin/user-nav.cart_and_checkout') }}</a>
                        </li>
                    @endif

                    @if ($user->nodeAdminLinks()->count() > 0)
                        @foreach ($user->nodeAdminLinks() as $nodeAdminLink)
                            <li class="nav-item">
                                <a class="nav-link" href="/account/node/{{ $nodeAdminLink->getNode()->id }}">
                                    {{ $nodeAdminLink->getNode()->name }}
                                </a>
                            </li>
                        @endforeach
                    @endif

                    @if ($user->producerAdminLinks()->count() > 0)
                        @foreach ($user->producerAdminLinks() as $producerAdminLink)
                            <li class="nav-item">
                                <a class="nav-link" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}">
                                    {{ $producerAdminLink->getProducer()->name }}
                                </a>
                            </li>
                        @endforeach
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="/logout">{{ trans('admin/user-nav.logout') }}</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
