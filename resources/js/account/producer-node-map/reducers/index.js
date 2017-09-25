import constants from '../constants';
import _ from 'underscore';

function producerNodeMap(state, action) {
    switch (action.type) {
        case constants.REQUEST_NODES:
            return Object.assign({}, state, {
                fetching: true
            });
        break;

    case constants.RECEIVE_NODES:
        return Object.assign({}, state, {
            fetching: false,
            nodes: action.data.nodes,
            selectedNodes: action.data.selectedNodes,
            location: {
                lat: parseFloat(action.data.location.lat) || 56.002490,
                lng: parseFloat(action.data.location.lng) || 13.293257,
            }
        });
        break;

    case constants.NODE_ADDED:
    case constants.NODE_REMOVED:
        return Object.assign({}, state, {
            fetching: false,
            selectedNodes: action.data.selectedNodes
        });
        break;

    default:
        return Object.assign({}, state, {});
        break;
    }
}

export default producerNodeMap;
