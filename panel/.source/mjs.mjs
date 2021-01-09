import * as file from '@taufik-nurrohman/file';
import * as folder from '@taufik-nurrohman/folder';

import {rollup} from 'rollup';
import {babel, getBabelOutputPlugin} from '@rollup/plugin-babel';
import resolve from '@rollup/plugin-node-resolve';

import {minify} from 'terser';

function factory(from, to, name, format, options = {}) {
    const c = Object.assign({
        input: from,
        output: {
            file: to,
            format,
            name,
            sourcemap: false
        },
        plugins: [
            babel({
                babelHelpers: 'bundled',
                plugins: [
                    '@babel/plugin-proposal-class-properties',
                    '@babel/plugin-proposal-private-methods'
                ],
                presets: [
                    ['@babel/preset-env', {
                        loose: true,
                        modules: false,
                        targets: '>0.25%'
                    }]
                ]
            }),
            getBabelOutputPlugin({
                allowAllFormats: true
            }),
            resolve()
        ]
    }, options);
    (async () => {
        const generator = await rollup(c);
        await generator.write(c.output);
        await generator.close();
        let content = file.getContent(c.output.file);
        minify(content, {
            compress: {
                unsafe: true
            }
        }).then(result => {
            file.setContent(c.output.file.replace(/\.js$/, '.min.js'), result.code);
        });
    })();
}

!folder.get('lot/asset/js') && folder.set('lot/asset/js', true);

factory('.source/-/lot/asset/mjs/0.mjs', 'lot/asset/js/0.js', null, 'iife');
factory('.source/-/lot/asset/mjs/1.mjs', 'lot/asset/js/1.js', null, 'iife');
factory('.source/-/lot/asset/mjs/index.mjs', 'lot/asset/js/index.js', '_', 'iife');
