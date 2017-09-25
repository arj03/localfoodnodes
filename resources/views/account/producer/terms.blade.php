<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">{{ trans('admin/producer.producer_terms') }}</div>
            <div class="card-body">
                {!! trans('admin/terms.producer') !!}
            </div>
            <div class="card-footer">
                <a class="btn btn-success" href="/account/user">{{ trans('admin/producer.cancel') }}</a>
                <a class="btn btn-success pull-right" href="/account/producer/create?terms=approved">{{ trans('admin/producer.agree') }}</a>
            </div>
        </div>
    </div>

    @include('account.producer.hdiw')
</div>
