@extends('account.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('account.page-header')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ trans('admin/user.events') }}</div>
                <div class="card-block">
                    @if ($user->eventLinks()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('admin/user.event-name') }}</th>
                                    </tr>
                                </thead>
                                @foreach ($user->eventLinks() as $eventLink)
                                    <tr>
                                        <td>{{ $eventLink->getEvent()->name }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        {{ trans('admin/user.no_events') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
