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

import OptionPicker from '@taufik-nurrohman/option-picker';

function onChange(init) {
    let sources = getElements('input[list]:not([type=hidden]),select');
    sources && toCount(sources) && sources.forEach(source => {
        letClass(source, 'input');
        letClass(source, 'select');
        let c = getClasses(source);
        let $ = new OptionPicker(source, getDatum(source, 'state') ?? {});
        setClasses($.self, c);
    });
    1 === init && W._.on('change', onChange);
}

W.OP = W.OptionPicker = OptionPicker;

export default onChange;