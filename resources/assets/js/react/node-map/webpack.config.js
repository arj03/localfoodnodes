process.env.NODE_ENV = 'production';

var path = require('path');
var webpack = require('webpack')

module.exports = {
  entry: ['babel-polyfill', './index'],
  output: {
      path: __dirname,
      // publicPath: '../../../../../public/',
      filename: '../dist/node-map.js'
  },
  plugins: [
    new webpack.DefinePlugin({
      'process.env': {
        'NODE_ENV': JSON.stringify('production')
      }
    }),
    new webpack.optimize.DedupePlugin(),
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: false,
      compress: {
        screw_ie8: true,
        warnings: false
      }
    }),
    new webpack.optimize.AggressiveMergingPlugin()
  ],
  devtool: process.env.NODE_ENV === 'production' ? false : "eval",
  module: {
    loaders: [
      {
        test: /\.js$/,
        loader: 'babel',
        exclude: /node_modules/,
        query: {
          presets:['react', 'es2015']
        }
      }
    ]
  }
};
