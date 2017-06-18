@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <form id="user-edit-form" action="/account/user/update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card">
                    <div class="card-header">{{ trans('admin/user.edit_information') }}</div>
                    <div class="card-block">
                        <div class="form-group">
                            <label class="form-control-label" for="name">
                                {{ trans('admin/user.name') }}
                                @if ($errors->has('name'))
                                    <div class="badge badge-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="{{ trans('admin/user.name') }}" value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="email">
                                {{ trans('admin/user.email') }}
                                @if ($errors->has('email'))
                                    <div class="badge badge-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="{{ trans('admin/user.email') }}" value="{{ $user->email }}">
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-lg-4">
                                <label class="form-control-label" for="address">{{ trans('admin/user.address') }}</label>
                                <input type="text" name="address" class="form-control" id="address" placeholder="{{ trans('admin/user.address') }}" value="{{ $user->address }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label class="form-control-label" for="zip">{{ trans('admin/user.zip') }}</label>
                                <input type="text" name="zip" class="form-control" id="zip" placeholder="{{ trans('admin/user.zip') }}" value="{{ $user->zip }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label class="form-control-label" for="city">{{ trans('admin/user.city') }}</label>
                                <input type="text" name="city" class="form-control" id="city" placeholder="{{ trans('admin/user.city') }}" value="{{ $user->city }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="language">{{ trans('admin/user.site_lang') }}</label>
                            <select name="language" id="language" class="form-control">
                                @foreach (config('app.locales') as $langCode => $language)
                                    <option value="{{ $langCode }}" {{ $langCode === $user->language ? 'selected' : '' }}>{{ $language }}</option>
                                @endforeach
                            </select>
                         </div>
                    </div>
                </div>

                @include('admin.image-card', [
                    'images' => $user->images(),
                    'deleteUrl' => '/account/image/{imageId}/delete',
                    'limit' => 1,
                ])

            </div>
        </div>

        @component('admin.form-control-bar')
            <button type="submit" form="user-edit-form" class="btn btn-success">{{ trans('admin/user.save_user') }}</button>
            <a href="/account/user/delete/confirm" class="btn btn-danger">{{ trans('admin/user.delete_user') }}</a>
        @endcomponent
    </form>
@endsection
