import * as file from '@taufik-nurrohman/file';
import * as folder from '@taufik-nurrohman/folder';

import {rollup} from 'rollup';
import alias from '@rollup/plugin-alias';
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
            alias({
                entries: {
                    '@taufik-nurrohman/text-editor.history': 'node_modules/@taufik-nurrohman/text-editor/index/history.mjs',
                    '@taufik-nurrohman/text-editor.source': 'node_modules/@taufik-nurrohman/text-editor/index/source.mjs',
                }
            }),
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

factory('.source/-/lot/asset/mjs/index.mjs', 'lot/asset/js/index.js', '_', 'iife');
factory('.source/-/lot/asset/mjs/r.mjs', 'lot/asset/js/r.js', null, 'iife');
