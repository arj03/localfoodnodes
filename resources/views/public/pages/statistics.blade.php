@extends('public.layout-page', [
    'header' => trans('public/pages/statistics.header'),
    'subHeader' => trans('public/pages/statistics.subheader'),
    'image' => '/images/money-ladder.jpg'
])

@section('title', trans('public/pages/statistics.title'))

@section('page-content')
    <div class="container top-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body body-text">
                        {!! trans('public/pages/statistics.info') !!}
                        <div id="statistics">
                            <orders-by-node-chart></orders-by-node-chart>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('/js/statistics.js') }}"></script>
@endsection
