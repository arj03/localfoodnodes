<div id="nodes"></div> <!-- Anchor -->
<?php $jsonTrans = json_encode(trans('admin/node')); ?>
<div id="producer-node-map-component-root" data-producer-id="{{ $producer->id }}" data-token="{{ csrf_token() }}" data-trans="{{ $jsonTrans }}">
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-body">{{ trans('admin/producer.loading_map') }}</div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/css/leaflet/leaflet.min.css" />
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.0.3/dist/leaflet.markercluster.js"></script>
<script src="{{ URL::asset('/js/producer-node-map.js') }}"></script>
