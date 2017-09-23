@extends('public.layout-page', [
    'header' => trans('public/pages/membership.header'),
    'subHeader' => trans('public/pages/membership.sub_header')
])

@section('title', trans('public/pages/membership.title'))

@section('page-content')
    <div class="container top-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card top-card">
                    <div class="card-block body-text">
                        {!! trans('public/pages/membership.body') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="metrics row">
                    <div class="metric col">
                        <div class="value">{{ $members }} <i class="fa fa-user"></i></div>
                        <div class="label">{{ trans('public/pages/membership.supporting') }}</div>
                    </div>
                    <div class="metric col">
                        <div class="value">{{ $averageMembership }} SEK</div>
                        <div class="label">{{ trans('public/pages/membership.avg_fee') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-12 col-lg-8">
                @if (Auth::check())
                    @include('account.user.membership-form')
                @else
                    @include('account.user.create-card')
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6 card-deck">
                <div class="card">
                    <div class="card-block body-text">
                        <h2 class="mb-4">{!! trans('public/pages/membership.block_2_header') !!}</h2>
                        {!! trans('public/pages/membership.block_2_content') !!}
                        <img src="/images/money-ladder.jpg">
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 card-deck">
                <div class="card">
                    <div class="card-block body-text">
                        <h2 class="mb-4">{!! trans('public/pages/membership.block_3_header') !!}</h2>
                        {!! trans('public/pages/membership.block_3_content') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
