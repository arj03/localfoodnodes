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
                    @if ($user->membershipPayments()->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('admin/user.date') }}</th>
                                    <th>{{ trans('admin/user.fee') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->membershipPayments() as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at }}</td>
                                        <td>{{ $payment->amount }} SEK</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        {{ trans('admin/user.membership_no_history') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
