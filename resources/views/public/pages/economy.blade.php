@extends('public.layout-page', [
    'header' => trans('public/economy.header'),
    'subHeader' => trans('public/economy.subheader'),
    'image' => '/images/money-ladder.jpg'
])

@section('title', trans('public/economy.title'))

@section('page-content')
    <script>
        var economyTrans = <?php echo json_encode(trans('public/economy')); ?>;
    </script>

    <div class="container top-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body body-text">
                        {!! trans('public/economy.economy_page') !!}
                    </div>
                    <div class="card-body" id="frontpage-metrics">
                        <metrics></metrics>
                        <div class="text-center">
                            <a class="d-block mt-2" href="/economy/transactions">{{ trans('public/economy.view_all_transactions') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('/js/frontpage.js') }}"></script>
@endsection
