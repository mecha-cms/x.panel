import {
    D,
    R,
    W,
    getElements,
    letClasses,
    setClass
} from '@taufik-nurrohman/document';

import {
    offEvents,
    onEvents
} from '@taufik-nurrohman/event'

import {
    toCount
} from '@taufik-nurrohman/to';

function onChange() {
    let toggles = getElements('[name="cookie[panel-skin-variant]"]:not(:disabled)');
    toCount(toggles) && toggles.forEach(toggle => {
        offEvents(['blur', 'change'], toggle, onChangeToggle);
        onEvents(['blur', 'change'], toggle, onChangeToggle);
    });
} onChange();

function onChangeToggle() {
    let value = this.value,
        date = new Date;
    letClasses(R, ['is:dark', 'is:light']);
    setClass(R, 'is:' + value);
    date.setFullYear(date.getFullYear() + 1);
    D.cookie = 'panel-skin-variant=' + value + '; expires=' + date.toUTCString() + '; path=/;';
}

W._.on('change', onChange);