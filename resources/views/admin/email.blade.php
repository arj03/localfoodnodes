@extends('admin.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="nav">
                    <a class="nav-link" href="/admin/email/user/activation">User activation</a>
                    <a class="nav-link" href="/admin/email/user/reset-password">Password reset</a>
                    <a class="nav-link" href="/admin/email/order/producer">Order producer</a>
                    <a class="nav-link" href="/admin/email/order/customer">Order consumer</a>
                </div>
            </div>
        </div>
    </div>
@endsection
