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

import OP from '@taufik-nurrohman/option-picker';

function onChange(init) {
    // Destroy!
    let $;
    for (let key in OP.instances) {
        $ = OP.instances[key];
        $.pop();
        delete OP.instances[key];
    }
    let sources = getElements('.input[list]:not([type="hidden"]),.select');
    sources && toCount(sources) && sources.forEach(source => {
        letClass(source, 'input');
        letClass(source, 'select');
        let c = getClasses(source);
        let $ = new OP(source, getDatum(source, 'state') ?? {});
        setClasses($.self, c);
    });
    1 === init && W._.on('change', onChange);
}

W.OP = OP;

export default onChange;