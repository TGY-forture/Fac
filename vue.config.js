module.exports = {
  // publicPath: './',
  devServer: {
    proxy: {
      '/api': {
        target: "http://localhost:80/Factory/proj/src/php",
        changeOrigin: true,
        ws: true,
        pathRewrite: {
          '^/api': ''
        }
      }
    }
  }
}