@if (Auth::user())
    <div class="user-nav hidden-md-down">
        <div class="scrollfix">
            <div class="logo">
                <img src="/images/nav-logo.png">
                <a href="/">
                    Local Food Nodes
                    <span class="beta">beta</span>
                </a>
            </div>

            @include('public.cart')

            <div class="block">
                <div class="block-header">
                    {{ trans('admin/user-nav.your_user') }}
                </div>
                <ul class="user">
                    <li>
                        <a class="{{ Request::is('account/user') ? 'active' : '' }}" href="/account/user">{{ $user->name }}</a>
                        <ul>
                            <li><a class="{{ Request::is('account/user/order*') ? 'active' : '' }}" href="/account/user/orders">- {{ trans('admin/user-nav.orders') }}</a></li>
                            <li><a class="{{ Request::is('account/user/pickups*') ? 'active' : '' }}" href="/account/user/pickups">- {{ trans('admin/user-nav.pickups') }}</a></li>
                            <li><a class="{{ Request::is('account/user/event*') ? 'active' : '' }}" href="/account/user/events">- {{ trans('admin/user-nav.events') }}</a></li>
                        </ul>
                    </li>

                    @if ($user->nodeLinks()->count() > 0)
                        <li>
                            <div>{{ trans('admin/user-nav.nodes_you_follow') }}</div>
                            <ul>
                                @foreach ($user->nodeLinks() as $nodeLink)
                                    <li>
                                        <a class="{{ Request::is($nodeLink->getNode()->permalink()->urlWithoutSlash . '*') ? 'active' : '' }}" href="{{ $nodeLink->getNode()->permalink()->url }}">- {{ $nodeLink->getNode()->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="block">
                <div class="block-header">
                    {{ trans('admin/user-nav.node_administration') }}
                </div>
                <ul>
                    @if ($user->nodeAdminLinks()->count() > 0)
                        @foreach ($user->nodeAdminLinks() as $nodeAdminLink)
                            <li>
                                <a class="{{ Request::is('account/node/' . $nodeAdminLink->getNode()->id) ? 'active' : '' }}" href="/account/node/{{ $nodeAdminLink->getNode()->id }}">{{ $nodeAdminLink->getNode()->name }}</a>
                                <ul>
                                    <li>
                                        <a class="{{ Request::is('account/node/' . $nodeAdminLink->getNode()->id . '/producers') ? 'active' : '' }}" href="/account/node/{{ $nodeAdminLink->getNode()->id }}/producers">- {{ trans('admin/user-nav.producers') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ Request::is('account/node/' . $nodeAdminLink->getNode()->id . '/users') ? 'active' : '' }}" href="/account/node/{{ $nodeAdminLink->getNode()->id }}/users">- {{ trans('admin/user-nav.users') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ Request::is('account/node/' . $nodeAdminLink->getNode()->id . '/event*') ? 'active' : '' }}" href="/account/node/{{ $nodeAdminLink->getNode()->id }}/events">- {{ trans('admin/user-nav.events') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @endforeach
                    @endif
                    <li>
                        <a href="/account/node/create"><i class="fa fa-plus-circle"></i> {{ trans('admin/user-nav.create_node') }}</a>
                    </li>
                </ul>
            </div>

            <div class="block">
                <div class="block-header">
                    {{ trans('admin/user-nav.producer_administration') }}
                </div>
                <ul>
                    @if ($user->producerAdminLinks()->count() > 0)
                        @foreach ($user->producerAdminLinks() as $producerAdminLink)
                            <li>
                                <a class="{{ Request::is('account/producer/' . $producerAdminLink->getProducer()->id) ? 'active' : '' }}" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}">{{ $producerAdminLink->getProducer()->name }}</a>
                                <ul>
                                    <li>
                                        <a class="{{ Request::is('account/producer/' . $producerAdminLink->getProducer()->id . '/product*') ? 'active' : '' }}" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/products">- {{ trans('admin/user-nav.products') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ Request::is('account/producer/' . $producerAdminLink->getProducer()->id . '/deliveries') ? 'active' : '' }}" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/deliveries">- {{ trans('admin/user-nav.deliveries') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ Request::is('account/producer/' . $producerAdminLink->getProducer()->id . '/order*') ? 'active' : '' }}" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/orders">- {{ trans('admin/user-nav.orders') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ Request::is('account/producer/' . $producerAdminLink->getProducer()->id . '/#nodes') ? 'active' : '' }}" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/#nodes">- {{ trans('admin/user-nav.delivery-nodes') }}</a>
                                    </li>
                                    <li>
                                        <a class="{{ Request::is('account/producer/' . $producerAdminLink->getProducer()->id . '/events') ? 'active' : '' }}" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/events">- {{ trans('admin/user-nav.events') }}</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/product/create"><i class="fa fa-plus-circle"></i> {{ trans('admin/user-nav.create_product') }}</a>
                            </li>
                        @endforeach
                    @elseif ($user->producerAdminLinks()->count() === 0)
                        <li>
                            <a href="/account/producer/create"><i class="fa fa-plus-circle"></i> {{ trans('admin/user-nav.create_producer') }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="block">
                <div class="block-header"></div>
                <ul>
                    <li><a href="http://faq.localfoodnodes.org">{{ trans('admin/user-nav.faq') }}</a></li>
                    <li><a href="http://guide.localfoodnodes.org">{{ trans('admin/user-nav.guide') }}</a></li>
                    <li><a href="/logout">{{ trans('admin/user-nav.logout') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
@endif
