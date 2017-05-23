@extends('public.layout-page')

@section('title', trans('public/pages/find-out-more.title'))

@section('page-content')
    <h1 class="mb-3">
        {{ trans('public/pages/find-out-more.header_1') }}
        <span>{{ trans('public/pages/find-out-more.subheader_1') }}</span>
    </h1>
    <p>{{ trans('public/pages/find-out-more.paragraph_1_1') }}</p>
    <p>{{ trans('public/pages/find-out-more.paragraph_1_2') }}</p>
    <p>{{ trans('public/pages/find-out-more.paragraph_1_3') }}</p>
    <p>{{ trans('public/pages/find-out-more.paragraph_1_4') }}</p>
    <p>{{ trans('public/pages/find-out-more.paragraph_1_5') }}</p>
    <p>{{ trans('public/pages/find-out-more.paragraph_1_6') }}</p>
    <h2 class="mt-5 mb-5">{{ trans('public/pages/find-out-more.header_2') }}</h2>
    <h3 class="mt-5 mb-3">{{ trans('public/pages/find-out-more.header_3') }}</h3>
    <p>{{ trans('public/pages/find-out-more.paragraph_3_1') }}</p>
    <p>{{ trans('public/pages/find-out-more.paragraph_3_2') }}</p>
    <h3 class="mt-5 mb-3">{{ trans('public/pages/find-out-more.header_4') }}</h3>
    <p>{{ trans('public/pages/find-out-more.paragraph_4_1') }}</p>
    <h3 class="mt-5 mb-3">{{ trans('public/pages/find-out-more.header_5') }}</h3>
    <p>{{ trans('public/pages/find-out-more.paragraph_5_1') }}</p>
    <p>{{ trans('public/pages/find-out-more.paragraph_5_1') }}</p>
    <p>{{ trans('public/pages/find-out-more.quip') }}.</p>
@endsection
