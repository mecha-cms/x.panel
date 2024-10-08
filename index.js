(function () {
    'use strict';
    var hasValue = function hasValue(x, data) {
        return -1 !== data.indexOf(x);
    };
    var isArray = function isArray(x) {
        return Array.isArray(x);
    };
    var isDefined = function isDefined(x) {
        return 'undefined' !== typeof x;
    };
    var isFunction = function isFunction(x) {
        return 'function' === typeof x;
    };
    var isInstance = function isInstance(x, of) {
        return x && isSet(of) && x instanceof of ;
    };
    var isInteger = function isInteger(x) {
        return isNumber(x) && 0 === x % 1;
    };
    var isNull = function isNull(x) {
        return null === x;
    };
    var isNumber = function isNumber(x) {
        return 'number' === typeof x;
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
    var toArray = function toArray(x) {
        return isArray(x) ? x : [x];
    };
    var toArrayKey = function toArrayKey(x, data) {
        var i = data.indexOf(x);
        return -1 !== i ? i : null;
    };
    var toCaseCamel = function toCaseCamel(x) {
        return x.replace(/[-_.](\w)/g, function (m0, m1) {
            return toCaseUpper(m1);
        });
    };
    var toCaseLower = function toCaseLower(x) {
        return x.toLowerCase();
    };
    var toCaseUpper = function toCaseUpper(x) {
        return x.toUpperCase();
    };
    var toCount = function toCount(x) {
        return x.length;
    };
    var toEdge = function toEdge(x, edges) {
        if (isSet(edges[0]) && x < edges[0]) {
            return edges[0];
        }
        if (isSet(edges[1]) && x > edges[1]) {
            return edges[1];
        }
        return x;
    };
    var toJSON = function toJSON(x) {
        return JSON.stringify(x);
    };
    var toNumber = function toNumber(x, base) {
        if (base === void 0) {
            base = 10;
        }
        return base ? parseInt(x, base) : parseFloat(x);
    };
    var toObjectCount = function toObjectCount(x) {
        return toCount(toObjectKeys(x));
    };
    var toObjectKeys = function toObjectKeys(x) {
        return Object.keys(x);
    };
    var toObjectValues = function toObjectValues(x) {
        return Object.values(x);
    };

    function _toQueryDeep(query, key) {
        var out = {},
            suffix = key ? '%5D' : "",
            i,
            k,
            v;
        for (i in query) {
            k = toURL(i);
            v = query[i];
            if (isObject(v)) {
                out = fromStates({}, out, _toQueryDeep(v, key + k + suffix + '%5B'));
            } else {
                out[key + k + suffix] = v;
            }
        }
        return out;
    }
    var toQuery = function toQuery(x) {
        var list = [],
            query = _toQueryDeep(x, ""),
            k,
            v;
        for (k in query) {
            v = query[k];
            // `{"a":"true","b":true}` â†’ `a=true&b`
            v = true !== v ? '=' + toURL(fromValue(v)) : "";
            list.push(k + v);
        }
        return toCount(list) ? '?' + list.join('&') : null;
    };
    var toURL = function toURL(x) {
        return encodeURIComponent(x);
    };
    var toValue = function toValue(x) {
        if (isArray(x)) {
            return x.map(function (v) {
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
    var fromJSON = function fromJSON(x) {
        var value = null;
        try {
            value = JSON.parse(x);
        } catch (e) {}
        return value;
    };

    function _fromQueryDeep(o, props, value) {
        var prop = props.split('['),
            i,
            j = toCount(prop),
            k;
        for (i = 0; i < j - 1; ++i) {
            k = ']' === prop[i].slice(-1) ? prop[i].slice(0, -1) : prop[i];
            k = "" === k ? toObjectCount(k) : k;
            o = o[k] || (o[k] = {});
        }
        k = ']' === prop[i].slice(-1) ? prop[i].slice(0, -1) : prop[i];
        o["" === k ? toObjectCount(o) : k] = value;
    }
    var fromQuery = function fromQuery(x, parseValue, defaultValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        if (defaultValue === void 0) {
            defaultValue = true;
        }
        var out = {},
            q = x && '?' === x[0] ? x.slice(1) : x;
        if ("" === q) {
            return out;
        }
        q.split('&').forEach(function (v) {
            var a = v.split('='),
                key = fromURL(a[0]),
                value = isSet(a[1]) ? fromURL(a[1]) : defaultValue;
            value = parseValue ? toValue(value) : value;
            // `a[b]=c`
            if (']' === key.slice(-1)) {
                _fromQueryDeep(out, key, value);
                // `a=b`
            } else {
                out[key] = value;
            }
        });
        return out;
    };
    var fromStates = function fromStates() {
        for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
            lot[_key] = arguments[_key];
        }
        var out = lot.shift();
        for (var i = 0, j = toCount(lot); i < j; ++i) {
            for (var k in lot[i]) {
                // Assign value
                if (!isSet(out[k])) {
                    out[k] = lot[i][k];
                    continue;
                }
                // Merge array
                if (isArray(out[k]) && isArray(lot[i][k])) {
                    out[k] = [ /* Clone! */ ].concat(out[k]);
                    for (var ii = 0, jj = toCount(lot[i][k]); ii < jj; ++ii) {
                        if (!hasValue(lot[i][k][ii], out[k])) {
                            out[k].push(lot[i][k][ii]);
                        }
                    }
                    // Merge object recursive
                } else if (isObject(out[k]) && isObject(lot[i][k])) {
                    out[k] = fromStates({
                        /* Clone! */ }, out[k], lot[i][k]);
                    // Replace value
                } else {
                    out[k] = lot[i][k];
                }
            }
        }
        return out;
    };
    var fromURL = function fromURL(x) {
        return decodeURIComponent(x);
    };
    var fromValue = function fromValue(x) {
        if (isArray(x)) {
            return x.map(function (v) {
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
    var R = D.documentElement;
    var getAttribute = function getAttribute(node, attribute, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        if (!hasAttribute(node, attribute)) {
            return null;
        }
        var value = node.getAttribute(attribute);
        return parseValue ? toValue(value) : value;
    };
    var getChildFirst = function getChildFirst(parent) {
        return parent.firstElementChild || null;
    };
    var getChildren = function getChildren(parent, index) {
        var children = parent.children;
        return isNumber(index) ? children[index] || null : children || [];
    };
    var getClasses = function getClasses(node, toArray) {
        if (toArray === void 0) {
            toArray = true;
        }
        var value = (getState(node, 'className') || "").trim();
        return toArray ? value.split(/\s+/).filter(function (v) {
            return "" !== v;
        }) : value;
    };
    var getDatum = function getDatum(node, datum, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        var value = getAttribute(node, 'data-' + datum, parseValue),
            v = (value + "").trim();
        if (parseValue && v && ('[' === v[0] && ']' === v.slice(-1) || '{' === v[0] && '}' === v.slice(-1)) && null !== (v = fromJSON(value))) {
            return v;
        }
        return value;
    };
    var getElement = function getElement(query, scope) {
        return (scope || D).querySelector(query);
    };
    var getElements = function getElements(query, scope) {
        return (scope || D).querySelectorAll(query);
    };
    var getFormElement = function getFormElement(nameOrIndex) {
        return D.forms[nameOrIndex] || null;
    };
    var getHTML = function getHTML(node, trim) {
        if (trim === void 0) {
            trim = true;
        }
        var state = 'innerHTML';
        if (!hasState(node, state)) {
            return false;
        }
        var content = node[state];
        content = trim ? content.trim() : content;
        return "" !== content ? content : null;
    };
    var getName = function getName(node) {
        return toCaseLower(node && node.nodeName || "") || null;
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
    var getParentForm = function getParentForm(node) {
        var state = 'form';
        if (hasState(node, state) && state === getName(node[state])) {
            return node[state];
        }
        return getParent(node, state);
    };
    var getPrev = function getPrev(node, anyNode) {
        return node['previous' + ('Element') + 'Sibling'] || null;
    };
    var getStyle = function getStyle(node, style, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        var value = W.getComputedStyle(node).getPropertyValue(style);
        if (parseValue) {
            value = toValue(value);
        }
        return value || "" === value || 0 === value ? value : null;
    };
    var getState = function getState(node, state) {
        return hasState(node, state) && node[state] || null;
    };
    var getText = function getText(node, trim) {
        if (trim === void 0) {
            trim = true;
        }
        var state = 'textContent';
        if (!hasState(node, state)) {
            return false;
        }
        var content = node[state];
        content = trim ? content.trim() : content;
        return "" !== content ? content : null;
    };
    var hasAttribute = function hasAttribute(node, attribute) {
        return node.hasAttribute(attribute);
    };
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
    };
    var hasParent = function hasParent(node, query) {
        return null !== getParent(node, query);
    };
    var hasState = function hasState(node, state) {
        return state in node;
    };
    var isNode = function isNode(node) {
        return isInstance(node, Node);
    };
    var isWindow = function isWindow(node) {
        return node === W;
    };
    var letAttribute = function letAttribute(node, attribute) {
        return node.removeAttribute(attribute), node;
    };
    var letClass = function letClass(node, value) {
        return node.classList.remove(value), node;
    };
    var letClasses = function letClasses(node, classes) {
        if (isArray(classes)) {
            return classes.forEach(function (name) {
                return node.classList.remove(name);
            }), node;
        }
        if (isObject(classes)) {
            for (var name in classes) {
                classes[name] && node.classList.remove(name);
            }
            return node;
        }
        return node.className = "", node;
    };
    var letDatum = function letDatum(node, datum) {
        return letAttribute(node, 'data-' + datum);
    };
    var letElement = function letElement(node) {
        var parent = getParent(node);
        return node.remove(), parent;
    };
    var letStyle = function letStyle(node, style) {
        return node.style[toCaseCamel(style)] = null, node;
    };
    var letText = function letText(node) {
        var state = 'textContent';
        return hasState(node, state) && (node[state] = ""), node;
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
    var setClass = function setClass(node, value) {
        return node.classList.add(value), node;
    };
    var setClasses = function setClasses(node, classes) {
        if (isArray(classes)) {
            return classes.forEach(function (name) {
                return node.classList.add(name);
            }), node;
        }
        if (isObject(classes)) {
            for (var name in classes) {
                if (classes[name]) {
                    node.classList.add(name);
                } else {
                    node.classList.remove(name);
                }
            }
        }
        // if (isString(classes)) {
        node.className = classes;
        // }
        return node;
    };
    var setData = function setData(node, data) {
        var value;
        for (var datum in data) {
            value = data[datum];
            if (value || "" === value || 0 === value) {
                setDatum(node, datum, value);
            } else {
                letDatum(node, datum);
            }
        }
        return node;
    };
    var setDatum = function setDatum(node, datum, value) {
        if (isArray(value) || isObject(value)) {
            value = toJSON(value);
        }
        return setAttribute(node, 'data-' + datum, value);
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
    var setNext = function setNext(current, node) {
        return getParent(current).insertBefore(node, getNext(current, true)), node;
    };
    var setPrev = function setPrev(current, node) {
        return getParent(current).insertBefore(node, current), node;
    };
    var setStyle = function setStyle(node, style, value) {
        if (isNumber(value)) {
            value += 'px';
        }
        return node.style[toCaseCamel(style)] = fromValue(value), node;
    };
    var setStyles = function setStyles(node, styles) {
        var value;
        for (var style in styles) {
            value = styles[style];
            if (value || "" === value || 0 === value) {
                setStyle(node, style, value);
            } else {
                letStyle(node, style);
            }
        }
        return node;
    };
    var setText = function setText(node, content, trim) {
        if (trim === void 0) {
            trim = true;
        }
        if (null === content) {
            return node;
        }
        var state = 'textContent';
        return hasState(node, state) && (node[state] = trim ? content.trim() : content), node;
    };
    var toggleClass = function toggleClass(node, name, force) {
        return node.classList.toggle(name, force), node;
    };
    var theHistory = W.history;
    var theLocation = W.location;
    var event = function event(name, options, cache) {
        return events$1[name] = new Event(name, options);
    };
    var events$1 = {};
    var fireEvent = function fireEvent(name, node, options, cache) {
        node.dispatchEvent(event(name, options));
    };
    var offEvent = function offEvent(name, node, then) {
        node.removeEventListener(name, then);
    };
    var offEventDefault = function offEventDefault(e) {
        return e && e.preventDefault();
    };
    var offEventPropagation = function offEventPropagation(e) {
        return e && e.stopPropagation();
    };
    var offEvents = function offEvents(names, node, then) {
        names.forEach(function (name) {
            return offEvent(name, node, then);
        });
    };
    var onEvent = function onEvent(name, node, then, options) {
        if (options === void 0) {
            options = false;
        }
        node.addEventListener(name, then, options);
    };
    var onEvents = function onEvents(names, node, then, options) {
        if (options === void 0) {
            options = false;
        }
        names.forEach(function (name) {
            return onEvent(name, node, then, options);
        });
    };
    var targets$8 = ':scope>:where([tabindex]):not([tabindex="-1"]):not(.not\\:active)';

    function fireFocus$8(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onEventOnly$a(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange$c(init) {
        var sources = getElements('.lot\\:bar[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var items = getElements(targets$8, source);
            items.forEach(function (item) {
                onEventOnly$a('keydown', item, onKeyDownBarItem);
            });
            onEventOnly$a('keydown', source, onKeyDownBar);
        });
        1 === init && W._.on('change', onChange$c);
    }

    function onKeyDownBar(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            stop;
        if (t !== e.target) {
            return;
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            var any;
            if ('ArrowLeft' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$8, t));
                fireFocus$8(any.pop());
                stop = true;
            } else if ('ArrowRight' === key || 'Home' === key) {
                fireFocus$8(getElement(targets$8, t));
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownBarItem(e) {
        var t = this,
            key = e.key,
            any,
            next,
            prev,
            stop;
        if (t !== e.target) {
            return;
        }
        next = getNext(t);
        while (next && hasClass(next, 'not:active')) {
            next = getNext(next);
        }
        prev = getPrev(t);
        while (prev && hasClass(prev, 'not:active')) {
            prev = getPrev(prev);
        }
        if ('ArrowLeft' === key) {
            fireFocus$8(prev);
            stop = true;
        } else if ('ArrowRight' === key) {
            fireFocus$8(next);
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets$8, getParent(t)));
            fireFocus$8(any.pop());
            stop = true;
        } else if ('Home' === key) {
            fireFocus$8(getElement(targets$8, getParent(t)));
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onEventOnly$9(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }
    var dialog = setElement('dialog'),
        dialogForm = setElement('form', "", {
            method: 'dialog'
        }),
        dialogTemplate = setElement('div');
    setChildLast(B, dialog);
    setChildLast(dialog, dialogForm);

    function onDialogCancel(e) {
        var t = this;
        offEvent('cancel', t, onDialogCancel);
        offEvent('close', t, onDialogClose);
        offEvent('submit', t, onDialogSubmit);
        t.x(toValue(t.returnValue));
        isFunction(t.c) && t.c.apply(t, [t.open]);
    }

    function onDialogClose(e) {
        var t = this;
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
        var node;
        while (node = content.shift()) {
            setChildLast(dialogForm, node);
        }
        dialog.showModal();
        dialog.returnValue = null;
        isFunction(then) && then.apply(dialog, [dialog.open]);
        var target = getElement('[autofocus]', dialogForm);
        if (target) {
            isFunction(target.focus) && target.focus();
            isFunction(target.select) && target.select(); // `<input>`
        }
        return new Promise(function (yay, nay) {
            dialog.c = then; // `c` for call-back
            dialog.v = yay; // `v` for check-mark
            dialog.x = nay; // `x` for cross-mark
            onEventOnly$9('cancel', dialog, onDialogCancel);
            onEventOnly$9('close', dialog, onDialogClose);
            onEventOnly$9('submit', dialog, onDialogSubmit);
        });
    }

    function onDialogTaskClick() {
        var t = this,
            dialog = getParent(t.form);
        dialog.returnValue = t.value;
        fireEvent('reset' === t.type ? 'cancel' : 'close', dialog);
        dialog.open && dialog.close();
    }

    function onDialogTaskKeyDown(e) {
        var key = e.key,
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
    var dialogAlertDescription = setElement('p'),
        dialogAlertTasks = setElement('p', "", {
            'role': 'group'
        }),
        dialogAlertTaskOkay = setElement('button', 'OK', {
            'class': 'button',
            'type': 'submit',
            'value': 'true'
        });
    onEventOnly$9('keydown', dialogAlertTaskOkay, onDialogTaskKeyDown);
    onEventOnly$9('click', dialogAlertTaskOkay, onDialogTaskClick);
    setChildLast(dialogAlertTasks, dialogAlertTaskOkay);
    setDialog.alert = function (description) {
        setHTML(dialogAlertDescription, description);
        return setDialog([dialogAlertDescription, dialogAlertTasks]);
    };
    var dialogConfirmDescription = setElement('p'),
        dialogConfirmTasks = setElement('p', "", {
            'role': 'group'
        }),
        dialogConfirmTaskOkay = setElement('button', 'OK', {
            'class': 'button',
            'type': 'submit',
            'value': 'true'
        }),
        dialogConfirmTaskCancel = setElement('button', 'Cancel', {
            'autofocus': true,
            'class': 'button',
            'type': 'reset',
            'value': 'false'
        });
    onEventOnly$9('click', dialogConfirmTaskCancel, onDialogTaskClick);
    onEventOnly$9('click', dialogConfirmTaskOkay, onDialogTaskClick);
    onEventOnly$9('keydown', dialogConfirmTaskCancel, onDialogTaskKeyDown);
    onEventOnly$9('keydown', dialogConfirmTaskOkay, onDialogTaskKeyDown);
    setChildLast(dialogConfirmTasks, dialogConfirmTaskOkay);
    setChildLast(dialogConfirmTasks, dialogConfirmTaskCancel);
    setDialog.confirm = function (description) {
        setHTML(dialogConfirmDescription, description);
        return setDialog([dialogConfirmDescription, dialogConfirmTasks]);
    };
    var dialogPromptKey = setElement('p'),
        dialogPromptValue = setElement('input', false, {
            'autofocus': true,
            'class': 'input',
            'type': 'text'
        }),
        dialogPromptValueP = setElement('p'),
        dialogPromptTasks = setElement('p', "", {
            'role': 'group'
        }),
        dialogPromptTaskOkay = setElement('button', 'OK', {
            'class': 'button',
            'type': 'submit',
            'value': ""
        }),
        dialogPromptTaskCancel = setElement('button', 'Cancel', {
            'class': 'button',
            'type': 'reset',
            'value': 'false'
        });

    function onDialogPromptValueInput() {
        dialogPromptTaskOkay.value = this.value;
    }
    onEventOnly$9('click', dialogPromptTaskCancel, onDialogTaskClick);
    onEventOnly$9('click', dialogPromptTaskOkay, onDialogTaskClick);
    onEventOnly$9('input', dialogPromptValue, onDialogPromptValueInput);
    onEventOnly$9('keydown', dialogPromptTaskCancel, onDialogTaskKeyDown);
    onEventOnly$9('keydown', dialogPromptTaskOkay, onDialogTaskKeyDown);
    onEventOnly$9('keyup', dialogPromptValue, onDialogPromptValueInput);
    setChildLast(dialogPromptTasks, dialogPromptTaskOkay);
    setChildLast(dialogPromptTasks, dialogPromptTaskCancel);
    setChildLast(dialogPromptValueP, dialogPromptValue);
    setDialog.prompt = function (key, value) {
        setHTML(dialogPromptKey, key);
        dialogPromptValue.value = dialogPromptTaskOkay.value = value;
        return setDialog([dialogPromptKey, dialogPromptValueP, dialogPromptTasks]);
    };

    function Dialog(init) {
        (W._.dialog = setDialog);
    }
    var debounce = function debounce(then, time) {
        var timer;
        return function () {
            var _arguments = arguments,
                _this = this;
            timer && clearTimeout(timer);
            timer = setTimeout(function () {
                return then.apply(_this, _arguments);
            }, time);
        };
    };
    var delay = function delay(then, time) {
        return function () {
            var _arguments2 = arguments,
                _this2 = this;
            setTimeout(function () {
                return then.apply(_this2, _arguments2);
            }, time);
        };
    };
    var getOffset = function getOffset(node) {
        return [node.offsetLeft, node.offsetTop];
    };
    var getRect = function getRect(node) {
        var h, rect, w, x, y, X, Y;
        if (isWindow(node)) {
            x = node.pageXOffset || R.scrollLeft || B.scrollLeft;
            y = node.pageYOffset || R.scrollTop || B.scrollTop;
            w = node.innerWidth;
            h = node.innerHeight;
        } else {
            rect = node.getBoundingClientRect();
            x = rect.left;
            y = rect.top;
            w = rect.width;
            h = rect.height;
            X = rect.right;
            Y = rect.bottom;
        }
        return [x, y, w, h, X, Y];
    };
    var getSize = function getSize(node) {
        return isWindow(node) ? [node.innerWidth, node.innerHeight] : [node.offsetWidth, node.offsetHeight];
    };
    var getScroll = function getScroll(node) {
        return [node.scrollLeft, node.scrollTop];
    };
    var setScroll = function setScroll(node, data) {
        node.scrollLeft = data[0];
        node.scrollTop = data[1];
        return node;
    };

    function hook($) {
        var hooks = {};

        function fire(name, data) {
            if (!isSet(hooks[name])) {
                return $;
            }
            hooks[name].forEach(function (then) {
                return then.apply($, data);
            });
            return $;
        }

        function off(name, then) {
            if (!isSet(name)) {
                return hooks = {}, $;
            }
            if (isSet(hooks[name])) {
                if (isSet(then)) {
                    for (var i = 0, _j = hooks[name].length; i < _j; ++i) {
                        if (then === hooks[name][i]) {
                            hooks[name].splice(i, 1);
                            break;
                        }
                    }
                    // Clean-up empty hook(s)
                    if (0 === j) {
                        delete hooks[name];
                    }
                } else {
                    delete hooks[name];
                }
            }
            return $;
        }

        function on(name, then) {
            if (!isSet(hooks[name])) {
                hooks[name] = [];
            }
            if (isSet(then)) {
                hooks[name].push(then);
            }
            return $;
        }
        $.hooks = hooks;
        $.fire = fire;
        $.off = off;
        $.on = on;
        return $;
    }
    var name$3 = 'OP',
        PROP_INDEX = 'i',
        PROP_SOURCE = '$',
        PROP_VALUE = 'v';
    var KEY_ARROW_DOWN = 'ArrowDown';
    var KEY_ARROW_LEFT$1 = 'ArrowLeft';
    var KEY_ARROW_RIGHT$1 = 'ArrowRight';
    var KEY_ARROW_UP = 'ArrowUp';
    var KEY_END$1 = 'End';
    var KEY_ENTER$1 = 'Enter';
    var KEY_ESCAPE = 'Escape';
    var KEY_START = 'Home';
    var KEY_TAB$1 = 'Tab';
    var ZERO_WIDTH_SPACE = "\u200C";

    function selectElementContents(node) {
        var range = D.createRange();
        range.selectNodeContents(node);
        var selection = W.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }

    function OP(source, state) {
        if (state === void 0) {
            state = {};
        }
        var $ = this;
        if (!source) {
            return $;
        }
        // Already instantiated, skip!
        if (source[name$3]) {
            return source[name$3];
        }
        // Return new instance if `OP` was called without the `new` operator
        if (!isInstance($, OP)) {
            return new OP(source, state);
        }
        var _hook = hook($),
            fire = _hook.fire;
        _hook.hooks;
        $.state = state = fromStates({}, OP.state, state);
        $.options = {};
        $.source = source;
        // Store current instance to `OP.instances`
        OP.instances[source.id || source.name || toObjectCount(OP.instances)] = $;
        // Mark current DOM as active option picker to prevent duplicate instance
        source[name$3] = $;

        function getLot() {
            return [toValue(getValue()), $.options];
        }

        function getValue() {
            if (selectBoxMultiple) {
                var values = [];
                for (var i = 0, _j = toCount(selectBoxOptions); i < _j; ++i) {
                    if (getOptionSelected(selectBoxOptions[i])) {
                        values.push(getOptionValue(selectBoxOptions[i]));
                    }
                }
                return values;
            }
            var value = source.value;
            return "" !== value ? value : null;
        }

        function getOptionValue(selectBoxOption) {
            var value = selectBoxOption.value || getText(selectBoxOption);
            return "" !== value ? value : null;
        }

        function getOptionSelected(selectBoxOption) {
            return hasAttribute(selectBoxOption, 'selected');
        }

        function getOptionFakeSelected(selectBoxFakeOption) {
            return hasClass(selectBoxFakeOption, classNameOptionM + 'selected');
        }

        function letOptionSelected(selectBoxOption) {
            letAttribute(selectBoxOption, 'selected');
            selectBoxOption.selected = false;
        }

        function letOptionFakeSelected(selectBoxFakeOption) {
            letClass(selectBoxFakeOption, classNameOptionM + 'selected');
        }

        function setOptionSelected(selectBoxOption) {
            setAttribute(selectBoxOption, 'selected', true);
            selectBoxOption.selected = true;
        }

        function setOptionFakeSelected(selectBoxFakeOption) {
            setClass(selectBoxFakeOption, classNameOptionM + 'selected');
        }

        function setLabelContent(content) {
            content = content || ZERO_WIDTH_SPACE;
            selectBoxFakeLabel.title = content.replace(/<.*?>/g, "");
            setHTML(selectBoxFakeLabel, content);
        }

        function setValue(value) {
            if (selectBoxMultiple) {
                var values = toArray(_value),
                    _value;
                for (var i = 0, _j2 = toCount(selectBoxOptions); i < _j2; ++i) {
                    _value = getOptionValue(selectBoxOptions[i]);
                    if (values.includes(toValue(_value))) {
                        setOptionSelected(selectBoxOptions[i]);
                    } else {
                        letOptionSelected(selectBoxOptions[i]);
                    }
                }
            } else {
                source.value = value;
            }
        }
        var classNameB = state['class'],
            classNameE = classNameB + '__',
            classNameM = classNameB + '--',
            classNameInputB = classNameE + 'input',
            classNameOptionB = classNameE + 'option',
            classNameOptionM = classNameOptionB + '--',
            classNameOptionsB = classNameE + 'options',
            classNameValueB = classNameE + 'value',
            classNameValuesB = classNameE + 'values',
            selectBox = setElement(source, {
                'class': classNameE + 'source',
                'tabindex': -1
            }),
            selectBoxFakeInput = 'input' === getName(selectBox) ? setElement('span', "", {
                'class': classNameInputB
            }) : null,
            selectBoxPlaceholder = selectBoxFakeInput ? source.placeholder : "",
            selectBoxFakeInputPlaceholder = setElement('span', selectBoxPlaceholder),
            selectBoxFakeInputValue = setElement('span', "", {
                'contenteditable': "",
                'tabindex': 0
            }),
            selectBoxIsDisabled = function selectBoxIsDisabled() {
                return selectBox.disabled;
            },
            selectBoxItems = getChildren(selectBox),
            selectBoxMultiple = selectBox.multiple,
            selectBoxOptionIndex = 0,
            selectBoxOptions = selectBox.options,
            selectBoxParent = state.parent || D,
            selectBoxSize = 'input' === getName(selectBox) ? 0 : selectBox.size,
            selectBoxTitle = selectBox.title,
            selectBoxValue = getValue(),
            selectBoxFake = setElement('div', {
                'class': classNameB,
                'tabindex': selectBoxFakeInput || selectBoxIsDisabled() ? false : 0,
                'title': selectBoxTitle
            }),
            selectBoxFakeLabel = setElement('div', ZERO_WIDTH_SPACE, {
                'class': classNameValuesB
            }),
            selectBoxList = selectBox.list,
            selectBoxFakeBorderBottomWidth = 0,
            selectBoxFakeBorderTopWidth = 0,
            selectBoxFakeDropDown = setElement('div', {
                'class': classNameOptionsB,
                'tabindex': -1
            }),
            selectBoxFakeOptions = [],
            _keyIsCtrl = false,
            _keyIsShift = false;
        if (selectBoxMultiple && !selectBoxSize) {
            selectBox.size = selectBoxSize = state.size;
        }
        if (selectBoxFakeInput && selectBoxList) {
            selectBoxItems = getChildren(selectBoxList);
            selectBoxOptions = selectBoxList.options;
            selectBoxSize = null;
            if (selectBoxValue) {
                setHTML(selectBoxFakeInputPlaceholder, ZERO_WIDTH_SPACE);
                setText(selectBoxFakeInputValue, selectBoxValue);
            }
        }
        if (selectBoxFakeInput) {
            setChildLast(selectBoxFakeInput, selectBoxFakeInputValue);
            setChildLast(selectBoxFakeInput, selectBoxFakeInputPlaceholder);
        }
        setChildLast(selectBoxFake, selectBoxFakeInput || selectBoxFakeLabel);
        setNext(selectBox, selectBoxFake);

        function doBlur() {
            letClass(selectBoxFake, classNameM + 'focus');
            fire('blur', getLot());
        }

        function doFocus() {
            setClass(selectBoxFake, classNameM + 'focus');
            fire('focus', getLot());
        }

        function doEnter() {
            setClass(selectBoxFake, classNameM + 'open');
            fire('enter', getLot());
        }

        function doExit() {
            if (selectBoxMultiple || selectBoxSize) {
                return;
            }
            letClass(selectBoxFake, classNameM + 'open');
            fire('exit', getLot());
        }

        function doFit() {
            selectBoxFakeBorderBottomWidth = toNumber(getStyle(selectBoxFake, 'border-bottom-width'));
            selectBoxFakeBorderTopWidth = toNumber(getStyle(selectBoxFake, 'border-top-width'));
            setSelectBoxFakeOptionsPosition(selectBoxFake);
        }

        function doToggle(force) {
            toggleClass(selectBoxFake, classNameM + 'open', force);
            var isOpen = isEnter();
            fire(isOpen ? 'enter' : 'exit', getLot());
            return isOpen;
        }

        function doValue(content, index, value, classNames) {
            return setElement('span', content, {
                'class': classNameValueB + ' ' + classNames,
                'data-index': index,
                'data-value': value
            }).outerHTML;
        }

        function isEnter() {
            return hasClass(selectBoxFake, classNameM + 'open');
        }

        function onSelectBoxFocus() {
            (selectBoxFakeInput ? selectBoxFakeInputValue : selectBoxFake).focus();
        }

        function onSelectBoxFakeOptionClick(e) {
            if (!selectBoxOptions || selectBoxIsDisabled()) {
                return;
            }
            var selectBoxFakeLabelContent = [],
                content,
                index,
                value,
                selectBoxFakeOption = this,
                selectBoxOption = selectBoxFakeOption[PROP_SOURCE],
                selectBoxValuePrevious = selectBoxValue;
            selectBoxOptionIndex = selectBoxFakeOption[PROP_INDEX];
            selectBoxValue = selectBoxFakeOption[PROP_VALUE];
            e && e.isTrusted && onSelectBoxFocus();
            offEventDefault(e);
            if (selectBoxMultiple && (_keyIsCtrl || _keyIsShift)) {
                if (getOptionFakeSelected(selectBoxFakeOption)) {
                    letOptionSelected(selectBoxOption);
                    letOptionFakeSelected(selectBoxFakeOption);
                } else {
                    setOptionSelected(selectBoxOption);
                    setOptionFakeSelected(selectBoxFakeOption);
                }
                for (var i = 0, _j3 = toCount(selectBoxOptions), v; i < _j3; ++i) {
                    if (getOptionSelected(selectBoxOptions[i])) {
                        content = getText(v = selectBoxFakeOptions[i]);
                        index = v[PROP_INDEX];
                        value = v[PROP_VALUE];
                        selectBoxFakeLabelContent.push(doValue(content, index, value, getClasses(v, false)));
                    }
                }
                setLabelContent(selectBoxFakeLabelContent.join('<span>' + state.join + '</span>'));
                fire('change', getLot());
                return;
            }
            content = getText(selectBoxFakeOption);
            index = selectBoxFakeOption[PROP_INDEX];
            value = selectBoxFakeOption[PROP_VALUE];
            if (content && selectBoxFakeInput) {
                setHTML(selectBoxFakeInputPlaceholder, ZERO_WIDTH_SPACE);
                setText(selectBoxFakeInputValue, content);
            }
            setLabelContent(doValue(content, index, value, getClasses(selectBoxFakeOption, false)));
            if (selectBoxFakeInput) {
                selectElementContents(selectBoxFakeInputValue), setValue(content);
            }
            selectBoxFakeOptions.forEach(function (selectBoxFakeOption) {
                if (selectBoxValue === selectBoxFakeOption[PROP_VALUE]) {
                    setOptionSelected(selectBoxFakeOption[PROP_SOURCE]);
                    setOptionFakeSelected(selectBoxFakeOption);
                } else {
                    letOptionSelected(selectBoxFakeOption[PROP_SOURCE]);
                    letOptionFakeSelected(selectBoxFakeOption);
                }
            });
            if (selectBoxValue !== selectBoxValuePrevious) {
                fire('change', getLot());
            }
        }

        function onSelectBoxFakeBlur(e) {
            doBlur();
        }

        function onSelectBoxFakeClick(e) {
            if (selectBoxIsDisabled()) {
                return;
            }
            selectBoxOptionIndex = selectBox.selectedIndex;
            if (selectBoxSize) {
                return doEnter();
            }
            if (selectBoxFakeInput) {
                selectBoxFakeInputValue.focus();
            } else {
                doToggle() && doFit();
            }
        }

        function onSelectBoxFakeFocus(e) {
            selectBoxOptionIndex = selectBox.selectedIndex;
            doFocus();
        }

        function onSelectBoxFakeKeyDown(e) {
            if (!selectBoxOptions) {
                return;
            }
            _keyIsCtrl = e.ctrlKey;
            _keyIsShift = e.shiftKey;
            var key = e.key,
                selectBoxOptionIndexCurrent = selectBoxOptionIndex,
                selectBoxFakeOption = selectBoxFakeOptions[selectBoxOptionIndexCurrent],
                selectBoxFakeOptionIsDisabled = function selectBoxFakeOptionIsDisabled(selectBoxFakeOption) {
                    return hasClass(selectBoxFakeOption, classNameOptionM + 'disabled');
                },
                doClick = function doClick(selectBoxFakeOption) {
                    return onSelectBoxFakeOptionClick.call(selectBoxFakeOption);
                },
                isOpen = isEnter(); // Cache the enter state
            if (KEY_ARROW_DOWN === key) {
                // Continue walking down until it finds an option that is not disabled and not hidden
                while (selectBoxFakeOption = selectBoxFakeOptions[++selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption) && !selectBoxFakeOption.hidden) {
                        break;
                    }
                }
                if (selectBoxFakeOption) {
                    doClick(selectBoxFakeOption), doToggle(isOpen);
                }
                offEventDefault(e);
            } else if (KEY_ARROW_UP === key) {
                // Continue walking up until it finds an option that is not disabled and not hidden
                while (selectBoxFakeOption = selectBoxFakeOptions[--selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption) && !selectBoxFakeOption.hidden) {
                        break;
                    }
                }
                if (selectBoxFakeOption) {
                    doClick(selectBoxFakeOption), doToggle(isOpen);
                }
                offEventDefault(e);
            } else if (KEY_END$1 === key) {
                // Start from the last option position + 1
                selectBoxOptionIndexCurrent = toCount(selectBoxOptions);
                // Continue walking up until it finds an option that is not disabled and not hidden
                while (selectBoxFakeOption = selectBoxFakeOptions[--selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption) && !selectBoxFakeOption.hidden) {
                        break;
                    }
                }
                selectBoxFakeOption && (doClick(selectBoxFakeOption), doToggle(isOpen));
                offEventDefault(e);
            } else if (KEY_ENTER$1 === key) {
                selectBoxFakeOption && doClick(selectBoxFakeOption);
                doToggle(), offEventDefault(e);
            } else if (KEY_ESCAPE === key) {
                !selectBoxSize && doExit();
                // offEventDefault(e);
            } else if (KEY_START === key) {
                // Start from the first option position - 1
                selectBoxOptionIndexCurrent = -1;
                // Continue walking up until it finds an option that is not disabled and not hidden
                while (selectBoxFakeOption = selectBoxFakeOptions[++selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption) && !selectBoxFakeOption.hidden) {
                        break;
                    }
                }
                selectBoxFakeOption && (doClick(selectBoxFakeOption), doToggle(isOpen));
                offEventDefault(e);
            } else if (KEY_TAB$1 === key) {
                !selectBoxFakeInput && selectBoxFakeOption && doClick(selectBoxFakeOption);
                !selectBoxSize && doExit();
                // offEventDefault(e);
            }
            isEnter() && !_keyIsCtrl && !_keyIsShift && doFit();
        }

        function onSelectBoxFakeKeyUp() {
            _keyIsCtrl = _keyIsShift = false;
        }

        function onSelectBoxFakeInputValueBlur() {
            var value = getText(selectBoxFakeInputValue);
            if (null !== value) {
                setValue(fromValue(value));
            }
            doBlur();
        }

        function onSelectBoxFakeInputValueFocus() {
            if (!selectBoxOptions) {
                return;
            }
            var t = this,
                value = getText(t),
                selectBoxOption,
                selectBoxFakeOption;
            selectBoxOptionIndex = -1; // `<input>` does not have `selectedIndex` property!
            selectElementContents(t);
            for (var i = 0, _j4 = toCount(selectBoxOptions); i < _j4; ++i) {
                selectBoxOption = selectBoxOptions[i];
                selectBoxFakeOption = selectBoxFakeOptions[i];
                if (value === getText(selectBoxOption)) {
                    selectBoxOptionIndex = i;
                    setOptionSelected(selectBoxOption);
                    setOptionFakeSelected(selectBoxFakeOption);
                } else {
                    letOptionSelected(selectBoxOption);
                    letOptionFakeSelected(selectBoxFakeOption);
                }
            }
            doFocus(), doToggle() && doFit();
        }
        var bounce = debounce(function (self, key, valuePrev) {
            var value = getText(self),
                first,
                selectBoxFakeOption;
            if (null === value) {
                setHTML(selectBoxFakeInputPlaceholder, selectBoxPlaceholder);
                selectBoxFakeDropDown.hidden = false;
                for (var i = 0, _j5 = toCount(selectBoxFakeOptions); i < _j5; ++i) {
                    setHTML(selectBoxFakeOption = selectBoxFakeOptions[i], getText(selectBoxFakeOption));
                    selectBoxFakeOption.hidden = false;
                }
            } else {
                setHTML(selectBoxFakeInputPlaceholder, ZERO_WIDTH_SPACE);
                if (valuePrev !== (value = toCaseLower(value)) && KEY_ARROW_DOWN !== key && KEY_ARROW_LEFT$1 !== key && KEY_ARROW_RIGHT$1 !== key && KEY_ARROW_UP !== key && KEY_ENTER$1 !== key) {
                    for (var _i = 0, _j6 = toCount(selectBoxFakeOptions), v; _i < _j6; ++_i) {
                        letOptionSelected((selectBoxFakeOption = selectBoxFakeOptions[_i])[PROP_SOURCE]);
                        letOptionFakeSelected(selectBoxFakeOption);
                        v = getText(selectBoxFakeOption);
                        if (v && toCaseLower(v).includes(value)) {
                            !first && (first = selectBoxFakeOption);
                            setHTML(selectBoxFakeOption, v.replace(new RegExp(value.replace(/[!$^*()+=[]{}|:<>,.?\/-]/g, '\\$&'), 'gi'), function ($0) {
                                return '<mark>' + $0 + '</mark>';
                            }));
                            selectBoxFakeOption.hidden = false;
                        } else {
                            setHTML(selectBoxFakeOption, v);
                            selectBoxFakeOption.hidden = true;
                        }
                    }
                    // Always select the first match, but do not update the value
                    if (first) {
                        selectBoxOptionIndex = first[PROP_INDEX];
                        setOptionSelected(first[PROP_SOURCE]);
                        setOptionFakeSelected(first);
                        selectBoxFakeDropDown.hidden = false;
                        // No match!
                    } else {
                        selectBoxFakeDropDown.hidden = true;
                    }
                    valuePrev = value;
                } else {
                    var marked = 0;
                    for (var _i2 = 0, _j7 = toCount(selectBoxFakeOptions), _v; _i2 < _j7; ++_i2) {
                        selectBoxFakeOption = selectBoxFakeOptions[_i2];
                        _v = getHTML(selectBoxFakeOption);
                        if (hasValue('</mark>', _v)) {
                            ++marked;
                        }
                    }
                    // Reset all filter(s) if there is only one or none option marked
                    if (marked <= 1) {
                        for (var _i3 = 0, _j8 = toCount(selectBoxFakeOptions), _v2; _i3 < _j8; ++_i3) {
                            selectBoxFakeOption = selectBoxFakeOptions[_i3];
                            _v2 = getText(selectBoxFakeOption);
                            setHTML(selectBoxFakeOption, _v2);
                            selectBoxFakeOption.hidden = false;
                        }
                    }
                }
            }
            if (KEY_ENTER$1 !== key && KEY_ESCAPE !== key && KEY_TAB$1 !== key) {
                doEnter(), doFit();
            }
        }, 1);

        function onSelectBoxFakeInputValueKeyDown(e) {
            var t = this,
                key = e.key;
            onSelectBoxFakeKeyDown.call(t, e), bounce(t, key, getText(t));
        }

        function onSelectBoxFakeInputValueKeyUp() {
            onSelectBoxFakeKeyUp();
        }
        var waitForPaste = delay(function (input, placeholder) {
            var value = getText(input);
            setHTML(placeholder, null !== value ? ZERO_WIDTH_SPACE : selectBoxPlaceholder);
            setText(input, value);
            selectElementContents(input);
        }, 1);

        function onSelectBoxFakeInputValuePaste() {
            waitForPaste(selectBoxFakeInputValue, selectBoxFakeInputPlaceholder);
        }

        function onSelectBoxParentClick(e) {
            var target = e.target;
            if (target !== selectBoxFake) {
                while (target = getParent(target)) {
                    if (selectBoxFake === target) {
                        break;
                    }
                }
            }
            selectBoxFake !== target && doExit();
        }

        function onSelectBoxWindow() {
            isEnter() && setSelectBoxFakeOptionsPosition(selectBoxFake, 1);
        }

        function setSelectBoxFakeOptions(selectBoxItem, parent) {
            if ('optgroup' === getName(selectBoxItem)) {
                var selectBoxFakeOptionGroup = setElement('span', {
                        'class': classNameOptionB + '-group' + (selectBoxItem.disabled ? ' ' + classNameOptionM + 'disabled' : "")
                    }),
                    _selectBoxItems = getChildren(selectBoxItem);
                selectBoxFakeOptionGroup.title = selectBoxItem.label;
                for (var i = 0, _j9 = toCount(_selectBoxItems); i < _j9; ++i) {
                    setSelectBoxFakeOptions(_selectBoxItems[i], selectBoxFakeOptionGroup);
                }
                setChildLast(parent, selectBoxFakeOptionGroup);
                return;
            }
            var selectBoxOptionValue = getAttribute(selectBoxItem, 'value', false),
                selectBoxOptionValueReal = selectBoxOptionValue,
                selectBoxOptionText = getText(selectBoxItem),
                selectBoxOptionTitle = selectBoxItem.title,
                selectBoxFakeOption = setElement('a', selectBoxOptionText, {
                    'class': classNameOptionB,
                    'title': selectBoxOptionTitle || selectBoxOptionText
                });
            selectBoxOptionValue = selectBoxOptionValue || selectBoxOptionText;
            selectBoxFakeOption[PROP_INDEX] = selectBoxOptionIndex;
            selectBoxFakeOption[PROP_SOURCE] = selectBoxItem;
            selectBoxFakeOption[PROP_VALUE] = selectBoxOptionValue;
            setData(selectBoxFakeOption, {
                index: selectBoxOptionIndex,
                value: selectBoxOptionValueReal
            });
            $.options[selectBoxOptionValue] = selectBoxOptionText;
            var selectBoxOptionIsDisabled = selectBoxItem.disabled;
            if (selectBoxOptionIsDisabled) {
                setClass(selectBoxFakeOption, classNameOptionM + 'disabled');
            } else {
                onEvent('click', selectBoxFakeOption, onSelectBoxFakeOptionClick);
            }
            setChildLast(parent, selectBoxFakeOption);
            selectBoxFakeOptions.push(selectBoxFakeOption);
            if ("" === selectBoxOptionValueReal) {
                selectBoxOptionValue = null;
            }
            if (isArray(selectBoxValue) && hasValue(selectBoxOptionValue, selectBoxValue) || selectBoxOptionValue === selectBoxValue) {
                setClass(selectBoxFakeOption, classNameOptionM + 'selected');
                setLabelContent(doValue(selectBoxOptionText, selectBoxOptionIndex, selectBoxOptionValueReal, getClasses(selectBoxFakeOption, false)));
                setOptionSelected(selectBoxItem);
            } else {
                letOptionSelected(selectBoxItem);
            }
            ++selectBoxOptionIndex;
        }

        function setSelectBoxFakeOptionsPosition(selectBoxFake, useEvent) {
            if (!selectBoxSize) {
                var _getRect = getRect(selectBoxFake),
                    left = _getRect[0],
                    top = _getRect[1],
                    width = _getRect[2],
                    height = _getRect[3],
                    heightWindow = getSize(W)[1],
                    heightMax = heightWindow - top - height;
                setStyles(selectBoxFakeDropDown, {
                    'bottom': "",
                    'left': left,
                    'max-height': heightMax,
                    'top': top + height - selectBoxFakeBorderTopWidth,
                    'width': width
                });
                if (heightMax < (heightWindow - height) / 2) {
                    heightMax = top;
                    setStyles(selectBoxFakeDropDown, {
                        'top': "",
                        'bottom': heightWindow - top - selectBoxFakeBorderBottomWidth,
                        'max-height': heightMax + selectBoxFakeBorderTopWidth
                    });
                    letClass(selectBoxFake, classNameM + 'down');
                    setClass(selectBoxFake, classNameM + 'up');
                } else {
                    letClass(selectBoxFake, classNameM + 'up');
                    setClass(selectBoxFake, classNameM + 'down');
                }
            }
            if (!useEvent) {
                var selectBoxFakeOption = selectBoxFakeOptions.find(function (selectBoxFakeOption) {
                    return hasClass(selectBoxFakeOption, classNameOptionM + 'selected');
                });
                if (selectBoxFakeOption) {
                    var _height = getSize(selectBoxFakeOption)[1],
                        heightParent = getSize(selectBoxFakeDropDown)[1],
                        _getOffset = getOffset(selectBoxFakeOption),
                        _left = _getOffset[0],
                        _top = _getOffset[1],
                        topScroll = getScroll(selectBoxFakeDropDown)[1];
                    if (_top < topScroll) {
                        setScroll(selectBoxFakeDropDown, [_left, _top]);
                    } else if (_top + _height - heightParent > topScroll) {
                        setScroll(selectBoxFakeDropDown, [_left, _top + _height - heightParent]);
                    }
                }
            }
            fire('fit', getLot());
        }
        onEvents(['resize', 'scroll'], W, onSelectBoxWindow);
        onEvent('click', selectBoxParent, onSelectBoxParentClick);
        onEvent('focus', selectBox, onSelectBoxFocus);
        onEvent('click', selectBoxFake, onSelectBoxFakeClick);
        if (selectBoxFakeInput) {
            onEvent('blur', selectBoxFakeInputValue, onSelectBoxFakeInputValueBlur);
            onEvent('focus', selectBoxFakeInputValue, onSelectBoxFakeInputValueFocus);
            onEvent('keydown', selectBoxFakeInputValue, onSelectBoxFakeInputValueKeyDown);
            onEvent('keyup', selectBoxFakeInputValue, onSelectBoxFakeInputValueKeyUp);
            onEvent('paste', selectBoxFakeInputValue, onSelectBoxFakeInputValuePaste);
        } else {
            onEvent('blur', selectBoxFake, onSelectBoxFakeBlur);
            onEvent('focus', selectBoxFake, onSelectBoxFakeFocus);
            onEvent('keydown', selectBoxFake, onSelectBoxFakeKeyDown);
            onEvent('keyup', selectBoxFake, onSelectBoxFakeKeyUp);
        }
        var j = toCount(selectBoxItems);
        if (j) {
            setChildLast(selectBoxFake, selectBoxFakeDropDown);
            for (var i = 0; i < j; ++i) {
                setSelectBoxFakeOptions(selectBoxItems[i], selectBoxFakeDropDown);
            }
            if (selectBoxSize) {
                var selectBoxFakeOption = selectBoxFakeOptions[0],
                    selectBoxFakeOptionSize = getSize(selectBoxFakeOption),
                    heightMax = selectBoxFakeOptionSize[1] * selectBoxSize;
                setStyle(selectBoxFakeDropDown, 'max-height', heightMax);
            }
        }
        if (selectBoxSize) {
            // Force `down` and `open` class
            setClass(selectBoxFake, classNameM + 'down');
            setClass(selectBoxFake, classNameM + 'open');
        }
        $.get = function (parseValue) {
            if (parseValue === void 0) {
                parseValue = true;
            }
            var value = getValue();
            return parseValue ? toValue(value) : value;
        };
        $.pop = function () {
            if (!source[name$3]) {
                return $; // Already ejected
            }
            delete source[name$3];
            offEvents(['resize', 'scroll'], W, onSelectBoxWindow);
            offEvent('click', selectBoxParent, onSelectBoxParentClick);
            offEvent('focus', selectBox, onSelectBoxFocus);
            letClass(selectBox, classNameE + 'source');
            offEvent('click', selectBoxFake, onSelectBoxFakeClick);
            if (selectBoxFakeInput) {
                offEvent('blur', selectBoxFakeInputValue, onSelectBoxFakeInputValueBlur);
                offEvent('focus', selectBoxFakeInputValue, onSelectBoxFakeInputValueFocus);
                offEvent('keydown', selectBoxFakeInputValue, onSelectBoxFakeInputValueKeyDown);
                offEvent('keyup', selectBoxFakeInputValue, onSelectBoxFakeInputValueKeyUp);
                offEvent('paste', selectBoxFakeInputValue, onSelectBoxFakeInputValuePaste);
            } else {
                offEvent('blur', selectBoxFake, onSelectBoxFakeBlur);
                offEvent('focus', selectBoxFake, onSelectBoxFakeFocus);
                offEvent('keydown', selectBoxFake, onSelectBoxFakeKeyDown);
                offEvent('keyup', selectBoxFake, onSelectBoxFakeKeyUp);
            }
            letText(selectBoxFake);
            letElement(selectBoxFake);
            return fire('pop', getLot());
        };
        $.set = function (value) {
            if (!selectBoxOptions) {
                return $;
            }
            setValue(fromValue(value));
            selectBoxFakeOptions.forEach(function (selectBoxFakeOption, index) {
                var selectBoxOption = selectBoxOptions[index];
                toggleClass(selectBoxFakeOption, classNameOptionM + 'selected', selectBoxOption && getOptionSelected(selectBoxOption));
            });
            fire('change', getLot());
            return $;
        };
        $.self = selectBoxFake;
        return $;
    }
    OP.instances = {};
    OP.state = {
        'class': 'option-picker',
        'join': ', ',
        'parent': null,
        'size': 5
    };
    OP.version = '1.3.10';

    function onChange$b(init) {
        // Destroy!
        var $;
        for (var key in OP.instances) {
            $ = OP.instances[key];
            $.pop();
            delete OP.instances[key];
        }
        var sources = getElements('.input[list]:not([type="hidden"]),.select');
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            letClass(source, 'input');
            letClass(source, 'select');
            var c = getClasses(source);
            var $ = new OP(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            setClasses($.self, c);
        });
        1 === init && W._.on('change', onChange$b);
    }
    W.OP = OP;
    var esc = function esc(pattern, extra) {
        if (extra === void 0) {
            extra = "";
        }
        return pattern.replace(toPattern('[' + extra + x.replace(/./g, '\\$&') + ']'), '\\$&');
    };
    var isPattern = function isPattern(pattern) {
        return isInstance(pattern, RegExp);
    };
    var toPattern = function toPattern(pattern, opt) {
        if (isPattern(pattern)) {
            return pattern;
        }
        // No need to escape `/` in the pattern string
        pattern = pattern.replace(/\//g, '\\/');
        return new RegExp(pattern, isSet(opt) ? opt : 'g');
    };
    var x = "!$^*()+=[]{}|:<>,.?/-";
    var name$2 = 'TP';
    var KEY_A = 'a';
    var KEY_ARROW_LEFT = 'ArrowLeft';
    var KEY_ARROW_RIGHT = 'ArrowRight';
    var KEY_BEGIN = 'Home';
    var KEY_DELETE_LEFT = 'Backspace';
    var KEY_DELETE_RIGHT = 'Delete';
    var KEY_END = 'End';
    var KEY_ENTER = 'Enter';
    var KEY_TAB = 'Tab';

    function TP(source, state) {
        if (state === void 0) {
            state = {};
        }
        var $ = this;
        if (!source) {
            return $;
        }
        // Already instantiated, skip!
        if (source[name$2]) {
            return source[name$2];
        }
        // Return new instance if `TP` was called without the `new` operator
        if (!isInstance($, TP)) {
            return new TP(source, state);
        }
        var sourceIsDisabled = function sourceIsDisabled() {
                return source.disabled;
            },
            sourceIsReadOnly = function sourceIsReadOnly() {
                return source.readOnly;
            },
            thePlaceholder = getAttribute(source, 'placeholder'),
            theTabIndex = getAttribute(source, 'tabindex');
        var _hook = hook($);
        _hook.hooks;
        var fire = _hook.fire;
        $.state = state = fromStates({}, TP.state, isString(state) ? {
            join: state
        } : state || {});
        $.source = source;
        // Store current instance to `TP.instances`
        TP.instances[source.id || source.name || toObjectCount(TP.instances)] = $;
        // Mark current DOM as active tag picker to prevent duplicate instance
        source[name$2] = $;
        var classNameB = state['class'],
            classNameE = classNameB + '__',
            classNameM = classNameB + '--',
            form = getParentForm(source),
            // Capture the closest `<form>` element
            self = setElement('div', {
                'class': classNameB,
                'tabindex': sourceIsDisabled() ? false : -1
            }),
            text = setElement('span', {
                'class': classNameE + 'tag ' + classNameE + 'input'
            }),
            textCopy = setElement('input', {
                'class': classNameE + 'copy',
                'tabindex': -1,
                'type': 'text'
            }),
            textInput = setElement('span', {
                'contenteditable': sourceIsDisabled() ? false : 'true',
                'spellcheck': 'false',
                'style': 'white-space:pre;'
            }),
            textInputHint = setElement('span'),
            textOutput = setElement('span', {
                'class': classNameE + 'tags'
            });
        var currentTagIndex = 0,
            currentTags = {};
        var _keyIsCtrl, _keyIsShift, _keyIsTab;

        function getCharBeforeCaret(container) {
            var range,
                selection = W.getSelection();
            if (selection.rangeCount > 0) {
                range = selection.getRangeAt(0).cloneRange();
                range.collapse(true);
                range.setStart(container, 0);
                return (range + "").slice(-1);
            }
        }

        function getCurrentTags() {
            return currentTags;
        }

        function getTag(tag, fireHooks) {
            var index = toArrayKey(tag, $.tags);
            fireHooks && fire('get.tag', [tag, index]);
            return isNumber(index) ? tag : null;
        }

        function setCurrentTags() {
            currentTags = {}; // Reset!
            var i,
                items = getChildren(textOutput),
                j = toCount(items) - 1; // Minus 1 to skip the tag editor element
            for (i = 0; i < j; ++i) {
                if (hasClass(items[i], classNameE + 'tag--selected')) {
                    currentTags[i] = items[i];
                }
            }
        }

        function setTag(tag, index) {
            if (isNumber(index)) {
                index = index < 0 ? 0 : index;
                $.tags.splice(index, 0, tag);
            } else {
                $.tags.push(tag);
            }
            source.value = $.tags.join(state.join);
        }

        function setTagElement(tag, index) {
            var element = setElement('span', {
                'class': classNameE + 'tag',
                'tabindex': sourceIsDisabled() || sourceIsReadOnly() ? false : 0,
                'title': tag
            });
            var x = setElement('a', {
                'class': classNameE + 'tag-x',
                'href': "",
                'tabindex': -1,
                'target': '_top'
            });
            onEvent('click', x, onClickTagX);
            setChildLast(element, x);
            onEvent('click', element, onClickTag);
            onEvents(['blur', 'focus'], element, onBlurFocusTag);
            if (hasParent(textOutput)) {
                if (isNumber(index) && $.tags[index]) {
                    setPrev(getChildren(textOutput, index), element);
                } else {
                    setPrev(text, element);
                }
            }
        }

        function setTags(values) {
            values = values ? values.split(state.join) : [];
            // Remove all tag(s) …
            if (hasParent(self)) {
                var theTagPrev, theTagPrevIndex, theTagPrevTitle;
                while (theTagPrev = getPrev(text)) {
                    letTagElement(theTagPrevTitle = theTagPrev.title);
                    if (!hasValue(theTagPrevTitle, values)) {
                        theTagPrevIndex = toArrayKey(theTagPrevTitle, $.tags);
                        fire('change', [theTagPrevTitle, theTagPrevIndex]);
                        fire('let.tag', [theTagPrevTitle, theTagPrevIndex]);
                    }
                }
            }
            $.tags = [];
            source.value = "";
            // … then add tag(s)
            for (var i = 0, theTagsMax = state.max, value; i < theTagsMax; ++i) {
                if (!values[i]) {
                    break;
                }
                if ("" !== (value = doValidTag(values[i]))) {
                    setTagElement(value), setTag(value);
                    fire('change', [value, i]);
                    fire('set.tag', [value, i]);
                }
            }
        }

        function letTag(tag) {
            var index = toArrayKey(tag, $.tags);
            if (isNumber(index) && index >= 0) {
                $.tags.splice(index, 1);
                source.value = $.tags.join(state.join);
            }
        }

        function letTagElement(tag) {
            var index = toArrayKey(tag, $.tags),
                element;
            if (isNumber(index) && index >= 0 && (element = getChildren(textOutput, index))) {
                offEvent('click', element, onClickTag);
                offEvents(['blur', 'focus'], element, onBlurFocusTag);
                var x = getChildFirst(element);
                if (x) {
                    offEvent('click', x, onClickTagX);
                    letElement(x);
                }
                letElement(element);
            }
        }

        function letTextCopy(selectTextInput) {
            letElement(textCopy);
            {
                setValue("", 1);
            }
        }

        function setTextCopy(selectTextCopy) {
            setChildLast(self, textCopy);
            textCopy.value = $.tags.join(state.join);
            {
                textCopy.focus();
                textCopy.select();
            }
        }

        function setValue(value, fireFocus) {
            setText(textInput, value);
            setText(textInputHint, value ? "" : thePlaceholder);
            if (fireFocus) {
                textInput.focus();
                // Move caret to the end!
                var range = D.createRange(),
                    selection = W.getSelection();
                range.selectNodeContents(textInput);
                range.collapse(false);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        }
        setValue("");

        function doBlurTags(exceptThisTag) {
            doToTags(exceptThisTag, function () {
                letClass(this, classNameE + 'tag--selected');
            });
        }

        function doFocusTags(exceptThisTag) {
            doToTags(exceptThisTag, function () {
                setClass(this, classNameE + 'tag--selected');
            });
        }

        function doInput() {
            if (sourceIsDisabled() || sourceIsReadOnly()) {
                return;
            }
            var tag = doValidTagChar(getText(textInput)).trim(),
                pattern = state.pattern;
            if (pattern && tag) {
                if (!toPattern(pattern, "").test(tag)) {
                    fire('not.tag', [tag, -1]);
                    setValue(tag, 1);
                    return;
                }
            }
            setValue("");
            if (tag = doValidTag(tag)) {
                if (!getTag(tag)) {
                    setTagElement(tag), setTag(tag);
                    var index = toCount($.tags);
                    fire('change', [tag, index]);
                    fire('set.tag', [tag, index]);
                } else {
                    fire('has.tag', [tag, toArrayKey(tag, $.tags)]);
                }
            }
        }

        function doSubmitTry() {
            onSubmitForm() && form && form.dispatchEvent(new Event('submit', {
                cancelable: true
            }));
        }

        function doToTags(exceptThisTag, then) {
            var i,
                items = getChildren(textOutput),
                j = toCount(items) - 1; // Minus 1 to skip the tag editor element
            for (i = 0; i < j; ++i) {
                if (exceptThisTag === items[i]) {
                    continue;
                }
                then.call(items[i], i);
            }
        }

        function doValidTag(v) {
            return doValidTagChar($.f(v)).trim();
        }

        function doValidTagChar(v) {
            v = v || "";
            state.escape.forEach(function (char) {
                v = v.split(char).join("");
            });
            return v;
        }

        function onBlurFocusTextCopy(e) {
            var type = e.type;
            if ('blur' === type) {
                doBlurTags();
                letClasses(self, [classNameM + 'focus', classNameM + 'focus-self']);
            } else {
                setClasses(self, [classNameM + 'focus', classNameM + 'focus-self']);
            }
        }

        function onBlurFocusTag(e) {
            if (sourceIsReadOnly()) {
                return;
            }
            currentTags = {}; // Reset!
            var t = this,
                type = e.type,
                tag = t.title,
                tags = $.tags,
                index = toArrayKey(tag, tags),
                classNameTagM = classNameE + 'tag--';
            if ('blur' === type) {
                if (!_keyIsCtrl && !_keyIsShift || _keyIsShift && _keyIsTab // Do not do multiple selection on Shift+Tab
                ) {
                    doBlurTags(t);
                    letClass(t, classNameTagM + 'selected');
                    letClasses(self, [classNameM + 'focus', classNameM + 'focus-tag']);
                }
            } else {
                setClass(t, classNameTagM + 'selected');
                setClasses(self, [classNameM + 'focus', classNameM + 'focus-tag']);
                currentTagIndex = index;
                currentTags[index] = t;
            }
            fire(type + '.tag', [tag, index]);
        }

        function onBlurFocusText(e) {
            var tags = $.tags,
                type = e.type,
                classNameTextM = classNameE + 'text--';
            letClass(self, classNameM + 'focus-tag');
            if ('blur' === type) {
                letClass(text, classNameTextM + 'focus');
                letClasses(self, [classNameM + 'focus', classNameM + 'focus-input']);
                doInput();
            } else {
                setClass(text, classNameTextM + 'focus');
                setClasses(self, [classNameM + 'focus', classNameM + 'focus-input']);
                doBlurTags(text);
            }
            fire(type, [tags, toCount(tags)]);
        }

        function onBlurFocusSelf(e) {
            var type = e.type;
            if ('blur' === type) {
                letClass(self, classNameM + 'focus');
            } else {
                setClass(self, classNameM + 'focus');
            }
        }

        function onClickSelf(e) {
            if (e && self === e.target) {
                textInput.focus();
            }
            var tags = $.tags;
            fire('click', [tags, toCount(tags)]);
        }

        function onClickTag() {
            var t = this,
                tag = t.title,
                tags = $.tags;
            fire('click.tag', [tag, toArrayKey(tag, tags)]);
        }

        function onClickTagX(e) {
            if (!sourceIsDisabled() && !sourceIsReadOnly()) {
                var t = this,
                    tag = getParent(t).title,
                    index = toArrayKey(tag, $.tags);
                letTagElement(tag), letTag(tag), setValue("", 1);
                fire('change', [tag, index]);
                fire('click.tag', [tag, index]);
                fire('let.tag', [tag, index]);
            }
            offEventDefault(e);
        }

        function onCopyCutPasteTextCopy(e) {
            var type = e.type;
            if ('copy' === type) {
                delay(function () {
                    return letTextCopy();
                }, 1)();
            } else if ('cut' === type) {
                !sourceIsReadOnly() && setTags("");
                delay(function () {
                    return letTextCopy();
                }, 1)();
            } else if ('paste' === type) {
                delay(function () {
                    !sourceIsReadOnly() && setTags(textCopy.value);
                    letTextCopy();
                }, 1)();
            }
            delay(function () {
                var tags = $.tags;
                fire(type, [tags, toCount(tags)]);
            }, 1)();
        }

        function onBlurSelf() {
            doBlurTags(), letClass(self, classNameM + 'focus-self');
        }

        function onFocusSource() {
            textInput.focus();
        }

        function onKeyDownSelf(e) {
            if (sourceIsDisabled() || sourceIsReadOnly()) {
                return;
            }
            $.tags;
            var key = e.key,
                keyIsCtrl = _keyIsCtrl = e.ctrlKey,
                keyIsShift = _keyIsShift = e.shiftKey,
                classNameTagM = classNameE + 'tag--';
            _keyIsTab = KEY_TAB === key;
            var theTag, theTagIndex, theTagNext, theTagPrev, theTagTitle, theTags;
            if (!keyIsCtrl) {
                // Remove tag(s) with `Backspace` or `Delete` key
                if (!keyIsShift && (KEY_DELETE_LEFT === key || KEY_DELETE_RIGHT === key)) {
                    setCurrentTags();
                    theTags = getCurrentTags();
                    var isBackspace = KEY_DELETE_LEFT === key;
                    for (theTagIndex in theTags) {
                        theTag = theTags[theTagIndex];
                        letTagElement(theTagTitle = theTag.title), letTag(theTagTitle);
                    }
                    currentTagIndex = +(toObjectKeys(theTags)[0] || 0);
                    if (theTag = getChildren(textOutput, isBackspace ? currentTagIndex - 1 : currentTagIndex)) {
                        if (text === theTag) {
                            setValue("", 1);
                        } else {
                            theTag.focus();
                        }
                    } else {
                        setValue("", 1);
                    }
                    offEventDefault(e);
                    return;
                }
                // Focus to the first tag
                if (KEY_BEGIN === key) {
                    if (theTag = getChildren(textOutput, 0)) {
                        theTag.focus(), offEventDefault(e);
                    }
                    return;
                }
                // Focus to the last tag
                if (KEY_END === key) {
                    if (theTag = getChildren(textOutput, toCount($.tags) - 1)) {
                        theTag.focus(), offEventDefault(e);
                        return;
                    }
                }
                // Focus to the previous tag
                if (KEY_ARROW_LEFT === key) {
                    if (theTag = getChildren(textOutput, currentTagIndex - 1)) {
                        var theTagWasFocus = hasClass(theTag, classNameTagM + 'selected');
                        theTag.focus(), offEventDefault(e);
                        if (keyIsShift) {
                            theTagNext = getNext(theTag);
                            if (theTagWasFocus) {
                                letClass(theTagNext, classNameTagM + 'selected');
                            }
                            return;
                        }
                        doBlurTags(theTag);
                        return;
                    }
                    if (!keyIsShift) {
                        doBlurTags(getChildren(textOutput, 0));
                        return;
                    }
                }
                // Focus to the next tag or to the tag editor
                if (KEY_ARROW_RIGHT === key) {
                    if (theTag = getChildren(textOutput, currentTagIndex + 1)) {
                        var _theTagWasFocus = hasClass(theTag, classNameTagM + 'selected');
                        text === theTag && !keyIsShift ? setValue("", 1) : theTag.focus(), offEventDefault(e);
                        if (keyIsShift) {
                            theTagPrev = getPrev(theTag);
                            if (_theTagWasFocus) {
                                letClass(theTagPrev, classNameTagM + 'selected');
                            }
                            return;
                        }
                        doBlurTags(theTag);
                        return;
                    }
                }
            }
            // Select all tag(s) with `Ctrl+A` key
            if (KEY_A === key) {
                setTextCopy();
                doFocusTags(), setCurrentTags(), offEventDefault(e);
            }
        }

        function onKeyDownText(e) {
            offEventPropagation(e);
            if (sourceIsReadOnly() && KEY_TAB !== e.key) {
                offEventDefault(e);
            }
            var escapes = state.escape,
                theTag,
                theTagLast = getPrev(text),
                theTagsCount = toCount($.tags),
                theTagsMax = state.max,
                theValue = getText(textInput) || "",
                key = e.key,
                keyIsCtrl = _keyIsCtrl = e.ctrlKey,
                keyIsEnter = KEY_ENTER === key;
            _keyIsShift = e.shiftKey;
            var keyIsTab = _keyIsTab = KEY_TAB === key;
            if (keyIsEnter) {
                key = '\n';
            }
            if (keyIsTab) {
                key = '\t';
            }
            delay(function () {
                theValue = getText(textInput) || "";
                setText(textInputHint, theValue ? "" : thePlaceholder);
                // Try to add support for browser(s) without `KeyboardEvent.prototype.key` feature
                if (hasValue(getCharBeforeCaret(textInput), escapes)) {
                    if (theTagsCount < theTagsMax) {
                        // Add the tag name found in the tag editor
                        doInput();
                    } else {
                        setValue("");
                        fire('max.tags', [theTagsMax]);
                    }
                    offEventDefault(e);
                }
            }, 1)();
            // Focus to the first tag
            if ("" === theValue && KEY_BEGIN === key) {
                if (theTag = getChildren(textOutput, 0)) {
                    theTag.focus(), offEventDefault(e);
                    return;
                }
            }
            // Focus to the last tag
            if ("" === theValue && KEY_END === key) {
                if (theTag = getChildren(textOutput, toCount($.tags) - 1)) {
                    theTag.focus(), offEventDefault(e);
                    return;
                }
            }
            // Select all tag(s) with `Ctrl+A` key
            if (keyIsCtrl && "" === theValue && KEY_A === key) {
                setTextCopy();
                doFocusTags(), setCurrentTags(), offEventDefault(e);
                return;
            }
            if (hasValue(key, escapes)) {
                if (theTagsCount < theTagsMax) {
                    // Add the tag name found in the tag editor
                    doInput();
                } else {
                    setValue("");
                    fire('max.tags', [theTagsMax]);
                }
                offEventDefault(e);
                return;
            }
            // Skip `Tab` key
            if (keyIsTab) {
                return; // :)
            }
            // Submit the closest `<form>` element with `Enter` key
            if (!keyIsCtrl && keyIsEnter) {
                doSubmitTry(), offEventDefault(e);
                return;
            }
            if (theTagLast && "" === theValue && !sourceIsReadOnly()) {
                if (KEY_DELETE_LEFT === key) {
                    theTag = $.tags[theTagsCount - 1];
                    letTagElement(theTag), letTag(theTag);
                    fire('change', [theTag, theTagsCount - 1]);
                    fire('let.tag', [theTag, theTagsCount - 1]);
                    offEventDefault(e);
                    return;
                }
                if (KEY_ARROW_LEFT === key) {
                    theTagLast.focus(); // Focus to the last tag
                    return;
                }
            }
        }

        function onKeyUpSelf() {
            _keyIsCtrl = _keyIsShift = false;
        }

        function onPasteText() {
            delay(function () {
                if (!sourceIsDisabled() && !sourceIsReadOnly()) {
                    getText(textInput).split(state.join).forEach(function (v) {
                        if (!hasValue(v, $.tags)) {
                            setTagElement(v), setTag(v);
                        }
                    });
                }
                setValue("");
            }, 1)();
        }

        function onSubmitForm(e) {
            if (sourceIsDisabled()) {
                return;
            }
            var theTagsMin = state.min;
            doInput(); // Force to add the tag name found in the tag editor
            if (theTagsMin > 0 && toCount($.tags) < theTagsMin) {
                setValue("", 1);
                fire('min.tags', [theTagsMin]);
                offEventDefault(e);
                return;
            }
            // Do normal `submit` event
            return 1;
        }
        setChildLast(self, textOutput);
        setChildLast(text, textInput);
        setChildLast(text, textInputHint);
        setChildLast(textOutput, text);
        setClass(source, classNameE + 'source');
        setNext(source, self);
        setElement(source, {
            'tabindex': -1
        });
        onEvent('blur', self, onBlurSelf);
        onEvent('click', self, onClickSelf);
        onEvent('focus', source, onFocusSource);
        onEvent('keydown', self, onKeyDownSelf);
        onEvent('keydown', textInput, onKeyDownText);
        onEvent('keyup', self, onKeyUpSelf);
        onEvent('paste', textInput, onPasteText);
        onEvents(['blur', 'focus'], self, onBlurFocusSelf);
        onEvents(['blur', 'focus'], textCopy, onBlurFocusTextCopy);
        onEvents(['blur', 'focus'], textInput, onBlurFocusText);
        onEvents(['copy', 'cut', 'paste'], textCopy, onCopyCutPasteTextCopy);
        form && onEvent('submit', form, onSubmitForm);
        $.blur = function () {
            return !sourceIsDisabled() && textInput.blur(), $;
        };
        $.click = function () {
            return self.click(), onClickSelf(), $;
        };
        // Default filter for the tag name
        $.f = function (v) {
            return toCaseLower(v || "").replace(/[^ a-z\d-]/g, "").trim();
        };
        $.focus = function () {
            if (!sourceIsDisabled()) {
                setValue(getText(textInput), 1);
            }
            return $;
        };
        $.get = function (tag) {
            return sourceIsDisabled() ? null : getTag(tag, 1);
        };
        $.input = textInput;
        $.let = function (tag) {
            if (!sourceIsDisabled() && !sourceIsReadOnly()) {
                var theTagsMin = state.min;
                if (!tag) {
                    setTags("");
                } else if (isArray(tag)) {
                    tag.forEach(function (v) {
                        if (theTagsMin > 0 && toCount($.tags) < theTagsMin) {
                            fire('min.tags', [theTagsMin]);
                            return $;
                        }
                        letTagElement(v), letTag(v);
                    });
                } else {
                    if (theTagsMin > 0 && toCount($.tags) < theTagsMin) {
                        fire('min.tags', [theTagsMin]);
                        return $;
                    }
                    letTagElement(tag), letTag(tag);
                }
            }
            return $;
        };
        $.pop = function () {
            if (!source[name$2]) {
                return $; // Already ejected!
            }
            delete source[name$2];
            var tags = $.tags;
            letClass(source, classNameE + 'source');
            offEvent('blur', self, onBlurSelf);
            offEvent('click', self, onClickSelf);
            offEvent('focus', source, onFocusSource);
            offEvent('keydown', self, onKeyDownSelf);
            offEvent('keydown', textInput, onKeyDownText);
            offEvent('keyup', self, onKeyUpSelf);
            offEvent('paste', textInput, onPasteText);
            offEvents(['blur', 'focus'], self, onBlurFocusSelf);
            offEvents(['blur', 'focus'], textCopy, onBlurFocusTextCopy);
            offEvents(['blur', 'focus'], textInput, onBlurFocusText);
            offEvents(['copy', 'cut', 'paste'], textCopy, onCopyCutPasteTextCopy);
            form && offEvent('submit', form, onSubmitForm);
            tags.forEach(letTagElement);
            setElement(source, {
                'tabindex': theTabIndex
            });
            return letElement(self), fire('pop', [tags]);
        };
        $.self = self;
        $.set = function (tag, index) {
            if (!tag) {
                return $;
            }
            if (!sourceIsDisabled() && !sourceIsReadOnly()) {
                if (isArray(tag)) {
                    setTags(tag.join(state.join));
                } else {
                    var tags = $.tags,
                        theTagsMax = state.max;
                    if (!getTag(tag)) {
                        if (toCount(tags) < theTagsMax) {
                            setTagElement(tag, index), setTag(tag, index);
                        } else {
                            fire('max.tags', [theTagsMax]);
                        }
                    } else {
                        fire('has.tag', [tag, toArrayKey(tag, tags)]);
                    }
                }
            }
            return $;
        };
        $.source = $.output = source;
        $.state = state;
        $.tags = [];
        setTags(source.value); // Fill value(s)
        return $;
    }
    TP.instances = {};
    TP.state = {
        'class': 'tag-picker',
        'escape': [','],
        'join': ', ',
        'max': 9999,
        'min': 0,
        'pattern': null
    };
    TP.version = '3.4.18';

    function onChange$a(init) {
        // Destroy!
        var $;
        for (var key in TP.instances) {
            $ = TP.instances[key];
            $.pop();
            delete TP.instances[key];
        }
        var sources = getElements('.lot\\:field.type\\:query .input:not([type="hidden"])');
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            letClass(source, 'input');
            var c = getClasses(source);
            var $ = new TP(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            setClasses($.self, c);
        });
        1 === init && W._.on('change', onChange$a);
    }
    W.TP = TP;
    var events = {
        blur: 0,
        click: 0,
        copy: 0,
        cut: 0,
        focus: 0,
        input: 0,
        keydown: 'key.down',
        keyup: 'key.up',
        mousedown: 'mouse.down',
        mouseenter: 'mouse.enter',
        mouseleave: 'mouse.exit',
        mousemove: 'mouse.move',
        mouseup: 'mouse.up',
        paste: 0,
        scroll: 0,
        touchend: 'mouse.up',
        touchmove: 'mouse.move',
        touchstart: 'mouse.down',
        wheel: 'scroll'
    };

    function isDisabled(self) {
        return self.disabled;
    }

    function isReadOnly(self) {
        return self.readOnly;
    }

    function theValue(self) {
        return self.value.replace(/\r/g, "");
    }

    function trim(str, dir) {
        return (str || "")['trim' + (-1 === dir ? 'Left' : 1 === dir ? 'Right' : "")]();
    }

    function TextEditor(self, state) {
        var $ = this;
        if (!self) {
            return $;
        }
        // Return new instance if `TextEditor` was called without the `new` operator
        if (!isInstance($, TextEditor)) {
            return new TextEditor(self, state);
        }
        self['_' + TextEditor.name] = hook($, TextEditor.prototype);
        return $.attach(self, fromStates({}, TextEditor.state, isInteger(state) || isString(state) ? {
            tab: state
        } : state || {}));
    }
    TextEditor.esc = esc;
    TextEditor.state = {
        'tab': '\t',
        'with': []
    };
    TextEditor.S = function (start, end, value) {
        var $ = this,
            current = value.slice(start, end);
        $.after = value.slice(end);
        $.before = value.slice(0, start);
        $.end = end;
        $.length = toCount(current);
        $.start = start;
        $.value = current;
        $.toString = function () {
            return current;
        };
    };
    TextEditor.version = '4.1.3';
    TextEditor.x = x;
    Object.defineProperty(TextEditor, 'name', {
        value: 'TextEditor'
    });
    var theValuePrevious;

    function theEvent(e) {
        var self = this,
            $ = self['_' + TextEditor.name],
            type = e.type,
            value = theValue(self);
        if (value !== theValuePrevious) {
            theValuePrevious = value;
            $.fire('change', [e]);
        }
        $.fire(events[type] || type, [e]);
    }
    var $$$1 = TextEditor.prototype;
    $$$1.$ = function () {
        var self = this.self;
        return new TextEditor.S(self.selectionStart, self.selectionEnd, theValue(self));
    };
    $$$1.attach = function (self, state) {
        var $ = this;
        self = self || $.self;
        state = state || $.state;
        $._active = true;
        $._value = theValue(self);
        $.self = self;
        $.state = state;
        // Attach event(s)
        for (var event in events) {
            onEvent(event, self, theEvent);
        }
        // Attach extension(s)
        if (isSet(state) && isArray(state.with)) {
            for (var i = 0, j = toCount(state.with); i < j; ++i) {
                var value = state.with[i];
                if (isString(value)) {
                    value = TextEditor[value];
                }
                // `const Extension = function (self, state = {}) {}`
                if (isFunction(value)) {
                    value.call($, self, state);
                    continue;
                }
                // `const Extension = {attach: function (self, state = {}) {}, detach: function (self, state = {}) {}}`
                if (isObject(value) && isFunction(value.attach)) {
                    value.attach.call($, self, state);
                    continue;
                }
            }
        }
        return $;
    };
    $$$1.blur = function () {
        var $ = this,
            _active = $._active,
            self = $.self;
        if (!_active) {
            return $;
        }
        return self.blur(), $;
    };
    $$$1.detach = function () {
        var $ = this,
            self = $.self,
            state = $.state;
        $._active = false;
        // Detach event(s)
        for (var event in events) {
            offEvent(event, self, theEvent);
        }
        // Detach extension(s)
        if (isArray(state.with)) {
            for (var i = 0, j = toCount(state.with); i < j; ++i) {
                var value = state.with[i];
                if (isString(value)) {
                    value = TextEditor[value];
                }
                if (isObject(value) && isFunction(value.detach)) {
                    value.detach.call($, self, state);
                    continue;
                }
            }
        }
        return $;
    };
    $$$1.focus = function (mode) {
        var $ = this,
            _active = $._active,
            self = $.self,
            x,
            y;
        if (!_active) {
            return $;
        }
        if (-1 === mode) {
            x = y = 0; // Put caret at the start of the editor, scroll to the start of the editor
        } else if (1 === mode) {
            x = toCount(theValue(self)); // Put caret at the end of the editor
            y = self.scrollHeight; // Scroll to the end of the editor
        }
        if (isSet(x) && isSet(y)) {
            self.selectionStart = self.selectionEnd = x;
            self.scrollTop = y;
        }
        return self.focus(), $;
    };
    $$$1.get = function () {
        var $ = this,
            _active = $._active,
            self = $.self;
        if (!_active) {
            return false;
        }
        return !isDisabled(self) && theValue(self) || null;
    };
    $$$1.insert = function (value, mode, clear) {
        var $ = this,
            from = /^[\s\S]*?$/;
        if (!$._active) {
            return $;
        }
        if (clear) {
            $.replace(from, ""); // Force to delete selection on insert before/after?
        }
        if (-1 === mode) {
            // Insert before
            from = /$/;
        } else if (1 === mode) {
            // Insert after
            from = /^/;
        }
        return $.replace(from, value, mode);
    };
    $$$1.let = function () {
        var $ = this,
            _active = $._active,
            self = $.self;
        if (!_active) {
            return $;
        }
        return self.value = $._value, $;
    };
    $$$1.match = function (pattern, then) {
        var $ = this,
            _$$$ = $.$(),
            after = _$$$.after,
            before = _$$$.before,
            value = _$$$.value;
        if (isArray(pattern)) {
            var _m = [before.match(pattern[0]), value.match(pattern[1]), after.match(pattern[2])];
            return isFunction(then) ? then.call($, _m[0] || [], _m[1] || [], _m[2] || []) : [!!_m[0], !!_m[1], !!_m[2]];
        }
        var m = value.match(pattern);
        return isFunction(then) ? then.call($, m || []) : !!m;
    };
    $$$1.peel = function (open, close, wrap) {
        var $ = this,
            _$$$2 = $.$(),
            after = _$$$2.after,
            before = _$$$2.before,
            value = _$$$2.value;
        open = esc(open);
        close = esc(close);
        var openPattern = toPattern(open + '$', ""),
            closePattern = toPattern('^' + close, "");
        if (wrap) {
            return $.replace(toPattern('^' + open + '([\\s\\S]*?)' + close + '$', ""), '$1');
        }
        if (openPattern.test(before) && closePattern.test(after)) {
            before = before.replace(openPattern, "");
            after = after.replace(closePattern, "");
            return $.set(before + value + after).select(before = toCount(before), before + toCount(value));
        }
        return $.select();
    };
    $$$1.pull = function (by, withEmptyLines) {
        if (withEmptyLines === void 0) {
            withEmptyLines = true;
        }
        var $ = this,
            state = $.state,
            _$$$3 = $.$(),
            before = _$$$3.before,
            end = _$$$3.end,
            length = _$$$3.length,
            start = _$$$3.start,
            value = _$$$3.value;
        if (isInteger(by = isSet(by) ? by : state.tab)) {
            by = ' '.repeat(by);
        }
        if ("" !== before && '\n' !== before.slice(-1) && by !== before.slice(-toCount(by))) {
            // Move cursor to the start of the line
            $.select(start = start - toCount(before.split('\n').pop()), length ? end : start);
        }
        by = esc(by);
        if (length) {
            if (withEmptyLines) {
                return $.replace(toPattern('^' + by, 'gm'), "");
            }
            return $.insert(value.split('\n').map(function (v) {
                if (toPattern('^(' + by + ')*$', "").test(v)) {
                    return v;
                }
                return v.replace(toPattern('^' + by, ""), "");
            }).join('\n'));
        }
        return $.replace(toPattern(by + '$', ""), "", -1);
    };
    $$$1.push = function (by, withEmptyLines) {
        if (withEmptyLines === void 0) {
            withEmptyLines = false;
        }
        var $ = this,
            state = $.state,
            _$$$4 = $.$(),
            before = _$$$4.before,
            end = _$$$4.end,
            length = _$$$4.length,
            start = _$$$4.start;
        if (isInteger(by = isSet(by) ? by : state.tab)) {
            by = ' '.repeat(by);
        }
        if ("" !== before && '\n' !== before.slice(-1) && by !== before.slice(-toCount(by))) {
            // Move cursor to the start of the line
            $.select(start = start - toCount(before.split('\n').pop()), length ? end : start);
        }
        if (length) {
            return $.replace(toPattern('^' + (withEmptyLines ? "" : '(?!$)'), 'gm'), by);
        }
        return $.insert(by, -1);
    };
    $$$1.replace = function (from, to, mode) {
        var $ = this,
            _$$$5 = $.$(),
            after = _$$$5.after,
            before = _$$$5.before,
            value = _$$$5.value;
        if (-1 === mode) {
            // Replace before
            before = before.replace(from, to);
        } else if (1 === mode) {
            // Replace after
            after = after.replace(from, to);
        } else {
            // Replace value
            value = value.replace(from, to);
        }
        return $.set(before + value + after).select(before = toCount(before), before + toCount(value));
    };
    $$$1.select = function () {
        var $ = this,
            _active = $._active,
            self = $.self;
        if (!_active) {
            return $;
        }
        if (isDisabled(self) || isReadOnly(self)) {
            return self.focus(), $;
        }
        for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
            lot[_key] = arguments[_key];
        }
        var count = toCount(lot),
            _$$$6 = $.$(),
            start = _$$$6.start,
            end = _$$$6.end,
            x,
            y,
            X,
            Y;
        x = W.pageXOffset || R.scrollLeft || B.scrollLeft;
        y = W.pageYOffset || R.scrollTop || B.scrollTop;
        X = self.scrollLeft;
        Y = self.scrollTop;
        if (0 === count) {
            // Restore selection with `$.select()`
            lot[0] = start;
            lot[1] = end;
        } else if (1 === count) {
            // Move caret position with `$.select(7)`
            if (true === lot[0]) {
                // Select all with `$.select(true)`
                return self.focus(), self.select(), $;
            }
            lot[1] = lot[0];
        }
        self.focus();
        // Default `$.select(7, 100)`
        self.selectionStart = lot[0];
        self.selectionEnd = lot[1];
        self.scrollLeft = X;
        self.scrollTop = Y;
        return W.scroll(x, y), $;
    };
    $$$1.set = function (value) {
        var $ = this,
            _active = $._active,
            self = $.self;
        if (!_active) {
            return $;
        }
        if (isDisabled(self) || isReadOnly(self)) {
            return $;
        }
        return self.value = value, $;
    };
    $$$1.trim = function (open, close, start, end, tidy) {
        if (tidy === void 0) {
            tidy = true;
        }
        if (null !== open && false !== open) {
            open = open || "";
        }
        if (null !== close && false !== close) {
            close = close || "";
        }
        if (null !== start && false !== start) {
            start = start || "";
        }
        if (null !== end && false !== end) {
            end = end || "";
        }
        var $ = this,
            _$$$7 = $.$(),
            after = _$$$7.after,
            before = _$$$7.before,
            value = _$$$7.value,
            afterClean = trim(after, -1),
            beforeClean = trim(before, 1);
        after = false !== close ? (afterClean || !tidy ? close : "") + trim(after, -1) : after;
        before = false !== open ? trim(before, 1) + (beforeClean || !tidy ? open : "") : before;
        if (false !== end) value = trim(value, 1);
        if (false !== start) value = trim(value, -1);
        return $.set(before + value + after).select(before = toCount(before), before + toCount(value));
    };
    $$$1.wrap = function (open, close, wrap) {
        var $ = this,
            _$$$8 = $.$(),
            after = _$$$8.after,
            before = _$$$8.before,
            value = _$$$8.value;
        if (wrap) {
            return $.replace(/^[\s\S]*?$/, open + '$&' + close);
        }
        return $.set(before + open + value + close + after).select(before = toCount(before + open), before + toCount(value));
    };
    Object.defineProperty($$$1, 'value', {
        get: function get() {
            return this.self.value;
        },
        set: function set(value) {
            this.self.value = value;
        }
    });

    function History() {
        var $ = this;
        var $$ = $.constructor.prototype;
        $._history = [];
        $._historyState = -1;
        !isFunction($$.history) && ($$.history = function (of) {
            var $ = this,
                _active = $._active,
                _history = $._history;
            if (!_active) {
                return false;
            }
            if (!isSet(of)) {
                return _history;
            }
            return isSet(_history[of]) ? _history[of] : null;
        });
        !isFunction($$.loss) && ($$.loss = function (of) {
            var $ = this,
                current,
                _active = $._active;
            $._history;
            var _historyState = $._historyState;
            if (!_active) {
                return false;
            }
            if (true === of) {
                $._history = [];
                $._historyState = -1;
                return null;
            }
            current = $._history.splice(isSet(of) ? of : _historyState, 1);
            $._historyState = toEdge(_historyState - 1, [-1]);
            return current;
        });
        !isFunction($$.record) && ($$.record = function (of) {
            var $ = this,
                current,
                next,
                _$$$ = $.$(),
                end = _$$$.end,
                start = _$$$.start,
                _active = $._active,
                _history = $._history,
                _historyState = $._historyState;
            if (!_active) {
                return $;
            }
            current = _history[_historyState] || [];
            next = [$.get(), [start, end], Date.now()];
            if (next[0] === current[0] && next[1][0] === current[1][0] && next[1][1] === current[1][1]) {
                return $; // Do not save duplicate
            }
            ++_historyState;
            $._history[isSet(of) ? of : _historyState] = next;
            $._historyState = _historyState;
            return $;
        });
        !isFunction($$.redo) && ($$.redo = function () {
            var $ = this,
                state,
                _active = $._active,
                _history = $._history,
                _historyState = $._historyState;
            if (!_active) {
                return $;
            }
            state = _history[$._historyState = toEdge(_historyState + 1, [0, toCount(_history) - 1])];
            return state ? $.set(state[0]).select(state[1][0], state[1][1]) : $;
        });
        !isFunction($$.undo) && ($$.undo = function () {
            var $ = this,
                state,
                _active = $._active,
                _history = $._history,
                _historyState = $._historyState;
            if (!_active) {
                return $;
            }
            state = _history[$._historyState = toEdge(_historyState - 1, [0, toCount(_history) - 1])];
            return state ? $.set(state[0]).select(state[1][0], state[1][1]) : $;
        });
        return $;
    }
    Object.defineProperty(History, 'name', {
        value: 'TextEditor.History'
    });

    function Key(self) {
        var $ = this;
        $.commands = {};
        $.key = null;
        $.keys = {};
        $.queue = {};
        $.self = self || $;
        return $;
    }
    var $$ = Key.prototype;
    $$.command = function (v) {
        var $ = this;
        if (isString(v)) {
            return v === $.toString();
        }
        var command = $.keys[$.toString()];
        return isSet(command) ? command : false;
    };
    $$.fire = function (command) {
        var $ = this;
        var self = $.self || $,
            value,
            exist;
        if (isFunction(command)) {
            value = command.call(self);
            exist = true;
        } else if (isString(command) && (command = $.commands[command])) {
            value = command.call(self);
            exist = true;
        } else if (isArray(command)) {
            var data = command[1] || [];
            if (command = $.commands[command[0]]) {
                value = command.apply(self, data);
                exist = true;
            }
        }
        return exist ? isSet(value) ? value : true : null;
    };
    $$.pull = function (key) {
        var $ = this;
        $.key = null;
        if (!isSet(key)) {
            return $.queue = {}, $;
        }
        return delete $.queue[key], $;
    };
    $$.push = function (key) {
        var $ = this;
        return $.queue[$.key = key] = 1, $;
    };
    $$.toString = function () {
        return toObjectKeys(this.queue).join('-');
    };
    Object.defineProperty(Key, 'name', {
        value: 'Key'
    });
    var bounce$2 = debounce(function (map) {
        return map.pull();
    }, 1000);
    var name$1 = 'TextEditor.Key';
    var id = '_Key';

    function onBlur(e) {
        var $ = this;
        $._event = e;
        $[id].pull(); // Reset all key(s)
    }

    function onInput(e) {
        onBlur.call(this, e);
    }

    function onKeyDown$1(e) {
        var $ = this;
        var command,
            map = $[id],
            v;
        map.push(e.key); // Add current key to the queue
        $._event = e;
        if (command = map.command()) {
            v = map.fire(command);
            if (false === v) {
                offEventDefault(e);
                offEventPropagation(e);
            } else if (null === v) {
                console.warn('Unknown command: `' + command + '`');
            }
        }
        bounce$2(map); // Reset all key(s) after 1 second idle
    }

    function onKeyUp(e) {
        var $ = this;
        $._event = e;
        $[id].pull(e.key); // Reset current key
    }

    function attach$1() {
        var $ = this;
        var $$ = $.constructor.prototype;
        var map = new Key($);
        $.commands = fromStates($.commands = map.commands, $.state.commands || {});
        $.keys = fromStates($.keys = map.keys, $.state.keys || {});
        !isFunction($$.command) && ($$.command = function (command, of) {
            var $ = this;
            return $.commands[command] = of, $;
        });
        !isFunction($$.k) && ($$.k = function (join) {
            var $ = this,
                key = $[id] + "",
                keys;
            if (isSet(join) && '-' !== join) {
                keys = "" !== key ? key.split(/-(?!$)/) : [];
                if (false !== join) {
                    return keys.join(join);
                }
            }
            if (false === join) {
                if ('-' === key) {
                    return [key];
                }
                return keys;
            }
            return key;
        });
        !isFunction($$.key) && ($$.key = function (key, of) {
            var $ = this;
            return $.keys[key] = of, $;
        });
        $.on('blur', onBlur);
        $.on('input', onInput);
        $.on('key.down', onKeyDown$1);
        $.on('key.up', onKeyUp);
        return $[id] = map, $;
    }

    function detach$1() {
        var $ = this;
        $[id].pull();
        $.off('blur', onBlur);
        $.off('input', onInput);
        $.off('key.down', onKeyDown$1);
        $.off('key.up', onKeyUp);
        delete $[id];
        return $;
    }
    var TextEditorKey = {
        attach: attach$1,
        detach: detach$1,
        name: name$1
    };
    var ALT_PREFIX = 'Alt-';
    var CTRL_PREFIX = 'Control-';
    var SHIFT_PREFIX = 'Shift-';
    var bounce$1 = debounce(function ($) {
        return $.record();
    }, 10);
    var name = 'TextEditor.Source';

    function onKeyDown(e) {
        var $ = this,
            key = $.k(false).pop(),
            // Capture the last key
            keys = $.k();
        bounce$1($);
        if (e.defaultPrevented || $.keys[keys]) {
            return;
        }
        var charAfter,
            charBefore,
            charIndent = $.state.tab || '\t',
            charPairs = $.state.pairs || {},
            charPairsValues = toObjectValues(charPairs);
        if (isInteger(charIndent)) {
            charIndent = ' '.repeat(charIndent);
        }
        var _$$$ = $.$(),
            after = _$$$.after,
            before = _$$$.before,
            end = _$$$.end,
            start = _$$$.start,
            value = _$$$.value,
            lineAfter = after.split('\n').shift(),
            lineBefore = before.split('\n').pop(),
            lineMatch = /^\s+/.exec(lineBefore),
            lineMatchIndent = lineMatch && lineMatch[0] || "";
        if (CTRL_PREFIX + SHIFT_PREFIX + 'Enter' === keys) {
            if (before || after) {
                // Insert line above with `⎈⇧↵`
                offEventDefault(e);
                return $.select(start - toCount(lineBefore)).wrap(lineMatchIndent, '\n').insert(value).record(), false;
            }
            return;
        }
        if (CTRL_PREFIX + 'Enter' === keys) {
            if (before || after) {
                // Insert line below with `⎈↵`
                offEventDefault(e);
                return $.select(end + toCount(lineAfter)).wrap('\n' + lineMatchIndent, "").insert(value).record(), false;
            }
        }
        // Do nothing
        if (ALT_PREFIX === keys + '-' || CTRL_PREFIX === keys + '-') {
            offEventDefault(e);
            return;
        }
        if (' ' === keys) {
            charAfter = charPairs[charBefore = before.slice(-1)];
            if (!value && charAfter && charBefore && charAfter === after[0]) {
                offEventDefault(e);
                return $.wrap(' ', ' ');
            }
            return;
        }
        if ('Backspace' === keys || 'Delete' === keys) {
            charAfter = charPairs[charBefore = before.slice(-1)];
            // Do nothing on escape
            if ('\\' === charBefore) {
                return;
            }
            if (value) {
                if (after && before && charAfter && charAfter === after[0] && !before.endsWith('\\' + charBefore)) {
                    offEventDefault(e);
                    return $.record().peel(charBefore, charAfter).record();
                }
                return;
            }
            charAfter = charPairs[charBefore = before.trim().slice(-1)];
            if (charAfter && charBefore) {
                if (after.startsWith(' ' + charAfter) && before.endsWith(charBefore + ' ') || after.startsWith('\n' + lineMatchIndent + charAfter) && before.endsWith(charBefore + '\n' + lineMatchIndent)) {
                    // Collapse bracket(s)
                    offEventDefault(e);
                    return $.trim("", "").record();
                }
            }
            // Outdent
            if ('Delete' !== keys && lineBefore.endsWith(charIndent)) {
                offEventDefault(e);
                return $.pull(charIndent).record();
            }
            if (after && before && !before.endsWith('\\' + charBefore)) {
                if (charAfter === after[0] && charBefore === before.slice(-1)) {
                    // Peel pair
                    offEventDefault(e);
                    return $.peel(charBefore, charAfter).record();
                }
            }
            return;
        }
        if ('Enter' === keys || SHIFT_PREFIX + 'Enter' === keys) {
            if (!value) {
                if (after && before && (charAfter = charPairs[charBefore = before.slice(-1)]) && charAfter === after[0]) {
                    offEventDefault(e);
                    return $.wrap('\n' + lineMatchIndent + (charBefore !== charAfter ? charIndent : ""), '\n' + lineMatchIndent).record();
                }
                if (lineMatchIndent) {
                    offEventDefault(e);
                    return $.insert('\n' + lineMatchIndent, -1).record();
                }
            }
            return;
        }
        // Do nothing on escape
        if ('\\' === (charBefore = before.slice(-1))) {
            return;
        }
        charAfter = hasValue(after[0], charPairsValues) ? after[0] : charPairs[charBefore];
        // `|}`
        if (!value && after && before && charAfter && key === charAfter) {
            // Move to the next character
            // `}|`
            offEventDefault(e);
            return $.select(start + 1).record();
        }
        for (charBefore in charPairs) {
            charAfter = charPairs[charBefore];
            // `{|`
            if (key === charBefore && charAfter) {
                // Wrap pair or selection
                // `{|}` `{|aaa|}`
                offEventDefault(e);
                return $.wrap(charBefore, charAfter).record();
            }
            // `|}`
            if (key === charAfter) {
                if (value) {
                    // Wrap selection
                    // `{|aaa|}`
                    offEventDefault(e);
                    return $.record().wrap(charBefore, charAfter).record();
                }
                break;
            }
        }
        var charPair,
            charPairValue,
            m,
            tokens = [];
        if (value) {
            for (charPair in charPairs) {
                if (!(charPairValue = charPairs[charPair])) {
                    continue;
                }
                tokens.push('(?:\\' + charPair + '(?:\\\\.|[^\\' + charPair + (charPairValue !== charPair ? '\\' + charPairValue : "") + '])*\\' + charPairValue + ')');
            }
            tokens.push('\\w+'); // Word(s)
            tokens.push('\\s+'); // White-space(s)
            tokens.push('[\\s\\S]'); // Last try!
            if (CTRL_PREFIX + 'ArrowLeft' === keys) {
                offEventDefault(e);
                if (m = toPattern('(' + tokens.join('|') + ')$', "").exec(before)) {
                    return $.insert("").select(start - toCount(m[0])).insert(value).record();
                }
                return $.select();
            }
            if (CTRL_PREFIX + 'ArrowRight' === keys) {
                offEventDefault(e);
                if (m = after.match(toPattern('^(' + tokens.join('|') + ')', ""))) {
                    return $.insert("").select(end + toCount(m[0]) - toCount(value)).insert(value).record();
                }
                return $.select();
            }
        }
        // Force to select the current line if there is no selection
        end += toCount(lineAfter);
        start -= toCount(lineBefore);
        value = lineBefore + value + lineAfter;
        if (CTRL_PREFIX + 'ArrowUp' === keys) {
            offEventDefault(e);
            if (!hasValue('\n', before)) {
                return $.select();
            }
            $.insert("");
            $.replace(/^([^\n]*?)(\n|$)/, '$2', 1);
            $.replace(/(^|\n)([^\n]*?)$/, "", -1);
            var s = $.$();
            before = s.before;
            start = s.start;
            lineBefore = before.split('\n').pop();
            $.select(start = start - toCount(lineBefore)).wrap(value, '\n');
            $.select(start, start + toCount(value));
            return $.record();
        }
        if (CTRL_PREFIX + 'ArrowDown' === keys) {
            offEventDefault(e);
            if (!hasValue('\n', after)) {
                return $.select();
            }
            $.insert("");
            $.replace(/^([^\n]*?)(\n|$)/, "", 1);
            $.replace(/(^|\n)([^\n]*?)$/, '$1', -1);
            var _s = $.$();
            after = _s.after;
            end = _s.end;
            lineAfter = after.split('\n').shift();
            $.select(end = end + toCount(lineAfter)).wrap('\n', value);
            end += 1;
            $.select(end, end + toCount(value));
            return $.record();
        }
        return;
    }

    function attach() {
        var $ = this;
        var $$ = $.constructor.prototype;
        $.state = fromStates({
            pairs: {
                '`': '`',
                '(': ')',
                '{': '}',
                '[': ']',
                '"': '"',
                "'": "'",
                '<': '>'
            }
        }, $.state);
        !isFunction($$.alert) && ($$.alert = function (hint, then) {
            W.alert && W.alert(hint);
            return isFunction(then) && then.call(this, true);
        });
        !isFunction($$.confirm) && ($$.confirm = function (hint, then) {
            return isFunction(then) && then.call(this, W.confirm && W.confirm(hint));
        });
        !isFunction($$.insertBlock) && ($$.insertBlock = function (value, mode) {
            var $ = this;
            var _$$$2 = $.$(),
                after = _$$$2.after,
                before = _$$$2.before,
                end = _$$$2.end,
                start = _$$$2.start,
                lineAfter = after.split('\n').shift(),
                lineAfterCount = toCount(lineAfter),
                lineBefore = before.split('\n').pop(),
                lineBeforeCount = toCount(lineBefore),
                lineMatch = /^\s+/.exec(lineBefore),
                lineMatchIndent = lineMatch && lineMatch[0] || "";
            if (-1 === mode) {
                return $.select(start - lineBeforeCount).insert('\n', 1).push(lineMatchIndent).insert(value, 1, false);
            }
            if (1 === mode) {
                return $.select(end + lineAfterCount).insert('\n', -1).push(lineMatchIndent).insert(value, 1, false);
            }
            return $.select(start - lineBeforeCount, end + lineAfterCount).insert(value, mode, true).wrap(lineMatchIndent, "");
        });
        !isFunction($$.peelBlock) && ($$.peelBlock = function (open, close, wrap) {
            var $ = this;
            var _$$$3 = $.$(),
                after = _$$$3.after,
                before = _$$$3.before,
                end = _$$$3.end,
                start = _$$$3.start,
                value = _$$$3.value,
                closeCount = toCount(close),
                lineAfter = after.split('\n').shift(),
                lineAfterCount = toCount(lineAfter),
                lineBefore = before.split('\n').pop(),
                lineBeforeCount = toCount(lineBefore),
                openCount = toCount(open);
            if (wrap && close === value.slice(-closeCount) && open === value.slice(0, openCount) || close === lineAfter.slice(-closeCount) && open === lineBefore.slice(0, openCount)) {
                return $.select(start - lineBeforeCount + (wrap ? 0 : openCount), end + lineAfterCount - (wrap ? 0 : closeCount)).peel(open, close, wrap);
            }
            return $.select(start, end);
        });
        !isFunction($$.prompt) && ($$.prompt = function (hint, value, then) {
            return isFunction(then) && then.call(this, W.prompt ? W.prompt(hint, value) : false);
        });
        !isFunction($$.selectBlock) && ($$.selectBlock = function (withSpaces) {
            if (withSpaces === void 0) {
                withSpaces = true;
            }
            var $ = this;
            var _$$$4 = $.$(),
                after = _$$$4.after,
                before = _$$$4.before,
                end = _$$$4.end,
                start = _$$$4.start,
                value = _$$$4.value,
                lineAfter = after.split('\n').shift(),
                lineAfterCount = toCount(lineAfter),
                lineBefore = before.split('\n').pop(),
                lineBeforeCount = toCount(lineBefore);
            if (!withSpaces) {
                var lineAfterSpaces = /\s+$/.exec(lineAfter),
                    lineBeforeSpaces = /^\s+/.exec(lineBefore);
                if (lineAfterSpaces) {
                    lineAfterCount -= toCount(lineAfterSpaces[0]);
                }
                if (lineBeforeSpaces) {
                    lineBeforeCount -= toCount(lineBeforeSpaces[0]);
                }
            }
            $.select(start - lineBeforeCount, end + lineAfterCount);
            if (!withSpaces) {
                var s = $.$(),
                    m;
                end = s.end;
                start = s.start;
                value = s.value;
                if (m = /^(\s+)?[\s\S]+?(\s+)?$/.exec(value)) {
                    return $.select(start + toCount(m[1] || ""), end - toCount(m[2] || ""));
                }
            }
            return $;
        });
        !isFunction($$.toggle) && ($$.toggle = function (open, close, wrap) {
            var $ = this;
            var _$$$5 = $.$(),
                after = _$$$5.after,
                before = _$$$5.before,
                value = _$$$5.value,
                closeCount = toCount(close),
                openCount = toCount(open);
            if (wrap && close === value.slice(-closeCount) && open === value.slice(0, openCount) || close === after.slice(0, closeCount) && open === before.slice(-openCount)) {
                return $.peel(open, close, wrap);
            }
            return $.wrap(open, close, wrap);
        });
        !isFunction($$.toggleBlock) && ($$.toggleBlock = function (open, close, wrap) {
            var $ = this;
            var _$$$6 = $.$(),
                after = _$$$6.after,
                before = _$$$6.before,
                value = _$$$6.value,
                closeCount = toCount(close),
                lineAfter = after.split('\n').shift(),
                lineBefore = before.split('\n').pop(),
                openCount = toCount(open);
            if (wrap && close === value.slice(-closeCount) && open === value.slice(0, openCount) || close === lineAfter.slice(-closeCount) && open === lineBefore.slice(0, openCount)) {
                return $.peelBlock(open, close, wrap);
            }
            return $.wrapBlock(open, close, wrap);
        });
        !isFunction($$.wrapBlock) && ($$.wrapBlock = function (open, close, wrap) {
            var $ = this;
            var _$$$7 = $.$(),
                after = _$$$7.after,
                before = _$$$7.before,
                end = _$$$7.end,
                start = _$$$7.start,
                lineAfter = after.split('\n').shift(),
                lineAfterCount = toCount(lineAfter),
                lineBefore = before.split('\n').pop(),
                lineBeforeCount = toCount(lineBefore);
            return $.select(start - lineBeforeCount, end + lineAfterCount).wrap(open, close, wrap);
        });
        return $.on('key.down', onKeyDown).record();
    }

    function detach() {
        return this.off('key.down', onKeyDown);
    }
    var TextEditorSource = {
        attach: attach,
        detach: detach,
        name: name
    };
    TextEditor.instances = [];
    TextEditor.state.with.push(History);
    TextEditor.state.with.push(TextEditorKey);
    TextEditor.state.with.push(TextEditorSource);

    function onChange$9(init) {
        var instance;
        while (instance = TextEditor.instances.pop()) {
            instance.detach();
        }
        var sources = getElements('.lot\\:field.type\\:source .textarea'),
            editor,
            state,
            type;
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            editor = new TextEditor(source, state = (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            editor.command('pull', function () {
                return this.pull(), false;
            });
            editor.command('push', function () {
                return this.push(), false;
            });
            editor.key('Control-[', 'pull');
            editor.key('Control-]', 'push');
            editor.key('Escape', function () {
                var parent = getParent(this.self, '[tabindex]:not(.not\\:active)');
                if (parent) {
                    return parent.focus({
                        // <https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/focus#focusvisible>
                        focusVisible: true
                    }), false;
                }
                return true;
            });
            type = state.type || source.form.elements['data[type]'] || source.form.elements['page[type]'] || source.form.elements['file[type]'] || 'text/plain';
            if ('HTML' === type || 'text/html' === type) {
                editor.command('blocks', function () {});
                editor.command('bold', function () {});
                editor.command('code', function () {});
                editor.command('image', function () {});
                editor.command('italic', function () {});
                editor.command('link', function () {});
                editor.command('quote', function () {});
                editor.command('underline', function () {});
                editor.key('Control-Shift-"', 'quote');
                editor.key('Control-\'', 'quote');
                editor.key('Control-b', 'bold');
                editor.key('Control-e', 'code');
                editor.key('Control-h', 'blocks');
                editor.key('Control-i', 'italic');
                editor.key('Control-l', 'link');
                editor.key('Control-o', 'image');
                editor.key('Control-u', 'underline');
            }
            TextEditor.instances.push(editor);
        });
        if (1 === init) {
            W._.on('change', onChange$9);
        }
    }
    W.TextEditor = TextEditor;

    function Fields() {
        onChange$b(1);
        onChange$a(1);
        onChange$9(1);
    }
    var targets$7 = ':scope>:where(.lot\\:file[tabindex],.lot\\:folder[tabindex]):not([tabindex="-1"]):not(.not\\:active)';

    function fireFocus$7(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onEventOnly$8(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange$8(init) {
        var sources = getElements(':where(.lot\\:files,.lot\\:folders)[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var files = getElements(targets$7, source);
            files.forEach(function (file) {
                onEventOnly$8('keydown', file, onKeyDownFile);
            });
            onEventOnly$8('keydown', source, onKeyDownFiles);
        });
        1 === init && W._.on('change', onChange$8);
    }

    function onKeyDownFile(e) {
        var t = this,
            key = e.key,
            any,
            next,
            prev,
            stop;
        if (t !== e.target) {
            return;
        }
        next = getNext(t);
        while (next && hasClass(next, 'not:active')) {
            next = getNext(next);
        }
        prev = getPrev(t);
        while (prev && hasClass(prev, 'not:active')) {
            prev = getPrev(prev);
        }
        if ('ArrowDown' === key) {
            fireFocus$7(next);
            stop = true;
        } else if ('ArrowUp' === key) {
            fireFocus$7(prev);
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets$7, getParent(t)));
            fireFocus$7(any.pop());
            stop = true;
        } else if ('Home' === key) {
            fireFocus$7(getElement(targets$7, getParent(t)));
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownFiles(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            stop;
        if (t !== e.target) {
            return;
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            var any;
            if ('ArrowDown' === key || 'Home' === key) {
                fireFocus$7(getElement(targets$7, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$7, t));
                fireFocus$7(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$6 = ':scope>ul>li>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not\\:active)';

    function fireFocus$6(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onEventOnly$7(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange$7(init) {
        var sources = getElements('.lot\\:links[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var links = getElements(targets$6, source);
            links && toCount(links) && links.forEach(function (link) {
                onEventOnly$7('keydown', link, onKeyDownLink);
            });
            onEventOnly$7('keydown', source, onKeyDownLinks);
        });
        1 === init && W._.on('change', onChange$7);
    }

    function onKeyDownLink(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            parent,
            next,
            prev,
            stop,
            vertical;
        if (parent = getParent(t, '[aria-orientation]')) {
            vertical = 'v' === (getAttribute(parent, 'aria-orientation') || [""])[0];
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (parent = getParent(t)) {
                next = getNext(parent);
                while (next && (hasClass(next, 'as:separator') || hasClass(next, 'not:active'))) {
                    next = getNext(next);
                }
                prev = getPrev(parent);
                while (prev && (hasClass(prev, 'as:separator') || hasClass(prev, 'not:active'))) {
                    prev = getPrev(prev);
                }
            }
            if ('Arrow' + (vertical ? 'Up' : 'Left') === key) {
                fireFocus$6(prev && getChildFirst(prev));
                stop = true;
            } else if ('Arrow' + (vertical ? 'Down' : 'Right') === key) {
                fireFocus$6(next && getChildFirst(next));
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                    any = [].slice.call(getElements(targets$6, parent));
                    fireFocus$6(any.pop());
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                    fireFocus$6(getElement(targets$6, parent));
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownLinks(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            stop,
            vertical;
        if (t !== e.target) {
            return;
        }
        vertical = 'v' === (getAttribute(t, 'aria-orientation') || [""])[0];
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if ('Arrow' + (vertical ? 'Down' : 'Right') === key || 'Home' === key) {
                fireFocus$6(getElement(targets$6, t));
                stop = true;
            } else if ('Arrow' + (vertical ? 'Up' : 'Left') === key || 'End' === key) {
                any = [].slice.call(getElements(targets$6, t));
                fireFocus$6(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$5 = ':where(a,[tabindex]):not(.not\\:active)';

    function fireFocus$5(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onEventOnly$6(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function doHideMenus(but, trigger) {
        getElements('.lot\\:menu[tabindex].is\\:enter').forEach(function (node) {
            if (but !== node) {
                letClass(getParent(node), 'is:active');
                letClass(getPrev(node), 'is:active');
                letClass(node, 'is:enter');
                if (trigger) {
                    setAttribute(trigger, 'aria-expanded', 'false');
                }
            }
        });
    }

    function onChange$6(init) {
        offEvent('click', D, onClickDocument);
        var menuParents = getElements('.has\\:menu'),
            menuLinks = getElements('.lot\\:menu[tabindex]>ul>li>' + targets$5);
        if (menuParents && toCount(menuParents)) {
            menuParents.forEach(function (menuParent) {
                var menu = getElement('.lot\\:menu[tabindex]', menuParent),
                    a = getPrev(menu);
                if (menu && a) {
                    onEventOnly$6('click', a, onClickMenuShow);
                    onEventOnly$6('keydown', a, onKeyDownMenuToggle);
                }
            });
            onEventOnly$6('click', D, onClickDocument);
        }
        if (menuLinks && toCount(menuLinks)) {
            menuLinks.forEach(function (menuLink) {
                onEventOnly$6('keydown', menuLink, onKeyDownMenu$1);
            });
        }
        var sources = getElements('.lot\\:menu[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            onEventOnly$6('keydown', source, onKeyDownMenus$1);
        });
        1 === init && W._.on('change', onChange$6);
    }

    function onClickDocument() {
        doHideMenus(0);
    }

    function onClickMenuShow(e) {
        offEventDefault(e);
        offEventPropagation(e);
        var t = this,
            current = getNext(t);
        doHideMenus(current, t);
        W.setTimeout(function () {
            toggleClass(current, 'is:enter');
            toggleClass(getParent(t), 'is:active');
            toggleClass(t, 'is:active');
            setAttribute(t, 'aria-expanded', hasClass(t, 'is:active') ? 'true' : 'false');
        }, 1);
    }

    function onKeyDownMenu$1(e) {
        var t = this,
            key = e.key,
            any,
            current,
            parent,
            next,
            prev,
            stop;
        if (parent = getParent(t)) {
            next = getNext(parent);
            while (next && (hasClass(next, 'as:separator') || hasClass(next, 'not:active'))) {
                next = getNext(next);
            }
            prev = getPrev(parent);
            while (prev && (hasClass(prev, 'as:separator') || hasClass(prev, 'not:active'))) {
                prev = getPrev(prev);
            }
        }
        if ('ArrowDown' === key) {
            fireFocus$5(next && getChildFirst(next));
            stop = true;
        } else if ('ArrowLeft' === key || 'Escape' === key || 'Tab' === key) {
            // Hide menu then focus to the parent menu link
            if (parent = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
                letClass(getParent(t), 'is:active');
                letClass(parent, 'is:enter');
                letClass(t, 'is:active');
                setAttribute(getPrev(parent), 'aria-expanded', 'false');
                if ('Tab' !== key) {
                    fireFocus$5(getPrev(parent));
                }
                // Focus to the self menu
            } else if ('Escape' === key) {
                fireFocus$5(getParent(t, '.lot\\:menu[tabindex]'));
            }
            stop = 'Tab' !== key;
        } else if ('ArrowRight' === key) {
            next = getNext(t);
            if (next && hasClass(next, 'lot:menu')) {
                setAttribute(t, 'aria-expanded', 'true');
                setClass(getParent(t), 'is:active');
                setClass(next, 'is:enter');
                setClass(t, 'is:active');
                W.setTimeout(function () {
                    // Focus to the first link of child menu
                    fireFocus$5(getElement(targets$5, next));
                }, 1);
            }
            stop = true;
        } else if ('ArrowUp' === key) {
            if (current = prev && getChildFirst(prev)) {
                fireFocus$5(current);
            } else {
                if (current = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
                    // Apply only to the first level drop-down menu
                    if (hasClass(current, 'level:1')) {
                        // Hide menu then focus to the parent menu link
                        letClass(current, 'is:enter');
                        if (current = getPrev(current)) {
                            letClass(current, 'is:active');
                            letClass(getParent(current), 'is:active');
                            setAttribute(current, 'aria-expanded', 'false');
                            W.setTimeout(function () {
                                fireFocus$5(current);
                            }, 1);
                        }
                    }
                }
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
                any = [].slice.call(getElements(targets$5, parent));
                fireFocus$5(any.pop());
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
                fireFocus$5(getElement(targets$5, parent));
            }
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownMenus$1(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            stop;
        if (t !== e.target) {
            return;
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if ('ArrowDown' === key || 'Home' === key) {
                fireFocus$5(getElement(targets$5, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$5, t));
                fireFocus$5(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownMenuToggle(e) {
        var t = this,
            key = e.key,
            next = getNext(t),
            parent = getParent(t),
            stop;
        if (next && parent && hasClass(next, 'lot:menu')) {
            if (' ' === key || 'Enter' === key || 'Tab' === key) {
                if ('Tab' === key) {
                    hasClass(next, 'is:enter') && fireEvent('click', t);
                } else {
                    fireEvent('click', t);
                    W.setTimeout(function () {
                        // Focus to the first link of child menu
                        fireFocus$5(getElement(targets$5, next));
                    }, 1);
                    stop = true;
                }
                // Apply only to the first level drop-down menu
            } else if ('ArrowDown' === key && hasClass(next, 'level:1')) {
                setAttribute(t, 'aria-expanded', 'true');
                setClass(getParent(t), 'is:active');
                setClass(next, 'is:enter');
                setClass(t, 'is:active');
                W.setTimeout(function () {
                    // Focus to the first link of child menu
                    fireFocus$5(getElement(targets$5, next));
                }, 1);
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$4 = ':scope>ul>li>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not\\:active)';

    function fireFocus$4(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onEventOnly$5(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange$5(init) {
        var sources = getElements('.lot\\:menus[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var menus = getElements(targets$4, source);
            menus && toCount(menus) && menus.forEach(function (menu) {
                onEventOnly$5('keydown', menu, onKeyDownMenu);
            });
            onEventOnly$5('keydown', source, onKeyDownMenus);
        });
        1 === init && W._.on('change', onChange$5);
    }

    function onKeyDownMenu(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            parent,
            next,
            prev,
            stop,
            vertical;
        if (parent = getParent(t, '[aria-orientation]')) {
            vertical = 'v' === (getAttribute(parent, 'aria-orientation') || [""])[0];
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (parent = getParent(t)) {
                next = getNext(parent);
                while (next && (hasClass(next, 'as:separator') || hasClass(next, 'not:active'))) {
                    next = getNext(next);
                }
                prev = getPrev(parent);
                while (prev && (hasClass(prev, 'as:separator') || hasClass(prev, 'not:active'))) {
                    prev = getPrev(prev);
                }
            }
            if ('Arrow' + (vertical ? 'Up' : 'Left') === key) {
                fireFocus$4(prev && getChildFirst(prev));
                stop = true;
            } else if ('Arrow' + (vertical ? 'Down' : 'Right') === key) {
                fireFocus$4(next && getChildFirst(next));
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:menus[tabindex]')) {
                    any = [].slice.call(getElements(targets$4, parent));
                    fireFocus$4(any.pop());
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:menus[tabindex]')) {
                    fireFocus$4(getElement(targets$4, parent));
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownMenus(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            stop,
            vertical;
        if (t !== e.target) {
            return;
        }
        vertical = 'v' === (getAttribute(t, 'aria-orientation') || [""])[0];
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if ('Arrow' + (vertical ? 'Down' : 'Right') === key || 'Home' === key) {
                fireFocus$4(getElement(targets$4, t));
                stop = true;
            } else if ('Arrow' + (vertical ? 'Up' : 'Left') === key || 'End' === key) {
                any = [].slice.call(getElements(targets$4, t));
                fireFocus$4(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$3 = ':scope>.lot\\:page[tabindex]:not([tabindex="-1"]):not(.not\\:active)';

    function fireFocus$3(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onEventOnly$4(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange$4(init) {
        var sources = getElements('.lot\\:pages[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var pages = getElements(targets$3, source);
            pages.forEach(function (page) {
                onEventOnly$4('keydown', page, onKeyDownPage);
            });
            onEventOnly$4('keydown', source, onKeyDownPages);
        });
        1 === init && W._.on('change', onChange$4);
    }

    function onKeyDownPage(e) {
        var t = this,
            key = e.key,
            any,
            next,
            prev,
            stop;
        if (t !== e.target) {
            return;
        }
        next = getNext(t);
        while (next && hasClass(next, 'not:active')) {
            next = getNext(next);
        }
        prev = getPrev(t);
        while (prev && hasClass(prev, 'not:active')) {
            prev = getPrev(prev);
        }
        if ('ArrowDown' === key) {
            fireFocus$3(next);
            stop = true;
        } else if ('ArrowUp' === key) {
            fireFocus$3(prev);
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets$3, getParent(t)));
            fireFocus$3(any.pop());
            stop = true;
        } else if ('Home' === key) {
            fireFocus$3(getElement(targets$3, getParent(t)));
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownPages(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            stop;
        if (t !== e.target) {
            return;
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if ('ArrowDown' === key || 'Home' === key) {
                fireFocus$3(getElement(targets$3, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$3, t));
                fireFocus$3(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var commonjsGlobal = typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

    function getDefaultExportFromCjs(x) {
        return x && x.__esModule && Object.prototype.hasOwnProperty.call(x, 'default') ? x['default'] : x;
    }
    var siema_min = {
        exports: {}
    };
    (function (module, exports) {
        ! function (e, t) {
            module.exports = t();
        }("undefined" != typeof self ? self : commonjsGlobal, function () {
            return function (e) {
                function t(r) {
                    if (i[r]) return i[r].exports;
                    var n = i[r] = {
                        i: r,
                        l: !1,
                        exports: {}
                    };
                    return e[r].call(n.exports, n, n.exports, t), n.l = !0, n.exports;
                }
                var i = {};
                return t.m = e, t.c = i, t.d = function (e, i, r) {
                    t.o(e, i) || Object.defineProperty(e, i, {
                        configurable: !1,
                        enumerable: !0,
                        get: r
                    });
                }, t.n = function (e) {
                    var i = e && e.__esModule ? function () {
                        return e.default;
                    } : function () {
                        return e;
                    };
                    return t.d(i, "a", i), i;
                }, t.o = function (e, t) {
                    return Object.prototype.hasOwnProperty.call(e, t);
                }, t.p = "", t(t.s = 0);
            }([function (e, t, i) {
                function r(e, t) {
                    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
                }
                Object.defineProperty(t, "__esModule", {
                    value: !0
                });
                var n = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
                        return typeof e;
                    } : function (e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                    },
                    s = function () {
                        function e(e, t) {
                            for (var i = 0; i < t.length; i++) {
                                var r = t[i];
                                r.enumerable = r.enumerable || !1, r.configurable = !0, "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                            }
                        }
                        return function (t, i, r) {
                            return i && e(t.prototype, i), r && e(t, r), t;
                        };
                    }(),
                    l = function () {
                        function e(t) {
                            var i = this;
                            if (r(this, e), this.config = e.mergeSettings(t), this.selector = "string" == typeof this.config.selector ? document.querySelector(this.config.selector) : this.config.selector, null === this.selector) throw new Error("Something wrong with your selector 😭");
                            this.resolveSlidesNumber(), this.selectorWidth = this.selector.offsetWidth, this.innerElements = [].slice.call(this.selector.children), this.currentSlide = this.config.loop ? this.config.startIndex % this.innerElements.length : Math.max(0, Math.min(this.config.startIndex, this.innerElements.length - this.perPage)), this.transformProperty = e.webkitOrNot(), ["resizeHandler", "touchstartHandler", "touchendHandler", "touchmoveHandler", "mousedownHandler", "mouseupHandler", "mouseleaveHandler", "mousemoveHandler", "clickHandler"].forEach(function (e) {
                                i[e] = i[e].bind(i);
                            }), this.init();
                        }
                        return s(e, [{
                            key: "attachEvents",
                            value: function value() {
                                window.addEventListener("resize", this.resizeHandler), this.config.draggable && (this.pointerDown = !1, this.drag = {
                                    startX: 0,
                                    endX: 0,
                                    startY: 0,
                                    letItGo: null,
                                    preventClick: !1
                                }, this.selector.addEventListener("touchstart", this.touchstartHandler), this.selector.addEventListener("touchend", this.touchendHandler), this.selector.addEventListener("touchmove", this.touchmoveHandler), this.selector.addEventListener("mousedown", this.mousedownHandler), this.selector.addEventListener("mouseup", this.mouseupHandler), this.selector.addEventListener("mouseleave", this.mouseleaveHandler), this.selector.addEventListener("mousemove", this.mousemoveHandler), this.selector.addEventListener("click", this.clickHandler));
                            }
                        }, {
                            key: "detachEvents",
                            value: function value() {
                                window.removeEventListener("resize", this.resizeHandler), this.selector.removeEventListener("touchstart", this.touchstartHandler), this.selector.removeEventListener("touchend", this.touchendHandler), this.selector.removeEventListener("touchmove", this.touchmoveHandler), this.selector.removeEventListener("mousedown", this.mousedownHandler), this.selector.removeEventListener("mouseup", this.mouseupHandler), this.selector.removeEventListener("mouseleave", this.mouseleaveHandler), this.selector.removeEventListener("mousemove", this.mousemoveHandler), this.selector.removeEventListener("click", this.clickHandler);
                            }
                        }, {
                            key: "init",
                            value: function value() {
                                this.attachEvents(), this.selector.style.overflow = "hidden", this.selector.style.direction = this.config.rtl ? "rtl" : "ltr", this.buildSliderFrame(), this.config.onInit.call(this);
                            }
                        }, {
                            key: "buildSliderFrame",
                            value: function value() {
                                var e = this.selectorWidth / this.perPage,
                                    t = this.config.loop ? this.innerElements.length + 2 * this.perPage : this.innerElements.length;
                                this.sliderFrame = document.createElement("div"), this.sliderFrame.style.width = e * t + "px", this.enableTransition(), this.config.draggable && (this.selector.style.cursor = "-webkit-grab");
                                var i = document.createDocumentFragment();
                                if (this.config.loop)
                                    for (var r = this.innerElements.length - this.perPage; r < this.innerElements.length; r++) {
                                        var n = this.buildSliderFrameItem(this.innerElements[r].cloneNode(!0));
                                        i.appendChild(n);
                                    }
                                for (var s = 0; s < this.innerElements.length; s++) {
                                    var l = this.buildSliderFrameItem(this.innerElements[s]);
                                    i.appendChild(l);
                                }
                                if (this.config.loop)
                                    for (var o = 0; o < this.perPage; o++) {
                                        var a = this.buildSliderFrameItem(this.innerElements[o].cloneNode(!0));
                                        i.appendChild(a);
                                    }
                                this.sliderFrame.appendChild(i), this.selector.innerHTML = "", this.selector.appendChild(this.sliderFrame), this.slideToCurrent();
                            }
                        }, {
                            key: "buildSliderFrameItem",
                            value: function value(e) {
                                var t = document.createElement("div");
                                return t.style.cssFloat = this.config.rtl ? "right" : "left", t.style.float = this.config.rtl ? "right" : "left", t.style.width = (this.config.loop ? 100 / (this.innerElements.length + 2 * this.perPage) : 100 / this.innerElements.length) + "%", t.appendChild(e), t;
                            }
                        }, {
                            key: "resolveSlidesNumber",
                            value: function value() {
                                if ("number" == typeof this.config.perPage) this.perPage = this.config.perPage;
                                else if ("object" === n(this.config.perPage)) {
                                    this.perPage = 1;
                                    for (var e in this.config.perPage) window.innerWidth >= e && (this.perPage = this.config.perPage[e]);
                                }
                            }
                        }, {
                            key: "prev",
                            value: function value() {
                                var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 1,
                                    t = arguments[1];
                                if (!(this.innerElements.length <= this.perPage)) {
                                    var i = this.currentSlide;
                                    if (this.config.loop) {
                                        if (this.currentSlide - e < 0) {
                                            this.disableTransition();
                                            var r = this.currentSlide + this.innerElements.length,
                                                n = this.perPage,
                                                s = r + n,
                                                l = (this.config.rtl ? 1 : -1) * s * (this.selectorWidth / this.perPage),
                                                o = this.config.draggable ? this.drag.endX - this.drag.startX : 0;
                                            this.sliderFrame.style[this.transformProperty] = "translate3d(" + (l + o) + "px, 0, 0)", this.currentSlide = r - e;
                                        } else this.currentSlide = this.currentSlide - e;
                                    } else this.currentSlide = Math.max(this.currentSlide - e, 0);
                                    i !== this.currentSlide && (this.slideToCurrent(this.config.loop), this.config.onChange.call(this), t && t.call(this));
                                }
                            }
                        }, {
                            key: "next",
                            value: function value() {
                                var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 1,
                                    t = arguments[1];
                                if (!(this.innerElements.length <= this.perPage)) {
                                    var i = this.currentSlide;
                                    if (this.config.loop) {
                                        if (this.currentSlide + e > this.innerElements.length - this.perPage) {
                                            this.disableTransition();
                                            var r = this.currentSlide - this.innerElements.length,
                                                n = this.perPage,
                                                s = r + n,
                                                l = (this.config.rtl ? 1 : -1) * s * (this.selectorWidth / this.perPage),
                                                o = this.config.draggable ? this.drag.endX - this.drag.startX : 0;
                                            this.sliderFrame.style[this.transformProperty] = "translate3d(" + (l + o) + "px, 0, 0)", this.currentSlide = r + e;
                                        } else this.currentSlide = this.currentSlide + e;
                                    } else this.currentSlide = Math.min(this.currentSlide + e, this.innerElements.length - this.perPage);
                                    i !== this.currentSlide && (this.slideToCurrent(this.config.loop), this.config.onChange.call(this), t && t.call(this));
                                }
                            }
                        }, {
                            key: "disableTransition",
                            value: function value() {
                                this.sliderFrame.style.webkitTransition = "all 0ms " + this.config.easing, this.sliderFrame.style.transition = "all 0ms " + this.config.easing;
                            }
                        }, {
                            key: "enableTransition",
                            value: function value() {
                                this.sliderFrame.style.webkitTransition = "all " + this.config.duration + "ms " + this.config.easing, this.sliderFrame.style.transition = "all " + this.config.duration + "ms " + this.config.easing;
                            }
                        }, {
                            key: "goTo",
                            value: function value(e, t) {
                                if (!(this.innerElements.length <= this.perPage)) {
                                    var i = this.currentSlide;
                                    this.currentSlide = this.config.loop ? e % this.innerElements.length : Math.min(Math.max(e, 0), this.innerElements.length - this.perPage), i !== this.currentSlide && (this.slideToCurrent(), this.config.onChange.call(this), t && t.call(this));
                                }
                            }
                        }, {
                            key: "slideToCurrent",
                            value: function value(e) {
                                var t = this,
                                    i = this.config.loop ? this.currentSlide + this.perPage : this.currentSlide,
                                    r = (this.config.rtl ? 1 : -1) * i * (this.selectorWidth / this.perPage);
                                e ? requestAnimationFrame(function () {
                                    requestAnimationFrame(function () {
                                        t.enableTransition(), t.sliderFrame.style[t.transformProperty] = "translate3d(" + r + "px, 0, 0)";
                                    });
                                }) : this.sliderFrame.style[this.transformProperty] = "translate3d(" + r + "px, 0, 0)";
                            }
                        }, {
                            key: "updateAfterDrag",
                            value: function value() {
                                var e = (this.config.rtl ? -1 : 1) * (this.drag.endX - this.drag.startX),
                                    t = Math.abs(e),
                                    i = this.config.multipleDrag ? Math.ceil(t / (this.selectorWidth / this.perPage)) : 1,
                                    r = e > 0 && this.currentSlide - i < 0,
                                    n = e < 0 && this.currentSlide + i > this.innerElements.length - this.perPage;
                                e > 0 && t > this.config.threshold && this.innerElements.length > this.perPage ? this.prev(i) : e < 0 && t > this.config.threshold && this.innerElements.length > this.perPage && this.next(i), this.slideToCurrent(r || n);
                            }
                        }, {
                            key: "resizeHandler",
                            value: function value() {
                                this.resolveSlidesNumber(), this.currentSlide + this.perPage > this.innerElements.length && (this.currentSlide = this.innerElements.length <= this.perPage ? 0 : this.innerElements.length - this.perPage), this.selectorWidth = this.selector.offsetWidth, this.buildSliderFrame();
                            }
                        }, {
                            key: "clearDrag",
                            value: function value() {
                                this.drag = {
                                    startX: 0,
                                    endX: 0,
                                    startY: 0,
                                    letItGo: null,
                                    preventClick: this.drag.preventClick
                                };
                            }
                        }, {
                            key: "touchstartHandler",
                            value: function value(e) {
                                -1 !== ["TEXTAREA", "OPTION", "INPUT", "SELECT"].indexOf(e.target.nodeName) || (e.stopPropagation(), this.pointerDown = !0, this.drag.startX = e.touches[0].pageX, this.drag.startY = e.touches[0].pageY);
                            }
                        }, {
                            key: "touchendHandler",
                            value: function value(e) {
                                e.stopPropagation(), this.pointerDown = !1, this.enableTransition(), this.drag.endX && this.updateAfterDrag(), this.clearDrag();
                            }
                        }, {
                            key: "touchmoveHandler",
                            value: function value(e) {
                                if (e.stopPropagation(), null === this.drag.letItGo && (this.drag.letItGo = Math.abs(this.drag.startY - e.touches[0].pageY) < Math.abs(this.drag.startX - e.touches[0].pageX)), this.pointerDown && this.drag.letItGo) {
                                    e.preventDefault(), this.drag.endX = e.touches[0].pageX, this.sliderFrame.style.webkitTransition = "all 0ms " + this.config.easing, this.sliderFrame.style.transition = "all 0ms " + this.config.easing;
                                    var t = this.config.loop ? this.currentSlide + this.perPage : this.currentSlide,
                                        i = t * (this.selectorWidth / this.perPage),
                                        r = this.drag.endX - this.drag.startX,
                                        n = this.config.rtl ? i + r : i - r;
                                    this.sliderFrame.style[this.transformProperty] = "translate3d(" + (this.config.rtl ? 1 : -1) * n + "px, 0, 0)";
                                }
                            }
                        }, {
                            key: "mousedownHandler",
                            value: function value(e) {
                                -1 !== ["TEXTAREA", "OPTION", "INPUT", "SELECT"].indexOf(e.target.nodeName) || (e.preventDefault(), e.stopPropagation(), this.pointerDown = !0, this.drag.startX = e.pageX);
                            }
                        }, {
                            key: "mouseupHandler",
                            value: function value(e) {
                                e.stopPropagation(), this.pointerDown = !1, this.selector.style.cursor = "-webkit-grab", this.enableTransition(), this.drag.endX && this.updateAfterDrag(), this.clearDrag();
                            }
                        }, {
                            key: "mousemoveHandler",
                            value: function value(e) {
                                if (e.preventDefault(), this.pointerDown) {
                                    "A" === e.target.nodeName && (this.drag.preventClick = !0), this.drag.endX = e.pageX, this.selector.style.cursor = "-webkit-grabbing", this.sliderFrame.style.webkitTransition = "all 0ms " + this.config.easing, this.sliderFrame.style.transition = "all 0ms " + this.config.easing;
                                    var t = this.config.loop ? this.currentSlide + this.perPage : this.currentSlide,
                                        i = t * (this.selectorWidth / this.perPage),
                                        r = this.drag.endX - this.drag.startX,
                                        n = this.config.rtl ? i + r : i - r;
                                    this.sliderFrame.style[this.transformProperty] = "translate3d(" + (this.config.rtl ? 1 : -1) * n + "px, 0, 0)";
                                }
                            }
                        }, {
                            key: "mouseleaveHandler",
                            value: function value(e) {
                                this.pointerDown && (this.pointerDown = !1, this.selector.style.cursor = "-webkit-grab", this.drag.endX = e.pageX, this.drag.preventClick = !1, this.enableTransition(), this.updateAfterDrag(), this.clearDrag());
                            }
                        }, {
                            key: "clickHandler",
                            value: function value(e) {
                                this.drag.preventClick && e.preventDefault(), this.drag.preventClick = !1;
                            }
                        }, {
                            key: "remove",
                            value: function value(e, t) {
                                if (e < 0 || e >= this.innerElements.length) throw new Error("Item to remove doesn't exist 😭");
                                var i = e < this.currentSlide,
                                    r = this.currentSlide + this.perPage - 1 === e;
                                (i || r) && this.currentSlide--, this.innerElements.splice(e, 1), this.buildSliderFrame(), t && t.call(this);
                            }
                        }, {
                            key: "insert",
                            value: function value(e, t, i) {
                                if (t < 0 || t > this.innerElements.length + 1) throw new Error("Unable to inset it at this index 😭");
                                if (-1 !== this.innerElements.indexOf(e)) throw new Error("The same item in a carousel? Really? Nope 😭");
                                var r = t <= this.currentSlide > 0 && this.innerElements.length;
                                this.currentSlide = r ? this.currentSlide + 1 : this.currentSlide, this.innerElements.splice(t, 0, e), this.buildSliderFrame(), i && i.call(this);
                            }
                        }, {
                            key: "prepend",
                            value: function value(e, t) {
                                this.insert(e, 0), t && t.call(this);
                            }
                        }, {
                            key: "append",
                            value: function value(e, t) {
                                this.insert(e, this.innerElements.length + 1), t && t.call(this);
                            }
                        }, {
                            key: "destroy",
                            value: function value() {
                                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0],
                                    t = arguments[1];
                                if (this.detachEvents(), this.selector.style.cursor = "auto", e) {
                                    for (var i = document.createDocumentFragment(), r = 0; r < this.innerElements.length; r++) i.appendChild(this.innerElements[r]);
                                    this.selector.innerHTML = "", this.selector.appendChild(i), this.selector.removeAttribute("style");
                                }
                                t && t.call(this);
                            }
                        }], [{
                            key: "mergeSettings",
                            value: function value(e) {
                                var t = {
                                        selector: ".siema",
                                        duration: 200,
                                        easing: "ease-out",
                                        perPage: 1,
                                        startIndex: 0,
                                        draggable: !0,
                                        multipleDrag: !0,
                                        threshold: 20,
                                        loop: !1,
                                        rtl: !1,
                                        onInit: function onInit() {},
                                        onChange: function onChange() {}
                                    },
                                    i = e;
                                for (var r in i) t[r] = i[r];
                                return t;
                            }
                        }, {
                            key: "webkitOrNot",
                            value: function value() {
                                return "string" == typeof document.documentElement.style.transform ? "transform" : "WebkitTransform";
                            }
                        }]), e;
                    }();
                t.default = l, e.exports = t.default;
            }]);
        });
    }(siema_min));
    var siema_minExports = siema_min.exports;
    var Siema = /*@__PURE__*/ getDefaultExportFromCjs(siema_minExports);
    Siema.instances = [];

    function onEventOnly$3(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange$3(init) {
        var instance;
        while (instance = Siema.instances.pop()) {
            instance.destroy();
        }
        var sources = getElements('.siema');
        sources && toCount(sources) && sources.forEach(function (source) {
            var siema = new Siema({
                duration: 600,
                loop: true,
                selector: source
            });
            source._siemaInterval = W.setInterval(function () {
                return siema.next();
            }, 5000);
            onEventOnly$3('mousedown', source, onMouseDownSiema);
            onEventOnly$3('touchstart', source, onTouchStartSiema);
            Siema.instances.push(siema);
        });
        // Re-calculate the Siema dimension!
        {
            _.on('change.stack', function () {
                return fireEvent('resize', W);
            });
            _.on('change.tab', function () {
                return fireEvent('resize', W);
            });
        }
    }

    function onMouseDownSiema() {
        W.clearInterval(this._siemaInterval);
    }

    function onTouchStartSiema() {
        onMouseDownSiema.call(this);
    }
    W.Siema = Siema;
    var targets$2 = 'a[target^="stack:"]:not(.not\\:active)';

    function fireFocus$2(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onEventOnly$2(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange$2(init) {
        var sources = getElements('.lot\\:stacks[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var stackCurrent,
                stacks = [].slice.call(getChildren(source)).filter(function (v) {
                    return hasClass(v, 'lot:stack');
                }),
                input = setElement('input'),
                name;
            input.type = 'hidden';
            input.name = name = getDatum(source, 'name');
            name && setChildLast(source, input);
            stacks.forEach(function (stack) {
                var target = getElement(targets$2, stack);
                target._input = input;
                target._stacks = stacks;
                onEventOnly$2('click', target, onClickStack);
                onEventOnly$2('keydown', target, onKeyDownStack);
            });
            stackCurrent = stacks.find(function (value, key) {
                return 0 !== key && hasClass(value, 'is:current');
            });
            if (stackCurrent) {
                input.value = getDatum(stackCurrent, 'value');
            }
            onEventOnly$2('keydown', source, onKeyDownStacks);
        });
        1 === init && W._.on('change', onChange$2);
    }

    function onClickStack(e) {
        var t = this,
            parent = getParent(getParent(t)),
            self = getParent(parent, '.lot\\:stacks'),
            current,
            value;
        var name = t._input.name;
        if (!hasClass(parent, 'has:link')) {
            t._stacks.forEach(function (stack) {
                if (stack !== parent) {
                    letClass(current = getElement('a[target^="stack:"]', stack), 'is:current');
                    letClass(stack, 'is:current');
                    setAttribute(current, 'aria-expanded', 'false');
                }
            });
            if (hasClass(parent, 'can:toggle')) {
                setAttribute(t, 'aria-expanded', getAttribute(t, 'aria-expanded') ? 'false' : 'true');
                toggleClass(parent, 'is:current');
                toggleClass(t, 'is:current');
            } else {
                setAttribute(t, 'aria-expanded', 'true');
                setClass(parent, 'is:current');
                setClass(t, 'is:current');
            }
            current = hasClass(t, 'is:current');
            t._input.value = value = current ? getDatum(parent, 'value') : null;
            toggleClass(self, 'has:current', current);
            var pathname = theLocation.pathname,
                search = theLocation.search;
            var query = fromQuery(search);
            var q = fromQuery(name + '=' + value);
            if (null === value) {
                console.log('TODO: Remove query: `' + name + '`');
            }
            theHistory.replaceState({}, "", pathname + toQuery(fromStates(query, q.query || {})));
            W._.fire.apply(parent, ['change.stack', [value, name]]);
            offEventDefault(e);
        }
    }

    function onKeyDownStack(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            any,
            current,
            next,
            parent,
            prev,
            stop;
        if (!keyIsAlt && !keyIsCtrl) {
            if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
                if (parent = getParent(getParent(t))) {
                    next = getNext(parent);
                    while (next && hasClass(next, 'not:active')) {
                        next = getNext(next);
                    }
                }
                if (current = next && getChildFirst(next)) {
                    if ('ArrowRight' !== key || !hasClass(getParent(current), 'can:toggle')) {
                        fireEvent('click', getChildFirst(current));
                    }
                    fireFocus$2(getChildFirst(current));
                }
                stop = true;
            } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
                if (parent = getParent(getParent(t))) {
                    prev = getPrev(parent);
                    while (prev && hasClass(prev, 'not:active')) {
                        prev = getPrev(prev);
                    }
                }
                if (current = prev && getChildFirst(prev)) {
                    if ('ArrowLeft' !== key || !hasClass(getParent(current), 'can:toggle')) {
                        fireEvent('click', getChildFirst(current));
                    }
                    fireFocus$2(getChildFirst(current));
                }
                stop = true;
            } else if (' ' === key || 'Enter' === key) {
                if (hasClass(getParent(getParent(t)), 'can:toggle')) {
                    fireEvent('click', t), fireFocus$2(t);
                }
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:stacks[tabindex]')) {
                    any = [].slice.call(getElements(targets$2, parent));
                    if (current = any.pop()) {
                        fireEvent('click', current), fireFocus$2(current);
                    }
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:stacks[tabindex]')) {
                    if (current = getElement(targets$2, parent)) {
                        fireEvent('click', current), fireFocus$2(current);
                    }
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownStacks(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            current,
            next,
            prev,
            stop;
        if (keyIsAlt && keyIsCtrl && !keyIsShift) {
            current = getElement(targets$2 + '.is\\:current', t);
            current = current && getParent(getParent(current));
            if ('PageDown' === key) {
                next = current && getNext(current);
                if (current = next && getElement(targets$2, next)) {
                    fireEvent('click', current), fireFocus$2(current);
                }
                stop = true;
            } else if ('PageUp' === key) {
                prev = current && getPrev(current);
                if (current = prev && getElement(targets$2, prev)) {
                    fireEvent('click', current), fireFocus$2(current);
                }
                stop = true;
            }
        } else if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (t !== e.target) {
                return;
            }
            if ('ArrowDown' === key || 'ArrowRight' === key || 'Home' === key || 'PageDown' === key) {
                if (current = getElement(targets$2, t)) {
                    fireEvent('click', current), fireFocus$2(current);
                }
                stop = true;
            } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'End' === key || 'PageUp' === key) {
                any = [].slice.call(getElements(targets$2, t));
                if (current = any.pop()) {
                    fireEvent('click', current), fireFocus$2(current);
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$1 = 'a[target^="tab:"]:not(.not\\:active)';

    function fireFocus$1(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onEventOnly$1(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange$1(init) {
        var sources = getElements('.lot\\:tabs[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var panes = [].slice.call(getChildren(source)),
                tabCurrent,
                tabs = [].slice.call(getElements(targets$1, panes.shift())),
                input = setElement('input'),
                name;
            input.type = 'hidden';
            input.name = name = getDatum(source, 'name');
            name && setChildLast(source, input);
            tabs.forEach(function (tab, index) {
                tab._input = input;
                tab._of = index;
                tab._panes = panes;
                tab._tabs = tabs;
                onEventOnly$1('click', tab, onClickTab);
                onEventOnly$1('keydown', tab, onKeyDownTab);
            });
            tabCurrent = tabs.find(function (value, key) {
                return 0 !== key && hasClass(getParent(value), 'is:current');
            });
            if (tabCurrent) {
                input.value = getDatum(tabCurrent, 'value');
            }
            onEventOnly$1('keydown', source, onKeyDownTabs);
        });
        1 === init && W._.on('change', onChange$1);
    }

    function onClickTab(e) {
        var t = this,
            pane = t._panes[t._of],
            parent = getParent(t),
            self = getParent(parent, '.lot\\:tabs'),
            current,
            value;
        var name = t._input.name;
        if (!hasClass(parent, 'has:link')) {
            t._tabs.forEach(function (tab) {
                if (tab !== t) {
                    letClass(getParent(tab), 'is:current');
                    letClass(tab, 'is:current');
                    setAttribute(tab, 'aria-selected', 'false');
                    setAttribute(tab, 'tabindex', '-1');
                    var _pane = t._panes[tab._of];
                    _pane && letClass(_pane, 'is:current');
                }
            });
            if (hasClass(parent, 'can:toggle')) {
                toggleClass(parent, 'is:current');
                toggleClass(t, 'is:current');
                setAttribute(t, 'aria-selected', hasClass(t, 'is:current') ? 'true' : 'false');
                setAttribute(t, 'tabindex', hasClass(t, 'is:current') ? '0' : '-1');
            } else {
                setClass(parent, 'is:current');
                setClass(t, 'is:current');
                setAttribute(t, 'aria-selected', 'true');
                setAttribute(t, 'tabindex', '0');
            }
            current = hasClass(t, 'is:current');
            if (pane) {
                t._input.value = value = current ? getDatum(t, 'value') : null;
                toggleClass(pane, 'is:current', current);
                toggleClass(self, 'has:current', current);
                var pathname = theLocation.pathname,
                    search = theLocation.search;
                var query = fromQuery(search);
                var q = fromQuery(name + '=' + value);
                if (null === value) {
                    console.log('TODO: Remove query: `' + name + '`');
                }
                theHistory.replaceState({}, "", pathname + toQuery(fromStates(query, q.query || {})));
                W._.fire.apply(pane, ['change.tab', [value, name]]);
            }
            offEventDefault(e);
        }
    }

    function onKeyDownTab(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            any,
            current,
            next,
            parent,
            prev,
            stop;
        if (!keyIsAlt && !keyIsCtrl) {
            if ('ArrowDown' === key) {
                if (hasClass(t, 'can:toggle') && !hasClass(t, 'is:current')) {
                    current = t;
                } else {
                    if (parent = getParent(t)) {
                        next = getNext(parent);
                        while (next && hasClass(next, 'not:active')) {
                            next = getNext(next);
                        }
                    }
                    current = next && getChildFirst(next);
                }
                if (current) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if ('ArrowLeft' === key || 'PageUp' === key) {
                if (parent = getParent(t)) {
                    prev = getPrev(parent);
                    while (prev && hasClass(prev, 'not:active')) {
                        prev = getPrev(prev);
                    }
                }
                if (current = prev && getChildFirst(prev)) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if ('ArrowRight' === key || 'PageDown' === key) {
                if (parent = getParent(t)) {
                    next = getNext(parent);
                    while (next && hasClass(next, 'not:active')) {
                        next = getNext(next);
                    }
                }
                if (current = next && getChildFirst(next)) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if ('ArrowUp' === key) {
                if (hasClass(t, 'can:toggle') && hasClass(t, 'is:current')) {
                    current = t;
                } else {
                    if (parent = getParent(t)) {
                        prev = getPrev(parent);
                        while (prev && hasClass(prev, 'not:active')) {
                            prev = getPrev(prev);
                        }
                    }
                    current = prev && getChildFirst(prev);
                }
                if (current) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if (' ' === key || 'Enter' === key) {
                if (hasClass(t, 'can:toggle')) {
                    fireEvent('click', t), fireFocus$1(t);
                }
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:tabs[tabindex]')) {
                    any = [].slice.call(getElements(targets$1, parent));
                    if (current = any.pop()) {
                        fireEvent('click', current), fireFocus$1(current);
                    }
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:tabs[tabindex]')) {
                    if (current = getElement(targets$1, parent)) {
                        fireEvent('click', current), fireFocus$1(current);
                    }
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownTabs(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            current,
            next,
            prev,
            stop;
        if (keyIsAlt && keyIsCtrl && !keyIsShift) {
            current = getElement(targets$1 + '.is\\:current', t);
            current = current && getParent(current);
            if ('PageDown' === key) {
                next = current && getNext(current);
                if (current = next && getChildFirst(next)) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if ('PageUp' === key) {
                prev = current && getPrev(current);
                if (current = prev && getChildFirst(prev)) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            }
        } else if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (t !== e.target) {
                return;
            }
            if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
                if (current = getElement(targets$1 + '.is\\:current', t)) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
                if (current = getElement(targets$1 + '.is\\:current', t)) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if ('Home' === key) {
                if (current = getElement(targets$1, t)) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if ('End' === key) {
                any = [].slice.call(getElements(targets$1, t));
                if (current = any.pop()) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets = ':scope>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not\\:active)';

    function fireFocus(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function fireSelect(node) {
        node && isFunction(node.select) && node.select();
    }

    function onEventOnly(event, node, then) {
        offEvent(event, node, then);
        return onEvent(event, node, then);
    }

    function onChange(init) {
        var sources = getElements('.lot\\:tasks[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var tasks = getElements(targets, source);
            tasks && toCount(tasks) && tasks.forEach(function (task) {
                onEventOnly('keydown', task, onKeyDownTask);
            });
            onEventOnly('keydown', source, onKeyDownTasks);
        });
        1 === init && W._.on('change', onChange);
    }

    function onKeyDownTask(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            current,
            parent,
            next,
            prev,
            stop;
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            next = getNext(t);
            while (next && hasClass(next, 'not:active')) {
                next = getNext(next);
            }
            prev = getPrev(t);
            while (prev && hasClass(prev, 'not:active')) {
                prev = getPrev(prev);
            }
            if ('ArrowLeft' === key) {
                if (stop = !('selectionStart' in t && 0 !== t.selectionStart)) {
                    fireFocus(prev), fireSelect(prev);
                }
            } else if ('ArrowRight' === key) {
                if (stop = !('selectionEnd' in t && t.selectionEnd < toCount(t.value || ""))) {
                    fireFocus(next), fireSelect(next);
                }
            } else if ('End' === key) {
                stop = !('selectionEnd' in t && toCount(t.value || ""));
                if (stop && (parent = getParent(t, '.lot\\:tasks[tabindex]'))) {
                    any = [].slice.call(getElements(targets, parent));
                    if (current = any.pop()) {
                        fireFocus(current), fireSelect(current);
                    }
                }
            } else if ('Home' === key) {
                stop = !('selectionStart' in t && toCount(t.value || ""));
                if (stop && (parent = getParent(t, '.lot\\:tasks[tabindex]'))) {
                    if (current = getElement(targets, parent)) {
                        fireFocus(current), fireSelect(current);
                    }
                }
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownTasks(e) {
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            current,
            stop;
        if (t !== e.target) {
            return;
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if ('ArrowLeft' === key || 'End' === key) {
                any = [].slice.call(getElements(targets, t));
                if (current = any.pop()) {
                    fireFocus(current), fireSelect(current);
                }
                stop = true;
            } else if ('ArrowRight' === key || 'Home' === key) {
                if (current = getElement(targets, t)) {
                    fireFocus(current), fireSelect(current);
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    Key.instances = [];
    var bounce = debounce(function (map) {
        return map.pull();
    }, 1000);
    var map = new Key(W);
    Key.instances.push(map);
    map.keys['Escape'] = function () {
        var current = D.activeElement,
            parent = current && getParent(getParent(current), '[tabindex]:not(.not\\:active)');
        parent && parent.focus({
            // <https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/focus#focusvisible>
            focusVisible: true
        });
        return !parent;
    };
    map.keys['F3'] = function () {
        var mainSearchForm = getFormElement('get'),
            mainSearchFormInput = mainSearchForm && mainSearchForm.query;
        mainSearchFormInput && mainSearchFormInput.focus();
        return false;
    };
    map.keys['F10'] = function () {
        var firstBarFocusable = getElement('.lot\\:bar a:any-link'),
            parent;
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
    onEvent('blur', W, function (e) {
        this._event = e, map.pull();
    });
    onEvent('keydown', W, function (e) {
        this._event = e;
        map.push(e.key);
        var command = map.command();
        if (command) {
            var value = map.fire(command);
            if (false === value) {
                offEventDefault(e);
                offEventPropagation(e);
            } else if (null === value) {
                console.error('Unknown command:', command);
            }
        }
        bounce(map);
    });
    onEvent('keyup', W, function (e) {
        this._event = e, map.pull(e.key);
    });
    var _$1 = {
        commands: map.commands,
        keys: map.keys
    };
    var _hook = hook(_$1),
        fire = _hook.fire;
    _hook.hooks;
    _hook.off;
    _hook.on;
    W.Key = Key;
    W._ = _$1;
    onEvent('beforeload', D, function () {
        return fire('let');
    });
    onEvent('load', D, function () {
        return fire('get');
    });
    onEvent('DOMContentLoaded', D, function () {
        return fire('set');
    });
    onChange$c(1);
    Dialog();
    Fields();
    onChange$8(1);
    onChange$7(1);
    onChange$6(1);
    onChange$5(1);
    onChange$4(1);
    onChange$3();
    onChange$2(1);
    onChange$1(1);
    onChange(1);
})();