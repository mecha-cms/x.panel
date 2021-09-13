import {
    W,
    getChildren,
    getClasses,
    getDatum,
    getElements,
    getParent,
    hasClass,
    letClass,
    setChildLast,
    setClass,
    setClasses,
    setElement
} from '@taufik-nurrohman/document';

import {
    offEvent,
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

import {
    toCount
} from '@taufik-nurrohman/to';

function onChange() {
    let sources = getElements('.lot\\:tabs');
    sources && toCount(sources) && sources.forEach(source => {
        let panes = [].slice.call(getChildren(source)),
            input = setElement('input'),
            buttons = [].slice.call(getElements('a', panes.shift()));
        input.type = 'hidden';
        input.name = getDatum(source, 'name');
        setChildLast(source, input);
        function onClick(e) {
            let t = this;
            if (!hasClass(getParent(t), 'has:link')) {
                if (!hasClass(t, 'not:active')) {
                    buttons.forEach(button => {
                        letClass(button, 'is:current');
                        letClass(getParent(button), 'is:current');
                        if (panes[button._tabIndex]) {
                            letClass(panes[button._tabIndex], 'is:current');
                        }
                    });
                    setClass(t, 'is:current');
                    setClass(getParent(t), 'is:current');
                    if (panes[t._tabIndex]) {
                        setClass(panes[t._tabIndex], 'is:current');
                        input.value = getDatum(t, 'name');
                    }
                }
                offEventDefault(e);
            }
        }
        buttons.forEach((button, index) => {
            button._tabIndex = index;
            onEvent('click', button, onClick);
        });
        let buttonCurrent = buttons.find((value, key) => 0 !== key && hasClass(getParent(value), 'is:current'));
        if (buttonCurrent) {
            input.value = getDatum(buttonCurrent, 'name');
        }
    });
} onChange();

W._.on('change', onChange);