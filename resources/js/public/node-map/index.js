import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { createStore, applyMiddleware, compose } from 'redux';
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';
import createLogger from 'redux-logger';
import reducers from './reducers';
import _ from 'underscore';

const logger = createLogger();
const store = compose(applyMiddleware(thunk, logger))(createStore)(reducers);

import NodeMapContainer from './containers/NodeMapContainer';

ReactDOM.render(
  <Provider store={store}>
    <NodeMapContainer />
  </Provider>,
  document.getElementById('node-map-component-root')
);
