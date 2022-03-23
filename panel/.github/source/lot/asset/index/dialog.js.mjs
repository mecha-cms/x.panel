import {
    B,
    W,
    getElement,
    setChildLast,
    setElement,
    setHTML
} from '@taufik-nurrohman/document';

import {
    offEvent,
    onEvent
} from '@taufik-nurrohman/event';

import {
    isFunction
} from '@taufik-nurrohman/is';

import {
    toValue
} from '@taufik-nurrohman/to';

let dialog = setElement('dialog');

function onDialogCancel(e) {
    offEvent(e.type, this, onDialogCancel);
    return this.x(this.returnValue);
}
function onDialogSubmit(e) {
    offEvent(e.type, this, onDialogSubmit);
    return this.v(this.returnValue);
}

function setDialog(content) {
    setHTML(dialog, '<form method="dialog">' + content + '</form>');
    dialog.showModal();
    let target = getElement('[autofocus]', dialog);
    if (target) {
        isFunction(target.focus) && target.focus();
        isFunction(target.select) && target.select(); // `<input>`
    }
    return new Promise((resolve, reject) => {
        dialog.v = resolve;
        dialog.x = reject;
        onEvent('cancel', dialog, onDialogCancel);
        onEvent('submit', dialog, onDialogSubmit);
    });
}

setDialog.alert = function(description) {
    return setDialog('<p>' + description + '</p><p role="group"><button autofocus name="v" type="submit" value="1">OK</button></p>');
};

setDialog.confirm = function(description) {
    return setDialog('<p>' + description + '</p><p role="group"><button name="v" type="submit" value="1">OK</button> <button autofocus name="v" type="submit" value="0">Cancel</button></p>');
};

setDialog.prompt = function(key, value) {
    value = value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return setDialog('<p>' + key + '</p><p><input autofocus type="text" value="' + value + '"></p><p role="group"><button name="v" type="submit" value="1">OK</button> <button name="v" type="submit" value="0">Cancel</button></p>');
};

setChildLast(B, dialog);

W._.dialog = setDialog;