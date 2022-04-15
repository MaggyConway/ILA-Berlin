/* eslint-disable */

const path = require('path')
const webpack = require('webpack')
const merge = require('webpack-merge')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

const base = require('./webpack.base.conf')


const configDev = {
  mode: 'development',
  watch: true,
  devtool: 'inline-source-map',
  output: {
    filename: 'js/[name].js',
    path: path.resolve(__dirname, '../dist'),
    publicPath: '../'
  },
  optimization: {
    runtimeChunk: 'single'
  },
  module: {
    rules: [
      {
        test: /(\.scss|\.css)$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: true
            }
          },
          {
            loader: 'resolve-url-loader'
          },
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: true
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true
            }
          }
        ]
      },
      {
        test: /\.(png|jpe?g|gif|svg|webp)$/i,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: 'images/[name].[ext]'
            }
          }
        ]
      }  
    ]
  },
  plugins: [

    new MiniCssExtractPlugin({
      filename: "css/[name].css",
      chunkFilename: "css/[name].css"
    }),

    new webpack.DefinePlugin({
      PRODUCTION: JSON.stringify(false)
    }),

    new BrowserSyncPlugin({
      host: 'localhost',
      port: 8081,
      proxy: 'https://ila.lndo.site/', // instead of 'http://drupal.lndo.site/' add yours domain name
      reloadDelay: 50,
      injectChanges: false,
      reloadDebounce: 500,
      reloadOnRestart: true,
    })
  ]
}

module.exports = merge(base, configDev)
