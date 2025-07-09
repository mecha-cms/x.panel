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

import TagPicker from '@taufik-nurrohman/tag-picker';

function onChange(init) {
    let sources = getElements('.lot\\:field.type\\:query input:not([type=hidden])');
    sources && toCount(sources) && sources.forEach(source => {
        letClass(source, 'input');
        let c = getClasses(source);
        let $ = new TagPicker(source, getDatum(source, 'state') ?? {});
        setClasses($.self, c);
    });
    1 === init && W._.on('change', onChange);
}

W.TP = W.TagPicker = TagPicker;

export default onChange;