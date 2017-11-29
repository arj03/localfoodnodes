@extends('public.layout-page', [
    'header' => trans('public/economy.header'),
    'subHeader' => trans('public/economy.subheader'),
    'image' => '/images/money-ladder.jpg'
])

@section('title', trans('public/economy.title'))

@section('page-content')
    <div class="container top-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body body-text">
                        {!! trans('public/economy.economy_page') !!}
                    </div>
                    <div class="card-body" id="frontpage-metrics">
                        <metrics translations="{{ json_encode(trans('public/economy')) }}"></metrics>
                        <div class="text-center">
                            <a class="d-block mt-2" href="/economy/transactions">{{ trans('public/economy.view_all_transactions') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('/js/economy-in-out.js') }}"></script>
@endsection
