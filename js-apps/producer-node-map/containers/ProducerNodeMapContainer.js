import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { connect } from 'react-redux';
import * as actions from '../actions';
import _ from 'underscore';
import uuid from 'uuid';

const rootElement = 'producer-node-map-component-root';
const trans = JSON.parse(document.getElementById(rootElement).dataset.trans);

let map;

class ProducerNodeMapContainer extends Component {
    componentDidMount() {
        const { dispatch } = this.props;
        actions.fetchNodes(dispatch);
    }

    componentDidUpdate(prevProps, prevState) {
        if (prevProps.nodes !== this.props.nodes) {
            this.createMap();
        }

        if (prevProps.selectedNodes !== this.props.selectedNodes) {
            this.loadMap();
        }
    }

    createMap() {
        map = L.map(this.refs.map, {
            center: [
                this.props.location.lat,
                this.props.location.lng
            ],
            zoom: 8,
            scrollWheelZoom: false,
        });

        let tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/davidajnered/cj1nwwm82002u2ss6j5e9zrt6/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZGF2aWRham5lcmVkIiwiYSI6ImNpenZxcWhoMzAwMGcyd254dGU4YzNkMjQifQ.DJncF9-KJ5RQAozfIwlKDw', {
            attribution: '© <a href="https://www.mapbox.com/map-feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        	subdomains: 'abcd',
        	maxZoom: 19
        });

        tileLayer.addTo(map);

        this.loadMap();
    }

    loadMap() {
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

        markers.clearLayers()
        _(this.props.nodes).each((node) => {
            let popup = document.createElement('div');
            ReactDOM.render(that.getNodePreview(node), popup);

            let marker = L.marker([node.location.lat, node.location.lng], {icon: markerIcon});
            marker.bindPopup(popup);
            markers.addLayer(marker);
        });

        map.addLayer(markers);
    }

    addNode(node) {
        const { dispatch } = this.props;
        actions.addNode(dispatch, node);
        map.closePopup();
    }

    removeNode(node) {
        const { dispatch } = this.props;
        actions.removeNode(dispatch, node);
        map.closePopup();
    }

    getNodePreview(node) {
        const { selectedNodes } = this.props;

        let onClickFunction = this.addNode.bind(this, node);
        let label = trans.add_node;

        if (_(selectedNodes).find({id: node.id})) {
            onClickFunction = this.removeNode.bind(this, node);
            label = trans.remove_node;
        }

        return (
            <div className='map-preview'>
                <div className='header'>
                    <h3>{node.name}</h3>
                </div>
                <div className='body-text'>
                    <div className='btn btn-success' data-node-id={node.id} onClick={onClickFunction}>{label}</div>
                </div>
            </div>
        );
    }

    getSelectedNodesTable() {
        const { selectedNodes } = this.props;

        let items;
        if (selectedNodes && selectedNodes.length > 0) {
            items = _(selectedNodes).map((node) => {
                return (
                    <tr key={uuid.v4()}>
                        <td>{node.name}</td>
                        <td>
                            <div className='dropdown dropdown-action-component'>
                                <button type='button' className='btn dropdown-toggle' data-toggle='dropdown'>
                                    <i className='fa fa-gear'></i>
                                </button>
                                <ul className='dropdown-menu dropdown-menu-right'>
                                    <li><a className='dropdown-item' onClick={this.removeNode.bind(this, node)}>{trans.remove_node}</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                );
            });
        } else {
            return <span>{trans.no_nodes_selected}</span>;
        }

        return (
            <table className='table table-condensed table-hover'>
                <thead>
                <tr>
                    <th>{trans.delivery_nodes}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    {items}
                </tbody>
            </table>
        );
    }

    render() {
        if (this.props.fetching) {
            return (
                <div className='row'>
                    <div className='col-12 col-lg-8'>
                        <div className='card'>
                            <div className='card-block'>
                                {trans.loading_map}
                            </div>
                        </div>
                    </div>
                </div>
            );
        }

        let selectedNodesTable;
        if (this.props.selectedNodes) {
            selectedNodesTable = this.getSelectedNodesTable();
        }

        let producerId = document.getElementById(rootElement).dataset.producerId;
        let producerUrl = '/account/producer/' + producerId;

        return (
            <div className='row map'>
                <div className='col-12'>
                    <div className='card'>
                    <div className='card-header'>{trans.nearby_nodes}</div>
                        <div className='card-block' style={{height: 300}} ref='map'>{trans.loading_map}</div>
                        <div className='card-block'>
                            {selectedNodesTable}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

function mapStateToProps(state) {
    return state;
}

export default connect(mapStateToProps)(ProducerNodeMapContainer);
