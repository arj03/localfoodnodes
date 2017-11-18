@extends('public.layout')

@section('content')

    @if (isset($image))
        <style>
            .page-header {
                background-image: url({{ $image }});
            }
        </style>
    @endif

    <div class="container-fluid page-header">
        <div class="container">
            <div class="col-12 header-block">
                <h1 class="bold">
                    {{ $header }}
                    @if (isset($subHeader))
                        <div class="sub-header">{{ $subHeader }}</div>
                    @endif
                </h1>
            </div>
        </div>
    </div>

    @yield('page-content')
@endsection
