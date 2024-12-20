import {
    W,
    getClasses,
    getDatum,
    getElements,
    letClass,
    setClasses
} from '@taufik-nurrohman/document';

import {
    toCount
} from '@taufik-nurrohman/to';

import TP from '@taufik-nurrohman/tag-picker';

function onChange(init) {
    // Destroy!
    let $;
    for (let key in TP.instances) {
        $ = TP.instances[key];
        $.pop();
        delete TP.instances[key];
    }
    let sources = getElements('.lot\\:field.type\\:query input:not([type=hidden])');
    sources && toCount(sources) && sources.forEach(source => {
        letClass(source, 'input');
        let c = getClasses(source);
        let $ = new TP(source, getDatum(source, 'state') ?? {});
        setClasses($.self, c);
    });
    1 === init && W._.on('change', onChange);
}

W.TP = TP;

export default onChange;