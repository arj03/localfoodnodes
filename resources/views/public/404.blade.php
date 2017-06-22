@extends('public.layout')

@section('title', trans('public/404.title'))

@section('content')

    <div class="header">
        <div class="top">
            <div class="d-flex justify-content-between">
                <h1>{!! trans('public/404.title') !!}</h1>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card mt-5">
            <div class="card-block">
                {!! trans('public/404.no-page') !!}
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <?php $jsonTrans = json_encode(trans('public/index')); ?>
        <div class="node-map" id="node-map-component-root" data-ip="{{ Request::ip() }}" data-user-location="{{ json_encode($user->location) }}" data-trans="{{ $jsonTrans }}"></div>
    </div>

    <link rel="stylesheet" href="/css/leaflet/leaflet.min.css" />
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.0.3/dist/leaflet.markercluster.js"></script>
    <script src="{{ elixir('/js-apps/node-map.js') }}"></script>
@endsection
