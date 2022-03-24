(function() {
    'use strict';
    var isArray = function isArray(x) {
        return Array.isArray(x);
    };
    var isDefined = function isDefined(x) {
        return 'undefined' !== typeof x;
    };
    var isFunction = function isFunction(x) {
        return 'function' === typeof x;
    };
    var isInstance = function isInstance(x, of ) {
        return x && isSet( of ) && x instanceof of ;
    };
    var isNull = function isNull(x) {
        return null === x;
    };
    var isNumeric = function isNumeric(x) {
        return /^-?(?:\d*.)?\d+$/.test(x + "");
    };
    var isObject = function isObject(x, isPlain) {
        if (isPlain === void 0) {
            isPlain = true;
        }
        if ('object' !== typeof x) {
            return false;
        }
        return isPlain ? isInstance(x, Object) : true;
    };
    var isSet = function isSet(x) {
        return isDefined(x) && !isNull(x);
    };
    var isString = function isString(x) {
        return 'string' === typeof x;
    };
    var toNumber = function toNumber(x, base) {
        if (base === void 0) {
            base = 10;
        }
        return base ? parseInt(x, base) : parseFloat(x);
    };
    var toValue = function toValue(x) {
        if (isArray(x)) {
            return x.map(function(v) {
                return toValue(v);
            });
        }
        if (isNumeric(x)) {
            return toNumber(x);
        }
        if (isObject(x)) {
            for (var k in x) {
                x[k] = toValue(x[k]);
            }
            return x;
        }
        if ('false' === x) {
            return false;
        }
        if ('null' === x) {
            return null;
        }
        if ('true' === x) {
            return true;
        }
        return x;
    };
    var fromValue = function fromValue(x) {
        if (isArray(x)) {
            return x.map(function(v) {
                return fromValue(x);
            });
        }
        if (isObject(x)) {
            for (var k in x) {
                x[k] = fromValue(x[k]);
            }
            return x;
        }
        if (false === x) {
            return 'false';
        }
        if (null === x) {
            return 'null';
        }
        if (true === x) {
            return 'true';
        }
        return "" + x;
    };
    var D = document;
    var W = window;
    var B = D.body;
    var getElement = function getElement(query, scope) {
        return (scope || D).querySelector(query);
    };
    var getNext = function getNext(node, anyNode) {
        return node['next' + (anyNode ? "" : 'Element') + 'Sibling'] || null;
    };
    var getParent = function getParent(node, query) {
        if (query) {
            return node.closest(query) || null;
        }
        return node.parentNode || null;
    };
    var getPrev = function getPrev(node, anyNode) {
        return node['previous' + (anyNode ? "" : 'Element') + 'Sibling'] || null;
    };
    var hasState = function hasState(node, state) {
        return state in node;
    };
    var letAttribute = function letAttribute(node, attribute) {
        return node.removeAttribute(attribute), node;
    };
    var setAttribute = function setAttribute(node, attribute, value) {
        if (true === value) {
            value = attribute;
        }
        return node.setAttribute(attribute, fromValue(value)), node;
    };
    var setAttributes = function setAttributes(node, attributes) {
        var value;
        for (var attribute in attributes) {
            value = attributes[attribute];
            if (value || "" === value || 0 === value) {
                setAttribute(node, attribute, value);
            } else {
                letAttribute(node, attribute);
            }
        }
        return node;
    };
    var setChildLast = function setChildLast(parent, node) {
        return parent.append(node), node;
    };
    var setElement = function setElement(node, content, attributes) {
        node = isString(node) ? D.createElement(node) : node;
        if (isObject(content)) {
            attributes = content;
            content = false;
        }
        if (isString(content)) {
            setHTML(node, content);
        }
        if (isObject(attributes)) {
            setAttributes(node, attributes);
        }
        return node;
    };
    var setHTML = function setHTML(node, content, trim) {
        if (trim === void 0) {
            trim = true;
        }
        if (null === content) {
            return node;
        }
        var state = 'innerHTML';
        return hasState(node, state) && (node[state] = trim ? content.trim() : content), node;
    };
    var event = function event(name, options, cache) {
        if (cache && isSet(events[name])) {
            return events[name];
        }
        return events[name] = new Event(name, options);
    };
    var events = {};
    var fireEvent = function fireEvent(name, node, options, cache) {
        node.dispatchEvent(event(name, options, cache));
    };
    var offEvent = function offEvent(name, node, then) {
        node.removeEventListener(name, then);
    };
    var offEventDefault = function offEventDefault(e) {
        return e && e.preventDefault();
    };
    var onEvent = function onEvent(name, node, then, options) {
        if (options === void 0) {
            options = false;
        }
        node.addEventListener(name, then, options);
    };
    let dialog = setElement('dialog'),
        dialogForm = setElement('form', "", {
            method: 'dialog'
        }),
        dialogTemplate = setElement('template');
    setChildLast(B, dialog);
    setChildLast(dialog, dialogForm);

    function onDialogCancel(e) {
        let t = this;
        offEvent(e.type, t, onDialogCancel);
        return t.x(toValue(t.returnValue));
    }

    function onDialogSubmit(e) {
        let t = this;
        offEvent(e.type, t, onDialogSubmit);
        return t.v(toValue(t.returnValue));
    }

    function setDialog(content) {
        setHTML(dialogForm, "");
        if (isString(content)) {
            setHTML(dialogTemplate, content.trim());
            content = [...dialogTemplate.content.childNodes];
        } else {
            content = [...content];
        }
        let node;
        while (node = content.shift()) {
            setChildLast(dialogForm, node);
        }
        dialog.showModal();
        dialog.returnValue = null;
        let target = getElement('[autofocus]', dialogForm);
        if (target) {
            isFunction(target.focus) && target.focus();
            isFunction(target.select) && target.select(); // `<input>`
        }
        return new Promise((yes, no) => {
            dialog.v = yes;
            dialog.x = no;
            onEvent('cancel', dialog, onDialogCancel);
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
        let key = e.key,
            next,
            prev,
            t = this;
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
    setDialog.alert = function(description) {
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
    setDialog.confirm = function(description) {
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
    setDialog.prompt = function(key, value) {
        setHTML(dialogPromptKey, key);
        dialogPromptValue.value = dialogPromptTaskOkay.value = value;
        return setDialog([dialogPromptKey, dialogPromptValueP, dialogPromptTasks]);
    };
    W._.dialog = setDialog;
})();