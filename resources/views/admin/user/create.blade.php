@extends('public.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    <div class="container">

        @if (Request::route('type'))
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-xl-8">
                    <div class="card">
                        <div class="card-block body-text">
                            @if (Request::route('type') === 'user')
                                {!! trans('public/create-account.user') !!}
                            @elseif (Request::route('type') === 'node')
                                {!! trans('public/create-account.node') !!}
                            @elseif (Request::route('type') === 'producer')
                                {!! trans('public/create-account.producer') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center mb-5">
            <div class="col-12 col-xl-8 mt-5">
                @include('admin.user.create-card')
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
