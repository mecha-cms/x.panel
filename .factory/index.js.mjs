import Bar from './_/bar.mjs';
import Column from './_/column.mjs';
import Dialog from './_/dialog.mjs';
import Field from './_/field.mjs';
import File from './_/file.mjs';
import Link from './_/link.mjs';
import Menu from './_/menu.mjs';
import Page from './_/page.mjs';
import Row from './_/row.mjs';
import Siema from './_/siema.mjs';
import Stack from './_/stack.mjs';
import Tab from './_/tab.mjs';
import Task from './_/task.mjs';

import {
    B,
    D,
    R,
    W,
    getElement,
    getFormElement,
    getParent,
    hasClass
} from '@taufik-nurrohman/document';

import {
    fireEvent,
    offEventDefault,
    onEvent
} from '@taufik-nurrohman/event';

import {
    hook
} from '@taufik-nurrohman/hook';

import {
    isFunction
} from '@taufik-nurrohman/is';

import K from '@taufik-nurrohman/key';

import {
    debounce
} from '@taufik-nurrohman/tick';

import {
    toValue
} from '@taufik-nurrohman/to';

const bounce = debounce(map => map.pull(), 1000);
const map = new K(W);

map.keys['Escape'] = function () {
    let current = D.activeElement,
        parent = current && getParent(getParent(current), '[tabindex]:not(.not\\:active)');
    console.log([current,parent]);
    parent && parent.focus();
    return false;
};

map.keys['F3'] = function () {
    let mainSearchForm = getFormElement('get'),
        mainSearchFormInput = mainSearchForm && mainSearchForm.query;
    mainSearchFormInput && mainSearchFormInput.focus();
    return false;
};

map.keys['F10'] = function () {
    let current, firstBarFocusable = getElement('.lot\\:bar a:any-link'), parent;
    if (firstBarFocusable) {
        firstBarFocusable.focus();
        if (parent = getParent(firstBarFocusable)) {
            if (hasClass(parent, 'has:menu')) {
                firstBarFocusable.click(); // Open main menu!
            }
        }
    }
    return false;
};

onEvent('blur', W, e => map.pull());

onEvent('keydown', W, e => {
    map.push(e.key);
    let command = map.command();
    if (command) {
        let value = map.fire(command);
        if (false === value) {
            offEventDefault(e);
        } else if (null === value) {
            console.error('Unknown command:', command);
        }
    }
    bounce(map);
});

onEvent('keyup', W, e => map.pull(e.key));

const _ = {
    commands: map.commands,
    keys: map.keys
};

const {fire, hooks, off, on} = hook(_);

W.K = K;
W._ = _;

onEvent('beforeload', D, () => fire('let'));
onEvent('load', D, () => fire('get'));
onEvent('DOMContentLoaded', D, () => fire('set'));

Bar(1);
Column(1);
Dialog(1);
Field(1);
File(1);
Link(1);
Menu(1);
Page(1);
Row(1);
Siema(1);
Stack(1);
Tab(1);
Task(1);