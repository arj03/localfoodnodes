@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <form action="/account/{{ $eventOwner->eventOwnerUrl() }}/event/{{ $event->id }}/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include('account.event.generic-event-form')
        @component('account.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/event.save_event') }}</button>
            <a href="/account/{{ $eventOwner->eventOwnerUrl() }}/event/{{ $event->id }}/delete" class="btn btn-danger">{{ trans('admin/event.delete_event') }}</a>
        @endcomponent
    </form>
@endsection
