import {
    W,
    getClasses,
    getDatum,
    getElements,
    setClasses
} from '@taufik-nurrohman/document';

import {
    toCount
} from '@taufik-nurrohman/to';

import TP from '@taufik-nurrohman/tag-picker';

function onChange() {
    // Destroy!
    let $;
    for (let key in TP.instances) {
        $ = TP.instances[key];
        $.pop();
        delete TP.instances[key];
    }
    let sources = getElements('.lot\\:field.type\\:query input');
    sources && toCount(sources) && sources.forEach(source => {
        let c = getClasses(source);
        let $ = new TP(source, getDatum(source, 'state') ?? {});
        setClasses($.self, c);
    });
} onChange();

W._.on('change', onChange);

W.TP = TP;