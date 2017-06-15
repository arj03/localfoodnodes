@extends('public.layout')

@section('title', $producer->name)

@section('content')
    <div class="header">
        <div class="top">
            <div class="d-flex justify-content-between">
                <div>
                    <h1>{{ $producer->name }}</h1>
                    {{ $producer->address }} {{ $producer->zip }} {{ $producer->city }}
                </div>
            </div>
        </div>

        <div id="map"></div>
    </div>

    <div class="container top-container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">{{ $producer->name }}</div>
                    <div class="card-block">
                        {!! $producer->info !!}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">{{ trans('public/producer.products') }}</div>
                    <div class="card-block">
                        @if ($producer->products()->count() > 0)
                            <div class="row">
                                @foreach ($producer->products() as $product)
                                    @if ($product->isVisible() === true)
                                        <div class="col-6 col-lg-4 card-deck">
                                            <a class="card product-card" href="{{ $producer->permalink()->url }}{{ $product->permalink()->url }}">
                                                @if ($product->images()->count() > 0)
                                                    <img class="card-image-top" src="{{ $product->images()->first()->url('medium') }}">
                                                @else
                                                    <img class="card-image-top" src="/images/product-image-placeholder.jpg">
                                                @endif

                                                <div class="card-block">
                                                    <div class="producer-info">
                                                        <div class="name">{{ $product->producer()->name }}</div>
                                                    </div>
                                                    <div class="title">{{ $product->name }}</div>
                                                    <div class="price">
                                                        @if ($product->variants()->count() > 0)
                                                            {{ trans('public/producer.from') }} {{ $product->smallestVariant()->price }} {{ $product->producer()->currency }}
                                                        @else
                                                            {{ $product->price }} {{ $product->producer()->currency }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            {{ trans('public/producer.no_products') }}.
                        @endif
                    </div>
                </div>

                <div class="card image-card">
                    <div class="card-header">{{ trans('public/producer.events') }}</div>
                    <div class="card-block">
                        @if ($producer->events()->count() > 0)
                            @foreach ($producer->events() as $event)
                                <a href="{{ $event->permalink()->url }}">{{ $event->name }}</a>
                            @endforeach
                        @else
                            {{ $producer->name }} {{ trans('public/producer.no_events') }}.
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">{{ trans('public/product.order') }}</div>
                    <div class="card-block">
                        {{ trans('public/product.nav_to_node') }}.
                        <ul class="mt-3">
                            @foreach ($producer->nodeLinks() as $nodeLink)
                                <li>
                                    <a href="{{ $nodeLink->getNode()->permalink()->url }}">
                                        {{ $nodeLink->getNode()->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>


                @if ($producer->images()->count() > 0)
                    <div class="card image-card">
                        <div class="card-header">{{ trans('public/producer.images') }}</div>
                        <div class="images slick-slider">
                            @foreach ($producer->images() as $image)
                                <a data-fancybox="gallery" href="{{ $image->url('medium') }}">
                                    <img class="card-image-bottom" src="{{ $image->url('small') }}">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('public.components.header-map', [
        'lat' => $producer->location['lat'],
        'lng' => $producer->location['lng']
    ])
@endsection
