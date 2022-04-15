/* eslint-disable */

module.exports = {
  externals: {
    jquery: 'jQuery'
  },
  module: {
    rules: [{
      test    : /\.js?$/,
      exclude : [/node_modules/],
      loader  : 'babel-loader'
    },
    {
      test    : /\.(woff(2)?|eot|ttf|otf)$/,
      loader  : 'file-loader',
      options : {
        name       : '[name].[ext]',
        outputPath : 'fonts/'
      }
    }]
  }
}
