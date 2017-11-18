@extends('public.layout-page', [
    'header' => $header,
    'image' => '/images/shutterstock_326785574.jpg'
])

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('page-content')
    <div class="container top-container">
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-8 mt-5">
                <div class="card">
                    <div class="card-body body-text">
                        @if ($content)
                            {!! $content !!}
                        @endif

                        <div class="mt-5">
                            @include('public.user.create-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="terms-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('admin/user.terms_of_use') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body body-text">
                    <p>{!! trans('admin/terms.user') !!}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
