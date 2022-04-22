import {
    W,
    getClasses,
    getDatum,
    getElements,
    setClasses
} from '@taufik-nurrohman/document';

import {
    offEvent,
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

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
    let sources = getElements('input[list],select');
    sources && toCount(sources) && sources.forEach(source => {
        let c = getClasses(source);
        let $ = new OP(source, getDatum(source, 'state') ?? {});
        setClasses($.self, c);
    });
    1 === init && W._.on('change', onChange);
}

W.OP = OP;

export default onChange;