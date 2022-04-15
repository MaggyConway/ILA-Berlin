/* eslint-disable */

const path = require('path')
const webpack = require('webpack')
const merge = require('webpack-merge')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const TerserPlugin = require('terser-webpack-plugin')
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin')

const base = require('./webpack.base.conf')


const configProd = {
  mode: 'production',
  devtool: 'source-map',
  output: {
    filename: 'js/[name].js',
    path: path.resolve(__dirname, '../dist'),
    publicPath: '../'
  },
  optimization: {
    runtimeChunk: 'single',
    minimizer: [
      new TerserPlugin({
        test: /\.m?js$/,
        sourceMap: true,
        terserOptions: {
          safari10: true
        }
      })
    ]
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
          },
          {
            loader: 'img-loader',
            options: {
              plugins: [
                require('imagemin-gifsicle')({
                  interlaced: true
                }),
                require('imagemin-mozjpeg')({
                  progressive: true,
                  arithmetic: false
                }),
                require('imagemin-optipng')({
                  optimizationLevel: 5
                }),
                require('imagemin-svgo')({
                  plugins: [
                    { convertPathData: false }
                  ]
                })
              ]
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

    new OptimizeCSSAssetsPlugin({
      cssProcessorOptions: {
        map: {
          inline: false,
          annotation: true,
        },
        safe: true,
        discardComments: true
      },
    }),

    new webpack.DefinePlugin({
      PRODUCTION: JSON.stringify(true)
    })
  ]
}

module.exports = merge(base, configProd)
