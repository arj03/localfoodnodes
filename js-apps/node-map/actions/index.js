import _ from 'underscore';
import promise from 'promise';
import superagentPromise from 'superagent-promise';
import superagent from 'superagent';
import constants from '../constants';

const request = superagentPromise(superagent, promise);
const rootElement = 'node-map-component-root';

export function fetchContent(dispatch, params) {
    dispatch(requestContent());

    let query;
    if (params.location) {
        query = {location: params.location};
    } else if (params.bounds) {
        query = {bounds: params.bounds};
    }

    return request
    .get('/map/content')
    .query(query)
    .end((error, response) => {
        if (error) console.error(error);

        return response;
    })
    .then((response) => response.body)
    .then((data) => dispatch(receiveContent(data)))
    .catch((err) => console.error('error', err));
}

export function requestContent() {
    return {
        type: constants.REQUEST_CONTENT,
        fetching: true
    };
}

export function receiveContent(data) {
    return {
        type: constants.RECEIVE_CONTENT,
        data: data,
        fetching: false,
        received: Date.now()
    };
}

export function searchGeo(dispatch, searchString) {
    dispatch(requestSearchGeo());

    return request
    .get('http://nominatim.openstreetmap.org/search')
    .query({
        q: searchString,
        format: 'json',
        addressdetails: 1,
        featuretype: 'settlement'
    })
    .end((error, response) => {
        if (error) console.error(error);

        return response;
    })
    .then((response) => response.body)
    .then((data) => dispatch(receiveSearchGeo(data)))
    .catch((err) => console.error('error', err));
}

export function requestSearchGeo() {
    return {
        type: constants.REQUEST_SEARCH_GEO,
        fetching: true
    };
}

export function receiveSearchGeo(data) {
    return {
        type: constants.RECEIVE_SEARCH_GEO,
        data: data,
        fetching: false,
        received: Date.now()
    };
}

export function fetchUserLocation(dispatch) {
    // If user position is passed through root element
    let userLocation = JSON.parse(document.getElementById(rootElement).dataset.userLocation);
    if (userLocation) {
        return dispatch(receiveUserLocation({
            latitude: userLocation.lat,
            longitude: userLocation.lng
        }));
    } else {
        // Try to find position through IP address
        getUserIp()
        .then((ip) => {
            return request
            .get('https://freegeoip.net/json/?q=' + ip)
            .end((error, response) => {
                if (error) console.error(error);
            })
            .then((response) => response.body)
        })
        .then((data) => dispatch(receiveUserLocation(data)))
        .catch((err) => console.error('error', err));
    }
}

function getUserIp() {
    let ip = document.getElementById(rootElement).dataset.ip;

    if (ip === '::1') {
        // On localhost, use external service to get correct IP
        return new promise((resolve, reject) => {
            return request
            .get('http://ip.localfoodnodes.org')
            .end((error, response) => {
                if (error) console.error(error);
            })
            .then((response) => response.body)
            .then((data) => resolve(data.ip))
            .catch((e) => reject(e));
        })
    } else {
        return promise.resolve(ip);
    }

}

export function receiveUserLocation(data) {
    // Fallback if ip api is down
    if (!data) {
        data = {lat: 65, lon: 22};
    }

    return {
        type: constants.RECEIVE_USER_LOCATION,
        data: {
            lat: data.latitude,
            lon: data.longitude
        },
        fetching: false,
        received: Date.now()
    };
}
