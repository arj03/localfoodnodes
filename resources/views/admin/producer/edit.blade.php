@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <form action="/account/producer/{{ $producer->id }}/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $producer->id }}">
        @include('admin.producer.generic-producer-form')
        @component('admin.form-control-bar')
            <button type="submit" class="btn btn-success">{{ trans('admin/producer.save_producer') }}</button>
            @if ($producer->adminLinks()->count() > 1)
                <a href="/account/producer/{{ $producer->id }}/leave" class="btn btn-warning">{{ trans('admin/producer.leave_producer') }}</a>
            @else
                <a href="/account/producer/{{ $producer->id }}/delete/confirm" class="btn btn-danger">{{ trans('admin/producer.delete_producer') }}</a>
            @endif
        @endcomponent
    </form>
@endsection
