import {
    B,
    W,
    getChildFirst,
    getChildren,
    getElement,
    getNext,
    getParent,
    getPrev,
    isNode,
    setChildLast,
    setElement,
    setHTML
} from '@taufik-nurrohman/document';

import {
    fireEvent,
    offEvent,
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

import {
    isFunction,
    isString
} from '@taufik-nurrohman/is';

import {
    toValue
} from '@taufik-nurrohman/to';

let dialog = setElement('dialog'),
    dialogForm = setElement('form', "", {
        method: 'dialog'
    }),
    dialogTemplate = setElement('div');

setChildLast(B, dialog);
setChildLast(dialog, dialogForm);

function onDialogCancel(e) {
    let t = this;
    offEvent('cancel', t, onDialogCancel);
    offEvent('close', t, onDialogClose);
    offEvent('submit', t, onDialogSubmit);
    t.x(toValue(t.returnValue));
    isFunction(t.c) && t.c.apply(t, [t.open]);
}

function onDialogClose(e) {
    let t = this;
    offEvent('cancel', t, onDialogCancel);
    offEvent('close', t, onDialogClose);
    offEvent('submit', t, onDialogSubmit);
    t.v(toValue(t.returnValue));
    isFunction(t.c) && t.c.apply(t, [t.open]);
}

function onDialogSubmit(e) {
    onDialogClose.apply(this, [e]);
}

function setDialog(content, then) {
    setHTML(dialogForm, "");
    if (isString(content)) {
        setHTML(dialogTemplate, content.trim());
        content = dialogTemplate.childNodes;
    } else if (isNode(content)) {
        content = [content];
    }
    content = Array.from(content);
    let node;
    while (node = content.shift()) {
        setChildLast(dialogForm, node);
    }
    dialog.showModal();
    dialog.returnValue = null;
    isFunction(then) && then.apply(dialog, [dialog.open]);
    let target = getElement('[autofocus]', dialogForm);
    if (target) {
        isFunction(target.focus) && target.focus();
        isFunction(target.select) && target.select(); // `<input>`
    }
    return new Promise((yay, nay) => {
        dialog.c = then; // `c` for call-back
        dialog.v = yay; // `v` for check-mark
        dialog.x = nay; // `x` for cross-mark
        onEvent('cancel', dialog, onDialogCancel);
        onEvent('close', dialog, onDialogClose);
        onEvent('submit', dialog, onDialogSubmit);
    });
}

function onDialogTaskClick() {
    let t = this,
        dialog = getParent(t.form);
    dialog.returnValue = t.value;
    fireEvent('reset' === t.type ? 'cancel' : 'close', dialog);
    dialog.open && dialog.close();
}

function onDialogTaskKeyDown(e) {
    let key = e.key, next, prev, t = this;
    if ('ArrowLeft' === key || 'ArrowUp' === key) {
        (prev = getPrev(t)) && prev.focus();
        offEventDefault(e);
    } else if ('ArrowDown' === key || 'ArrowRight' === key) {
        (next = getNext(t)) && next.focus();
        offEventDefault(e);
    }
}

let dialogAlertDescription = setElement('p'),
    dialogAlertTasks = setElement('p', "", {
        role: 'group'
    }),
    dialogAlertTaskOkay = setElement('button', 'OK', {
        type: 'submit',
        value: 'true'
    });

onEvent('keydown', dialogAlertTaskOkay, onDialogTaskKeyDown);
onEvent('click', dialogAlertTaskOkay, onDialogTaskClick);

setChildLast(dialogAlertTasks, dialogAlertTaskOkay);

setDialog.alert = function (description) {
    setHTML(dialogAlertDescription, description);
    return setDialog([dialogAlertDescription, dialogAlertTasks]);
};

let dialogConfirmDescription = setElement('p'),
    dialogConfirmTasks = setElement('p', "", {
        role: 'group'
    }),
    dialogConfirmTaskOkay = setElement('button', 'OK', {
        type: 'submit',
        value: 'true'
    }),
    dialogConfirmTaskCancel = setElement('button', 'Cancel', {
        type: 'reset',
        value: 'false'
    });

onEvent('click', dialogConfirmTaskCancel, onDialogTaskClick);
onEvent('click', dialogConfirmTaskOkay, onDialogTaskClick);
onEvent('keydown', dialogConfirmTaskCancel, onDialogTaskKeyDown);
onEvent('keydown', dialogConfirmTaskOkay, onDialogTaskKeyDown);

setChildLast(dialogConfirmTasks, dialogConfirmTaskOkay);
setChildLast(dialogConfirmTasks, dialogConfirmTaskCancel);

setDialog.confirm = function (description) {
    setHTML(dialogConfirmDescription, description);
    return setDialog([dialogConfirmDescription, dialogConfirmTasks]);
};

let dialogPromptKey = setElement('p'),
    dialogPromptValue = setElement('input', false, {
        autofocus: true,
        type: 'text'
    }),
    dialogPromptValueP = setElement('p'),
    dialogPromptTasks = setElement('p', "", {
        role: 'group'
    }),
    dialogPromptTaskOkay = setElement('button', 'OK', {
        type: 'submit',
        value: ""
    }),
    dialogPromptTaskCancel = setElement('button', 'Cancel', {
        type: 'reset',
        value: 'false'
    });

function onDialogPromptValueInput() {
    dialogPromptTaskOkay.value = this.value;
}

onEvent('click', dialogPromptTaskCancel, onDialogTaskClick);
onEvent('click', dialogPromptTaskOkay, onDialogTaskClick);
onEvent('input', dialogPromptValue, onDialogPromptValueInput);
onEvent('keydown', dialogPromptTaskCancel, onDialogTaskKeyDown);
onEvent('keydown', dialogPromptTaskOkay, onDialogTaskKeyDown);
onEvent('keyup', dialogPromptValue, onDialogPromptValueInput);

setChildLast(dialogPromptTasks, dialogPromptTaskOkay);
setChildLast(dialogPromptTasks, dialogPromptTaskCancel);
setChildLast(dialogPromptValueP, dialogPromptValue);

setDialog.prompt = function (key, value) {
    setHTML(dialogPromptKey, key);
    dialogPromptValue.value = dialogPromptTaskOkay.value = value;
    return setDialog([dialogPromptKey, dialogPromptValueP, dialogPromptTasks]);
};

export default function (init) {
    1 === init && (W._.dialog = setDialog);
};