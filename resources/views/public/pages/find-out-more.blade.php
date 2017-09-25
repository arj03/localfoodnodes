@extends('public.layout-page', [
    'header' => trans('public/pages/find-out-more.header_1'),
    'subHeader' => trans('public/pages/find-out-more.subheader_1')
])

@section('title', trans('public/pages/find-out-more.title'))

@section('page-content')
<div class="container top-container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card top-card">
                <div class="card-body body-text">
                    <p>{{ trans('public/pages/find-out-more.paragraph_1_1') }}</p>
                    <p>{{ trans('public/pages/find-out-more.paragraph_1_2') }}</p>
                    <p>{{ trans('public/pages/find-out-more.paragraph_1_3') }}</p>
                    <p>{{ trans('public/pages/find-out-more.paragraph_1_4') }}</p>
                    <p>{{ trans('public/pages/find-out-more.paragraph_1_5') }}</p>
                    <p>{{ trans('public/pages/find-out-more.paragraph_1_6') }}</p>
                    <h3 class="mt-5 mb-3">{{ trans('public/pages/find-out-more.header_3') }}</h3>
                    <p>{{ trans('public/pages/find-out-more.paragraph_3_1') }}</p>
                    <p>{{ trans('public/pages/find-out-more.paragraph_3_2') }}</p>
                    <h3 class="mt-5 mb-3">{{ trans('public/pages/find-out-more.header_4') }}</h3>
                    <p>{{ trans('public/pages/find-out-more.paragraph_4_1') }}</p>
                    <h3 class="mt-5 mb-3">{{ trans('public/pages/find-out-more.header_5') }}</h3>
                    <p>{{ trans('public/pages/find-out-more.paragraph_5_1') }}</p>
                    <p>{{ trans('public/pages/find-out-more.paragraph_5_2') }}</p>
                    <p>{{ trans('public/pages/find-out-more.quip') }}.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
