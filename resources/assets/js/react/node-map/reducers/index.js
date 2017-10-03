import constants from '../constants';
import _ from 'underscore';

function nodeMap(state, action) {
    switch (action.type) {
        case constants.REQUEST_CONTENT:
            return Object.assign({}, state, {
                fetching: true
            });
            break;

        case constants.RECEIVE_CONTENT:
            return Object.assign({}, state, {
                fetching: false,
                nodes: action.data.nodes,
                reko: action.data.reko,
                searchResults: null
            });
            break;

        case constants.REQUEST_SEARCH_GEO:
            return Object.assign({}, state, {
                fetching: true
            });
            break;

        case constants.RECEIVE_SEARCH_GEO:
            return Object.assign({}, state, {
                fetching: false,
                searchResults: action.data,
            });
            break;

        case constants.RECEIVE_USER_LOCATION:
            return Object.assign({}, state, {
                fetching: false,
                userLocation: action.data,
            });
            break;

        default:
            return Object.assign({}, state, {});
            break;
    }
}

export default nodeMap;
