@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="row">
        <div class="col-12 col-xl-8">
            @include('admin.user.membership-form')

            <div class="card">
                <div class="card-header">History</div>
                <div class="card-block">
                    <ul>
                        @foreach ($user->membershipPayments() as $payment)
                        <li>{{ $payment->created_at }} {{ $payment->amount }} SEK</li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection
