module.exports = ctx => ({
    plugins: {

      'postcss-easy-import': {
        extensions: '.pcss'
      },
      cssnano: {}
    }
  });
