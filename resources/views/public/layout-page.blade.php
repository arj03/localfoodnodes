@extends('public.layout')

@section('content')

    @if (isset($image))
        <style>
            body.page .page .header {
                background-image: url({{ $image }});
            }
        </style>
    @endif

    <div class="container-fluid header">
        <div class="header-block">
            <h1 class="bold">{{ $header }}</h1>
            <h2>{{ $subHeader or '' }}</h2>
        </div>
    </div>

    @yield('page-content')
@endsection
