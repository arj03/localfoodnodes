@extends('public.layout')

@section('title', $event->name)

@section('content')
    <div class="header">
        <div class="top">
            <div class="row">
                <div class="col-12 col-xl-7">
                    <h1>{{ $event->name }}</h1>
                    <div class="d-block d-xl-none">{{ $event->start_datetime->format('Y-m-d H:i') }}</div>
                    {{ $event->address }} {{ $event->zip }} {{ $event->city }}
                </div>
                <div class="col-12 col-xl-5 d-none d-xl-block text-right">
                    <h1>{{ $event->start_datetime->format('Y-m-d H:i') }}</h1>
                </div>
            </div>
        </div>

        <div id="map"></div>
    </div>

    <div class="container top-container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">{{ $event->name }}</div>
                    <div class="card-body">
                        {!! $event->info !!}
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">{{ trans('public/event.information') }}</div>
                    <div class="card-body">
                        <div>{{ $event->address }} {{ $event->zip }} {{ $event->city }}</div>
                        <div>{{ $event->start_datetime->format('Y-m-d H:i') }} - {{ $event->end_datetime->format('Y-m-d H:i') }}</div>

                        @if (Auth::check())
                            <div>
                                @if ($user->eventLink($event->id))
                                    <a class="btn btn-success w-100" href="/account/user/event/{{ $event->id }}">{{ trans('public/event.not_going') }}</a>
                                @else
                                    <a class="btn btn-success w-100" href="/account/user/event/{{ $event->id }}">{{ trans('public/event.going') }}</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('public.components.header-map', [
        'lat' => $event->location['lat'],
        'lng' => $event->location['lng']
    ])
@endsection
