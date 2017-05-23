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
                <form action="/account/user/insert" method="post">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-header">{{ trans('admin/user.create_account') }}</div>
                        <div class="card-block">
                            <div class="form-group">
                                <label for="name">
                                    {{ trans('admin/user.name') }}
                                    @include('admin.field-error', ['field' => 'name'])
                                </label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="{{ trans('admin/user.name') }}" value="{{ Request::old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="email">
                                    {{ trans('admin/user.email') }}
                                    @include('admin.field-error', ['field' => 'email'])
                                </label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="{{ trans('admin/user.email') }}" value="{{ Request::old('email')}}">
                            </div>
                            <div class="form-group">
                                <label for="password">
                                    {{ trans('admin/user.password') }}
                                    @include('admin.field-error', ['field' => 'password'])
                                </label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="{{ trans('admin/user.password') }}">
                            </div>
                            <p>{{ trans('admin/user.term_link_pre') }} <a href="#" data-toggle="modal" data-target="#terms-modal">{{ trans('admin/user.terms_of_use') }}</a>.</p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">{{ trans('admin/user.create_account') }}</button>
                </form>
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
