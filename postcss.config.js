// module.exports = ctx => ({
//   plugins: {
//     // 'postcss-import': {},
//     'postcss-easy-import': {
//       extensions: '.pcss'
//     },
//     'postcss-inline-svg': {},
//     'postcss-mixins': {},
//     'postcss-simple-vars': {},
//     'postcss-nested': {},
//     'postcss-combine-media-query': {},
//     'autoprefixer': {},
//     cssnano: {},
//   }
// });

module.exports = ctx => ({
  plugins: {
    'postcss-easy-import': {},
    'postcss-custom-media': {},
    'postcss-discard-comments': {},
    'postcss-custom-selectors': {},
    'postcss-mixins': {},
    'postcss-nested': {},
    'autoprefixer': {},
    'postcss-preset-env': {
      autoprefixer: {
        grid: true,
        overrideBrowserslist: "last 1 version",
        from: undefined,
      },
      features: {
        'nesting-rules': true,
      },
      removeAll: true
    },
    'cssnano': {},
  }
});
