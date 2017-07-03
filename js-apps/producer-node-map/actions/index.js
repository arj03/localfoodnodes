import _ from 'underscore';
import promise from 'promise';
import superagentPromise from 'superagent-promise';
import superagent from 'superagent';
import constants from '../constants';

const request = superagentPromise(superagent, promise);
const rootElement = 'producer-node-map-component-root';
const trans = JSON.parse(document.getElementById(rootElement).dataset.trans);

export function fetchNodes(dispatch) {
    dispatch(requestNodes());

    let producerId = document.getElementById(rootElement).dataset.producerId;

    return request
    .get('/account/producer/' + producerId + '/map/nodes')
    .end((error, response) => {
        if (error) console.error(error);

        return response;
    })
    .then((response) => JSON.parse(response.text))
    .then((nodes) => dispatch(receiveNodes(nodes)))
    .catch((err) => console.error('error', err));
}

export function requestNodes() {
    return {
        type: constants.REQUEST_NODES,
        fetching: true
    };
}

export function receiveNodes(data) {
    return {
        type: constants.RECEIVE_NODES,
        data: data,
        fetching: false,
        received: Date.now()
    };
}

export function addNode(dispatch, node) {
    let token = document.getElementById(rootElement).dataset.token;
    let producerId = document.getElementById(rootElement).dataset.producerId;

    return request
    .post('/account/producer/' + producerId + '/map/node/add')
    .set('X-CSRF-TOKEN', token)
    .send({nodeId: node.id})
    .end((error, response) => {
        if (error) console.error(error);
        return response;
    })
    .then((response) => JSON.parse(response.text))
    .then((data) => dispatch(nodeAdded(data)));
}

export function nodeAdded(data) {
    var event = new CustomEvent('notification', { 'detail': trans['node_added'] });
    document.dispatchEvent(event);

    return {
        type: constants.NODE_ADDED,
        data: data,
        fetching: false,
        received: Date.now()
    };
}

export function removeNode(dispatch, node) {
    let token = document.getElementById(rootElement).dataset.token;
    let producerId = document.getElementById(rootElement).dataset.producerId;

    return request
    .post('/account/producer/' + producerId +'/map/node/remove')
    .set('X-CSRF-TOKEN', token)
    .send({nodeId: node.id})
    .end((error, response) => {
        if (error) console.error(error);
        return response;
    })
    .then((response) => JSON.parse(response.text))
    .then((data) => dispatch(nodeRemoved(data)))
}

export function nodeRemoved(data) {
    var event = new CustomEvent('notification', { 'detail': trans['node_removed'] });
    document.dispatchEvent(event);

    return {
        type: constants.NODE_REMOVED,
        data: data,
        fetching: false,
        received: Date.now()
    };
}

// CustomEvent polyfill for IE
(function () {
  if ( typeof window.CustomEvent === "function" ) return false;

  function CustomEvent ( event, params ) {
    params = params || { bubbles: false, cancelable: false, detail: undefined };
    var evt = document.createEvent( 'CustomEvent' );
    evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
    return evt;
   }

  CustomEvent.prototype = window.Event.prototype;

  window.CustomEvent = CustomEvent;
})();
