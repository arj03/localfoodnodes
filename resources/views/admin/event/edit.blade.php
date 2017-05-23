@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <form action="/account/{{ $eventOwner->eventOwnerUrl() }}/event/{{ $event->id }}/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include('admin.event.generic-event-form')
        @component('admin.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/event.save_event') }}</button>
            <a href="/account/{{ $eventOwner->eventOwnerUrl() }}/event/{{ $event->id }}/delete" class="btn btn-danger">{{ trans('admin/event.delete_event') }}</a>
        @endcomponent
    </form>
@endsection
