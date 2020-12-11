import babel from '@rollup/plugin-babel';
import resolve from '@rollup/plugin-node-resolve';

export default {
  input: 'lot/asset/mjs/panel.mjs',
  output: {
    file: 'lot/asset/js/panel.js',
    format: 'umd',
    name: '_',
    sourcemap: false
  },
  plugins: [
    resolve(),
    babel({
      babelHelpers: 'bundled',
      exclude: 'node_modules/**',
      plugins: [
        [
          '@babel/plugin-proposal-class-properties',
          {
            loose: true
          }
        ],
        [
          '@babel/plugin-proposal-private-methods',
          {
            loose: true
          }
        ]
      ],
      presets: [
        [
          '@babel/preset-env',
          {
            loose: true,
            modules: false,
            targets: '>0.25%'
          }
        ]
      ]
    })
  ]
};
