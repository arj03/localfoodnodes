import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { connect } from 'react-redux';
import * as actions from '../actions';
import _ from 'underscore';
import uuid from 'uuid';
import $ from 'jquery';

import SearchResultComponent from '../components/SearchResultComponent';

const rootElement = document.getElementById('node-map-component-root');
const trans = JSON.parse(rootElement.dataset.trans);

let map = null;
let addedNodes = [];
let addedReko = [];
let visibleLatLng = [];

class NodeMapContainer extends Component {
    constructor(props) {
        super(props);
        this.debouncedSearch = _.debounce(this.debouncedSearch, 300).bind(this);
        this.getNodePreview = this.getNodePreview.bind(this);
        this.onSelect = this.onSelect.bind(this);

        this.state = {
            searchString: '',
            action: '',
            position: {}
        }
    }

    componentDidMount() {
        const { dispatch } = this.props;
        actions.fetchUserLocation(dispatch);
    }

    componentDidUpdate(prevProps, prevState) {
        const { dispatch } = this.props;

        // User location needed before fetching nodes
        if (this.props.userLocation !== prevProps.userLocation) {
            this.createMap();
        }

        if (this.props.nodes !== prevProps.nodes) {
            this.createMarkers();
        }
    }

    createMap() {
        let that = this;

        map = L.map(this.refs.map, {
            center: [this.props.userLocation.lat, this.props.userLocation.lon],
            zoom: 8,
            scrollWheelZoom: false,
        });

        let tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/davidajnered/cj1nwwm82002u2ss6j5e9zrt6/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZGF2aWRham5lcmVkIiwiYSI6ImNpenZxcWhoMzAwMGcyd254dGU4YzNkMjQifQ.DJncF9-KJ5RQAozfIwlKDw', {
        	attribution: '© <a href="https://www.mapbox.com/map-feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        	subdomains: 'abcd',
        	maxZoom: 18
        });

        tileLayer.addTo(map);

        map.on('zoomend dragend', function(event) {
            let bounds = map.getBounds();
            that.createMarkersOnEvent(bounds);
        });

        this.setState({
            action: 'mapCreated',
            position: {
                lat: this.props.userLocation.lat,
                lng: this.props.userLocation.lon
            }
        });

        // First load
        this.createMarkersOnEvent(map.getBounds());
    }

    createMarkersOnEvent(bounds) {
        const { dispatch } = this.props;
        visibleLatLng = []; // Reset
        actions.fetchContent(dispatch, this.getSanitizedBoundsObject(bounds));
    }

    createMarkers() {
        let that = this;

        let markers = L.markerClusterGroup({
            iconCreateFunction: function(cluster) {
                return L.divIcon({ html: '<div class="leaflet-cluster-marker"></div>' });
            },
            showCoverageOnHover: false,
            spiderLegPolylineOptions: { opacity: 0 }
        });

        let markerIcon = L.icon({
            iconUrl: '/css/leaflet/images/marker-icon.png',
            iconSize: [32, 32],
            iconAnchor: [32, 32],
            popupAnchor: [-16, -20]
        });

        _(this.props.nodes).each((node, index) => {
            if (addedNodes.indexOf(node.id) === -1) {
                let marker = L.marker([node.location.lat, node.location.lng], {icon: markerIcon});
                let popup = document.createElement('div');
                ReactDOM.render(that.getNodePreview(node), popup);
                marker.bindPopup(popup);
                markers.addLayer(marker);
                addedNodes.push(node.id);
            }
        });

        _(this.props.reko).each((reko, index) => {
            if (addedReko.indexOf(reko.id) === -1) {
                let marker = L.marker([reko.location.lat, reko.location.lng], {icon: markerIcon});
                let popup = document.createElement('div');
                ReactDOM.render(that.getRekoPreview(reko), popup);
                marker.bindPopup(popup);
                markers.addLayer(marker);
                addedReko.push(reko.id);
            }
        });

        map.addLayer(markers);

        if (this.state.action === 'search' || this.state.action === 'mapCreated') {
            this.setBounds();
        }
    }

    setBounds() {
        visibleLatLng.push([this.state.position.lat, this.state.position.lng]);

        _(this.props.nodes).each((node) => {
            visibleLatLng.push([node.location.lat, node.location.lng]);
        });

        let bounds = new L.LatLngBounds(visibleLatLng);
        map.fitBounds(bounds);

        this.setState({
            action: null,
            position: {}
        });
    }

    getSanitizedBoundsObject(bounds) {
        // Check what type of object is passed
        // Map bounds or place bounding box
        if (bounds._southWest) {
            return {
                bounds: {
                    sw: {lat: bounds._southWest.lat, lng: bounds._southWest.lng},
                    ne: {lat: bounds._northEast.lat, lng: bounds._northEast.lng}
                }
            }
        } else if (_.isArray(bounds) && bounds.length === 4) {
            return {
                bounds: {
                    sw: {lat: bounds[0], lng: bounds[2]},
                    ne: {lat: bounds[1], lng: bounds[3]}
                }
            }
        }
    }

    getNodePreview(node) {
        return (
            <div className='map-preview'>
                <a href={node.permalink.url} className='header'>
                    <h3>{node.name}</h3>
                    <div className='meta'>
                        <div><i className='fa fa-home' />{node.address} {node.zip} {node.city}</div>
                        <div><i className='fa fa-clock-o' />{trans[node.delivery_weekday]} {node.delivery_time}</div>
                    </div>
                </a>
                <div className='body-text'>
                    <a href={node.permalink.url} className='btn btn-success'>{trans.visit_node} <i className='fa fa-caret-right' style={{float: 'right'}}/></a>
                </div>
            </div>
        );
    }

    getRekoPreview(reko) {
        return (
            <div className='map-preview'>
            <a href={reko.link} target='_blank' className='header'>
                <h3>{reko.name}</h3>
            </a>
            <div className='body-text'>
                <a href={reko.link} target='_blank' className='btn btn-success'>{trans.visit_node} <i className='fa fa-caret-right' style={{float: 'right'}}/></a>
            </div>
            </div>
        );
    }

    getMapLoader() {
        return (
            <div className='map-loader'>
                <img src="/images/loader.svg" />
            </div>
        );
    }

    search(event) {
        event.persist();
        this.setState({searchString: event.target.value})
        this.debouncedSearch(event);
    }

    onSelect(place) {
        const { dispatch } = this.props;

        visibleLatLng = []; // Reset
        map.panTo(new L.LatLng(place.lat, place.lon));

        this.setState({
            searchString: place.display_name,
            action: 'search',
            position: {
                lat: place.lat,
                lng: place.lon
            }
        });
        actions.fetchContent(dispatch, this.getSanitizedBoundsObject(place.boundingbox));
    }

    debouncedSearch(event) {
        const { dispatch } = this.props;
        actions.searchGeo(dispatch, event.target.value);
    }

    render() {
        let loader = (this.props.fetching || !this.props.userLocation) ? this.getMapLoader() : null;

        let searchResults = null;
        if (this.props.searchResults) {
            searchResults = <SearchResultComponent data={this.props.searchResults} onSelect={this.onSelect}/>;
        }

        return (
            <div className='map container-fluid'>
                <div className='body-text'>
                    <h2>{trans.go_local}</h2>
                    <div className='row no-gutters map-search'>
                        <div className='col-12 col-lg-6 offset-lg-3'>
                            <div className='input-group'>
                                <span className="input-group-addon"><i className="fa fa-search" /></span>
                                <input value={this.state.searchString} type="text" className="form-control" placeholder={trans.find_node_near_you} onChange={this.search.bind(this)} />
                            </div>
                            {searchResults}
                        </div>
                    </div>
                    <div className='map-holder' ref='map'>{loader}</div>
                </div>
            </div>
        );
    }
}

function mapStateToProps(state) {
    return state;
}

export default connect(mapStateToProps)(NodeMapContainer);
