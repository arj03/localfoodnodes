@extends('public.layout')

@section('content')
    <div class="container-fluid header">
        <div class="row">
            <div class="col-12">
                <h2 class="bold">{{ $header }}</h2>
                <h3 class="mb-5">{{ $subHeader }}</h3>
            </div>
        </div>
    </div>

    @yield('page-content')
@endsection
