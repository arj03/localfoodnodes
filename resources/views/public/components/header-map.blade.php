<link rel="stylesheet" href="/css/leaflet/leaflet.min.css" />
<script id="map-js-api" src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
<script>
    var lat = {{ $lat or 'null' }};
    var lng = {{ $lng or 'null' }};
    var zoom = 14;
    if (!lat || !lng) {
        zoom = 2;
    }

    var centerLat = lat || 56;
    var centerLng = lng || 13
    var center = [
        centerLat - 0.004,
        centerLng
    ];

    map = L.map('map', {
        center: center,
        zoom: zoom,
        zoomControl: false,
        scrollWheelZoom: false,
        dragging: false,
        touchZoom: false,
        doubleClickZoom: false,
        boxZoom: false,
        keyboard: false,
        tap: false
    });

    var tileLayer =  L.tileLayer('https://api.mapbox.com/styles/v1/davidajnered/cj1nwwm82002u2ss6j5e9zrt6/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZGF2aWRham5lcmVkIiwiYSI6ImNpenZxcWhoMzAwMGcyd254dGU4YzNkMjQifQ.DJncF9-KJ5RQAozfIwlKDw', {
        attribution: '© <a href="https://www.mapbox.com/map-feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: 'abcd',
        maxZoom: 19,
    });

    tileLayer.addTo(map);

    if (lat && lng) {
        var markerIcon = L.icon({
            iconUrl: '/css/leaflet/images/marker-icon.png',
            iconSize: [32, 32],
            iconAnchor: [32, 32],
            popupAnchor: [-16, -20]
        });
        var marker = L.marker([lat, lng], {icon: markerIcon}).addTo(map);
    }
</script>
