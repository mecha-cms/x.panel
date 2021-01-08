import * as file from '@taufik-nurrohman/file';
import * as folder from '@taufik-nurrohman/folder';

import cleancss from 'clean-css';
import sass from 'node-sass';
import moduleImporter from 'sass-module-importer';

const minifier = new cleancss({
    level: 2
});

function factory(from, to, options = {}) {
    sass.render(Object.assign({
        file: from,
        importer: moduleImporter(),
        outputStyle: 'expanded'
    }, options), (error, result) => {
        if (error) {
            throw error;
        }
        file.setContent(to, result.css);
        minifier.minify(result.css, (error, result) => {
            if (error) {
                throw error;
            }
            file.setContent(to.replace(/\.css$/, '.min.css'), result.styles);
        })
    });
}

!folder.get('lot/asset/css') && folder.set('lot/asset/css', true);

factory('.source/-/lot/asset/scss/index.scss', 'lot/asset/css/index.css');
factory('.source/-/lot/asset/scss/r.scss', 'lot/asset/css/r.css');

factory('.source/-/lot/layout/asset/scss/index.scss', 'lot/layout/asset/css/index.css');
