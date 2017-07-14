@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="card">
        <div class="card-header">Events</div>
        <div class="card-block">
            @if ($eventOwner->events()->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('admin/event.name') }}</th>
                                <th>{{ trans('admin/event.owner') }}</th>
                                <th>{{ trans('admin/event.address') }}</th>
                                <th>{{ trans('admin/event.start_date') }}</th>
                                <th>{{ trans('admin/event.end_date') }}</th>
                                <th>{{ trans('admin/event.guests') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventOwner->events() as $event)
                                <tr>
                                    <td><a href="/account/{{ $eventOwner->eventOwnerUrl() }}/event/{{ $event->id }}/edit">{{ $event->name }}</a></td>
                                    <td>{{ $event->owner()->name }}</td>
                                    <td>{{ $event->address }} {{ $event->zip }} {{ $event->city }}</td>
                                    <td>{{ $event->start_datetime->format('Y-m-d') }}</td>
                                    <td>{{ $event->end_datetime->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="/account/{{ $eventOwner->eventOwnerUrl() }}/event/{{ $event->id }}/guests">{{ $event->userLinks()->count() }} {{ trans('admin/event.going') }}</a>
                                    </td>
                                    <td>
                                        <div class="dropdown dropdown-action-component">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-gear"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="/account/{{ $eventOwner->eventOwnerUrl() }}/event/{{ $event->id }}/edit">
                                                    {{ trans('admin/event.edit_event') }}
                                                </a>
                                                <a class="dropdown-item" href="/account/{{ $eventOwner->eventOwnerUrl() }}/event/{{ $event->id }}/delete">
                                                    {{ trans('admin/event.delete_event') }}
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
                {{ trans('admin/event.no_events') }}
            @endif
        </div>
        <div class="card-footer">
            <a href="/account/{{ $eventOwner->eventOwnerUrl() }}/event/create">{{ trans('admin/event.create_event') }}</a>
        </div>
    </div>
@endsection
