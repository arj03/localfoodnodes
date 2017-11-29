@extends('public.layout')

@section('title', trans('public/index.title'))

@section('content')
    <div class="container-fluid frontpage-section frontpage-header">
        <div class="container">
            <div class="col-12 header-block">
                <h1 class="bold">
                    {{ trans('public/index.header') }}
                    <div class="sub-header">{!! trans('public/index.subheader') !!}</div>
                </h1>
            </div>
        </div>
    </div>

    <div class="container-fluid frontpage-section bg-white what-is-lfn">
        <div class="container d-flex justify-content-center text-center">
            <div class="col-12 pt-5 pb-5">
                <h2 class="thin">{{ trans('public/index.what_is_lfn') }}</h2>
                <p class="pt-5 pb-5 ingress">{{ trans('public/index.what_is_lfn_info') }}</p>
                <img src="/images/infographic.jpg">
                <div>
                    <a href="/find-out-more" class="btn btn-success">{{ trans('public/index.find_out_more') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="map-wrapper">
        <div class="node-map" id="node-map-component-root" data-ip="{{ Request::ip() }}" data-user-location="{{ json_encode($user->location) }}" data-trans="{{ json_encode(trans('public/index')) }}"></div>

        <div class="metrics">
            <div class="metric">
                <div class="value">{{ $metrics['userCount'] }}</div>
                <div class="label">{{ trans('public/index.locals') }}</div>
            </div>
            <div class="metric">
                <div class="value">{{ $metrics['nodeCount'] }}</div>
                <div class="label">{{ trans('public/index.local_nodes') }}</div>
            </div>
            <div class="metric">
                <div class="value">{{ $metrics['producerCount'] }}</div>
                <div class="label">{{ trans('public/index.local_producers') }}</div>
            </div>
        </div>
    </div>

    <div class="container frontpage-section mt-5">
        <h2 class="thin mb-5">{{ trans('public/index.co_create') }}</h2>
        {!! trans('public/index.co_create_info') !!}

        <div class="card-deck mt-5">
            <div class="card mb-5">
                <div class="card-img-wrapper">
                    <img class="card-img-top" src="/images/shutterstock_436974091.jpg" alt="Card image cap">
                    <h3 class="card-img-header bold">{{ trans('public/index.create_user_header') }}</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ trans('public/index.create_user_info') }}</p>
                </div>
                <div class="card-footer">
                    <a class="btn btn-success" href="/account/user/create/user">{{ trans('public/index.create_user') }}</a>
                </div>
            </div>

            <div class="card mb-5">
                <div class="card-img-wrapper">
                    <img class="card-img-top" src="/images/shutterstock_326785574.jpg" alt="Card image cap">
                    <h3 class="card-img-header bold">{{ trans('public/index.create_node_header') }}</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ trans('public/index.create_node_info') }}</p>
                </div>
                <div class="card-footer">
                    <a class="btn btn-success" href="/account/user/create/node">{{ trans('public/index.create_node') }}</a>
                </div>
            </div>

            <div class="card mb-5">
                <div class="card-img-wrapper">
                    <img class="card-img-top" src="/images/shutterstock_271622087.jpg" alt="Card image cap">
                    <h3 class="card-img-header bold">{{ trans('public/index.create_producer_header') }}</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ trans('public/index.create_producer_info') }}</p>
                </div>
                <div class="card-footer">
                    <a class="btn btn-success" href="/account/user/create/producer">{{ trans('public/index.create_producer') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid frontpage-section bg-white pt-5 pb-5">
        <div class="container text-center">
            <h2 class="thin mb-5">
                {{ trans('public/pages/statistics.header') }}
                <div class="sub-header">{{ trans('public/pages/statistics.subheader') }}</div>
            </h2>

            <div id="economy-circulation">
                <economy-circulation translations="{{ json_encode(trans('public/economy')) }}"></economy-circulation>
            </div>
            <a href="/statistics">{{ trans('public/pages/statistics.more_link') }}</a>
        </div>
    </div>

    <div class="container-fluid frontpage-section membership pt-5 pb-5">
        <div class="container">
            <div class="col-12">
                <h2 class="bold mb-5">
                    {{ trans('public/index.co_fund_heading') }}
                    <div class="sub-header">{{ trans('public/index.co_fund_subheading') }}</div>
                </h2>

                <div class="d-flex justify-content-center">
                    <div class="col-12 col-md-8">
                        <p>{{ trans('public/index.co_fund_paragraph') }}</p>

                        <div class="metrics row mt-5">
                            <div class="metric col-12 col-sm-6">
                                <i class="fa fa-user"></i>
                                <div class="metric-inner">
                                    <div class="value">{{ $members }}</div>
                                    <div class="label">{{ trans('public/pages/membership.supporting') }}</div>
                                </div>
                            </div>
                            <div class="metric col-12 col-sm-6">
                                <i class="fa fa-money"></i>
                                <div class="metric-inner">
                                    <div class="value">{{ $averageMembership }} SEK</div>
                                    <div class="label">{{ trans('public/pages/membership.avg_fee') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="/membership" class="btn mt-5">{{ trans('public/index.co_fund_read_more') }}</a>
            </div>
        </div>
    </div>

    <div id="economy-in-out" class="frontpage-section">
        <div class="container pt-5 pb-5">
            <h2 class="thin mb-5">{{ trans('public/economy.economy') }}</h2>
            <div class="row mb-5">
                {!! trans('public/economy.economy_info') !!}
            </div>
            <metrics translations="{{ json_encode(trans('public/economy')) }}"></metrics>
            <div class="text-center">
                <a class="btn btn-success mt-5" href="/economy">{{ trans('public/economy.read_more_economy') }}</a>
                <a class="d-block mt-2" href="/economy/transactions">{{ trans('public/economy.view_all_transactions') }}</a>
            </div>
        </div>
    </div>

    <div class="container frontpage-section find-out-more mt-5" id="find-out-more">
        <img class="logo" src="/images/nav-logo-dark.png">
        <h2 class="thin mt-5 mb-5">{{ trans('public/pages/find-out-more.subheader_1') }}</h2>

        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <p>{{ trans('public/pages/find-out-more.paragraph_1_1') }}</p>
                <p>{{ trans('public/pages/find-out-more.paragraph_1_2') }}</p>
            </div>
            <div class="col-12 col-md-6">
                <p>{{ trans('public/pages/find-out-more.paragraph_1_3') }}</p>
                <p>{{ trans('public/pages/find-out-more.paragraph_1_4') }}</p>
                <p>{{ trans('public/pages/find-out-more.paragraph_1_5') }}</p>
            </div>
        </div>
    </div>

    <div class="container frontpage-section">
        <h2 class="thin mt-5 mb-5">#{{ trans('public/pages/find-out-more.quip') }}</h2>
    </div>

    <link rel="stylesheet" href="/css/leaflet/leaflet.min.css" />
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.0.3/dist/leaflet.markercluster.js"></script>
    <script src="{{ mix('/js/node-map.js') }}"></script>
    <script src="{{ mix('/js/economy-in-out.js') }}"></script>
    <script src="{{ mix('/js/economy-circulation.js') }}"></script>
@endsection
