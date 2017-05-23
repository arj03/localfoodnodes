process.env.NODE_ENV = 'production';

var path = require('path');
var webpack = require('webpack')

module.exports = {
  devtool: 'inline-eval-cheap-source-map',
  entry: './index',
  output: {
      path: __dirname,
      publicPath: '../../public/',
      filename: '../../public/js-apps/node-map.js'
  },
  plugins: [
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: false,
      compress: {
        screw_ie8: true,
        warnings: false
      }
    })
  ],
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
