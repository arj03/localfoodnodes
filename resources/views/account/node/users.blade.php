@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <div class="card">
        <div class="card-header">
            {{ trans('admin/node.users') }}
            <a href="mailto:{{ $userEmails->implode(',') }}" target="_blank"><i class="fa fa-envelope"></i></a>
        </div>
        <div class="card-body">
            @if ($node->userLinks()->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('admin/node.name') }}</th>
                                <th>{{ trans('admin/node.address') }}</th>
                                <th>{{ trans('admin/node.zip') }}</th>
                                <th>{{ trans('admin/node.city') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($node->userLinks() as $userLink)
                                <tr>
                                    <td>{{ $userLink->getUser()->name }}</td>
                                    <td>{{ $userLink->getUser()->address }}</td>
                                    <td>{{ $userLink->getUser()->zip }}</td>
                                    <td>{{ $userLink->getUser()->city }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{ trans('admin/node.no_users') }}
            @endif
        </div>
    </div>
@endsection
