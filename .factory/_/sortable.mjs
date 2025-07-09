import {
    W,
    getDatum,
    getElements,
    hasClass
} from '@taufik-nurrohman/document';

import {
    toCount
} from '@taufik-nurrohman/to';

import {
    MultiDrag,
    Sortable
} from 'sortablejs';

Sortable.instances = [];

Sortable.mount(new MultiDrag);

function onChange(init) {
    let instance;
    while (instance = Sortable.instances.pop()) {
        instance.destroy();
    }
    let sources = getElements('.can\\:sort:not(.not\\:active)');
    sources && toCount(sources) && sources.forEach(source => {
        let batch = getDatum(source, 'batch'),
            handle;
        if (hasClass(source, 'content:columns') || hasClass(source, 'lot:columns')) {
            // TODO
        }
        if (hasClass(source, 'content:fields') || hasClass(source, 'lot:fields')) {
            handle = 'label[for]';
        }
        if (hasClass(source, 'content:files') || hasClass(source, 'lot:files')) {
            // TODO
        }
        if (hasClass(source, 'content:folders') || hasClass(source, 'lot:folders')) {
            // TODO
        }
        if (hasClass(source, 'content:pages') || hasClass(source, 'lot:pages')) {
            // TODO
        }
        if (hasClass(source, 'content:rows') || hasClass(source, 'lot:rows')) {
            // TODO
        }
        if (hasClass(source, 'content:stacks') || hasClass(source, 'lot:stacks')) {
            // TODO
        }
        if (hasClass(source, 'content:tabs') || hasClass(source, 'lot:tabs')) {
            // TODO
        }
        let sortable = new Sortable(source, {
            animation: 150,
            avoidImplicitDeselect: false,
            dataIdAttr: 'data-value',
            emptyInsertThreshold: 5,
            fallbackOnBody: true,
            fallbackTolerance: 3,
            filter: '.not\\:active,:disabled,[aria-disabled=true],[disabled],input[type=hidden]',
            group: batch,
            handle,
            // multiDrag: true,
            onSort,
            swapThreshold: 0.5
        });
        Sortable.instances.push(sortable);
    });
    1 === init && W._.on('change', onChange);
}

function onSort(e) {
    let t = e.item;
    W._.fire.apply(t, ['sort', [getDatum(t, 'value'), getDatum(t, 'name')]]);
}

W.Sortable = Sortable;

export default onChange;