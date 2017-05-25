@extends('public.layout')

@section('title', trans('public/404.title'))

@section('content')
    <div class="container">
        <div class="card mt-5">
            <div class="card-block">
                {!! trans('public/404.no-page') !!}
            </div>
        </div>
    </div>
@endsection
