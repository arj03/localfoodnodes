import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { createStore, applyMiddleware, compose } from 'redux';
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';
import createLogger from 'redux-logger';
import reducers from './reducers';

const logger = createLogger();
const store = compose(applyMiddleware(thunk, logger))(createStore)(reducers);

import ProducerNodeMapContainer from './containers/ProducerNodeMapContainer';

ReactDOM.render(
  <Provider store={store}>
    <ProducerNodeMapContainer />
  </Provider>,
  document.getElementById('producer-node-map-component-root')
);
