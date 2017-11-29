@extends('account.layout')

@section('title', 'Node')

@section('content')
    @include('account.page-header')

    <div class="card-deck mb-5">
        <div class="card">
            <div class="card-header">{{ trans('admin/node.node_info') }}</div>
            <div class="card-body">
                <ul>
                    <li>{{ $node->name }}</li>
                    <li>{{ $node->address }} {{ $node->zip }} {{ $node->city }}</li>
                    <li>{{ $node->email }}</li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="/account/node/{{ $node->id }}/edit">{{ trans('admin/node.edit_node') }}</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">{{ trans('admin/node.events') }}</div>
            <div class="card-body">
                @if ($node->events()->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/node.name') }}</th>
                                    <th>{{ trans('admin/node.date') }}</th>
                                    <th>{{ trans('admin/node.guests') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($node->events() as $event)
                                    <tr>
                                        <td><a href="/account/node/{{ $node->id }}/event/{{ $event->id }}/edit">{{ $event->name }}</a></td>
                                        <td>{{ $event->start_datetime->format('Y-m-d H:i') }} - {{ $event->end_datetime->format('Y-m-d H:i') }}</td>
                                        <td><a href="/account/node/{{ $node->id }}/event/{{ $event->id }}/guests">{{ $event->userLinks()->count() }} {{ trans('admin/node.guests') }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>{{ trans('admin/node.no_events') }}</p>
                @endif
            </div>
            <div class="card-footer">
                <a href="/account/node/{{ $node->id }}/event/create">{{ trans('admin/node.create_event') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('admin/node.users') }}
                    <a href="mailto:{{ $userEmails->implode(',') }}" target="_blank"><i class="fa fa-envelope"></i></a>
                </div>
                <div class="card-body">
                    @if ($node->userLinks()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/node.name') }}</th>
                                        <th>{{ trans('admin/node.address') }}</th>
                                        <th>{{ trans('admin/node.city') }}</th>
                                        <th>{{ trans('admin/node.zip') }}</th>
                                        <th>{{ trans('admin/node.email') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($node->userLinks() as $userLink)
                                        <tr>
                                            <td>{{ $userLink->getUser()->name }}</td>
                                            <td>{{ $userLink->getUser()->address }}</td>
                                            <td>{{ $userLink->getUser()->zip }}</td>
                                            <td>{{ $userLink->getUser()->city }}</td>
                                            <td><a href="mailto:{{ $userLink->getUser()->email }}">{{ $userLink->getUser()->email }}</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{ trans('admin/node.no_users') }}
                    @endif
                    <a href="/account/node/{{ $node->id }}/users">{{ trans('admin/node.all_users') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('admin/node.producers') }}
                    <a href="mailto:{{ $producerEmails->implode(',') }}" target="_blank"><i class="fa fa-envelope"></i></a>
                </div>
                <div class="card-body">
                    @if ($node->producerLinks()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/node.name') }}</th>
                                        <th>{{ trans('admin/node.address') }}</th>
                                        <th>{{ trans('admin/node.zip') }}</th>
                                        <th>{{ trans('admin/node.city') }}</th>
                                        <th>{{ trans('admin/node.email') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($node->producerLinks() as $producerLink)
                                        <tr>
                                            <td>
                                                @if ($producerLink->getProducer()->isBlacklisted($node->id))
                                                    <i class="fa fa-user-times text-danger"></i>
                                                @endif
                                                {{ $producerLink->getProducer()->name }}
                                            </td>
                                            <td>{{ $producerLink->getProducer()->address }}</td>
                                            <td>{{ $producerLink->getProducer()->zip }}</td>
                                            <td>{{ $producerLink->getProducer()->city }}</td>
                                            <td>
                                                <a href="mailto:{{ $producerLink->getProducer()->email }}" title="{{ $producerLink->getProducer()->email }}">
                                                    {{ $producerLink->getProducer()->email }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="dropdown dropdown-action-component">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-gear"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/account/node/{{ $node->id }}/producer/{{ $producerLink->getProducer()->id }}/blacklist">
                                                            {{ $producerLink->getProducer()->isBlacklisted($node->id) ? trans('admin/node.unblock_producer') : trans('admin/node.block_producer') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{ trans('admin/node.no_producers') }}
                    @endif
                    <a href="/account/node/{{ $node->id }}/producers">{{ trans('admin/node.all_producers') }}</a>
                </div>
            </div>
        </div>

        @if ($node->adminLinks()->count() > 0)
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">{{ trans('admin/node.administrators') }}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/node.name') }}</th>
                                        <th>{{ trans('admin/node.email') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($node->adminLinks() as $adminLink)
                                        <tr>
                                            <td>{{ $adminLink->getUser()->name }}</td>
                                            <td>{{ $adminLink->getUser()->email }}</td>
                                        </tr>
                                    @endforeach

                                    @foreach ($node->adminInvites() as $adminInvite)
                                        <tr>
                                            <td class="text-muted">{{ $adminInvite->getUser()->name }}</td>
                                            <td>
                                                <div class="dropdown dropdown-action-component">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="/account/node/{{ $node->id }}/invite/{{ $adminInvite->user_id }}/cancel">{{ trans('admin/node.cancel_invite') }}</a>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form action="/account/node/{{ $node->id }}/invite/send" method="post">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <input type="email" name="email" id="email" class="form-control" placeholder="User email">
                                <button type="submit" class="input-group-addon btn btn-success">{{ trans('admin/node.invite_admin') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
