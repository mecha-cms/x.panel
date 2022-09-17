import Bar from './_/bar.mjs';
import Column from './_/column.mjs';
import Dialog from './_/dialog.mjs';
import Field from './_/field.mjs';
import File from './_/file.mjs';
import Link from './_/link.mjs';
import Menu from './_/menu.mjs';
import Page from './_/page.mjs';
import Row from './_/row.mjs';
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

onEvent('keydown', W, function (e) {
    if (e.defaultPrevented) {
        return;
    }
    let t = this,
        target = e.target,
        key = e.key,
        keyIsAlt = e.altKey,
        keyIsCtrl = e.ctrlKey,
        keyIsShift = e.shiftKey,
        firstBarFocusable = getElement('.lot\\:bar a:any-link'),
        mainSearchForm = getFormElement('get'),
        mainSearchFormInput = mainSearchForm && mainSearchForm.query,
        parent, stop;
    if (mainSearchFormInput && 'F3' === key && !keyIsAlt && !keyIsCtrl && !keyIsShift) {
        mainSearchFormInput.focus();
        stop = true;
    } else if (firstBarFocusable && 'F10' === key && !keyIsAlt && !keyIsCtrl && !keyIsShift) {
        firstBarFocusable.focus();
        if (parent = getParent(firstBarFocusable)) {
            if (hasClass(parent, 'has:menu')) {
                firstBarFocusable.click();
            }
        }
        stop = true;
    } else if (B !== target && R !== target && W !== target) {
        if ('Escape' === key && (parent = getParent(getParent(target), '[tabindex]:not(.not\\:active)'))) {
            parent.focus();
            stop = true;
        }
    }
    stop && offEventDefault(e);
});

Bar(1);
Column(1);
Dialog(1);
Field(1);
File(1);
Link(1);
Menu(1);
Page(1);
Row(1);
Stack(1);
Tab(1);
Task(1);