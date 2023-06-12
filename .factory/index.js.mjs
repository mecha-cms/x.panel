import Bar from './_/lot/bar.mjs';
import Columns from './_/lot/columns.mjs';
import Dialog from './_/dialog.mjs';
import Fields from './_/lot/fields.mjs';
import FilesFolders from './_/lot/files-folders.mjs';
import Links from './_/lot/links.mjs';
import Menu from './_/lot/menu.mjs';
import Menus from './_/lot/menus.mjs';
import Pages from './_/lot/pages.mjs';
import Rows from './_/lot/rows.mjs';
import Siema from './_/siema.mjs';
import Stacks from './_/lot/stacks.mjs';
import Tabs from './_/lot/tabs.mjs';
import Tasks from './_/lot/tasks.mjs';

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
    parent && parent.focus();
    return !parent;
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
Columns(1);
Dialog(1);
Fields(1);
FilesFolders(1);
Links(1);
Menu(1);
Menus(1);
Pages(1);
Rows(1);
Siema(1);
Stacks(1);
Tabs(1);
Tasks(1);