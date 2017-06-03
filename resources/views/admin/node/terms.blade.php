<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">{{ trans('admin/node.node_terms') }}</div>
            <div class="card-block">
                {!! trans('admin/terms.node') !!}
            </div>
            <div class="card-footer">
                <a class="btn btn-success" href="/account/user">{{ trans('admin/node.cancel') }}</a>
                <a class="btn btn-success pull-right" href="/account/node/create?terms=approved">{{ trans('admin/node.agree') }}</a>
            </div>
        </div>
    </div>

    @include('admin.node.hdiw')
</div>
