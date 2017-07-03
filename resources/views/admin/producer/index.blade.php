@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="row">
        <div class="col-12 col-lg-6 card-deck">
            <div class="card">
                <div class="card-header">
                    {{ $producer->name }}
                    <div class="dropdown dropdown-action-component">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-gear"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="/account/producer/{{ $producer->id }}/edit">{{ trans('admin/producer.edit_information') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-block">
                    <ul>
                        <li>{{ $producer->name }}</li>
                        <li>{{ $producer->address }} {{ $producer->zip }} {{ $producer->city }}</li>
                        <li>{{ $producer->email }}</li>
                        <li>{{ $producer->link_homepage }}</li>
                    </ul>
                </div>
                <div class="card-block">
                    <ul>
                        <li>Currency: {{ $producer->currency }}</li>
                        <li>Payment info: {{ $producer->payment_info }}</li>

                        @if ($producer->link_facebook || $producer->link_twitter || $producer->link_instagram)
                            <li>Facebook: {{ $producer->link_facebook }}</li>
                            <li>Twitter: {{ $producer->link_twitter }}</li>
                            <li>Instagram: {{ $producer->link_instagram }}</li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="/account/producer/{{ $producer->id }}/edit">{{ trans('admin/producer.edit_producer') }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Deliveries -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('admin/producer.next_delivery') }}
                    @if ($producer->getNextOrderDate() && $producer->getNextOrderDate()->count() > 0)
                        {{ $producer->getNextOrderDate()->date('Y-m-d') }}
                    @endif
                </div>
                <div class="card-block">
                    @if ($producer->getNextOrderDate() && $producer->getNextOrderDate()->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/producer.order') }}</th>
                                    <th>{{ trans('admin/producer.delivery') }}</th>
                                    <th>{{ trans('admin/producer.product') }}</th>
                                    <th>{{ trans('admin/producer.quantity') }}</th>
                                    <th>{{ trans('admin/producer.node') }}</th>
                                    <th>{{ trans('admin/producer.customer') }}</th>
                                    <th class="text-right">{{ trans('admin/producer.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($producer->getNextOrderDate()->orderDateItemLinks(null, $producer->id) as $orderDateItemLink)
                                    <tr>
                                        <td>
                                            <a href="/account/producer/{{ $producer->id }}/order/{{ $orderDateItemLink->id }}">{{ strtoupper($orderDateItemLink->ref) }}</a>
                                        </td>
                                        <td>{{ $producer->getNextOrderDate()->date('Y-m-d') }}</td>
                                        <td>
                                            <a href="/account/producer/{{ $producer->id }}/product/{{ $orderDateItemLink->getItem()->product['id'] }}/edit">
                                                {{ $orderDateItemLink->getItem()->getName() }}
                                            </a>
                                        </td>
                                        <td>{{ $orderDateItemLink->quantity }}</td>
                                        <td>{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                        <td>{{ $orderDateItemLink->getItem()->user['name'] }}</td>
                                        <td class="text-right"><span class="{{ $orderDateItemLink->getItem()->getCurrentStatus()->getHtmlClass() }}">{{ $orderDateItemLink->getItem()->getCurrentStatus() }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>{{ trans('admin/producer.no_upcoming_deliveries') }}</p>
                    @endif
                </div>
            </div>
        </div> <!-- Deliveries end -->

        <!-- Product -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ trans('admin/producer.your_products') }}</div>
                <div class="card-block">
                    @if ($producer->products()->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/producer.product') }}</th>
                                    <th>{{ trans('admin/producer.production') }}</th>
                                    <th>{{ trans('admin/producer.deliveries') }}</th>
                                    <th>{{ trans('admin/producer.variants') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($producer->products() as $product)
                                <tr>
                                    <td>
                                        <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/edit">{{ $product->name }}</a>
                                        @if ($product->isVisible() !== true)
                                            <i class="fa fa-warning" title="{{ $product->isVisible() }}"></i>
                                        @endif
                                    </td>
                                    <!-- Production -->
                                    <td>
                                        @if ($product->productions()->count() > 0)
                                            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production">{{ trans('admin/producer.edit_production') }}</a>
                                        @else
                                            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/production">{{ trans('admin/producer.create_production') }}</a>
                                        @endif
                                    </td>
                                    <!-- Deliveries -->
                                    <td>
                                        @if ($product->deliveryLinks()->count() > 0)
                                            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/deliveries">{{ trans('admin/producer.edit_delivery_dates') }}</a>
                                        @else
                                            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/deliveries">{{ trans('admin/producer.select_delivery_dates') }}</a>
                                        @endif
                                    </td>
                                    <!-- Variants -->
                                    <td>
                                        @if ($product->variants()->count() > 0)
                                            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/variants">{{ $product->variantsAsString() }}</a>
                                        @else
                                            <a href="/account/producer/{{ $producer->id }}/product/{{ $product->id }}/variants">{{ trans('admin/producer.create_variants') }}</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="/account/producer/{{ $producer->id }}/products">{{ trans('admin/producer.all_products') }}</a>
                    @else
                        <p>{{ trans('admin/producer.no_products') }}</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="/account/producer/{{ $producer->id }}/product/create">{{ trans('admin/producer.create_product') }}</a>
                </div>
            </div> <!-- Products end -->

            <!-- Nodes -->
            @include('admin.producer.nodes')
        </div>
    </div>

    <div class="row">
        @if ($producer->adminLinks()->count() > 0)
            <div class="col-12 col-lg-6 card-deck">
                <!-- Producer admin -->
                <div class="card">
                    <div class="card-header">{{ trans('admin/producer.administrators') }}</div>
                    <div class="card-block">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/producer.name') }}</th>
                                    <th>{{ trans('admin/producer.email') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($producer->adminLinks() as $adminLink)
                                    <tr>
                                        <td>{{ $adminLink->getUser()->name }}</td>
                                        <td>{{ $adminLink->getUser()->email }}</td>
                                    </tr>
                                @endforeach

                                @foreach ($producer->adminInvites() as $adminInvite)
                                    <tr>
                                        <td class="text-muted">{{ $adminInvite->getUser()->name }}</td>
                                        <td>
                                            <div class="dropdown dropdown-action-component">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-gear"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="/account/producer/{{ $producer->id }}/invite/{{ $adminInvite->user_id }}/cancel">
                                                        {{ trans('admin/producer.cancel_invite') }}
                                                    </a>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                        </table>
                    </div>
                    <div class="card-footer">
                        <form action="/account/producer/{{ $producer->id }}/invite/send" method="post">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <input type="email" name="email" id="email" class="form-control" placeholder="User email">
                                <button type="submit" class="input-group-addon btn btn-success">
                                    {{ trans('admin/producer.invite_admin') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- Producer admin end -->
        @endif

        <!-- Events -->
        <div class="col-12 col-lg-6 card-deck">
            <div class="card">
                <div class="card-header">{{ trans('admin/producer.events') }}</div>
                <div class="card-block">
                    @if ($producer->events()->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/producer.event_name') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($producer->events() as $event)
                                    <tr>
                                        <td><a href="/account/event/{{ $event->id }}/edit">{{ $event->name }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="/account/producer/{{ $producer->id }}/events">{{ trans('admin/producer.all_events') }}</a>
                    @else
                        <p>{{ trans('admin/producer.no_events') }}</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="/account/producer/{{ $producer->id }}/event/create">{{ trans('admin/producer.create_event') }}</a>
                </div>
            </div> <!-- Events end -->
        </div>
    </div>
@endsection
