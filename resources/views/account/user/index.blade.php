@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    <div class="row">
        @if ($user->nodeAdminInvites()->count() > 0 || $user->producerAdminInvites()->count() > 0)
            @if ($user->nodeAdminInvites())
                @foreach ($user->nodeAdminInvites() as $nodeAdminLink)
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">You have been added as node admin</div>
                            <div class="card-body">
                                <p>You have been added as an admin to {{ $nodeAdminLink->getNode()->name }}</p>
                                <a class="btn btn-success" href="/account/node/{{ $nodeAdminLink->getNode()->id }}/invite/accept">Accept</a>
                                <a class="btn btn-danger" href="/account/node/{{ $nodeAdminLink->getNode()->id }}/invite/{{ $user->id }}/cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if ($user->producerAdminInvites())
                @foreach ($user->producerAdminInvites() as $producerAdminLink)
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">You have been added as admin</div>
                            <div class="card-body">
                                <p>You have been added as an admin to {{ $producerAdminLink->getProducer()->name }}</p>
                                <a class="btn btn-success" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/invite/accept">Accept</a>
                                <a class="btn btn-danger" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/invite/{{ $user->id }}/cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif
    </div>

    <div class="card-deck">
        @include('account.user.membership-card')

        <div class="card">
            <div class="card-header">{{ $user->name }}</div>
            <div class="card-body">
                <div>{{ $user->name }}</div>
                <div>{{ $user->email }}</div>
                <div>{{ $user->address }}</div>
                <div>{{ $user->zip }}</div>
                <div>{{ $user->city }}</div>
                <div>{{ trans('admin/user.language') }}: <a href="/account/user/edit">{{ $user->getLanguageName() }}</a></div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="/account/user/edit">{{ trans('admin/user.edit_information') }}</a>
                <a href="/account/user/password/edit">{{ trans('admin/user.change_password') }}</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">{{ trans('admin/user.nodes_you_follow') }}</div>
            <div class="card-body">
                @if ($user->nodeLinks()->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($user->nodeLinks() as $nodeLink)
                                    <tr>
                                        <td><a href="{{ $nodeLink->getNode()->permalink()->url }}">{{ $nodeLink->getNode()->name }}</a></td>
                                        <td class="text-right"><a href="/account/user/node/{{ $nodeLink->getNode()->id }}"><i class="fa fa-times-circle"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{ trans('admin/user.no_nodes') }}
                @endif
            </div>
        </div>
    </div> <!-- End carddeck -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('admin/user.next_pickup') }}
                    @if ($user->getNextOrderDate() && $user->getNextOrderDate()->count() > 0)
                        {{ $user->getNextOrderDate()->date('Y-m-d') }}
                    @endif
                </div>
                <div class="card-body">
                    @if ($user->getNextOrderDate() && $user->getNextOrderDate()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/user.order') }}</th>
                                        <th>{{ trans('admin/user.product') }}</th>
                                        <th>{{ trans('admin/user.quantity') }}</th>
                                        <th>{{ trans('admin/user.producer') }}</th>
                                        <th>{{ trans('admin/user.node') }}</th>
                                        <th>{{ trans('admin/user.total') }}</th>
                                        <th class="text-right">{{ trans('admin/user.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->getNextOrderDate()->orderDateItemLinks($user->id) as $orderDateItemLink)
                                        <tr>
                                            <td>{{ $orderDateItemLink->ref }}</td>
                                            <td>
                                                <a href="/account/user/order/{{ $orderDateItemLink->id }}">
                                                    {{ $orderDateItemLink->getItem()->getName() }}
                                                </a>
                                            </td>
                                            <td>{{ $orderDateItemLink->quantity }}</td>
                                            <td>
                                                <a href="/account/user/orders/producer/{{ $orderDateItemLink->producer_id }}">
                                                    {{ $orderDateItemLink->getItem()->producer['name'] }}
                                                </a>
                                            </td>
                                            <td>{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                            <td>{!! $orderDateItemLink->getPriceWithUnit() !!}</td>
                                            <td class="text-right"><span class="{{ $orderDateItemLink->getItem()->getCurrentStatus()->getHtmlClass() }}">{{ $orderDateItemLink->getItem()->getCurrentStatus()}}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{ trans('admin/user.no_orders') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @if (Session::has('welcome_modal'))
        <div class="modal fade" id="welcome-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('public/welcome-message.welcome_header') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body body-text">
                        <p>{!! trans('public/welcome-message.welcome_paragraph1') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph2') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph3') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph4') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph5') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph6') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph7') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph8') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph9') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph10') !!}</p>
                        <p>{!! trans('public/welcome-message.welcome_paragraph11') !!}</p>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('#welcome-modal').modal('show');
        </script>
    @endif
@endsection
