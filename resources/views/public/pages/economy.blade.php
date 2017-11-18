@extends('public.layout-page', [
    'header' => 'Viktor...',
    'subHeader' => 'Jobba!',
    'image' => '/images/money-ladder.jpg'
])

@section('title', trans('public/pages/economy.title'))

@section('page-content')
    <div class="container top-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body body-text">
                        Viktors ekonomitext här
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
