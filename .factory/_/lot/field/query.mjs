import {
    W,
    getClasses,
    getDatum,
    getElements,
    letClasses,
    setClasses
} from '@taufik-nurrohman/document';

import {
    toCount
} from '@taufik-nurrohman/to';

import TagPicker from '@taufik-nurrohman/tag-picker';

function onChange(init) {
    let sources = getElements('.lot\\:field.type\\:query input:not([type=hidden])');
    sources && toCount(sources) && sources.forEach(source => {
        let c = getClasses(source);
        letClasses(source);
        let $ = new TagPicker(source, getDatum(source, 'state') ?? {});
        setClasses($.mask, c);
    });
    1 === init && W._.on('change', onChange);
}

W.TP = W.TagPicker = TagPicker;

export default onChange;