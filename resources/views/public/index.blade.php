@extends('public.layout')

@section('title', trans('public/index.title'))

@section('content')
    <div class="container-fluid frontpage-section frontpage-header">
        <div class="row">
            <div class="col-12">
                <h2 class="bold">{{ trans('public/index.food_matters') }}</h2>
                <h3>{{ trans('public/index.recreating') }}</h3>
                <a href="/find-out-more" class="btn-outline">{{ trans('public/index.find_out_more') }}</a>
            </div>
        </div>
    </div>

    <?php $jsonTrans = json_encode(trans('public/index')); ?>
    <div class="node-map" id="node-map-component-root" data-ip="{{ Request::ip() }}" data-user-location="{{ json_encode($user->location) }}" data-trans="{{ $jsonTrans }}"></div>

    <div class="container-fluid frontpage-section local-food-impact hidden-md-down">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row metrics">
                        <div class="col-12 col-md-4">
                            <div class="metric">
                                <b>{{ $metrics['userCount'] }}</b>
                                <div>{{ trans('public/index.locals') }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="metric">
                                <b>{{ $metrics['nodeCount'] }}</b>
                                <div>{{ trans('public/index.local_nodes') }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="metric">
                                <b>{{ $metrics['producerCount'] }}</b>
                                <div>{{ trans('public/index.local_producers') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::check())
        <div class="container-fluid frontpage-section create-pushes">
            <h2 class="thin">{{ trans('public/index.co_create') }}</h2>
            <div class="row no-gutters">
                <div class="col-12 col-lg nodes">
                    <h2 class="bold">{{ trans('public/index.no_node') }}</h2>
                    <a class="btn-outline" href="/account/node/create">{{ trans('public/index.create_node') }}</a>
                </div>
                <div class="col-12 col-lg producers">
                    <h2 class="bold">{{ trans('public/index.food_producer') }}</h2>
                    <a class="btn-outline" href="/account/producer/create">{{ trans('public/index.list') }}</a>
                </div>
            </div>
        </div>
    @else
        <div class="container-fluid frontpage-section create-pushes">
            <h2 class="thin">{{ trans('public/index.co_create') }}</h2>
            <div class="row no-gutters">
                <div class="col-12 col-lg users">
                    <h2 class="bold">{{ trans('public/index.sign_up') }}</h2>
                    <a class="btn-outline" href="/account/user/create/user">{{ trans('public/index.sign_up_button') }}</a>
                </div>
                <div class="col-12 col-lg nodes">
                    <h2 class="bold">{{ trans('public/index.no_node') }}</h2>
                    <a class="btn-outline" href="/account/user/create/node">{{ trans('public/index.create_node') }}</a>
                </div>
                <div class="col-12 col-lg producers">
                    <h2 class="bold">{{ trans('public/index.food_producer') }}</h2>
                    <a class="btn-outline" href="/account/user/create/producer">{{ trans('public/index.list') }}</a>
                </div>
            </div>
        </div>
    @endif

    <div class="container frontpage-section upcoming-events">
        <div class="row">
            <div class="col-12">
                <h2 class="thin">{{ trans('public/index.upcoming') }}</h2>
                <div class="events">
                    @foreach ($events as $event)
                        <a href="{{ $event->permalink()->url }}" class="event">
                            <div class="date">
                                <div class="day">{{ $event->start_datetime->format('d') }}</div>
                                <div class="month">{{ $event->start_datetime->format('F') }}</div>
                            </div>
                            <div @if ($event->images()->count() > 0)
                                    style="background-image: url({{ $event->images()->first()->url() }});"
                                @endif
                            class="info">
                                <div class="block">
                                    <h2 class="bold">{{ $event->name }}</h2>
                                    <div>
                                        <b>{{ trans('public/index.where') }}</b>{{ $event->address }} {{ $event->city }}
                                    </div>
                                    <div>
                                        <b>{{ trans('public/index.when') }}</b>
                                        {{ $event->start_datetime->format('Y-m-d H:i') }} - {{ $event->end_datetime->format('Y-m-d H:i') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="/css/leaflet/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.0.3/dist/leaflet.markercluster.js"></script>
    <script src="{{ elixir('/js-apps/node-map.js') }}"></script>
@endsection
