@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="row">
        <div class="col-12">
            @if ($user->nodeAdminInvites()->count() > 0 || $user->producerAdminInvites()->count() > 0)
                <div class="card">
                    <div class="card-header">Messages</div>
                    <div class="card-block">
                        <div class="row">
                            @if ($user->nodeAdminInvites())
                                @foreach ($user->nodeAdminInvites() as $nodeAdminLink)
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-header">You have been added as node admin</div>
                                            <div class="card-block">
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
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-header">You have been added as admin</div>
                                            <div class="card-block">
                                                <p>You have been added as an admin to {{ $producerAdminLink->getProducer()->name }}</p>
                                                <a class="btn btn-success" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/invite/accept">Accept</a>
                                                <a class="btn btn-danger" href="/account/producer/{{ $producerAdminLink->getProducer()->id }}/invite/{{ $user->id }}/cancel">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        @include('admin.user.membership-card')

        <div class="col-12 col-xl-4 card-deck">
            <div class="card">
                <div class="card-header">{{ $user->name }}</div>
                <div class="card-block">
                    <div>{{ $user->name }}</div>
                    <div>{{ $user->email }}</div>
                    <div>{{ $user->address }}</div>
                    <div>{{ $user->zip }}</div>
                    <div>{{ $user->city }}</div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="/account/user/edit">{{ trans('admin/user.edit_information') }}</a>
                    <a href="/account/user/password/edit">{{ trans('admin/user.change_password') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4 card-deck">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.nodes_you_follow') }}</div>
                <div class="card-block">
                    @if ($user->nodeLinks()->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td>{{ trans('admin/user.node_name') }}</td>
                                    <td class="text-right"></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->nodeLinks() as $nodeLink)
                                    <tr>
                                        <td><a href="{{ $nodeLink->getNode()->permalink()->url }}">{{ $nodeLink->getNode()->name }}</a></td>
                                        <td class="text-right"><a href="/account/user/node/{{ $nodeLink->getNode()->id }}"><i class="fa fa-times-circle"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        {{ trans('admin/user.no_nodes') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.next_pickup') }}</div>
                <div class="card-block">
                    @if ($user->orderDates()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/user.pickup') }}</th>
                                        <th>{{ trans('admin/user.product') }}</th>
                                        <th>{{ trans('admin/user.quantity') }}</th>
                                        <th>{{ trans('admin/user.producer') }}</th>
                                        <th>{{ trans('admin/user.node') }}</th>
                                        <th>{{ trans('admin/user.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->orderDates()->first()->orderDateItemLinks($user->id) as $orderDateItemLink)
                                        <tr>
                                            <td>{{ $orderDateItemLink->getDate()->date('Y-m-d') }}</td>
                                            <td>{{ $orderDateItemLink->getItem()->getName() }}</td>
                                            <td>{{ $orderDateItemLink->quantity }}</td>
                                            <td>{{ $orderDateItemLink->getItem()->producer['name'] }}</td>
                                            <td>{{ $orderDateItemLink->getItem()->node['name'] }}</td>
                                            <td>{{ $orderDateItemLink->getPrice() }} {{ $orderDateItemLink->getItem()->producer['currency'] }}</td>
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
