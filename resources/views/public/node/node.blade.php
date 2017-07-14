@extends('public.layout')

@section('title', $node->name)

@section('content')
    <div class="header">
        <div class="top">
            <div class="d-flex justify-content-between">
                <div>
                    <h1>{{ $node->name }}</h1>
                    <div class="address">{{ $node->address }} {{ $node->zip }} {{ $node->city }}</div>
                </div>
                <div class="deliveries">
                    <h1>{{ trans_choice('public/weekdays.' . $node->delivery_weekday, 2) }} {{ $node->delivery_time }}</h1>
                </div>
            </div>
        </div>
        <div id="map"></div>
    </div>

    <div class="container top-container">
        <div class="row">
            <div class="col-12 col-lg-8">

                <!-- Products -->
                @if ($products->count() > 0 || $tags->contains('active', true))
                    <div class="card">
                        <div class="card-header">
                            {{ trans('public/node.products') }}
                            @if (Request::has('date'))
                                - {{ Request::get('date') }} <a href="{{ $node->permalink()->url }}"><i class="fa fa-times-circle"></i></a>
                            @endif
                        </div>

                        <div class="card-block product-filter">
                            @foreach ($tags as $label => $tag)
                                @if ($tag['active'])
                                    <a href="{{ $tag['url'] }}" class="badge active">{{ $label }}</a>
                                @else
                                    <a href="{{ $tag['url'] }}" class="badge">{{ $label }}</a>
                                @endif
                            @endforeach
                        </div>

                        <div class="card-block">
                            @if ($products->count() > 0)
                                <div class="row">
                                    @foreach ($products->sortBy('name') as $product)
                                        @if ($product->isVisible($node->id) === true)
                                            <div class="col-6 col-lg-4 card-deck">
                                                <a class="card product-card" href="{{ $node->permalink()->url }}{{ $product->permalink()->url }}">
                                                    @if ($product->images()->count() > 0)
                                                        <img class="card-image-top" src="{{ $product->images()->first()->url('medium') }}">
                                                    @else
                                                        <img class="card-image-top" src="/images/product-image-placeholder.jpg">
                                                    @endif

                                                    <div class="card-block">
                                                        <div class="producer-info">
                                                            <div class="name">{{ $product->producer()->name }}</div>
                                                        </div>
                                                        <div class="title">
                                                            {{ $product->name }}
                                                        </div>
                                                        <div class="price">
                                                            @if ($product->variants()->count() > 0)
                                                                {{ trans('public/node.from') }} {{ $product->smallestVariant()->getPriceWithUnit() }}
                                                            @else
                                                                {{ $product->getPriceWithUnit() }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <i class="fa fa-map-marker"></i> {{ $product->producer()->getDistance($node) }} {{ trans('public/node.km') }}
                                                        <div class="tags">
                                                            @foreach ($product->tags() as $tag)
                                                                <div class="badge badge-default">{{ trans('public/tags.' . $tag->tag) }}</div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                {{ trans('public/node.no_products') }}
                            @endif
                        </div>
                    </div>
                @endif <!-- Product end -->

                <!-- Events -->
                @if ($events->count() > 0)
                    <div class="events row no-gutters">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div>
                                        {{ trans('public/node.events') }}
                                        @if (Request::has('date'))
                                            - {{ Request::get('date') }} <a href="{{ $node->permalink()->url }}"><i class="fa fa-times-circle"></i></a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-block">
                                    @foreach ($events as $event)
                                        @include('public.components.event')
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">{{ $node->name }}</div>
                    <div class="card-block">
                        {!! $node->info !!}
                        <div class="row">
                            <div class="col-12">
                                <div class="fb-share-button" data-href="{{ $_SERVER['REQUEST_URI'] }}" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">{{ trans('public/node.share') }}</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">{{ trans('public/node.info') }}</div>
                    <div class="card-block node-metrics">
                        <div class="row">
                            <div class="metric col-4">
                                <div class="value">{{ $node->userLinks()->count() }}</div>
                                <div class="label">{{ trans('public/node.locals') }}</div>
                            </div>
                            <div class="metric col-4">
                                <div class="value">{{ $node->producerLinks()->count() }}</div>
                                <div class="label">{{ trans('public/node.producers') }}</div>
                            </div>
                            <div class="metric col-4">
                                <div class="value">{{ $node->getAverageProducerDistance() }}km</div>
                                <div class="label">{!! trans('public/node.avg_dist') !!}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <p>
                            <b>{{ trans('public/node.pick_up_order') }}</b><br>
                            {{ $node->address }} {{ $node->zip }} {{ $node->city }}<br>
                            {{ $node->delivery_weekday }} {{ $node->delivery_time }}
                        </p>
                        <p>
                            <a href="{{ $node->link_facebook }}" target="_blank">{{ trans('public/node.find_communication') }}</a>
                            @if ($node->link_facebook_producers)
                                <br><a href="{{ $node->link_facebook_producers }}" target="_blank">{{ trans('public/node.find_producer_communication') }}</a>
                            @endif
                        </p>

                        @if ($node->email)
                            <p>{{ trans('public/node.contact') }} <a href="mailto:{{ $node->email }}">{{ $node->email }}</a></p>
                        @endif
                    </div>
                    @if (Auth::check())
                        <div class="card-footer">
                            @if ($user->isAddedToNode($node->id))
                                <a class="btn btn-success w-100" href="/account/user/node/{{ $node->id }}">{{ trans('public/node.leave') }}</a>
                            @else
                                <a class="btn btn-success w-100" href="/account/user/node/{{ $node->id }}">{{ trans('public/node.join') }}</a>
                            @endif
                        </div>
                    @endif
                </div>

                @if (isset($calendar))
                    <div class="card">
                        <div class="calendar node-calendar">
                            <div class="calendar-nav">
                                <i class="fa fa-chevron-left calendar-nav-item" id="calendar-nav-prev"></i>
                                <i class="fa fa-chevron-right calendar-nav-item" id="calendar-nav-next"></i>
                            </div>
                            @foreach ($calendar as $monthDate => $month)
                                <div class="month {{ $calendarMonth !== $monthDate ? 'hidden' : '' }}">
                                    <div class="month-header">{{ trans('public/node.calendar') }} - {{ date('F Y', strtotime($monthDate)) }}</div>
                                    <div class="days">
                                        @for ($i = 0; $i < $month['offsetStart']; $i++)
                                            <div class="day disabled"></div>
                                        @endfor

                                        @if ($month['days'])
                                            @foreach ($month['days'] as $dayDate => $dayData)
                                                @if (!empty($dayData['events']))
                                                    <a href="{{ $dayData['url'] }}" class="{{ join(' ', $dayData['activities']) }}">
                                                        <div class="date">{{ date('j', strtotime($dayDate)) }}</div>
                                                    </a>
                                                @else
                                                    <div class="day">
                                                        <div class="date">{{ date('j', strtotime($dayDate)) }}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif

                                        @for ($i = 0; $i < $month['offsetEnd']; $i++)
                                            <div class="day disabled"></div>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-block">
                            <div class="calendar-explanation">
                                <p><i class="fa fa-square text-success"></i> {{ trans('public/node.pickup') }}</p>
                                <p><i class="fa fa-square text-event"></i> {{ trans('public/node.event') }}</p>
                            </div>
                        </div>
                    </div> <!-- Calendar end -->
                @endif

                <!-- Node images -->
                @if ($node->images()->count() > 0)
                    <div class="card image-card">
                        <div class="card-header">{{ trans('public/node.images') }}</div>
                        <div class="images slick-slider">
                            @foreach ($node->images() as $image)
                                <a data-fancybox="gallery" href="{{ $image->url('medium') }}">
                                    <img class="card-image-bottom" src="{{ $image->url('small') }}">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Producer -->
                @if ($node->producerLinks()->count() > 0)
                    <div class="card">
                        <div class="card-header">{{ trans('public/node.producers') }}</div>
                        <div class="card-block">
                            <div class="producers">
                                @foreach ($node->producerLinks() as $producerLink)
                                    <div class="producer">
                                        <a href="{{ $producerLink->getProducer()->permalink()->url }}">{{ $producerLink->getProducer()->name }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div> <!-- Sidebar end -->
        </div>
    </div>

    @include('public.components.header-map', [
        'lat' => $node->location['lat'],
        'lng' => $node->location['lng']
    ])

    <script>
        jQuery(document).ready(function() {
            $('.calendar-nav-item').on('click', function(event) {
                if ($(this).hasClass('disabled')) {
                    return;
                }

                var $current = $('.calendar').find('.month:visible');

                if ($(this).is('#calendar-nav-prev')) {
                    var $requested = $current.prev('.month');
                } else {
                    var $requested = $current.next('.month');
                }

                $current.addClass('hidden');
                $requested.removeClass('hidden');

                updateNavigation($requested);
            });
        });

        function updateNavigation($current) {
            $prev = $current.prev('.month');
            $next = $current.next('.month');

            if ($prev.length === 0) {
                $('#calendar-nav-prev').addClass('disabled');
            } else {
                $('#calendar-nav-prev').removeClass('disabled');
            }

            if ($next.length === 0) {
                $('#calendar-nav-next').addClass('disabled');
            } else {
                $('#calendar-nav-next').removeClass('disabled');
            }
        }

        updateNavigation($('.calendar').find('.month:visible'));
    </script>
@endsection
