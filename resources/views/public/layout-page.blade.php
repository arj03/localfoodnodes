@extends('public.layout')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-block body-text">
                    @yield('page-content')
                </div>
            </div>
        </div>
    </div>
@endsection
