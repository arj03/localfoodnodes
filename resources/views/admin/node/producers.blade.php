@extends('admin.layout')

@section('title', join(array_keys($breadcrumbs), ' - '))

@section('content')
    @include('admin.page-header')

    <div class="card">
        <div class="card-header">{{ trans('admin/node.producers') }}</div>
        <div class="card-block">
            @if ($node->producerLinks()->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('admin/node.name') }}</th>
                                <th>{{ trans('admin/node.email') }}</th>
                                <th>{{ trans('admin/node.address') }}</th>
                                <th>{{ trans('admin/node.zip') }}</th>
                                <th>{{ trans('admin/node.city') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($node->producerLinks() as $producerLink)
                                <tr>
                                    <td>{{ $producerLink->getProducer()->name }}</td>
                                    <td><a href="mailto:{{ $producerLink->getProducer()->email }}">{{ $producerLink->getProducer()->email }}</a></td>
                                    <td>{{ $producerLink->getProducer()->address }}</td>
                                    <td>{{ $producerLink->getProducer()->zip }}</td>
                                    <td>{{ $producerLink->getProducer()->city }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                {{ trans('admin/node.no_producers') }}
            @endif
        </div>
    </div>
@endsection
