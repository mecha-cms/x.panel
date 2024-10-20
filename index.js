(function () {
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
    var hasValue = function hasValue(x, data) {
        return -1 !== data.indexOf(x);
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

    function removeNull(object) {
        if (isArray(object)) {
            var out = [];
            for (var i = 0, j = toCount(object); i < j; ++i) {
                if (null === object[i]) {
                    continue;
                }
                if (isArray(object[i])) {
                    if (null === (object[i] = removeNull(object[i])) || 0 === object[i].length) {
                        continue;
                    }
                } else if (isObject(object[i])) {
                    if (null === (object[i] = removeNull(object[i])) || 0 === toObjectCount(object[i])) {
                        continue;
                    }
                }
                out.push(object[i]);
            }
            return 0 !== toCount(out) ? out : false;
        }
        for (var k in object) {
            if (null === object[k]) {
                delete object[k];
                continue;
            }
            if (isArray(object[k]) || isObject(object[k])) {
                if (null === (object[k] = removeNull(object[k])) || 0 === toObjectCount(object[k])) {
                    delete object[k];
                }
            }
        }
        return 0 !== toObjectCount(object) ? object : false;
    }
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
        return node['previous' + (anyNode ? "" : 'Element') + 'Sibling'] || null;
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
    var toggleClass$1 = function toggleClass(node, name, force) {
        return node.classList.toggle(name, force), node;
    };
    var theHistory = W.history;
    var theLocation = W.location;
    var targets$8 = ':scope>:where([tabindex]):not([tabindex="-1"]):not(.not\\:active)';

    function onChange$d(init) {
        var sources = getElements('.lot\\:bar[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var items = getElements(targets$8, source);
            items.forEach(function (item) {
                onEventOnly('keydown', item, onKeyDownBarItem);
            });
            onEventOnly('keydown', source, onKeyDownBar);
        });
        1 === init && W._.on('change', onChange$d);
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
                fireFocus(any.pop());
                stop = true;
            } else if ('ArrowRight' === key || 'Home' === key) {
                fireFocus(getElement(targets$8, t));
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
            fireFocus(prev);
            stop = true;
        } else if ('ArrowRight' === key) {
            fireFocus(next);
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets$8, getParent(t)));
            fireFocus(any.pop());
            stop = true;
        } else if ('Home' === key) {
            fireFocus(getElement(targets$8, getParent(t)));
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
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
        target && (fireFocus(target), fireSelect(target));
        return new Promise(function (yay, nay) {
            dialog.c = then; // `c` for call-back
            dialog.v = yay; // `v` for check-mark
            dialog.x = nay; // `x` for cross-mark
            onEventOnly('cancel', dialog, onDialogCancel);
            onEventOnly('close', dialog, onDialogClose);
            onEventOnly('submit', dialog, onDialogSubmit);
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
            (prev = getPrev(t)) && fireFocus(prev);
            offEventDefault(e);
        } else if ('ArrowDown' === key || 'ArrowRight' === key) {
            (next = getNext(t)) && fireFocus(next);
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
    onEventOnly('keydown', dialogAlertTaskOkay, onDialogTaskKeyDown);
    onEventOnly('click', dialogAlertTaskOkay, onDialogTaskClick);
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
    onEventOnly('click', dialogConfirmTaskCancel, onDialogTaskClick);
    onEventOnly('click', dialogConfirmTaskOkay, onDialogTaskClick);
    onEventOnly('keydown', dialogConfirmTaskCancel, onDialogTaskKeyDown);
    onEventOnly('keydown', dialogConfirmTaskOkay, onDialogTaskKeyDown);
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
    onEventOnly('click', dialogPromptTaskCancel, onDialogTaskClick);
    onEventOnly('click', dialogPromptTaskOkay, onDialogTaskClick);
    onEventOnly('input', dialogPromptValue, onDialogPromptValueInput);
    onEventOnly('keydown', dialogPromptTaskCancel, onDialogTaskKeyDown);
    onEventOnly('keydown', dialogPromptTaskOkay, onDialogTaskKeyDown);
    onEventOnly('keyup', dialogPromptValue, onDialogPromptValueInput);
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
    var getRect$1 = function getRect(node) {
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

    function hook($, $$) {
        $$ = $$ || $;
        $$.fire = function (event, data, that) {
            var $ = this,
                hooks = $.hooks;
            if (!isSet(hooks[event])) {
                return $;
            }
            hooks[event].forEach(function (then) {
                return then.apply(that || $, data);
            });
            return $;
        };
        $$.off = function (event, then) {
            var $ = this,
                hooks = $.hooks;
            if (!isSet(event)) {
                return hooks = {}, $;
            }
            if (isSet(hooks[event])) {
                if (isSet(then)) {
                    var j = hooks[event].length;
                    // Clean-up empty hook(s)
                    if (0 === j) {
                        delete hooks[event];
                    } else {
                        for (var i = 0; i < j; ++i) {
                            if (then === hooks[event][i]) {
                                hooks[event].splice(i, 1);
                                break;
                            }
                        }
                    }
                } else {
                    delete hooks[event];
                }
            }
            return $;
        };
        $$.on = function (event, then) {
            var $ = this,
                hooks = $.hooks;
            if (!isSet(hooks[event])) {
                hooks[event] = [];
            }
            if (isSet(then)) {
                hooks[event].push(then);
            }
            return $;
        };
        return $.hooks = {}, $;
    }
    var name$4 = 'OP',
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
        if (source[name$4]) {
            return source[name$4];
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
        source[name$4] = $;

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
            toggleClass$1(selectBoxFake, classNameM + 'open', force);
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
                var _getRect = getRect$1(selectBoxFake),
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
            if (!source[name$4]) {
                return $; // Already ejected
            }
            delete source[name$4];
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
                toggleClass$1(selectBoxFakeOption, classNameOptionM + 'selected', selectBoxOption && getOptionSelected(selectBoxOption));
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

    function onChange$c(init) {
        // Destroy!
        var $;
        for (var key in OP.instances) {
            $ = OP.instances[key];
            $.pop();
            delete OP.instances[key];
        }
        var sources = getElements('input[list]:not([type=hidden]),select');
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            letClass(source, 'input');
            letClass(source, 'select');
            var c = getClasses(source);
            var $ = new OP(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            setClasses($.self, c);
        });
        1 === init && W._.on('change', onChange$c);
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
    var name$3 = 'TP';
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
        if (source[name$3]) {
            return source[name$3];
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
        source[name$3] = $;
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
            if (!source[name$3]) {
                return $; // Already ejected!
            }
            delete source[name$3];
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

    function onChange$b(init) {
        // Destroy!
        var $;
        for (var key in TP.instances) {
            $ = TP.instances[key];
            $.pop();
            delete TP.instances[key];
        }
        var sources = getElements('.lot\\:field.type\\:query input:not([type=hidden])');
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            letClass(source, 'input');
            var c = getClasses(source);
            var $ = new TP(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            setClasses($.self, c);
        });
        1 === init && W._.on('change', onChange$b);
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
    var name$2 = 'TextEditor';

    function getValue(self) {
        return (self.value || getAttribute(self, 'value') || "").replace(/\r/g, "");
    }

    function isDisabled(self) {
        return self.disabled;
    }

    function isReadOnly(self) {
        return self.readOnly;
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
        self['_' + name$2] = hook($, TextEditor.prototype);
        return $.attach(self, fromStates({}, TextEditor.state, isInteger(state) || isString(state) ? {
            tab: state
        } : state || {}));
    }
    TextEditor.esc = esc;
    TextEditor.state = {
        'n': 'text-editor',
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
    TextEditor.version = '4.1.5';
    TextEditor.x = x;
    Object.defineProperty(TextEditor, 'name', {
        value: name$2
    });
    var theValuePrevious;

    function theEvent(e) {
        var self = this,
            $ = self['_' + name$2],
            type = e.type,
            value = getValue(self);
        if (value !== theValuePrevious) {
            theValuePrevious = value;
            $.fire('change');
        }
        $.fire(events[type] || type, [e]);
    }
    var $$$1 = TextEditor.prototype;
    $$$1.$ = function () {
        var self = this.self;
        return new TextEditor.S(self.selectionStart, self.selectionEnd, getValue(self));
    };
    $$$1.attach = function (self, state) {
        var $ = this;
        self = self || $.self;
        if (state && (isInteger(state) || isString(state))) {
            state = {
                tab: state
            };
        }
        state = fromStates({}, $.state, state || {});
        if (hasClass(self, state.n + '__self')) {
            return $;
        }
        $._active = !isDisabled(self) && !isReadOnly(self);
        $._value = getValue(self);
        $.self = self;
        $.state = state;
        // Attach event(s)
        for (var event in events) {
            onEvent(event, self, theEvent);
        }
        setClass(self, state.n + '__self');
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
        return this.self.blur();
    };
    $$$1.detach = function () {
        var $ = this,
            self = $.self,
            state = $.state;
        if (!hasClass(self, state.n + '__self')) {
            return $;
        }
        $._active = false;
        // Detach event(s)
        for (var event in events) {
            offEvent(event, self, theEvent);
        }
        letClass(self, state.n + '__self');
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
            return self.focus(), $;
        }
        if (-1 === mode) {
            x = y = 0; // Put caret at the start of the editor, scroll to the start of the editor
        } else if (1 === mode) {
            x = toCount(getValue(self)); // Put caret at the end of the editor
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
        return !isDisabled(self) && getValue(self) || null;
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

    function onChange$a(init) {
        var instance;
        while (instance = TextEditor.instances.pop()) {
            instance.detach();
        }
        var sources = getElements('.lot\\:field.type\\:source textarea'),
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
            W._.on('change', onChange$a);
        }
    }
    W.TextEditor = TextEditor;

    function Fields() {
        onChange$c(1);
        onChange$b(1);
        onChange$a(1);
    }
    var targets$7 = ':scope>:where(.lot\\:file[tabindex],.lot\\:folder[tabindex]):not([tabindex="-1"]):not(.not\\:active)';

    function onChange$9(init) {
        var sources = getElements(':where(.lot\\:files,.lot\\:folders)[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var files = getElements(targets$7, source);
            files.forEach(function (file) {
                onEventOnly('keydown', file, onKeyDownFile);
            });
            onEventOnly('keydown', source, onKeyDownFiles);
        });
        1 === init && W._.on('change', onChange$9);
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
            fireFocus(next);
            stop = true;
        } else if ('ArrowUp' === key) {
            fireFocus(prev);
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets$7, getParent(t)));
            fireFocus(any.pop());
            stop = true;
        } else if ('Home' === key) {
            fireFocus(getElement(targets$7, getParent(t)));
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
                fireFocus(getElement(targets$7, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$7, t));
                fireFocus(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$6 = ':scope>ul>li>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not\\:active)';

    function onChange$8(init) {
        var sources = getElements('.lot\\:links[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var links = getElements(targets$6, source);
            links && toCount(links) && links.forEach(function (link) {
                onEventOnly('keydown', link, onKeyDownLink);
            });
            onEventOnly('keydown', source, onKeyDownLinks);
        });
        1 === init && W._.on('change', onChange$8);
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
                fireFocus(prev && getChildFirst(prev));
                stop = true;
            } else if ('Arrow' + (vertical ? 'Down' : 'Right') === key) {
                fireFocus(next && getChildFirst(next));
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                    any = [].slice.call(getElements(targets$6, parent));
                    fireFocus(any.pop());
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                    fireFocus(getElement(targets$6, parent));
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
                fireFocus(getElement(targets$6, t));
                stop = true;
            } else if ('Arrow' + (vertical ? 'Up' : 'Left') === key || 'End' === key) {
                any = [].slice.call(getElements(targets$6, t));
                fireFocus(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$5 = ':where(a,[tabindex]):not(.not\\:active)';

    function doHideMenus(but, trigger) {
        getElements('.lot\\:menu[tabindex].is\\:enter').forEach(function (node) {
            if (but !== node) {
                letClass(getParent(node), 'is:active');
                letClass(getPrev(node), 'is:active');
                letClass(node, 'is:enter');
                if (trigger) {
                    setAttribute(trigger, 'aria-expanded', 'false');
                }
                W._.fire('menu.exit', [], node);
            }
        });
    }

    function onChange$7(init) {
        var menuParents = getElements('.has\\:menu'),
            menuLinks = getElements('.lot\\:menu[tabindex]>ul>li>' + targets$5);
        if (menuParents && toCount(menuParents)) {
            menuParents.forEach(function (menuParent) {
                var menu = getElement('.lot\\:menu[tabindex]', menuParent),
                    a = getPrev(menu);
                if (menu && a) {
                    onEventOnly('click', a, onClickMenuShow);
                    onEventOnly('keydown', a, onKeyDownMenuToggle);
                }
            });
            onEventOnly('click', D, onClickDocument);
        }
        if (menuLinks && toCount(menuLinks)) {
            menuLinks.forEach(function (menuLink) {
                onEventOnly('keydown', menuLink, onKeyDownMenu$1);
            });
        }
        var sources = getElements('.lot\\:menu[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            onEventOnly('keydown', source, onKeyDownMenus$1);
        });
        1 === init && W._.on('change', onChange$7);
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
            toggleClass$1(current, 'is:enter');
            toggleClass$1(getParent(t), 'is:active');
            toggleClass$1(t, 'is:active');
            setAttribute(t, 'aria-expanded', hasClass(t, 'is:active') ? 'true' : 'false');
            W._.fire('menu.enter', [], current);
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
            fireFocus(next && getChildFirst(next));
            stop = true;
        } else if ('ArrowLeft' === key || 'Escape' === key || 'Tab' === key) {
            // Hide menu then focus to the parent menu link
            if (parent = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
                letClass(getParent(t), 'is:active');
                letClass(parent, 'is:enter');
                letClass(t, 'is:active');
                setAttribute(getPrev(parent), 'aria-expanded', 'false');
                if ('Tab' !== key) {
                    fireFocus(getPrev(parent));
                }
                // Focus to the self menu
            } else if ('Escape' === key) {
                fireFocus(getParent(t, '.lot\\:menu[tabindex]'));
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
                    fireFocus(getElement(targets$5, next));
                }, 1);
            }
            stop = true;
        } else if ('ArrowUp' === key) {
            if (current = prev && getChildFirst(prev)) {
                fireFocus(current);
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
                                fireFocus(current);
                            }, 1);
                        }
                    }
                }
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
                any = [].slice.call(getElements(targets$5, parent));
                fireFocus(any.pop());
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
                fireFocus(getElement(targets$5, parent));
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
                fireFocus(getElement(targets$5, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$5, t));
                fireFocus(any.pop());
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
                        fireFocus(getElement(targets$5, next));
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
                    fireFocus(getElement(targets$5, next));
                }, 1);
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$4 = ':scope>ul>li>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not\\:active)';

    function onChange$6(init) {
        var sources = getElements('.lot\\:menus[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var menus = getElements(targets$4, source);
            menus && toCount(menus) && menus.forEach(function (menu) {
                onEventOnly('keydown', menu, onKeyDownMenu);
            });
            onEventOnly('keydown', source, onKeyDownMenus);
        });
        1 === init && W._.on('change', onChange$6);
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
                fireFocus(prev && getChildFirst(prev));
                stop = true;
            } else if ('Arrow' + (vertical ? 'Down' : 'Right') === key) {
                fireFocus(next && getChildFirst(next));
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:menus[tabindex]')) {
                    any = [].slice.call(getElements(targets$4, parent));
                    fireFocus(any.pop());
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:menus[tabindex]')) {
                    fireFocus(getElement(targets$4, parent));
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
                fireFocus(getElement(targets$4, t));
                stop = true;
            } else if ('Arrow' + (vertical ? 'Up' : 'Left') === key || 'End' === key) {
                any = [].slice.call(getElements(targets$4, t));
                fireFocus(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$3 = ':scope>.lot\\:page[tabindex]:not([tabindex="-1"]):not(.not\\:active)';

    function onChange$5(init) {
        var sources = getElements('.lot\\:pages[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var pages = getElements(targets$3, source);
            pages.forEach(function (page) {
                onEventOnly('keydown', page, onKeyDownPage);
            });
            onEventOnly('keydown', source, onKeyDownPages);
        });
        1 === init && W._.on('change', onChange$5);
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
            fireFocus(next);
            stop = true;
        } else if ('ArrowUp' === key) {
            fireFocus(prev);
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets$3, getParent(t)));
            fireFocus(any.pop());
            stop = true;
        } else if ('Home' === key) {
            fireFocus(getElement(targets$3, getParent(t)));
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
                fireFocus(getElement(targets$3, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$3, t));
                fireFocus(any.pop());
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
    var SIEMA_INTERVAL = 0;

    function onChange$4(init) {
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
            source._ = source._ || {};
            source._[SIEMA_INTERVAL] = W.setInterval(function () {
                return siema.next();
            }, 5000);
            onEventOnly('mousedown', source, onMouseDownSiema);
            onEventOnly('touchstart', source, onTouchStartSiema);
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
        W.clearInterval(this._[SIEMA_INTERVAL]);
    }

    function onTouchStartSiema() {
        onMouseDownSiema.call(this);
    }
    W.Siema = Siema;
    /**!
     * Sortable 1.15.3
     * @author	RubaXa   <trash@rubaxa.org>
     * @author	owenm    <owen23355@gmail.com>
     * @license MIT
     */
    function ownKeys(object, enumerableOnly) {
        var keys = Object.keys(object);
        if (Object.getOwnPropertySymbols) {
            var symbols = Object.getOwnPropertySymbols(object);
            if (enumerableOnly) {
                symbols = symbols.filter(function (sym) {
                    return Object.getOwnPropertyDescriptor(object, sym).enumerable;
                });
            }
            keys.push.apply(keys, symbols);
        }
        return keys;
    }

    function _objectSpread2(target) {
        for (var i = 1; i < arguments.length; i++) {
            var source = arguments[i] != null ? arguments[i] : {};
            if (i % 2) {
                ownKeys(Object(source), true).forEach(function (key) {
                    _defineProperty(target, key, source[key]);
                });
            } else if (Object.getOwnPropertyDescriptors) {
                Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
            } else {
                ownKeys(Object(source)).forEach(function (key) {
                    Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
                });
            }
        }
        return target;
    }

    function _typeof(obj) {
        "@babel/helpers - typeof";
        if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
            _typeof = function _typeof(obj) {
                return typeof obj;
            };
        } else {
            _typeof = function _typeof(obj) {
                return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
            };
        }
        return _typeof(obj);
    }

    function _defineProperty(obj, key, value) {
        if (key in obj) {
            Object.defineProperty(obj, key, {
                value: value,
                enumerable: true,
                configurable: true,
                writable: true
            });
        } else {
            obj[key] = value;
        }
        return obj;
    }

    function _extends() {
        _extends = Object.assign || function (target) {
            for (var i = 1; i < arguments.length; i++) {
                var source = arguments[i];
                for (var key in source) {
                    if (Object.prototype.hasOwnProperty.call(source, key)) {
                        target[key] = source[key];
                    }
                }
            }
            return target;
        };
        return _extends.apply(this, arguments);
    }

    function _objectWithoutPropertiesLoose(source, excluded) {
        if (source == null) return {};
        var target = {};
        var sourceKeys = Object.keys(source);
        var key, i;
        for (i = 0; i < sourceKeys.length; i++) {
            key = sourceKeys[i];
            if (excluded.indexOf(key) >= 0) continue;
            target[key] = source[key];
        }
        return target;
    }

    function _objectWithoutProperties(source, excluded) {
        if (source == null) return {};
        var target = _objectWithoutPropertiesLoose(source, excluded);
        var key, i;
        if (Object.getOwnPropertySymbols) {
            var sourceSymbolKeys = Object.getOwnPropertySymbols(source);
            for (i = 0; i < sourceSymbolKeys.length; i++) {
                key = sourceSymbolKeys[i];
                if (excluded.indexOf(key) >= 0) continue;
                if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
                target[key] = source[key];
            }
        }
        return target;
    }

    function _toConsumableArray(arr) {
        return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread();
    }

    function _arrayWithoutHoles(arr) {
        if (Array.isArray(arr)) return _arrayLikeToArray(arr);
    }

    function _iterableToArray(iter) {
        if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
    }

    function _unsupportedIterableToArray(o, minLen) {
        if (!o) return;
        if (typeof o === "string") return _arrayLikeToArray(o, minLen);
        var n = Object.prototype.toString.call(o).slice(8, -1);
        if (n === "Object" && o.constructor) n = o.constructor.name;
        if (n === "Map" || n === "Set") return Array.from(o);
        if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
    }

    function _arrayLikeToArray(arr, len) {
        if (len == null || len > arr.length) len = arr.length;
        for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];
        return arr2;
    }

    function _nonIterableSpread() {
        throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }
    var version = "1.15.3";

    function userAgent(pattern) {
        if (typeof window !== 'undefined' && window.navigator) {
            return !! /*@__PURE__*/ navigator.userAgent.match(pattern);
        }
    }
    var IE11OrLess = userAgent(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i);
    var Edge = userAgent(/Edge/i);
    var FireFox = userAgent(/firefox/i);
    var Safari = userAgent(/safari/i) && !userAgent(/chrome/i) && !userAgent(/android/i);
    var IOS = userAgent(/iP(ad|od|hone)/i);
    var ChromeForAndroid = userAgent(/chrome/i) && userAgent(/android/i);
    var captureMode = {
        capture: false,
        passive: false
    };

    function on(el, event, fn) {
        el.addEventListener(event, fn, !IE11OrLess && captureMode);
    }

    function off(el, event, fn) {
        el.removeEventListener(event, fn, !IE11OrLess && captureMode);
    }

    function matches( /**HTMLElement*/ el, /**String*/ selector) {
        if (!selector) return;
        selector[0] === '>' && (selector = selector.substring(1));
        if (el) {
            try {
                if (el.matches) {
                    return el.matches(selector);
                } else if (el.msMatchesSelector) {
                    return el.msMatchesSelector(selector);
                } else if (el.webkitMatchesSelector) {
                    return el.webkitMatchesSelector(selector);
                }
            } catch (_) {
                return false;
            }
        }
        return false;
    }

    function getParentOrHost(el) {
        return el.host && el !== document && el.host.nodeType ? el.host : el.parentNode;
    }

    function closest( /**HTMLElement*/ el, /**String*/ selector, /**HTMLElement*/ ctx, includeCTX) {
        if (el) {
            ctx = ctx || document;
            do {
                if (selector != null && (selector[0] === '>' ? el.parentNode === ctx && matches(el, selector) : matches(el, selector)) || includeCTX && el === ctx) {
                    return el;
                }
                if (el === ctx) break;
                /* jshint boss:true */
            } while (el = getParentOrHost(el));
        }
        return null;
    }
    var R_SPACE = /\s+/g;

    function toggleClass(el, name, state) {
        if (el && name) {
            if (el.classList) {
                el.classList[state ? 'add' : 'remove'](name);
            } else {
                var className = (' ' + el.className + ' ').replace(R_SPACE, ' ').replace(' ' + name + ' ', ' ');
                el.className = (className + (state ? ' ' + name : '')).replace(R_SPACE, ' ');
            }
        }
    }

    function css(el, prop, val) {
        var style = el && el.style;
        if (style) {
            if (val === void 0) {
                if (document.defaultView && document.defaultView.getComputedStyle) {
                    val = document.defaultView.getComputedStyle(el, '');
                } else if (el.currentStyle) {
                    val = el.currentStyle;
                }
                return prop === void 0 ? val : val[prop];
            } else {
                if (!(prop in style) && prop.indexOf('webkit') === -1) {
                    prop = '-webkit-' + prop;
                }
                style[prop] = val + (typeof val === 'string' ? '' : 'px');
            }
        }
    }

    function matrix(el, selfOnly) {
        var appliedTransforms = '';
        if (typeof el === 'string') {
            appliedTransforms = el;
        } else {
            do {
                var transform = css(el, 'transform');
                if (transform && transform !== 'none') {
                    appliedTransforms = transform + ' ' + appliedTransforms;
                }
                /* jshint boss:true */
            } while (!selfOnly && (el = el.parentNode));
        }
        var matrixFn = window.DOMMatrix || window.WebKitCSSMatrix || window.CSSMatrix || window.MSCSSMatrix;
        /*jshint -W056 */
        return matrixFn && new matrixFn(appliedTransforms);
    }

    function find(ctx, tagName, iterator) {
        if (ctx) {
            var list = ctx.getElementsByTagName(tagName),
                i = 0,
                n = list.length;
            if (iterator) {
                for (; i < n; i++) {
                    iterator(list[i], i);
                }
            }
            return list;
        }
        return [];
    }

    function getWindowScrollingElement() {
        var scrollingElement = document.scrollingElement;
        if (scrollingElement) {
            return scrollingElement;
        } else {
            return document.documentElement;
        }
    }
    /**
     * Returns the "bounding client rect" of given element
     * @param  {HTMLElement} el                       The element whose boundingClientRect is wanted
     * @param  {[Boolean]} relativeToContainingBlock  Whether the rect should be relative to the containing block of (including) the container
     * @param  {[Boolean]} relativeToNonStaticParent  Whether the rect should be relative to the relative parent of (including) the contaienr
     * @param  {[Boolean]} undoScale                  Whether the container's scale() should be undone
     * @param  {[HTMLElement]} container              The parent the element will be placed in
     * @return {Object}                               The boundingClientRect of el, with specified adjustments
     */
    function getRect(el, relativeToContainingBlock, relativeToNonStaticParent, undoScale, container) {
        if (!el.getBoundingClientRect && el !== window) return;
        var elRect, top, left, bottom, right, height, width;
        if (el !== window && el.parentNode && el !== getWindowScrollingElement()) {
            elRect = el.getBoundingClientRect();
            top = elRect.top;
            left = elRect.left;
            bottom = elRect.bottom;
            right = elRect.right;
            height = elRect.height;
            width = elRect.width;
        } else {
            top = 0;
            left = 0;
            bottom = window.innerHeight;
            right = window.innerWidth;
            height = window.innerHeight;
            width = window.innerWidth;
        }
        if ((relativeToContainingBlock || relativeToNonStaticParent) && el !== window) {
            // Adjust for translate()
            container = container || el.parentNode;
            // solves #1123 (see: https://stackoverflow.com/a/37953806/6088312)
            // Not needed on <= IE11
            if (!IE11OrLess) {
                do {
                    if (container && container.getBoundingClientRect && (css(container, 'transform') !== 'none' || relativeToNonStaticParent && css(container, 'position') !== 'static')) {
                        var containerRect = container.getBoundingClientRect();
                        // Set relative to edges of padding box of container
                        top -= containerRect.top + parseInt(css(container, 'border-top-width'));
                        left -= containerRect.left + parseInt(css(container, 'border-left-width'));
                        bottom = top + elRect.height;
                        right = left + elRect.width;
                        break;
                    }
                    /* jshint boss:true */
                } while (container = container.parentNode);
            }
        }
        if (undoScale && el !== window) {
            // Adjust for scale()
            var elMatrix = matrix(container || el),
                scaleX = elMatrix && elMatrix.a,
                scaleY = elMatrix && elMatrix.d;
            if (elMatrix) {
                top /= scaleY;
                left /= scaleX;
                width /= scaleX;
                height /= scaleY;
                bottom = top + height;
                right = left + width;
            }
        }
        return {
            top: top,
            left: left,
            bottom: bottom,
            right: right,
            width: width,
            height: height
        };
    }
    /**
     * Checks if a side of an element is scrolled past a side of its parents
     * @param  {HTMLElement}  el           The element who's side being scrolled out of view is in question
     * @param  {String}       elSide       Side of the element in question ('top', 'left', 'right', 'bottom')
     * @param  {String}       parentSide   Side of the parent in question ('top', 'left', 'right', 'bottom')
     * @return {HTMLElement}               The parent scroll element that the el's side is scrolled past, or null if there is no such element
     */
    function isScrolledPast(el, elSide, parentSide) {
        var parent = getParentAutoScrollElement(el, true),
            elSideVal = getRect(el)[elSide];
        /* jshint boss:true */
        while (parent) {
            var parentSideVal = getRect(parent)[parentSide],
                visible = void 0;
            {
                visible = elSideVal >= parentSideVal;
            }
            if (!visible) return parent;
            if (parent === getWindowScrollingElement()) break;
            parent = getParentAutoScrollElement(parent, false);
        }
        return false;
    }
    /**
     * Gets nth child of el, ignoring hidden children, sortable's elements (does not ignore clone if it's visible)
     * and non-draggable elements
     * @param  {HTMLElement} el       The parent element
     * @param  {Number} childNum      The index of the child
     * @param  {Object} options       Parent Sortable's options
     * @return {HTMLElement}          The child at index childNum, or null if not found
     */
    function getChild(el, childNum, options, includeDragEl) {
        var currentChild = 0,
            i = 0,
            children = el.children;
        while (i < children.length) {
            if (children[i].style.display !== 'none' && children[i] !== Sortable.ghost && (includeDragEl || children[i] !== Sortable.dragged) && closest(children[i], options.draggable, el, false)) {
                if (currentChild === childNum) {
                    return children[i];
                }
                currentChild++;
            }
            i++;
        }
        return null;
    }
    /**
     * Gets the last child in the el, ignoring ghostEl or invisible elements (clones)
     * @param  {HTMLElement} el       Parent element
     * @param  {selector} selector    Any other elements that should be ignored
     * @return {HTMLElement}          The last child, ignoring ghostEl
     */
    function lastChild(el, selector) {
        var last = el.lastElementChild;
        while (last && (last === Sortable.ghost || css(last, 'display') === 'none' || selector && !matches(last, selector))) {
            last = last.previousElementSibling;
        }
        return last || null;
    }
    /**
     * Returns the index of an element within its parent for a selected set of
     * elements
     * @param  {HTMLElement} el
     * @param  {selector} selector
     * @return {number}
     */
    function index(el, selector) {
        var index = 0;
        if (!el || !el.parentNode) {
            return -1;
        }
        /* jshint boss:true */
        while (el = el.previousElementSibling) {
            if (el.nodeName.toUpperCase() !== 'TEMPLATE' && el !== Sortable.clone && (!selector || matches(el, selector))) {
                index++;
            }
        }
        return index;
    }
    /**
     * Returns the scroll offset of the given element, added with all the scroll offsets of parent elements.
     * The value is returned in real pixels.
     * @param  {HTMLElement} el
     * @return {Array}             Offsets in the format of [left, top]
     */
    function getRelativeScrollOffset(el) {
        var offsetLeft = 0,
            offsetTop = 0,
            winScroller = getWindowScrollingElement();
        if (el) {
            do {
                var elMatrix = matrix(el),
                    scaleX = elMatrix.a,
                    scaleY = elMatrix.d;
                offsetLeft += el.scrollLeft * scaleX;
                offsetTop += el.scrollTop * scaleY;
            } while (el !== winScroller && (el = el.parentNode));
        }
        return [offsetLeft, offsetTop];
    }
    /**
     * Returns the index of the object within the given array
     * @param  {Array} arr   Array that may or may not hold the object
     * @param  {Object} obj  An object that has a key-value pair unique to and identical to a key-value pair in the object you want to find
     * @return {Number}      The index of the object in the array, or -1
     */
    function indexOfObject(arr, obj) {
        for (var i in arr) {
            if (!arr.hasOwnProperty(i)) continue;
            for (var key in obj) {
                if (obj.hasOwnProperty(key) && obj[key] === arr[i][key]) return Number(i);
            }
        }
        return -1;
    }

    function getParentAutoScrollElement(el, includeSelf) {
        // skip to window
        if (!el || !el.getBoundingClientRect) return getWindowScrollingElement();
        var elem = el;
        var gotSelf = false;
        do {
            // we don't need to get elem css if it isn't even overflowing in the first place (performance)
            if (elem.clientWidth < elem.scrollWidth || elem.clientHeight < elem.scrollHeight) {
                var elemCSS = css(elem);
                if (elem.clientWidth < elem.scrollWidth && (elemCSS.overflowX == 'auto' || elemCSS.overflowX == 'scroll') || elem.clientHeight < elem.scrollHeight && (elemCSS.overflowY == 'auto' || elemCSS.overflowY == 'scroll')) {
                    if (!elem.getBoundingClientRect || elem === document.body) return getWindowScrollingElement();
                    if (gotSelf || includeSelf) return elem;
                    gotSelf = true;
                }
            }
            /* jshint boss:true */
        } while (elem = elem.parentNode);
        return getWindowScrollingElement();
    }

    function extend(dst, src) {
        if (dst && src) {
            for (var key in src) {
                if (src.hasOwnProperty(key)) {
                    dst[key] = src[key];
                }
            }
        }
        return dst;
    }

    function isRectEqual(rect1, rect2) {
        return Math.round(rect1.top) === Math.round(rect2.top) && Math.round(rect1.left) === Math.round(rect2.left) && Math.round(rect1.height) === Math.round(rect2.height) && Math.round(rect1.width) === Math.round(rect2.width);
    }
    var _throttleTimeout;

    function throttle(callback, ms) {
        return function () {
            if (!_throttleTimeout) {
                var args = arguments,
                    _this = this;
                if (args.length === 1) {
                    callback.call(_this, args[0]);
                } else {
                    callback.apply(_this, args);
                }
                _throttleTimeout = setTimeout(function () {
                    _throttleTimeout = void 0;
                }, ms);
            }
        };
    }

    function cancelThrottle() {
        clearTimeout(_throttleTimeout);
        _throttleTimeout = void 0;
    }

    function scrollBy(el, x, y) {
        el.scrollLeft += x;
        el.scrollTop += y;
    }

    function clone(el) {
        var Polymer = window.Polymer;
        var $ = window.jQuery || window.Zepto;
        if (Polymer && Polymer.dom) {
            return Polymer.dom(el).cloneNode(true);
        } else if ($) {
            return $(el).clone(true)[0];
        } else {
            return el.cloneNode(true);
        }
    }

    function setRect(el, rect) {
        css(el, 'position', 'absolute');
        css(el, 'top', rect.top);
        css(el, 'left', rect.left);
        css(el, 'width', rect.width);
        css(el, 'height', rect.height);
    }

    function unsetRect(el) {
        css(el, 'position', '');
        css(el, 'top', '');
        css(el, 'left', '');
        css(el, 'width', '');
        css(el, 'height', '');
    }

    function getChildContainingRectFromElement(container, options, ghostEl) {
        var rect = {};
        Array.from(container.children).forEach(function (child) {
            var _rect$left, _rect$top, _rect$right, _rect$bottom;
            if (!closest(child, options.draggable, container, false) || child.animated || child === ghostEl) return;
            var childRect = getRect(child);
            rect.left = Math.min((_rect$left = rect.left) !== null && _rect$left !== void 0 ? _rect$left : Infinity, childRect.left);
            rect.top = Math.min((_rect$top = rect.top) !== null && _rect$top !== void 0 ? _rect$top : Infinity, childRect.top);
            rect.right = Math.max((_rect$right = rect.right) !== null && _rect$right !== void 0 ? _rect$right : -Infinity, childRect.right);
            rect.bottom = Math.max((_rect$bottom = rect.bottom) !== null && _rect$bottom !== void 0 ? _rect$bottom : -Infinity, childRect.bottom);
        });
        rect.width = rect.right - rect.left;
        rect.height = rect.bottom - rect.top;
        rect.x = rect.left;
        rect.y = rect.top;
        return rect;
    }
    var expando = 'Sortable' + new Date().getTime();

    function AnimationStateManager() {
        var animationStates = [],
            animationCallbackId;
        return {
            captureAnimationState: function captureAnimationState() {
                animationStates = [];
                if (!this.options.animation) return;
                var children = [].slice.call(this.el.children);
                children.forEach(function (child) {
                    if (css(child, 'display') === 'none' || child === Sortable.ghost) return;
                    animationStates.push({
                        target: child,
                        rect: getRect(child)
                    });
                    var fromRect = _objectSpread2({}, animationStates[animationStates.length - 1].rect);
                    // If animating: compensate for current animation
                    if (child.thisAnimationDuration) {
                        var childMatrix = matrix(child, true);
                        if (childMatrix) {
                            fromRect.top -= childMatrix.f;
                            fromRect.left -= childMatrix.e;
                        }
                    }
                    child.fromRect = fromRect;
                });
            },
            addAnimationState: function addAnimationState(state) {
                animationStates.push(state);
            },
            removeAnimationState: function removeAnimationState(target) {
                animationStates.splice(indexOfObject(animationStates, {
                    target: target
                }), 1);
            },
            animateAll: function animateAll(callback) {
                var _this = this;
                if (!this.options.animation) {
                    clearTimeout(animationCallbackId);
                    if (typeof callback === 'function') callback();
                    return;
                }
                var animating = false,
                    animationTime = 0;
                animationStates.forEach(function (state) {
                    var time = 0,
                        target = state.target,
                        fromRect = target.fromRect,
                        toRect = getRect(target),
                        prevFromRect = target.prevFromRect,
                        prevToRect = target.prevToRect,
                        animatingRect = state.rect,
                        targetMatrix = matrix(target, true);
                    if (targetMatrix) {
                        // Compensate for current animation
                        toRect.top -= targetMatrix.f;
                        toRect.left -= targetMatrix.e;
                    }
                    target.toRect = toRect;
                    if (target.thisAnimationDuration) {
                        // Could also check if animatingRect is between fromRect and toRect
                        if (isRectEqual(prevFromRect, toRect) && !isRectEqual(fromRect, toRect) &&
                            // Make sure animatingRect is on line between toRect & fromRect
                            (animatingRect.top - toRect.top) / (animatingRect.left - toRect.left) === (fromRect.top - toRect.top) / (fromRect.left - toRect.left)) {
                            // If returning to same place as started from animation and on same axis
                            time = calculateRealTime(animatingRect, prevFromRect, prevToRect, _this.options);
                        }
                    }
                    // if fromRect != toRect: animate
                    if (!isRectEqual(toRect, fromRect)) {
                        target.prevFromRect = fromRect;
                        target.prevToRect = toRect;
                        if (!time) {
                            time = _this.options.animation;
                        }
                        _this.animate(target, animatingRect, toRect, time);
                    }
                    if (time) {
                        animating = true;
                        animationTime = Math.max(animationTime, time);
                        clearTimeout(target.animationResetTimer);
                        target.animationResetTimer = setTimeout(function () {
                            target.animationTime = 0;
                            target.prevFromRect = null;
                            target.fromRect = null;
                            target.prevToRect = null;
                            target.thisAnimationDuration = null;
                        }, time);
                        target.thisAnimationDuration = time;
                    }
                });
                clearTimeout(animationCallbackId);
                if (!animating) {
                    if (typeof callback === 'function') callback();
                } else {
                    animationCallbackId = setTimeout(function () {
                        if (typeof callback === 'function') callback();
                    }, animationTime);
                }
                animationStates = [];
            },
            animate: function animate(target, currentRect, toRect, duration) {
                if (duration) {
                    css(target, 'transition', '');
                    css(target, 'transform', '');
                    var elMatrix = matrix(this.el),
                        scaleX = elMatrix && elMatrix.a,
                        scaleY = elMatrix && elMatrix.d,
                        translateX = (currentRect.left - toRect.left) / (scaleX || 1),
                        translateY = (currentRect.top - toRect.top) / (scaleY || 1);
                    target.animatingX = !!translateX;
                    target.animatingY = !!translateY;
                    css(target, 'transform', 'translate3d(' + translateX + 'px,' + translateY + 'px,0)');
                    this.forRepaintDummy = repaint(target); // repaint
                    css(target, 'transition', 'transform ' + duration + 'ms' + (this.options.easing ? ' ' + this.options.easing : ''));
                    css(target, 'transform', 'translate3d(0,0,0)');
                    typeof target.animated === 'number' && clearTimeout(target.animated);
                    target.animated = setTimeout(function () {
                        css(target, 'transition', '');
                        css(target, 'transform', '');
                        target.animated = false;
                        target.animatingX = false;
                        target.animatingY = false;
                    }, duration);
                }
            }
        };
    }

    function repaint(target) {
        return target.offsetWidth;
    }

    function calculateRealTime(animatingRect, fromRect, toRect, options) {
        return Math.sqrt(Math.pow(fromRect.top - animatingRect.top, 2) + Math.pow(fromRect.left - animatingRect.left, 2)) / Math.sqrt(Math.pow(fromRect.top - toRect.top, 2) + Math.pow(fromRect.left - toRect.left, 2)) * options.animation;
    }
    var plugins = [];
    var defaults = {
        initializeByDefault: true
    };
    var PluginManager = {
        mount: function mount(plugin) {
            // Set default static properties
            for (var option in defaults) {
                if (defaults.hasOwnProperty(option) && !(option in plugin)) {
                    plugin[option] = defaults[option];
                }
            }
            plugins.forEach(function (p) {
                if (p.pluginName === plugin.pluginName) {
                    throw "Sortable: Cannot mount plugin ".concat(plugin.pluginName, " more than once");
                }
            });
            plugins.push(plugin);
        },
        pluginEvent: function pluginEvent(eventName, sortable, evt) {
            var _this = this;
            this.eventCanceled = false;
            evt.cancel = function () {
                _this.eventCanceled = true;
            };
            var eventNameGlobal = eventName + 'Global';
            plugins.forEach(function (plugin) {
                if (!sortable[plugin.pluginName]) return;
                // Fire global events if it exists in this sortable
                if (sortable[plugin.pluginName][eventNameGlobal]) {
                    sortable[plugin.pluginName][eventNameGlobal](_objectSpread2({
                        sortable: sortable
                    }, evt));
                }
                // Only fire plugin event if plugin is enabled in this sortable,
                // and plugin has event defined
                if (sortable.options[plugin.pluginName] && sortable[plugin.pluginName][eventName]) {
                    sortable[plugin.pluginName][eventName](_objectSpread2({
                        sortable: sortable
                    }, evt));
                }
            });
        },
        initializePlugins: function initializePlugins(sortable, el, defaults, options) {
            plugins.forEach(function (plugin) {
                var pluginName = plugin.pluginName;
                if (!sortable.options[pluginName] && !plugin.initializeByDefault) return;
                var initialized = new plugin(sortable, el, sortable.options);
                initialized.sortable = sortable;
                initialized.options = sortable.options;
                sortable[pluginName] = initialized;
                // Add default options from plugin
                _extends(defaults, initialized.defaults);
            });
            for (var option in sortable.options) {
                if (!sortable.options.hasOwnProperty(option)) continue;
                var modified = this.modifyOption(sortable, option, sortable.options[option]);
                if (typeof modified !== 'undefined') {
                    sortable.options[option] = modified;
                }
            }
        },
        getEventProperties: function getEventProperties(name, sortable) {
            var eventProperties = {};
            plugins.forEach(function (plugin) {
                if (typeof plugin.eventProperties !== 'function') return;
                _extends(eventProperties, plugin.eventProperties.call(sortable[plugin.pluginName], name));
            });
            return eventProperties;
        },
        modifyOption: function modifyOption(sortable, name, value) {
            var modifiedValue;
            plugins.forEach(function (plugin) {
                // Plugin must exist on the Sortable
                if (!sortable[plugin.pluginName]) return;
                // If static option listener exists for this option, call in the context of the Sortable's instance of this plugin
                if (plugin.optionListeners && typeof plugin.optionListeners[name] === 'function') {
                    modifiedValue = plugin.optionListeners[name].call(sortable[plugin.pluginName], value);
                }
            });
            return modifiedValue;
        }
    };

    function dispatchEvent(_ref) {
        var sortable = _ref.sortable,
            rootEl = _ref.rootEl,
            name = _ref.name,
            targetEl = _ref.targetEl,
            cloneEl = _ref.cloneEl,
            toEl = _ref.toEl,
            fromEl = _ref.fromEl,
            oldIndex = _ref.oldIndex,
            newIndex = _ref.newIndex,
            oldDraggableIndex = _ref.oldDraggableIndex,
            newDraggableIndex = _ref.newDraggableIndex,
            originalEvent = _ref.originalEvent,
            putSortable = _ref.putSortable,
            extraEventProperties = _ref.extraEventProperties;
        sortable = sortable || rootEl && rootEl[expando];
        if (!sortable) return;
        var evt,
            options = sortable.options,
            onName = 'on' + name.charAt(0).toUpperCase() + name.substr(1);
        // Support for new CustomEvent feature
        if (window.CustomEvent && !IE11OrLess && !Edge) {
            evt = new CustomEvent(name, {
                bubbles: true,
                cancelable: true
            });
        } else {
            evt = document.createEvent('Event');
            evt.initEvent(name, true, true);
        }
        evt.to = toEl || rootEl;
        evt.from = fromEl || rootEl;
        evt.item = targetEl || rootEl;
        evt.clone = cloneEl;
        evt.oldIndex = oldIndex;
        evt.newIndex = newIndex;
        evt.oldDraggableIndex = oldDraggableIndex;
        evt.newDraggableIndex = newDraggableIndex;
        evt.originalEvent = originalEvent;
        evt.pullMode = putSortable ? putSortable.lastPutMode : undefined;
        var allEventProperties = _objectSpread2(_objectSpread2({}, extraEventProperties), PluginManager.getEventProperties(name, sortable));
        for (var option in allEventProperties) {
            evt[option] = allEventProperties[option];
        }
        if (rootEl) {
            rootEl.dispatchEvent(evt);
        }
        if (options[onName]) {
            options[onName].call(sortable, evt);
        }
    }
    var _excluded = ["evt"];
    var pluginEvent = function pluginEvent(eventName, sortable) {
        var _ref = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {},
            originalEvent = _ref.evt,
            data = _objectWithoutProperties(_ref, _excluded);
        PluginManager.pluginEvent.bind(Sortable)(eventName, sortable, _objectSpread2({
            dragEl: dragEl,
            parentEl: parentEl,
            ghostEl: ghostEl,
            rootEl: rootEl,
            nextEl: nextEl,
            lastDownEl: lastDownEl,
            cloneEl: cloneEl,
            cloneHidden: cloneHidden,
            dragStarted: moved,
            putSortable: putSortable,
            activeSortable: Sortable.active,
            originalEvent: originalEvent,
            oldIndex: oldIndex,
            oldDraggableIndex: oldDraggableIndex,
            newIndex: newIndex,
            newDraggableIndex: newDraggableIndex,
            hideGhostForTarget: _hideGhostForTarget,
            unhideGhostForTarget: _unhideGhostForTarget,
            cloneNowHidden: function cloneNowHidden() {
                cloneHidden = true;
            },
            cloneNowShown: function cloneNowShown() {
                cloneHidden = false;
            },
            dispatchSortableEvent: function dispatchSortableEvent(name) {
                _dispatchEvent({
                    sortable: sortable,
                    name: name,
                    originalEvent: originalEvent
                });
            }
        }, data));
    };

    function _dispatchEvent(info) {
        dispatchEvent(_objectSpread2({
            putSortable: putSortable,
            cloneEl: cloneEl,
            targetEl: dragEl,
            rootEl: rootEl,
            oldIndex: oldIndex,
            oldDraggableIndex: oldDraggableIndex,
            newIndex: newIndex,
            newDraggableIndex: newDraggableIndex
        }, info));
    }
    var dragEl,
        parentEl,
        ghostEl,
        rootEl,
        nextEl,
        lastDownEl,
        cloneEl,
        cloneHidden,
        oldIndex,
        newIndex,
        oldDraggableIndex,
        newDraggableIndex,
        activeGroup,
        putSortable,
        awaitingDragStarted = false,
        ignoreNextClick = false,
        sortables = [],
        tapEvt,
        touchEvt,
        lastDx,
        lastDy,
        tapDistanceLeft,
        tapDistanceTop,
        moved,
        lastTarget,
        lastDirection,
        pastFirstInvertThresh = false,
        isCircumstantialInvert = false,
        targetMoveDistance,
        // For positioning ghost absolutely
        ghostRelativeParent,
        ghostRelativeParentInitialScroll = [],
        // (left, top)
        _silent = false,
        savedInputChecked = [];
    /** @const */
    var documentExists = typeof document !== 'undefined',
        PositionGhostAbsolutely = IOS,
        CSSFloatProperty = Edge || IE11OrLess ? 'cssFloat' : 'float',
        // This will not pass for IE9, because IE9 DnD only works on anchors
        supportDraggable = documentExists && !ChromeForAndroid && !IOS && 'draggable' in document.createElement('div'),
        supportCssPointerEvents = function () {
            if (!documentExists) return;
            // false when <= IE11
            if (IE11OrLess) {
                return false;
            }
            var el = document.createElement('x');
            el.style.cssText = 'pointer-events:auto';
            return el.style.pointerEvents === 'auto';
        }(),
        _detectDirection = function _detectDirection(el, options) {
            var elCSS = css(el),
                elWidth = parseInt(elCSS.width) - parseInt(elCSS.paddingLeft) - parseInt(elCSS.paddingRight) - parseInt(elCSS.borderLeftWidth) - parseInt(elCSS.borderRightWidth),
                child1 = getChild(el, 0, options),
                child2 = getChild(el, 1, options),
                firstChildCSS = child1 && css(child1),
                secondChildCSS = child2 && css(child2),
                firstChildWidth = firstChildCSS && parseInt(firstChildCSS.marginLeft) + parseInt(firstChildCSS.marginRight) + getRect(child1).width,
                secondChildWidth = secondChildCSS && parseInt(secondChildCSS.marginLeft) + parseInt(secondChildCSS.marginRight) + getRect(child2).width;
            if (elCSS.display === 'flex') {
                return elCSS.flexDirection === 'column' || elCSS.flexDirection === 'column-reverse' ? 'vertical' : 'horizontal';
            }
            if (elCSS.display === 'grid') {
                return elCSS.gridTemplateColumns.split(' ').length <= 1 ? 'vertical' : 'horizontal';
            }
            if (child1 && firstChildCSS["float"] && firstChildCSS["float"] !== 'none') {
                var touchingSideChild2 = firstChildCSS["float"] === 'left' ? 'left' : 'right';
                return child2 && (secondChildCSS.clear === 'both' || secondChildCSS.clear === touchingSideChild2) ? 'vertical' : 'horizontal';
            }
            return child1 && (firstChildCSS.display === 'block' || firstChildCSS.display === 'flex' || firstChildCSS.display === 'table' || firstChildCSS.display === 'grid' || firstChildWidth >= elWidth && elCSS[CSSFloatProperty] === 'none' || child2 && elCSS[CSSFloatProperty] === 'none' && firstChildWidth + secondChildWidth > elWidth) ? 'vertical' : 'horizontal';
        },
        _dragElInRowColumn = function _dragElInRowColumn(dragRect, targetRect, vertical) {
            var dragElS1Opp = vertical ? dragRect.left : dragRect.top,
                dragElS2Opp = vertical ? dragRect.right : dragRect.bottom,
                dragElOppLength = vertical ? dragRect.width : dragRect.height,
                targetS1Opp = vertical ? targetRect.left : targetRect.top,
                targetS2Opp = vertical ? targetRect.right : targetRect.bottom,
                targetOppLength = vertical ? targetRect.width : targetRect.height;
            return dragElS1Opp === targetS1Opp || dragElS2Opp === targetS2Opp || dragElS1Opp + dragElOppLength / 2 === targetS1Opp + targetOppLength / 2;
        },
        /**
         * Detects first nearest empty sortable to X and Y position using emptyInsertThreshold.
         * @param  {Number} x      X position
         * @param  {Number} y      Y position
         * @return {HTMLElement}   Element of the first found nearest Sortable
         */
        _detectNearestEmptySortable = function _detectNearestEmptySortable(x, y) {
            var ret;
            sortables.some(function (sortable) {
                var threshold = sortable[expando].options.emptyInsertThreshold;
                if (!threshold || lastChild(sortable)) return;
                var rect = getRect(sortable),
                    insideHorizontally = x >= rect.left - threshold && x <= rect.right + threshold,
                    insideVertically = y >= rect.top - threshold && y <= rect.bottom + threshold;
                if (insideHorizontally && insideVertically) {
                    return ret = sortable;
                }
            });
            return ret;
        },
        _prepareGroup = function _prepareGroup(options) {
            function toFn(value, pull) {
                return function (to, from, dragEl, evt) {
                    var sameGroup = to.options.group.name && from.options.group.name && to.options.group.name === from.options.group.name;
                    if (value == null && (pull || sameGroup)) {
                        // Default pull value
                        // Default pull and put value if same group
                        return true;
                    } else if (value == null || value === false) {
                        return false;
                    } else if (pull && value === 'clone') {
                        return value;
                    } else if (typeof value === 'function') {
                        return toFn(value(to, from, dragEl, evt), pull)(to, from, dragEl, evt);
                    } else {
                        var otherGroup = (pull ? to : from).options.group.name;
                        return value === true || typeof value === 'string' && value === otherGroup || value.join && value.indexOf(otherGroup) > -1;
                    }
                };
            }
            var group = {};
            var originalGroup = options.group;
            if (!originalGroup || _typeof(originalGroup) != 'object') {
                originalGroup = {
                    name: originalGroup
                };
            }
            group.name = originalGroup.name;
            group.checkPull = toFn(originalGroup.pull, true);
            group.checkPut = toFn(originalGroup.put);
            group.revertClone = originalGroup.revertClone;
            options.group = group;
        },
        _hideGhostForTarget = function _hideGhostForTarget() {
            if (!supportCssPointerEvents && ghostEl) {
                css(ghostEl, 'display', 'none');
            }
        },
        _unhideGhostForTarget = function _unhideGhostForTarget() {
            if (!supportCssPointerEvents && ghostEl) {
                css(ghostEl, 'display', '');
            }
        };
    // #1184 fix - Prevent click event on fallback if dragged but item not changed position
    if (documentExists && !ChromeForAndroid) {
        document.addEventListener('click', function (evt) {
            if (ignoreNextClick) {
                evt.preventDefault();
                evt.stopPropagation && evt.stopPropagation();
                evt.stopImmediatePropagation && evt.stopImmediatePropagation();
                ignoreNextClick = false;
                return false;
            }
        }, true);
    }
    var nearestEmptyInsertDetectEvent = function nearestEmptyInsertDetectEvent(evt) {
        if (dragEl) {
            evt = evt.touches ? evt.touches[0] : evt;
            var nearest = _detectNearestEmptySortable(evt.clientX, evt.clientY);
            if (nearest) {
                // Create imitation event
                var event = {};
                for (var i in evt) {
                    if (evt.hasOwnProperty(i)) {
                        event[i] = evt[i];
                    }
                }
                event.target = event.rootEl = nearest;
                event.preventDefault = void 0;
                event.stopPropagation = void 0;
                nearest[expando]._onDragOver(event);
            }
        }
    };
    var _checkOutsideTargetEl = function _checkOutsideTargetEl(evt) {
        if (dragEl) {
            dragEl.parentNode[expando]._isOutsideThisEl(evt.target);
        }
    };
    /**
     * @class  Sortable
     * @param  {HTMLElement}  el
     * @param  {Object}       [options]
     */
    function Sortable(el, options) {
        if (!(el && el.nodeType && el.nodeType === 1)) {
            throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(el));
        }
        this.el = el; // root element
        this.options = options = _extends({}, options);
        // Export instance
        el[expando] = this;
        var defaults = {
            group: null,
            sort: true,
            disabled: false,
            store: null,
            handle: null,
            draggable: /^[uo]l$/i.test(el.nodeName) ? '>li' : '>*',
            swapThreshold: 1,
            // percentage; 0 <= x <= 1
            invertSwap: false,
            // invert always
            invertedSwapThreshold: null,
            // will be set to same as swapThreshold if default
            removeCloneOnHide: true,
            direction: function direction() {
                return _detectDirection(el, this.options);
            },
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            ignore: 'a, img',
            filter: null,
            preventOnFilter: true,
            animation: 0,
            easing: null,
            setData: function setData(dataTransfer, dragEl) {
                dataTransfer.setData('Text', dragEl.textContent);
            },
            dropBubble: false,
            dragoverBubble: false,
            dataIdAttr: 'data-id',
            delay: 0,
            delayOnTouchOnly: false,
            touchStartThreshold: (Number.parseInt ? Number : window).parseInt(window.devicePixelRatio, 10) || 1,
            forceFallback: false,
            fallbackClass: 'sortable-fallback',
            fallbackOnBody: false,
            fallbackTolerance: 0,
            fallbackOffset: {
                x: 0,
                y: 0
            },
            supportPointer: Sortable.supportPointer !== false && 'PointerEvent' in window && !Safari,
            emptyInsertThreshold: 5
        };
        PluginManager.initializePlugins(this, el, defaults);
        // Set default options
        for (var name in defaults) {
            !(name in options) && (options[name] = defaults[name]);
        }
        _prepareGroup(options);
        // Bind all private methods
        for (var fn in this) {
            if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
                this[fn] = this[fn].bind(this);
            }
        }
        // Setup drag mode
        this.nativeDraggable = options.forceFallback ? false : supportDraggable;
        if (this.nativeDraggable) {
            // Touch start threshold cannot be greater than the native dragstart threshold
            this.options.touchStartThreshold = 1;
        }
        // Bind events
        if (options.supportPointer) {
            on(el, 'pointerdown', this._onTapStart);
        } else {
            on(el, 'mousedown', this._onTapStart);
            on(el, 'touchstart', this._onTapStart);
        }
        if (this.nativeDraggable) {
            on(el, 'dragover', this);
            on(el, 'dragenter', this);
        }
        sortables.push(this.el);
        // Restore sorting
        options.store && options.store.get && this.sort(options.store.get(this) || []);
        // Add animation state manager
        _extends(this, AnimationStateManager());
    }
    Sortable.prototype = /** @lends Sortable.prototype */ {
        constructor: Sortable,
        _isOutsideThisEl: function _isOutsideThisEl(target) {
            if (!this.el.contains(target) && target !== this.el) {
                lastTarget = null;
            }
        },
        _getDirection: function _getDirection(evt, target) {
            return typeof this.options.direction === 'function' ? this.options.direction.call(this, evt, target, dragEl) : this.options.direction;
        },
        _onTapStart: function _onTapStart( /** Event|TouchEvent */ evt) {
            if (!evt.cancelable) return;
            var _this = this,
                el = this.el,
                options = this.options,
                preventOnFilter = options.preventOnFilter,
                type = evt.type,
                touch = evt.touches && evt.touches[0] || evt.pointerType && evt.pointerType === 'touch' && evt,
                target = (touch || evt).target,
                originalTarget = evt.target.shadowRoot && (evt.path && evt.path[0] || evt.composedPath && evt.composedPath()[0]) || target,
                filter = options.filter;
            _saveInputCheckedState(el);
            // Don't trigger start event when an element is been dragged, otherwise the evt.oldindex always wrong when set option.group.
            if (dragEl) {
                return;
            }
            if (/mousedown|pointerdown/.test(type) && evt.button !== 0 || options.disabled) {
                return; // only left button and enabled
            }
            // cancel dnd if original target is content editable
            if (originalTarget.isContentEditable) {
                return;
            }
            // Safari ignores further event handling after mousedown
            if (!this.nativeDraggable && Safari && target && target.tagName.toUpperCase() === 'SELECT') {
                return;
            }
            target = closest(target, options.draggable, el, false);
            if (target && target.animated) {
                return;
            }
            if (lastDownEl === target) {
                // Ignoring duplicate `down`
                return;
            }
            // Get the index of the dragged element within its parent
            oldIndex = index(target);
            oldDraggableIndex = index(target, options.draggable);
            // Check filter
            if (typeof filter === 'function') {
                if (filter.call(this, evt, target, this)) {
                    _dispatchEvent({
                        sortable: _this,
                        rootEl: originalTarget,
                        name: 'filter',
                        targetEl: target,
                        toEl: el,
                        fromEl: el
                    });
                    pluginEvent('filter', _this, {
                        evt: evt
                    });
                    preventOnFilter && evt.cancelable && evt.preventDefault();
                    return; // cancel dnd
                }
            } else if (filter) {
                filter = filter.split(',').some(function (criteria) {
                    criteria = closest(originalTarget, criteria.trim(), el, false);
                    if (criteria) {
                        _dispatchEvent({
                            sortable: _this,
                            rootEl: criteria,
                            name: 'filter',
                            targetEl: target,
                            fromEl: el,
                            toEl: el
                        });
                        pluginEvent('filter', _this, {
                            evt: evt
                        });
                        return true;
                    }
                });
                if (filter) {
                    preventOnFilter && evt.cancelable && evt.preventDefault();
                    return; // cancel dnd
                }
            }
            if (options.handle && !closest(originalTarget, options.handle, el, false)) {
                return;
            }
            // Prepare `dragstart`
            this._prepareDragStart(evt, touch, target);
        },
        _prepareDragStart: function _prepareDragStart( /** Event */ evt, /** Touch */ touch, /** HTMLElement */ target) {
            var _this = this,
                el = _this.el,
                options = _this.options,
                ownerDocument = el.ownerDocument,
                dragStartFn;
            if (target && !dragEl && target.parentNode === el) {
                var dragRect = getRect(target);
                rootEl = el;
                dragEl = target;
                parentEl = dragEl.parentNode;
                nextEl = dragEl.nextSibling;
                lastDownEl = target;
                activeGroup = options.group;
                Sortable.dragged = dragEl;
                tapEvt = {
                    target: dragEl,
                    clientX: (touch || evt).clientX,
                    clientY: (touch || evt).clientY
                };
                tapDistanceLeft = tapEvt.clientX - dragRect.left;
                tapDistanceTop = tapEvt.clientY - dragRect.top;
                this._lastX = (touch || evt).clientX;
                this._lastY = (touch || evt).clientY;
                dragEl.style['will-change'] = 'all';
                dragStartFn = function dragStartFn() {
                    pluginEvent('delayEnded', _this, {
                        evt: evt
                    });
                    if (Sortable.eventCanceled) {
                        _this._onDrop();
                        return;
                    }
                    // Delayed drag has been triggered
                    // we can re-enable the events: touchmove/mousemove
                    _this._disableDelayedDragEvents();
                    if (!FireFox && _this.nativeDraggable) {
                        dragEl.draggable = true;
                    }
                    // Bind the events: dragstart/dragend
                    _this._triggerDragStart(evt, touch);
                    // Drag start event
                    _dispatchEvent({
                        sortable: _this,
                        name: 'choose',
                        originalEvent: evt
                    });
                    // Chosen item
                    toggleClass(dragEl, options.chosenClass, true);
                };
                // Disable "draggable"
                options.ignore.split(',').forEach(function (criteria) {
                    find(dragEl, criteria.trim(), _disableDraggable);
                });
                on(ownerDocument, 'dragover', nearestEmptyInsertDetectEvent);
                on(ownerDocument, 'mousemove', nearestEmptyInsertDetectEvent);
                on(ownerDocument, 'touchmove', nearestEmptyInsertDetectEvent);
                on(ownerDocument, 'mouseup', _this._onDrop);
                on(ownerDocument, 'touchend', _this._onDrop);
                on(ownerDocument, 'touchcancel', _this._onDrop);
                // Make dragEl draggable (must be before delay for FireFox)
                if (FireFox && this.nativeDraggable) {
                    this.options.touchStartThreshold = 4;
                    dragEl.draggable = true;
                }
                pluginEvent('delayStart', this, {
                    evt: evt
                });
                // Delay is impossible for native DnD in Edge or IE
                if (options.delay && (!options.delayOnTouchOnly || touch) && (!this.nativeDraggable || !(Edge || IE11OrLess))) {
                    if (Sortable.eventCanceled) {
                        this._onDrop();
                        return;
                    }
                    // If the user moves the pointer or let go the click or touch
                    // before the delay has been reached:
                    // disable the delayed drag
                    on(ownerDocument, 'mouseup', _this._disableDelayedDrag);
                    on(ownerDocument, 'touchend', _this._disableDelayedDrag);
                    on(ownerDocument, 'touchcancel', _this._disableDelayedDrag);
                    on(ownerDocument, 'mousemove', _this._delayedDragTouchMoveHandler);
                    on(ownerDocument, 'touchmove', _this._delayedDragTouchMoveHandler);
                    options.supportPointer && on(ownerDocument, 'pointermove', _this._delayedDragTouchMoveHandler);
                    _this._dragStartTimer = setTimeout(dragStartFn, options.delay);
                } else {
                    dragStartFn();
                }
            }
        },
        _delayedDragTouchMoveHandler: function _delayedDragTouchMoveHandler( /** TouchEvent|PointerEvent **/ e) {
            var touch = e.touches ? e.touches[0] : e;
            if (Math.max(Math.abs(touch.clientX - this._lastX), Math.abs(touch.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1))) {
                this._disableDelayedDrag();
            }
        },
        _disableDelayedDrag: function _disableDelayedDrag() {
            dragEl && _disableDraggable(dragEl);
            clearTimeout(this._dragStartTimer);
            this._disableDelayedDragEvents();
        },
        _disableDelayedDragEvents: function _disableDelayedDragEvents() {
            var ownerDocument = this.el.ownerDocument;
            off(ownerDocument, 'mouseup', this._disableDelayedDrag);
            off(ownerDocument, 'touchend', this._disableDelayedDrag);
            off(ownerDocument, 'touchcancel', this._disableDelayedDrag);
            off(ownerDocument, 'mousemove', this._delayedDragTouchMoveHandler);
            off(ownerDocument, 'touchmove', this._delayedDragTouchMoveHandler);
            off(ownerDocument, 'pointermove', this._delayedDragTouchMoveHandler);
        },
        _triggerDragStart: function _triggerDragStart( /** Event */ evt, /** Touch */ touch) {
            touch = touch || evt.pointerType == 'touch' && evt;
            if (!this.nativeDraggable || touch) {
                if (this.options.supportPointer) {
                    on(document, 'pointermove', this._onTouchMove);
                } else if (touch) {
                    on(document, 'touchmove', this._onTouchMove);
                } else {
                    on(document, 'mousemove', this._onTouchMove);
                }
            } else {
                on(dragEl, 'dragend', this);
                on(rootEl, 'dragstart', this._onDragStart);
            }
            try {
                if (document.selection) {
                    // Timeout neccessary for IE9
                    _nextTick(function () {
                        document.selection.empty();
                    });
                } else {
                    window.getSelection().removeAllRanges();
                }
            } catch (err) {}
        },
        _dragStarted: function _dragStarted(fallback, evt) {
            awaitingDragStarted = false;
            if (rootEl && dragEl) {
                pluginEvent('dragStarted', this, {
                    evt: evt
                });
                if (this.nativeDraggable) {
                    on(document, 'dragover', _checkOutsideTargetEl);
                }
                var options = this.options;
                // Apply effect
                !fallback && toggleClass(dragEl, options.dragClass, false);
                toggleClass(dragEl, options.ghostClass, true);
                Sortable.active = this;
                fallback && this._appendGhost();
                // Drag start event
                _dispatchEvent({
                    sortable: this,
                    name: 'start',
                    originalEvent: evt
                });
            } else {
                this._nulling();
            }
        },
        _emulateDragOver: function _emulateDragOver() {
            if (touchEvt) {
                this._lastX = touchEvt.clientX;
                this._lastY = touchEvt.clientY;
                _hideGhostForTarget();
                var target = document.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
                var parent = target;
                while (target && target.shadowRoot) {
                    target = target.shadowRoot.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
                    if (target === parent) break;
                    parent = target;
                }
                dragEl.parentNode[expando]._isOutsideThisEl(target);
                if (parent) {
                    do {
                        if (parent[expando]) {
                            var inserted = void 0;
                            inserted = parent[expando]._onDragOver({
                                clientX: touchEvt.clientX,
                                clientY: touchEvt.clientY,
                                target: target,
                                rootEl: parent
                            });
                            if (inserted && !this.options.dragoverBubble) {
                                break;
                            }
                        }
                        target = parent; // store last element
                    }
                    /* jshint boss:true */
                    while (parent = getParentOrHost(parent));
                }
                _unhideGhostForTarget();
            }
        },
        _onTouchMove: function _onTouchMove( /**TouchEvent*/ evt) {
            if (tapEvt) {
                var options = this.options,
                    fallbackTolerance = options.fallbackTolerance,
                    fallbackOffset = options.fallbackOffset,
                    touch = evt.touches ? evt.touches[0] : evt,
                    ghostMatrix = ghostEl && matrix(ghostEl, true),
                    scaleX = ghostEl && ghostMatrix && ghostMatrix.a,
                    scaleY = ghostEl && ghostMatrix && ghostMatrix.d,
                    relativeScrollOffset = PositionGhostAbsolutely && ghostRelativeParent && getRelativeScrollOffset(ghostRelativeParent),
                    dx = (touch.clientX - tapEvt.clientX + fallbackOffset.x) / (scaleX || 1) + (relativeScrollOffset ? relativeScrollOffset[0] - ghostRelativeParentInitialScroll[0] : 0) / (scaleX || 1),
                    dy = (touch.clientY - tapEvt.clientY + fallbackOffset.y) / (scaleY || 1) + (relativeScrollOffset ? relativeScrollOffset[1] - ghostRelativeParentInitialScroll[1] : 0) / (scaleY || 1);
                // only set the status to dragging, when we are actually dragging
                if (!Sortable.active && !awaitingDragStarted) {
                    if (fallbackTolerance && Math.max(Math.abs(touch.clientX - this._lastX), Math.abs(touch.clientY - this._lastY)) < fallbackTolerance) {
                        return;
                    }
                    this._onDragStart(evt, true);
                }
                if (ghostEl) {
                    if (ghostMatrix) {
                        ghostMatrix.e += dx - (lastDx || 0);
                        ghostMatrix.f += dy - (lastDy || 0);
                    } else {
                        ghostMatrix = {
                            a: 1,
                            b: 0,
                            c: 0,
                            d: 1,
                            e: dx,
                            f: dy
                        };
                    }
                    var cssMatrix = "matrix(".concat(ghostMatrix.a, ",").concat(ghostMatrix.b, ",").concat(ghostMatrix.c, ",").concat(ghostMatrix.d, ",").concat(ghostMatrix.e, ",").concat(ghostMatrix.f, ")");
                    css(ghostEl, 'webkitTransform', cssMatrix);
                    css(ghostEl, 'mozTransform', cssMatrix);
                    css(ghostEl, 'msTransform', cssMatrix);
                    css(ghostEl, 'transform', cssMatrix);
                    lastDx = dx;
                    lastDy = dy;
                    touchEvt = touch;
                }
                evt.cancelable && evt.preventDefault();
            }
        },
        _appendGhost: function _appendGhost() {
            // Bug if using scale(): https://stackoverflow.com/questions/2637058
            // Not being adjusted for
            if (!ghostEl) {
                var container = this.options.fallbackOnBody ? document.body : rootEl,
                    rect = getRect(dragEl, true, PositionGhostAbsolutely, true, container),
                    options = this.options;
                // Position absolutely
                if (PositionGhostAbsolutely) {
                    // Get relatively positioned parent
                    ghostRelativeParent = container;
                    while (css(ghostRelativeParent, 'position') === 'static' && css(ghostRelativeParent, 'transform') === 'none' && ghostRelativeParent !== document) {
                        ghostRelativeParent = ghostRelativeParent.parentNode;
                    }
                    if (ghostRelativeParent !== document.body && ghostRelativeParent !== document.documentElement) {
                        if (ghostRelativeParent === document) ghostRelativeParent = getWindowScrollingElement();
                        rect.top += ghostRelativeParent.scrollTop;
                        rect.left += ghostRelativeParent.scrollLeft;
                    } else {
                        ghostRelativeParent = getWindowScrollingElement();
                    }
                    ghostRelativeParentInitialScroll = getRelativeScrollOffset(ghostRelativeParent);
                }
                ghostEl = dragEl.cloneNode(true);
                toggleClass(ghostEl, options.ghostClass, false);
                toggleClass(ghostEl, options.fallbackClass, true);
                toggleClass(ghostEl, options.dragClass, true);
                css(ghostEl, 'transition', '');
                css(ghostEl, 'transform', '');
                css(ghostEl, 'box-sizing', 'border-box');
                css(ghostEl, 'margin', 0);
                css(ghostEl, 'top', rect.top);
                css(ghostEl, 'left', rect.left);
                css(ghostEl, 'width', rect.width);
                css(ghostEl, 'height', rect.height);
                css(ghostEl, 'opacity', '0.8');
                css(ghostEl, 'position', PositionGhostAbsolutely ? 'absolute' : 'fixed');
                css(ghostEl, 'zIndex', '100000');
                css(ghostEl, 'pointerEvents', 'none');
                Sortable.ghost = ghostEl;
                container.appendChild(ghostEl);
                // Set transform-origin
                css(ghostEl, 'transform-origin', tapDistanceLeft / parseInt(ghostEl.style.width) * 100 + '% ' + tapDistanceTop / parseInt(ghostEl.style.height) * 100 + '%');
            }
        },
        _onDragStart: function _onDragStart( /**Event*/ evt, /**boolean*/ fallback) {
            var _this = this;
            var dataTransfer = evt.dataTransfer;
            var options = _this.options;
            pluginEvent('dragStart', this, {
                evt: evt
            });
            if (Sortable.eventCanceled) {
                this._onDrop();
                return;
            }
            pluginEvent('setupClone', this);
            if (!Sortable.eventCanceled) {
                cloneEl = clone(dragEl);
                cloneEl.removeAttribute("id");
                cloneEl.draggable = false;
                cloneEl.style['will-change'] = '';
                this._hideClone();
                toggleClass(cloneEl, this.options.chosenClass, false);
                Sortable.clone = cloneEl;
            }
            // #1143: IFrame support workaround
            _this.cloneId = _nextTick(function () {
                pluginEvent('clone', _this);
                if (Sortable.eventCanceled) return;
                if (!_this.options.removeCloneOnHide) {
                    rootEl.insertBefore(cloneEl, dragEl);
                }
                _this._hideClone();
                _dispatchEvent({
                    sortable: _this,
                    name: 'clone'
                });
            });
            !fallback && toggleClass(dragEl, options.dragClass, true);
            // Set proper drop events
            if (fallback) {
                ignoreNextClick = true;
                _this._loopId = setInterval(_this._emulateDragOver, 50);
            } else {
                // Undo what was set in _prepareDragStart before drag started
                off(document, 'mouseup', _this._onDrop);
                off(document, 'touchend', _this._onDrop);
                off(document, 'touchcancel', _this._onDrop);
                if (dataTransfer) {
                    dataTransfer.effectAllowed = 'move';
                    options.setData && options.setData.call(_this, dataTransfer, dragEl);
                }
                on(document, 'drop', _this);
                // #1276 fix:
                css(dragEl, 'transform', 'translateZ(0)');
            }
            awaitingDragStarted = true;
            _this._dragStartId = _nextTick(_this._dragStarted.bind(_this, fallback, evt));
            on(document, 'selectstart', _this);
            moved = true;
            if (Safari) {
                css(document.body, 'user-select', 'none');
            }
        },
        // Returns true - if no further action is needed (either inserted or another condition)
        _onDragOver: function _onDragOver( /**Event*/ evt) {
            var el = this.el,
                target = evt.target,
                dragRect,
                targetRect,
                revert,
                options = this.options,
                group = options.group,
                activeSortable = Sortable.active,
                isOwner = activeGroup === group,
                canSort = options.sort,
                fromSortable = putSortable || activeSortable,
                vertical,
                _this = this,
                completedFired = false;
            if (_silent) return;

            function dragOverEvent(name, extra) {
                pluginEvent(name, _this, _objectSpread2({
                    evt: evt,
                    isOwner: isOwner,
                    axis: vertical ? 'vertical' : 'horizontal',
                    revert: revert,
                    dragRect: dragRect,
                    targetRect: targetRect,
                    canSort: canSort,
                    fromSortable: fromSortable,
                    target: target,
                    completed: completed,
                    onMove: function onMove(target, after) {
                        return _onMove(rootEl, el, dragEl, dragRect, target, getRect(target), evt, after);
                    },
                    changed: changed
                }, extra));
            }
            // Capture animation state
            function capture() {
                dragOverEvent('dragOverAnimationCapture');
                _this.captureAnimationState();
                if (_this !== fromSortable) {
                    fromSortable.captureAnimationState();
                }
            }
            // Return invocation when dragEl is inserted (or completed)
            function completed(insertion) {
                dragOverEvent('dragOverCompleted', {
                    insertion: insertion
                });
                if (insertion) {
                    // Clones must be hidden before folding animation to capture dragRectAbsolute properly
                    if (isOwner) {
                        activeSortable._hideClone();
                    } else {
                        activeSortable._showClone(_this);
                    }
                    if (_this !== fromSortable) {
                        // Set ghost class to new sortable's ghost class
                        toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : activeSortable.options.ghostClass, false);
                        toggleClass(dragEl, options.ghostClass, true);
                    }
                    if (putSortable !== _this && _this !== Sortable.active) {
                        putSortable = _this;
                    } else if (_this === Sortable.active && putSortable) {
                        putSortable = null;
                    }
                    // Animation
                    if (fromSortable === _this) {
                        _this._ignoreWhileAnimating = target;
                    }
                    _this.animateAll(function () {
                        dragOverEvent('dragOverAnimationComplete');
                        _this._ignoreWhileAnimating = null;
                    });
                    if (_this !== fromSortable) {
                        fromSortable.animateAll();
                        fromSortable._ignoreWhileAnimating = null;
                    }
                }
                // Null lastTarget if it is not inside a previously swapped element
                if (target === dragEl && !dragEl.animated || target === el && !target.animated) {
                    lastTarget = null;
                }
                // no bubbling and not fallback
                if (!options.dragoverBubble && !evt.rootEl && target !== document) {
                    dragEl.parentNode[expando]._isOutsideThisEl(evt.target);
                    // Do not detect for empty insert if already inserted
                    !insertion && nearestEmptyInsertDetectEvent(evt);
                }!options.dragoverBubble && evt.stopPropagation && evt.stopPropagation();
                return completedFired = true;
            }
            // Call when dragEl has been inserted
            function changed() {
                newIndex = index(dragEl);
                newDraggableIndex = index(dragEl, options.draggable);
                _dispatchEvent({
                    sortable: _this,
                    name: 'change',
                    toEl: el,
                    newIndex: newIndex,
                    newDraggableIndex: newDraggableIndex,
                    originalEvent: evt
                });
            }
            if (evt.preventDefault !== void 0) {
                evt.cancelable && evt.preventDefault();
            }
            target = closest(target, options.draggable, el, true);
            dragOverEvent('dragOver');
            if (Sortable.eventCanceled) return completedFired;
            if (dragEl.contains(evt.target) || target.animated && target.animatingX && target.animatingY || _this._ignoreWhileAnimating === target) {
                return completed(false);
            }
            ignoreNextClick = false;
            if (activeSortable && !options.disabled && (isOwner ? canSort || (revert = parentEl !== rootEl) // Reverting item into the original list
                    : putSortable === this || (this.lastPutMode = activeGroup.checkPull(this, activeSortable, dragEl, evt)) && group.checkPut(this, activeSortable, dragEl, evt))) {
                vertical = this._getDirection(evt, target) === 'vertical';
                dragRect = getRect(dragEl);
                dragOverEvent('dragOverValid');
                if (Sortable.eventCanceled) return completedFired;
                if (revert) {
                    parentEl = rootEl; // actualization
                    capture();
                    this._hideClone();
                    dragOverEvent('revert');
                    if (!Sortable.eventCanceled) {
                        if (nextEl) {
                            rootEl.insertBefore(dragEl, nextEl);
                        } else {
                            rootEl.appendChild(dragEl);
                        }
                    }
                    return completed(true);
                }
                var elLastChild = lastChild(el, options.draggable);
                if (!elLastChild || _ghostIsLast(evt, vertical, this) && !elLastChild.animated) {
                    // Insert to end of list
                    // If already at end of list: Do not insert
                    if (elLastChild === dragEl) {
                        return completed(false);
                    }
                    // if there is a last element, it is the target
                    if (elLastChild && el === evt.target) {
                        target = elLastChild;
                    }
                    if (target) {
                        targetRect = getRect(target);
                    }
                    if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, !!target) !== false) {
                        capture();
                        if (elLastChild && elLastChild.nextSibling) {
                            // the last draggable element is not the last node
                            el.insertBefore(dragEl, elLastChild.nextSibling);
                        } else {
                            el.appendChild(dragEl);
                        }
                        parentEl = el; // actualization
                        changed();
                        return completed(true);
                    }
                } else if (elLastChild && _ghostIsFirst(evt, vertical, this)) {
                    // Insert to start of list
                    var firstChild = getChild(el, 0, options, true);
                    if (firstChild === dragEl) {
                        return completed(false);
                    }
                    target = firstChild;
                    targetRect = getRect(target);
                    if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, false) !== false) {
                        capture();
                        el.insertBefore(dragEl, firstChild);
                        parentEl = el; // actualization
                        changed();
                        return completed(true);
                    }
                } else if (target.parentNode === el) {
                    targetRect = getRect(target);
                    var direction = 0,
                        targetBeforeFirstSwap,
                        differentLevel = dragEl.parentNode !== el,
                        differentRowCol = !_dragElInRowColumn(dragEl.animated && dragEl.toRect || dragRect, target.animated && target.toRect || targetRect, vertical),
                        side1 = vertical ? 'top' : 'left',
                        scrolledPastTop = isScrolledPast(target, 'top', 'top') || isScrolledPast(dragEl, 'top', 'top'),
                        scrollBefore = scrolledPastTop ? scrolledPastTop.scrollTop : void 0;
                    if (lastTarget !== target) {
                        targetBeforeFirstSwap = targetRect[side1];
                        pastFirstInvertThresh = false;
                        isCircumstantialInvert = !differentRowCol && options.invertSwap || differentLevel;
                    }
                    direction = _getSwapDirection(evt, target, targetRect, vertical, differentRowCol ? 1 : options.swapThreshold, options.invertedSwapThreshold == null ? options.swapThreshold : options.invertedSwapThreshold, isCircumstantialInvert, lastTarget === target);
                    var sibling;
                    if (direction !== 0) {
                        // Check if target is beside dragEl in respective direction (ignoring hidden elements)
                        var dragIndex = index(dragEl);
                        do {
                            dragIndex -= direction;
                            sibling = parentEl.children[dragIndex];
                        } while (sibling && (css(sibling, 'display') === 'none' || sibling === ghostEl));
                    }
                    // If dragEl is already beside target: Do not insert
                    if (direction === 0 || sibling === target) {
                        return completed(false);
                    }
                    lastTarget = target;
                    lastDirection = direction;
                    var nextSibling = target.nextElementSibling,
                        after = false;
                    after = direction === 1;
                    var moveVector = _onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, after);
                    if (moveVector !== false) {
                        if (moveVector === 1 || moveVector === -1) {
                            after = moveVector === 1;
                        }
                        _silent = true;
                        setTimeout(_unsilent, 30);
                        capture();
                        if (after && !nextSibling) {
                            el.appendChild(dragEl);
                        } else {
                            target.parentNode.insertBefore(dragEl, after ? nextSibling : target);
                        }
                        // Undo chrome's scroll adjustment (has no effect on other browsers)
                        if (scrolledPastTop) {
                            scrollBy(scrolledPastTop, 0, scrollBefore - scrolledPastTop.scrollTop);
                        }
                        parentEl = dragEl.parentNode; // actualization
                        // must be done before animation
                        if (targetBeforeFirstSwap !== undefined && !isCircumstantialInvert) {
                            targetMoveDistance = Math.abs(targetBeforeFirstSwap - getRect(target)[side1]);
                        }
                        changed();
                        return completed(true);
                    }
                }
                if (el.contains(dragEl)) {
                    return completed(false);
                }
            }
            return false;
        },
        _ignoreWhileAnimating: null,
        _offMoveEvents: function _offMoveEvents() {
            off(document, 'mousemove', this._onTouchMove);
            off(document, 'touchmove', this._onTouchMove);
            off(document, 'pointermove', this._onTouchMove);
            off(document, 'dragover', nearestEmptyInsertDetectEvent);
            off(document, 'mousemove', nearestEmptyInsertDetectEvent);
            off(document, 'touchmove', nearestEmptyInsertDetectEvent);
        },
        _offUpEvents: function _offUpEvents() {
            var ownerDocument = this.el.ownerDocument;
            off(ownerDocument, 'mouseup', this._onDrop);
            off(ownerDocument, 'touchend', this._onDrop);
            off(ownerDocument, 'pointerup', this._onDrop);
            off(ownerDocument, 'touchcancel', this._onDrop);
            off(document, 'selectstart', this);
        },
        _onDrop: function _onDrop( /**Event*/ evt) {
            var el = this.el,
                options = this.options;
            // Get the index of the dragged element within its parent
            newIndex = index(dragEl);
            newDraggableIndex = index(dragEl, options.draggable);
            pluginEvent('drop', this, {
                evt: evt
            });
            parentEl = dragEl && dragEl.parentNode;
            // Get again after plugin event
            newIndex = index(dragEl);
            newDraggableIndex = index(dragEl, options.draggable);
            if (Sortable.eventCanceled) {
                this._nulling();
                return;
            }
            awaitingDragStarted = false;
            isCircumstantialInvert = false;
            pastFirstInvertThresh = false;
            clearInterval(this._loopId);
            clearTimeout(this._dragStartTimer);
            _cancelNextTick(this.cloneId);
            _cancelNextTick(this._dragStartId);
            // Unbind events
            if (this.nativeDraggable) {
                off(document, 'drop', this);
                off(el, 'dragstart', this._onDragStart);
            }
            this._offMoveEvents();
            this._offUpEvents();
            if (Safari) {
                css(document.body, 'user-select', '');
            }
            css(dragEl, 'transform', '');
            if (evt) {
                if (moved) {
                    evt.cancelable && evt.preventDefault();
                    !options.dropBubble && evt.stopPropagation();
                }
                ghostEl && ghostEl.parentNode && ghostEl.parentNode.removeChild(ghostEl);
                if (rootEl === parentEl || putSortable && putSortable.lastPutMode !== 'clone') {
                    // Remove clone(s)
                    cloneEl && cloneEl.parentNode && cloneEl.parentNode.removeChild(cloneEl);
                }
                if (dragEl) {
                    if (this.nativeDraggable) {
                        off(dragEl, 'dragend', this);
                    }
                    _disableDraggable(dragEl);
                    dragEl.style['will-change'] = '';
                    // Remove classes
                    // ghostClass is added in dragStarted
                    if (moved && !awaitingDragStarted) {
                        toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : this.options.ghostClass, false);
                    }
                    toggleClass(dragEl, this.options.chosenClass, false);
                    // Drag stop event
                    _dispatchEvent({
                        sortable: this,
                        name: 'unchoose',
                        toEl: parentEl,
                        newIndex: null,
                        newDraggableIndex: null,
                        originalEvent: evt
                    });
                    if (rootEl !== parentEl) {
                        if (newIndex >= 0) {
                            // Add event
                            _dispatchEvent({
                                rootEl: parentEl,
                                name: 'add',
                                toEl: parentEl,
                                fromEl: rootEl,
                                originalEvent: evt
                            });
                            // Remove event
                            _dispatchEvent({
                                sortable: this,
                                name: 'remove',
                                toEl: parentEl,
                                originalEvent: evt
                            });
                            // drag from one list and drop into another
                            _dispatchEvent({
                                rootEl: parentEl,
                                name: 'sort',
                                toEl: parentEl,
                                fromEl: rootEl,
                                originalEvent: evt
                            });
                            _dispatchEvent({
                                sortable: this,
                                name: 'sort',
                                toEl: parentEl,
                                originalEvent: evt
                            });
                        }
                        putSortable && putSortable.save();
                    } else {
                        if (newIndex !== oldIndex) {
                            if (newIndex >= 0) {
                                // drag & drop within the same list
                                _dispatchEvent({
                                    sortable: this,
                                    name: 'update',
                                    toEl: parentEl,
                                    originalEvent: evt
                                });
                                _dispatchEvent({
                                    sortable: this,
                                    name: 'sort',
                                    toEl: parentEl,
                                    originalEvent: evt
                                });
                            }
                        }
                    }
                    if (Sortable.active) {
                        /* jshint eqnull:true */
                        if (newIndex == null || newIndex === -1) {
                            newIndex = oldIndex;
                            newDraggableIndex = oldDraggableIndex;
                        }
                        _dispatchEvent({
                            sortable: this,
                            name: 'end',
                            toEl: parentEl,
                            originalEvent: evt
                        });
                        // Save sorting
                        this.save();
                    }
                }
            }
            this._nulling();
        },
        _nulling: function _nulling() {
            pluginEvent('nulling', this);
            rootEl = dragEl = parentEl = ghostEl = nextEl = cloneEl = lastDownEl = cloneHidden = tapEvt = touchEvt = moved = newIndex = newDraggableIndex = oldIndex = oldDraggableIndex = lastTarget = lastDirection = putSortable = activeGroup = Sortable.dragged = Sortable.ghost = Sortable.clone = Sortable.active = null;
            savedInputChecked.forEach(function (el) {
                el.checked = true;
            });
            savedInputChecked.length = lastDx = lastDy = 0;
        },
        handleEvent: function handleEvent( /**Event*/ evt) {
            switch (evt.type) {
                case 'drop':
                case 'dragend':
                    this._onDrop(evt);
                    break;
                case 'dragenter':
                case 'dragover':
                    if (dragEl) {
                        this._onDragOver(evt);
                        _globalDragOver(evt);
                    }
                    break;
                case 'selectstart':
                    evt.preventDefault();
                    break;
            }
        },
        /**
         * Serializes the item into an array of string.
         * @returns {String[]}
         */
        toArray: function toArray() {
            var order = [],
                el,
                children = this.el.children,
                i = 0,
                n = children.length,
                options = this.options;
            for (; i < n; i++) {
                el = children[i];
                if (closest(el, options.draggable, this.el, false)) {
                    order.push(el.getAttribute(options.dataIdAttr) || _generateId(el));
                }
            }
            return order;
        },
        /**
         * Sorts the elements according to the array.
         * @param  {String[]}  order  order of the items
         */
        sort: function sort(order, useAnimation) {
            var items = {},
                rootEl = this.el;
            this.toArray().forEach(function (id, i) {
                var el = rootEl.children[i];
                if (closest(el, this.options.draggable, rootEl, false)) {
                    items[id] = el;
                }
            }, this);
            useAnimation && this.captureAnimationState();
            order.forEach(function (id) {
                if (items[id]) {
                    rootEl.removeChild(items[id]);
                    rootEl.appendChild(items[id]);
                }
            });
            useAnimation && this.animateAll();
        },
        /**
         * Save the current sorting
         */
        save: function save() {
            var store = this.options.store;
            store && store.set && store.set(this);
        },
        /**
         * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
         * @param   {HTMLElement}  el
         * @param   {String}       [selector]  default: `options.draggable`
         * @returns {HTMLElement|null}
         */
        closest: function closest$1(el, selector) {
            return closest(el, selector || this.options.draggable, this.el, false);
        },
        /**
         * Set/get option
         * @param   {string} name
         * @param   {*}      [value]
         * @returns {*}
         */
        option: function option(name, value) {
            var options = this.options;
            if (value === void 0) {
                return options[name];
            } else {
                var modifiedValue = PluginManager.modifyOption(this, name, value);
                if (typeof modifiedValue !== 'undefined') {
                    options[name] = modifiedValue;
                } else {
                    options[name] = value;
                }
                if (name === 'group') {
                    _prepareGroup(options);
                }
            }
        },
        /**
         * Destroy
         */
        destroy: function destroy() {
            pluginEvent('destroy', this);
            var el = this.el;
            el[expando] = null;
            off(el, 'mousedown', this._onTapStart);
            off(el, 'touchstart', this._onTapStart);
            off(el, 'pointerdown', this._onTapStart);
            if (this.nativeDraggable) {
                off(el, 'dragover', this);
                off(el, 'dragenter', this);
            }
            // Remove draggable attributes
            Array.prototype.forEach.call(el.querySelectorAll('[draggable]'), function (el) {
                el.removeAttribute('draggable');
            });
            this._onDrop();
            this._disableDelayedDragEvents();
            sortables.splice(sortables.indexOf(this.el), 1);
            this.el = el = null;
        },
        _hideClone: function _hideClone() {
            if (!cloneHidden) {
                pluginEvent('hideClone', this);
                if (Sortable.eventCanceled) return;
                css(cloneEl, 'display', 'none');
                if (this.options.removeCloneOnHide && cloneEl.parentNode) {
                    cloneEl.parentNode.removeChild(cloneEl);
                }
                cloneHidden = true;
            }
        },
        _showClone: function _showClone(putSortable) {
            if (putSortable.lastPutMode !== 'clone') {
                this._hideClone();
                return;
            }
            if (cloneHidden) {
                pluginEvent('showClone', this);
                if (Sortable.eventCanceled) return;
                // show clone at dragEl or original position
                if (dragEl.parentNode == rootEl && !this.options.group.revertClone) {
                    rootEl.insertBefore(cloneEl, dragEl);
                } else if (nextEl) {
                    rootEl.insertBefore(cloneEl, nextEl);
                } else {
                    rootEl.appendChild(cloneEl);
                }
                if (this.options.group.revertClone) {
                    this.animate(dragEl, cloneEl);
                }
                css(cloneEl, 'display', '');
                cloneHidden = false;
            }
        }
    };

    function _globalDragOver( /**Event*/ evt) {
        if (evt.dataTransfer) {
            evt.dataTransfer.dropEffect = 'move';
        }
        evt.cancelable && evt.preventDefault();
    }

    function _onMove(fromEl, toEl, dragEl, dragRect, targetEl, targetRect, originalEvent, willInsertAfter) {
        var evt,
            sortable = fromEl[expando],
            onMoveFn = sortable.options.onMove,
            retVal;
        // Support for new CustomEvent feature
        if (window.CustomEvent && !IE11OrLess && !Edge) {
            evt = new CustomEvent('move', {
                bubbles: true,
                cancelable: true
            });
        } else {
            evt = document.createEvent('Event');
            evt.initEvent('move', true, true);
        }
        evt.to = toEl;
        evt.from = fromEl;
        evt.dragged = dragEl;
        evt.draggedRect = dragRect;
        evt.related = targetEl || toEl;
        evt.relatedRect = targetRect || getRect(toEl);
        evt.willInsertAfter = willInsertAfter;
        evt.originalEvent = originalEvent;
        fromEl.dispatchEvent(evt);
        if (onMoveFn) {
            retVal = onMoveFn.call(sortable, evt, originalEvent);
        }
        return retVal;
    }

    function _disableDraggable(el) {
        el.draggable = false;
    }

    function _unsilent() {
        _silent = false;
    }

    function _ghostIsFirst(evt, vertical, sortable) {
        var firstElRect = getRect(getChild(sortable.el, 0, sortable.options, true));
        var childContainingRect = getChildContainingRectFromElement(sortable.el, sortable.options, ghostEl);
        var spacer = 10;
        return vertical ? evt.clientX < childContainingRect.left - spacer || evt.clientY < firstElRect.top && evt.clientX < firstElRect.right : evt.clientY < childContainingRect.top - spacer || evt.clientY < firstElRect.bottom && evt.clientX < firstElRect.left;
    }

    function _ghostIsLast(evt, vertical, sortable) {
        var lastElRect = getRect(lastChild(sortable.el, sortable.options.draggable));
        var childContainingRect = getChildContainingRectFromElement(sortable.el, sortable.options, ghostEl);
        var spacer = 10;
        return vertical ? evt.clientX > childContainingRect.right + spacer || evt.clientY > lastElRect.bottom && evt.clientX > lastElRect.left : evt.clientY > childContainingRect.bottom + spacer || evt.clientX > lastElRect.right && evt.clientY > lastElRect.top;
    }

    function _getSwapDirection(evt, target, targetRect, vertical, swapThreshold, invertedSwapThreshold, invertSwap, isLastTarget) {
        var mouseOnAxis = vertical ? evt.clientY : evt.clientX,
            targetLength = vertical ? targetRect.height : targetRect.width,
            targetS1 = vertical ? targetRect.top : targetRect.left,
            targetS2 = vertical ? targetRect.bottom : targetRect.right,
            invert = false;
        if (!invertSwap) {
            // Never invert or create dragEl shadow when target movemenet causes mouse to move past the end of regular swapThreshold
            if (isLastTarget && targetMoveDistance < targetLength * swapThreshold) {
                // multiplied only by swapThreshold because mouse will already be inside target by (1 - threshold) * targetLength / 2
                // check if past first invert threshold on side opposite of lastDirection
                if (!pastFirstInvertThresh && (lastDirection === 1 ? mouseOnAxis > targetS1 + targetLength * invertedSwapThreshold / 2 : mouseOnAxis < targetS2 - targetLength * invertedSwapThreshold / 2)) {
                    // past first invert threshold, do not restrict inverted threshold to dragEl shadow
                    pastFirstInvertThresh = true;
                }
                if (!pastFirstInvertThresh) {
                    // dragEl shadow (target move distance shadow)
                    if (lastDirection === 1 ? mouseOnAxis < targetS1 + targetMoveDistance // over dragEl shadow
                        : mouseOnAxis > targetS2 - targetMoveDistance) {
                        return -lastDirection;
                    }
                } else {
                    invert = true;
                }
            } else {
                // Regular
                if (mouseOnAxis > targetS1 + targetLength * (1 - swapThreshold) / 2 && mouseOnAxis < targetS2 - targetLength * (1 - swapThreshold) / 2) {
                    return _getInsertDirection(target);
                }
            }
        }
        invert = invert || invertSwap;
        if (invert) {
            // Invert of regular
            if (mouseOnAxis < targetS1 + targetLength * invertedSwapThreshold / 2 || mouseOnAxis > targetS2 - targetLength * invertedSwapThreshold / 2) {
                return mouseOnAxis > targetS1 + targetLength / 2 ? 1 : -1;
            }
        }
        return 0;
    }
    /**
     * Gets the direction dragEl must be swapped relative to target in order to make it
     * seem that dragEl has been "inserted" into that element's position
     * @param  {HTMLElement} target       The target whose position dragEl is being inserted at
     * @return {Number}                   Direction dragEl must be swapped
     */
    function _getInsertDirection(target) {
        if (index(dragEl) < index(target)) {
            return 1;
        } else {
            return -1;
        }
    }
    /**
     * Generate id
     * @param   {HTMLElement} el
     * @returns {String}
     * @private
     */
    function _generateId(el) {
        var str = el.tagName + el.className + el.src + el.href + el.textContent,
            i = str.length,
            sum = 0;
        while (i--) {
            sum += str.charCodeAt(i);
        }
        return sum.toString(36);
    }

    function _saveInputCheckedState(root) {
        savedInputChecked.length = 0;
        var inputs = root.getElementsByTagName('input');
        var idx = inputs.length;
        while (idx--) {
            var el = inputs[idx];
            el.checked && savedInputChecked.push(el);
        }
    }

    function _nextTick(fn) {
        return setTimeout(fn, 0);
    }

    function _cancelNextTick(id) {
        return clearTimeout(id);
    }
    // Fixed #973:
    if (documentExists) {
        on(document, 'touchmove', function (evt) {
            if ((Sortable.active || awaitingDragStarted) && evt.cancelable) {
                evt.preventDefault();
            }
        });
    }
    // Export utils
    Sortable.utils = {
        on: on,
        off: off,
        css: css,
        find: find,
        is: function is(el, selector) {
            return !!closest(el, selector, el, false);
        },
        extend: extend,
        throttle: throttle,
        closest: closest,
        toggleClass: toggleClass,
        clone: clone,
        index: index,
        nextTick: _nextTick,
        cancelNextTick: _cancelNextTick,
        detectDirection: _detectDirection,
        getChild: getChild,
        expando: expando
    };
    /**
     * Get the Sortable instance of an element
     * @param  {HTMLElement} element The element
     * @return {Sortable|undefined}         The instance of Sortable
     */
    Sortable.get = function (element) {
        return element[expando];
    };
    /**
     * Mount a plugin to Sortable
     * @param  {...SortablePlugin|SortablePlugin[]} plugins       Plugins being mounted
     */
    Sortable.mount = function () {
        for (var _len = arguments.length, plugins = new Array(_len), _key = 0; _key < _len; _key++) {
            plugins[_key] = arguments[_key];
        }
        if (plugins[0].constructor === Array) plugins = plugins[0];
        plugins.forEach(function (plugin) {
            if (!plugin.prototype || !plugin.prototype.constructor) {
                throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(plugin));
            }
            if (plugin.utils) Sortable.utils = _objectSpread2(_objectSpread2({}, Sortable.utils), plugin.utils);
            PluginManager.mount(plugin);
        });
    };
    /**
     * Create sortable instance
     * @param {HTMLElement}  el
     * @param {Object}      [options]
     */
    Sortable.create = function (el, options) {
        return new Sortable(el, options);
    };
    // Export
    Sortable.version = version;
    var autoScrolls = [],
        scrollEl,
        scrollRootEl,
        scrolling = false,
        lastAutoScrollX,
        lastAutoScrollY,
        touchEvt$1,
        pointerElemChangedInterval;

    function AutoScrollPlugin() {
        function AutoScroll() {
            this.defaults = {
                scroll: true,
                forceAutoScrollFallback: false,
                scrollSensitivity: 30,
                scrollSpeed: 10,
                bubbleScroll: true
            };
            // Bind all private methods
            for (var fn in this) {
                if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
                    this[fn] = this[fn].bind(this);
                }
            }
        }
        AutoScroll.prototype = {
            dragStarted: function dragStarted(_ref) {
                var originalEvent = _ref.originalEvent;
                if (this.sortable.nativeDraggable) {
                    on(document, 'dragover', this._handleAutoScroll);
                } else {
                    if (this.options.supportPointer) {
                        on(document, 'pointermove', this._handleFallbackAutoScroll);
                    } else if (originalEvent.touches) {
                        on(document, 'touchmove', this._handleFallbackAutoScroll);
                    } else {
                        on(document, 'mousemove', this._handleFallbackAutoScroll);
                    }
                }
            },
            dragOverCompleted: function dragOverCompleted(_ref2) {
                var originalEvent = _ref2.originalEvent;
                // For when bubbling is canceled and using fallback (fallback 'touchmove' always reached)
                if (!this.options.dragOverBubble && !originalEvent.rootEl) {
                    this._handleAutoScroll(originalEvent);
                }
            },
            drop: function drop() {
                if (this.sortable.nativeDraggable) {
                    off(document, 'dragover', this._handleAutoScroll);
                } else {
                    off(document, 'pointermove', this._handleFallbackAutoScroll);
                    off(document, 'touchmove', this._handleFallbackAutoScroll);
                    off(document, 'mousemove', this._handleFallbackAutoScroll);
                }
                clearPointerElemChangedInterval();
                clearAutoScrolls();
                cancelThrottle();
            },
            nulling: function nulling() {
                touchEvt$1 = scrollRootEl = scrollEl = scrolling = pointerElemChangedInterval = lastAutoScrollX = lastAutoScrollY = null;
                autoScrolls.length = 0;
            },
            _handleFallbackAutoScroll: function _handleFallbackAutoScroll(evt) {
                this._handleAutoScroll(evt, true);
            },
            _handleAutoScroll: function _handleAutoScroll(evt, fallback) {
                var _this = this;
                var x = (evt.touches ? evt.touches[0] : evt).clientX,
                    y = (evt.touches ? evt.touches[0] : evt).clientY,
                    elem = document.elementFromPoint(x, y);
                touchEvt$1 = evt;
                // IE does not seem to have native autoscroll,
                // Edge's autoscroll seems too conditional,
                // MACOS Safari does not have autoscroll,
                // Firefox and Chrome are good
                if (fallback || this.options.forceAutoScrollFallback || Edge || IE11OrLess || Safari) {
                    autoScroll(evt, this.options, elem, fallback);
                    // Listener for pointer element change
                    var ogElemScroller = getParentAutoScrollElement(elem, true);
                    if (scrolling && (!pointerElemChangedInterval || x !== lastAutoScrollX || y !== lastAutoScrollY)) {
                        pointerElemChangedInterval && clearPointerElemChangedInterval();
                        // Detect for pointer elem change, emulating native DnD behaviour
                        pointerElemChangedInterval = setInterval(function () {
                            var newElem = getParentAutoScrollElement(document.elementFromPoint(x, y), true);
                            if (newElem !== ogElemScroller) {
                                ogElemScroller = newElem;
                                clearAutoScrolls();
                            }
                            autoScroll(evt, _this.options, newElem, fallback);
                        }, 10);
                        lastAutoScrollX = x;
                        lastAutoScrollY = y;
                    }
                } else {
                    // if DnD is enabled (and browser has good autoscrolling), first autoscroll will already scroll, so get parent autoscroll of first autoscroll
                    if (!this.options.bubbleScroll || getParentAutoScrollElement(elem, true) === getWindowScrollingElement()) {
                        clearAutoScrolls();
                        return;
                    }
                    autoScroll(evt, this.options, getParentAutoScrollElement(elem, false), false);
                }
            }
        };
        return _extends(AutoScroll, {
            pluginName: 'scroll',
            initializeByDefault: true
        });
    }

    function clearAutoScrolls() {
        autoScrolls.forEach(function (autoScroll) {
            clearInterval(autoScroll.pid);
        });
        autoScrolls = [];
    }

    function clearPointerElemChangedInterval() {
        clearInterval(pointerElemChangedInterval);
    }
    var autoScroll = throttle(function (evt, options, rootEl, isFallback) {
        // Bug: https://bugzilla.mozilla.org/show_bug.cgi?id=505521
        if (!options.scroll) return;
        var x = (evt.touches ? evt.touches[0] : evt).clientX,
            y = (evt.touches ? evt.touches[0] : evt).clientY,
            sens = options.scrollSensitivity,
            speed = options.scrollSpeed,
            winScroller = getWindowScrollingElement();
        var scrollThisInstance = false,
            scrollCustomFn;
        // New scroll root, set scrollEl
        if (scrollRootEl !== rootEl) {
            scrollRootEl = rootEl;
            clearAutoScrolls();
            scrollEl = options.scroll;
            scrollCustomFn = options.scrollFn;
            if (scrollEl === true) {
                scrollEl = getParentAutoScrollElement(rootEl, true);
            }
        }
        var layersOut = 0;
        var currentParent = scrollEl;
        do {
            var el = currentParent,
                rect = getRect(el),
                top = rect.top,
                bottom = rect.bottom,
                left = rect.left,
                right = rect.right,
                width = rect.width,
                height = rect.height,
                canScrollX = void 0,
                canScrollY = void 0,
                scrollWidth = el.scrollWidth,
                scrollHeight = el.scrollHeight,
                elCSS = css(el),
                scrollPosX = el.scrollLeft,
                scrollPosY = el.scrollTop;
            if (el === winScroller) {
                canScrollX = width < scrollWidth && (elCSS.overflowX === 'auto' || elCSS.overflowX === 'scroll' || elCSS.overflowX === 'visible');
                canScrollY = height < scrollHeight && (elCSS.overflowY === 'auto' || elCSS.overflowY === 'scroll' || elCSS.overflowY === 'visible');
            } else {
                canScrollX = width < scrollWidth && (elCSS.overflowX === 'auto' || elCSS.overflowX === 'scroll');
                canScrollY = height < scrollHeight && (elCSS.overflowY === 'auto' || elCSS.overflowY === 'scroll');
            }
            var vx = canScrollX && (Math.abs(right - x) <= sens && scrollPosX + width < scrollWidth) - (Math.abs(left - x) <= sens && !!scrollPosX);
            var vy = canScrollY && (Math.abs(bottom - y) <= sens && scrollPosY + height < scrollHeight) - (Math.abs(top - y) <= sens && !!scrollPosY);
            if (!autoScrolls[layersOut]) {
                for (var i = 0; i <= layersOut; i++) {
                    if (!autoScrolls[i]) {
                        autoScrolls[i] = {};
                    }
                }
            }
            if (autoScrolls[layersOut].vx != vx || autoScrolls[layersOut].vy != vy || autoScrolls[layersOut].el !== el) {
                autoScrolls[layersOut].el = el;
                autoScrolls[layersOut].vx = vx;
                autoScrolls[layersOut].vy = vy;
                clearInterval(autoScrolls[layersOut].pid);
                if (vx != 0 || vy != 0) {
                    scrollThisInstance = true;
                    /* jshint loopfunc:true */
                    autoScrolls[layersOut].pid = setInterval(function () {
                        // emulate drag over during autoscroll (fallback), emulating native DnD behaviour
                        if (isFallback && this.layer === 0) {
                            Sortable.active._onTouchMove(touchEvt$1); // To move ghost if it is positioned absolutely
                        }
                        var scrollOffsetY = autoScrolls[this.layer].vy ? autoScrolls[this.layer].vy * speed : 0;
                        var scrollOffsetX = autoScrolls[this.layer].vx ? autoScrolls[this.layer].vx * speed : 0;
                        if (typeof scrollCustomFn === 'function') {
                            if (scrollCustomFn.call(Sortable.dragged.parentNode[expando], scrollOffsetX, scrollOffsetY, evt, touchEvt$1, autoScrolls[this.layer].el) !== 'continue') {
                                return;
                            }
                        }
                        scrollBy(autoScrolls[this.layer].el, scrollOffsetX, scrollOffsetY);
                    }.bind({
                        layer: layersOut
                    }), 24);
                }
            }
            layersOut++;
        } while (options.bubbleScroll && currentParent !== winScroller && (currentParent = getParentAutoScrollElement(currentParent, false)));
        scrolling = scrollThisInstance; // in case another function catches scrolling as false in between when it is not
    }, 30);
    var drop = function drop(_ref) {
        var originalEvent = _ref.originalEvent,
            putSortable = _ref.putSortable,
            dragEl = _ref.dragEl,
            activeSortable = _ref.activeSortable,
            dispatchSortableEvent = _ref.dispatchSortableEvent,
            hideGhostForTarget = _ref.hideGhostForTarget,
            unhideGhostForTarget = _ref.unhideGhostForTarget;
        if (!originalEvent) return;
        var toSortable = putSortable || activeSortable;
        hideGhostForTarget();
        var touch = originalEvent.changedTouches && originalEvent.changedTouches.length ? originalEvent.changedTouches[0] : originalEvent;
        var target = document.elementFromPoint(touch.clientX, touch.clientY);
        unhideGhostForTarget();
        if (toSortable && !toSortable.el.contains(target)) {
            dispatchSortableEvent('spill');
            this.onSpill({
                dragEl: dragEl,
                putSortable: putSortable
            });
        }
    };

    function Revert() {}
    Revert.prototype = {
        startIndex: null,
        dragStart: function dragStart(_ref2) {
            var oldDraggableIndex = _ref2.oldDraggableIndex;
            this.startIndex = oldDraggableIndex;
        },
        onSpill: function onSpill(_ref3) {
            var dragEl = _ref3.dragEl,
                putSortable = _ref3.putSortable;
            this.sortable.captureAnimationState();
            if (putSortable) {
                putSortable.captureAnimationState();
            }
            var nextSibling = getChild(this.sortable.el, this.startIndex, this.options);
            if (nextSibling) {
                this.sortable.el.insertBefore(dragEl, nextSibling);
            } else {
                this.sortable.el.appendChild(dragEl);
            }
            this.sortable.animateAll();
            if (putSortable) {
                putSortable.animateAll();
            }
        },
        drop: drop
    };
    _extends(Revert, {
        pluginName: 'revertOnSpill'
    });

    function Remove() {}
    Remove.prototype = {
        onSpill: function onSpill(_ref4) {
            var dragEl = _ref4.dragEl,
                putSortable = _ref4.putSortable;
            var parentSortable = putSortable || this.sortable;
            parentSortable.captureAnimationState();
            dragEl.parentNode && dragEl.parentNode.removeChild(dragEl);
            parentSortable.animateAll();
        },
        drop: drop
    };
    _extends(Remove, {
        pluginName: 'removeOnSpill'
    });
    var multiDragElements = [],
        multiDragClones = [],
        lastMultiDragSelect,
        // for selection with modifier key down (SHIFT)
        multiDragSortable,
        initialFolding = false,
        // Initial multi-drag fold when drag started
        folding = false,
        // Folding any other time
        dragStarted = false,
        dragEl$1,
        clonesFromRect,
        clonesHidden;

    function MultiDragPlugin() {
        function MultiDrag(sortable) {
            // Bind all private methods
            for (var fn in this) {
                if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
                    this[fn] = this[fn].bind(this);
                }
            }
            if (!sortable.options.avoidImplicitDeselect) {
                if (sortable.options.supportPointer) {
                    on(document, 'pointerup', this._deselectMultiDrag);
                } else {
                    on(document, 'mouseup', this._deselectMultiDrag);
                    on(document, 'touchend', this._deselectMultiDrag);
                }
            }
            on(document, 'keydown', this._checkKeyDown);
            on(document, 'keyup', this._checkKeyUp);
            this.defaults = {
                selectedClass: 'sortable-selected',
                multiDragKey: null,
                avoidImplicitDeselect: false,
                setData: function setData(dataTransfer, dragEl) {
                    var data = '';
                    if (multiDragElements.length && multiDragSortable === sortable) {
                        multiDragElements.forEach(function (multiDragElement, i) {
                            data += (!i ? '' : ', ') + multiDragElement.textContent;
                        });
                    } else {
                        data = dragEl.textContent;
                    }
                    dataTransfer.setData('Text', data);
                }
            };
        }
        MultiDrag.prototype = {
            multiDragKeyDown: false,
            isMultiDrag: false,
            delayStartGlobal: function delayStartGlobal(_ref) {
                var dragged = _ref.dragEl;
                dragEl$1 = dragged;
            },
            delayEnded: function delayEnded() {
                this.isMultiDrag = ~multiDragElements.indexOf(dragEl$1);
            },
            setupClone: function setupClone(_ref2) {
                var sortable = _ref2.sortable,
                    cancel = _ref2.cancel;
                if (!this.isMultiDrag) return;
                for (var i = 0; i < multiDragElements.length; i++) {
                    multiDragClones.push(clone(multiDragElements[i]));
                    multiDragClones[i].sortableIndex = multiDragElements[i].sortableIndex;
                    multiDragClones[i].draggable = false;
                    multiDragClones[i].style['will-change'] = '';
                    toggleClass(multiDragClones[i], this.options.selectedClass, false);
                    multiDragElements[i] === dragEl$1 && toggleClass(multiDragClones[i], this.options.chosenClass, false);
                }
                sortable._hideClone();
                cancel();
            },
            clone: function clone(_ref3) {
                var sortable = _ref3.sortable,
                    rootEl = _ref3.rootEl,
                    dispatchSortableEvent = _ref3.dispatchSortableEvent,
                    cancel = _ref3.cancel;
                if (!this.isMultiDrag) return;
                if (!this.options.removeCloneOnHide) {
                    if (multiDragElements.length && multiDragSortable === sortable) {
                        insertMultiDragClones(true, rootEl);
                        dispatchSortableEvent('clone');
                        cancel();
                    }
                }
            },
            showClone: function showClone(_ref4) {
                var cloneNowShown = _ref4.cloneNowShown,
                    rootEl = _ref4.rootEl,
                    cancel = _ref4.cancel;
                if (!this.isMultiDrag) return;
                insertMultiDragClones(false, rootEl);
                multiDragClones.forEach(function (clone) {
                    css(clone, 'display', '');
                });
                cloneNowShown();
                clonesHidden = false;
                cancel();
            },
            hideClone: function hideClone(_ref5) {
                var _this = this;
                _ref5.sortable;
                var cloneNowHidden = _ref5.cloneNowHidden,
                    cancel = _ref5.cancel;
                if (!this.isMultiDrag) return;
                multiDragClones.forEach(function (clone) {
                    css(clone, 'display', 'none');
                    if (_this.options.removeCloneOnHide && clone.parentNode) {
                        clone.parentNode.removeChild(clone);
                    }
                });
                cloneNowHidden();
                clonesHidden = true;
                cancel();
            },
            dragStartGlobal: function dragStartGlobal(_ref6) {
                _ref6.sortable;
                if (!this.isMultiDrag && multiDragSortable) {
                    multiDragSortable.multiDrag._deselectMultiDrag();
                }
                multiDragElements.forEach(function (multiDragElement) {
                    multiDragElement.sortableIndex = index(multiDragElement);
                });
                // Sort multi-drag elements
                multiDragElements = multiDragElements.sort(function (a, b) {
                    return a.sortableIndex - b.sortableIndex;
                });
                dragStarted = true;
            },
            dragStarted: function dragStarted(_ref7) {
                var _this2 = this;
                var sortable = _ref7.sortable;
                if (!this.isMultiDrag) return;
                if (this.options.sort) {
                    // Capture rects,
                    // hide multi drag elements (by positioning them absolute),
                    // set multi drag elements rects to dragRect,
                    // show multi drag elements,
                    // animate to rects,
                    // unset rects & remove from DOM
                    sortable.captureAnimationState();
                    if (this.options.animation) {
                        multiDragElements.forEach(function (multiDragElement) {
                            if (multiDragElement === dragEl$1) return;
                            css(multiDragElement, 'position', 'absolute');
                        });
                        var dragRect = getRect(dragEl$1, false, true, true);
                        multiDragElements.forEach(function (multiDragElement) {
                            if (multiDragElement === dragEl$1) return;
                            setRect(multiDragElement, dragRect);
                        });
                        folding = true;
                        initialFolding = true;
                    }
                }
                sortable.animateAll(function () {
                    folding = false;
                    initialFolding = false;
                    if (_this2.options.animation) {
                        multiDragElements.forEach(function (multiDragElement) {
                            unsetRect(multiDragElement);
                        });
                    }
                    // Remove all auxiliary multidrag items from el, if sorting enabled
                    if (_this2.options.sort) {
                        removeMultiDragElements();
                    }
                });
            },
            dragOver: function dragOver(_ref8) {
                var target = _ref8.target,
                    completed = _ref8.completed,
                    cancel = _ref8.cancel;
                if (folding && ~multiDragElements.indexOf(target)) {
                    completed(false);
                    cancel();
                }
            },
            revert: function revert(_ref9) {
                var fromSortable = _ref9.fromSortable,
                    rootEl = _ref9.rootEl,
                    sortable = _ref9.sortable,
                    dragRect = _ref9.dragRect;
                if (multiDragElements.length > 1) {
                    // Setup unfold animation
                    multiDragElements.forEach(function (multiDragElement) {
                        sortable.addAnimationState({
                            target: multiDragElement,
                            rect: folding ? getRect(multiDragElement) : dragRect
                        });
                        unsetRect(multiDragElement);
                        multiDragElement.fromRect = dragRect;
                        fromSortable.removeAnimationState(multiDragElement);
                    });
                    folding = false;
                    insertMultiDragElements(!this.options.removeCloneOnHide, rootEl);
                }
            },
            dragOverCompleted: function dragOverCompleted(_ref10) {
                var sortable = _ref10.sortable,
                    isOwner = _ref10.isOwner,
                    insertion = _ref10.insertion,
                    activeSortable = _ref10.activeSortable,
                    parentEl = _ref10.parentEl,
                    putSortable = _ref10.putSortable;
                var options = this.options;
                if (insertion) {
                    // Clones must be hidden before folding animation to capture dragRectAbsolute properly
                    if (isOwner) {
                        activeSortable._hideClone();
                    }
                    initialFolding = false;
                    // If leaving sort:false root, or already folding - Fold to new location
                    if (options.animation && multiDragElements.length > 1 && (folding || !isOwner && !activeSortable.options.sort && !putSortable)) {
                        // Fold: Set all multi drag elements's rects to dragEl's rect when multi-drag elements are invisible
                        var dragRectAbsolute = getRect(dragEl$1, false, true, true);
                        multiDragElements.forEach(function (multiDragElement) {
                            if (multiDragElement === dragEl$1) return;
                            setRect(multiDragElement, dragRectAbsolute);
                            // Move element(s) to end of parentEl so that it does not interfere with multi-drag clones insertion if they are inserted
                            // while folding, and so that we can capture them again because old sortable will no longer be fromSortable
                            parentEl.appendChild(multiDragElement);
                        });
                        folding = true;
                    }
                    // Clones must be shown (and check to remove multi drags) after folding when interfering multiDragElements are moved out
                    if (!isOwner) {
                        // Only remove if not folding (folding will remove them anyways)
                        if (!folding) {
                            removeMultiDragElements();
                        }
                        if (multiDragElements.length > 1) {
                            var clonesHiddenBefore = clonesHidden;
                            activeSortable._showClone(sortable);
                            // Unfold animation for clones if showing from hidden
                            if (activeSortable.options.animation && !clonesHidden && clonesHiddenBefore) {
                                multiDragClones.forEach(function (clone) {
                                    activeSortable.addAnimationState({
                                        target: clone,
                                        rect: clonesFromRect
                                    });
                                    clone.fromRect = clonesFromRect;
                                    clone.thisAnimationDuration = null;
                                });
                            }
                        } else {
                            activeSortable._showClone(sortable);
                        }
                    }
                }
            },
            dragOverAnimationCapture: function dragOverAnimationCapture(_ref11) {
                var dragRect = _ref11.dragRect,
                    isOwner = _ref11.isOwner,
                    activeSortable = _ref11.activeSortable;
                multiDragElements.forEach(function (multiDragElement) {
                    multiDragElement.thisAnimationDuration = null;
                });
                if (activeSortable.options.animation && !isOwner && activeSortable.multiDrag.isMultiDrag) {
                    clonesFromRect = _extends({}, dragRect);
                    var dragMatrix = matrix(dragEl$1, true);
                    clonesFromRect.top -= dragMatrix.f;
                    clonesFromRect.left -= dragMatrix.e;
                }
            },
            dragOverAnimationComplete: function dragOverAnimationComplete() {
                if (folding) {
                    folding = false;
                    removeMultiDragElements();
                }
            },
            drop: function drop(_ref12) {
                var evt = _ref12.originalEvent,
                    rootEl = _ref12.rootEl,
                    parentEl = _ref12.parentEl,
                    sortable = _ref12.sortable,
                    dispatchSortableEvent = _ref12.dispatchSortableEvent,
                    oldIndex = _ref12.oldIndex,
                    putSortable = _ref12.putSortable;
                var toSortable = putSortable || this.sortable;
                if (!evt) return;
                var options = this.options,
                    children = parentEl.children;
                // Multi-drag selection
                if (!dragStarted) {
                    if (options.multiDragKey && !this.multiDragKeyDown) {
                        this._deselectMultiDrag();
                    }
                    toggleClass(dragEl$1, options.selectedClass, !~multiDragElements.indexOf(dragEl$1));
                    if (!~multiDragElements.indexOf(dragEl$1)) {
                        multiDragElements.push(dragEl$1);
                        dispatchEvent({
                            sortable: sortable,
                            rootEl: rootEl,
                            name: 'select',
                            targetEl: dragEl$1,
                            originalEvent: evt
                        });
                        // Modifier activated, select from last to dragEl
                        if (evt.shiftKey && lastMultiDragSelect && sortable.el.contains(lastMultiDragSelect)) {
                            var lastIndex = index(lastMultiDragSelect),
                                currentIndex = index(dragEl$1);
                            if (~lastIndex && ~currentIndex && lastIndex !== currentIndex) {
                                // Must include lastMultiDragSelect (select it), in case modified selection from no selection
                                // (but previous selection existed)
                                var n, i;
                                if (currentIndex > lastIndex) {
                                    i = lastIndex;
                                    n = currentIndex;
                                } else {
                                    i = currentIndex;
                                    n = lastIndex + 1;
                                }
                                for (; i < n; i++) {
                                    if (~multiDragElements.indexOf(children[i])) continue;
                                    toggleClass(children[i], options.selectedClass, true);
                                    multiDragElements.push(children[i]);
                                    dispatchEvent({
                                        sortable: sortable,
                                        rootEl: rootEl,
                                        name: 'select',
                                        targetEl: children[i],
                                        originalEvent: evt
                                    });
                                }
                            }
                        } else {
                            lastMultiDragSelect = dragEl$1;
                        }
                        multiDragSortable = toSortable;
                    } else {
                        multiDragElements.splice(multiDragElements.indexOf(dragEl$1), 1);
                        lastMultiDragSelect = null;
                        dispatchEvent({
                            sortable: sortable,
                            rootEl: rootEl,
                            name: 'deselect',
                            targetEl: dragEl$1,
                            originalEvent: evt
                        });
                    }
                }
                // Multi-drag drop
                if (dragStarted && this.isMultiDrag) {
                    folding = false;
                    // Do not "unfold" after around dragEl if reverted
                    if ((parentEl[expando].options.sort || parentEl !== rootEl) && multiDragElements.length > 1) {
                        var dragRect = getRect(dragEl$1),
                            multiDragIndex = index(dragEl$1, ':not(.' + this.options.selectedClass + ')');
                        if (!initialFolding && options.animation) dragEl$1.thisAnimationDuration = null;
                        toSortable.captureAnimationState();
                        if (!initialFolding) {
                            if (options.animation) {
                                dragEl$1.fromRect = dragRect;
                                multiDragElements.forEach(function (multiDragElement) {
                                    multiDragElement.thisAnimationDuration = null;
                                    if (multiDragElement !== dragEl$1) {
                                        var rect = folding ? getRect(multiDragElement) : dragRect;
                                        multiDragElement.fromRect = rect;
                                        // Prepare unfold animation
                                        toSortable.addAnimationState({
                                            target: multiDragElement,
                                            rect: rect
                                        });
                                    }
                                });
                            }
                            // Multi drag elements are not necessarily removed from the DOM on drop, so to reinsert
                            // properly they must all be removed
                            removeMultiDragElements();
                            multiDragElements.forEach(function (multiDragElement) {
                                if (children[multiDragIndex]) {
                                    parentEl.insertBefore(multiDragElement, children[multiDragIndex]);
                                } else {
                                    parentEl.appendChild(multiDragElement);
                                }
                                multiDragIndex++;
                            });
                            // If initial folding is done, the elements may have changed position because they are now
                            // unfolding around dragEl, even though dragEl may not have his index changed, so update event
                            // must be fired here as Sortable will not.
                            if (oldIndex === index(dragEl$1)) {
                                var update = false;
                                multiDragElements.forEach(function (multiDragElement) {
                                    if (multiDragElement.sortableIndex !== index(multiDragElement)) {
                                        update = true;
                                        return;
                                    }
                                });
                                if (update) {
                                    dispatchSortableEvent('update');
                                    dispatchSortableEvent('sort');
                                }
                            }
                        }
                        // Must be done after capturing individual rects (scroll bar)
                        multiDragElements.forEach(function (multiDragElement) {
                            unsetRect(multiDragElement);
                        });
                        toSortable.animateAll();
                    }
                    multiDragSortable = toSortable;
                }
                // Remove clones if necessary
                if (rootEl === parentEl || putSortable && putSortable.lastPutMode !== 'clone') {
                    multiDragClones.forEach(function (clone) {
                        clone.parentNode && clone.parentNode.removeChild(clone);
                    });
                }
            },
            nullingGlobal: function nullingGlobal() {
                this.isMultiDrag = dragStarted = false;
                multiDragClones.length = 0;
            },
            destroyGlobal: function destroyGlobal() {
                this._deselectMultiDrag();
                off(document, 'pointerup', this._deselectMultiDrag);
                off(document, 'mouseup', this._deselectMultiDrag);
                off(document, 'touchend', this._deselectMultiDrag);
                off(document, 'keydown', this._checkKeyDown);
                off(document, 'keyup', this._checkKeyUp);
            },
            _deselectMultiDrag: function _deselectMultiDrag(evt) {
                if (typeof dragStarted !== "undefined" && dragStarted) return;
                // Only deselect if selection is in this sortable
                if (multiDragSortable !== this.sortable) return;
                // Only deselect if target is not item in this sortable
                if (evt && closest(evt.target, this.options.draggable, this.sortable.el, false)) return;
                // Only deselect if left click
                if (evt && evt.button !== 0) return;
                while (multiDragElements.length) {
                    var el = multiDragElements[0];
                    toggleClass(el, this.options.selectedClass, false);
                    multiDragElements.shift();
                    dispatchEvent({
                        sortable: this.sortable,
                        rootEl: this.sortable.el,
                        name: 'deselect',
                        targetEl: el,
                        originalEvent: evt
                    });
                }
            },
            _checkKeyDown: function _checkKeyDown(evt) {
                if (evt.key === this.options.multiDragKey) {
                    this.multiDragKeyDown = true;
                }
            },
            _checkKeyUp: function _checkKeyUp(evt) {
                if (evt.key === this.options.multiDragKey) {
                    this.multiDragKeyDown = false;
                }
            }
        };
        return _extends(MultiDrag, {
            // Static methods & properties
            pluginName: 'multiDrag',
            utils: {
                /**
                 * Selects the provided multi-drag item
                 * @param  {HTMLElement} el    The element to be selected
                 */
                select: function select(el) {
                    var sortable = el.parentNode[expando];
                    if (!sortable || !sortable.options.multiDrag || ~multiDragElements.indexOf(el)) return;
                    if (multiDragSortable && multiDragSortable !== sortable) {
                        multiDragSortable.multiDrag._deselectMultiDrag();
                        multiDragSortable = sortable;
                    }
                    toggleClass(el, sortable.options.selectedClass, true);
                    multiDragElements.push(el);
                },
                /**
                 * Deselects the provided multi-drag item
                 * @param  {HTMLElement} el    The element to be deselected
                 */
                deselect: function deselect(el) {
                    var sortable = el.parentNode[expando],
                        index = multiDragElements.indexOf(el);
                    if (!sortable || !sortable.options.multiDrag || !~index) return;
                    toggleClass(el, sortable.options.selectedClass, false);
                    multiDragElements.splice(index, 1);
                }
            },
            eventProperties: function eventProperties() {
                var _this3 = this;
                var oldIndicies = [],
                    newIndicies = [];
                multiDragElements.forEach(function (multiDragElement) {
                    oldIndicies.push({
                        multiDragElement: multiDragElement,
                        index: multiDragElement.sortableIndex
                    });
                    // multiDragElements will already be sorted if folding
                    var newIndex;
                    if (folding && multiDragElement !== dragEl$1) {
                        newIndex = -1;
                    } else if (folding) {
                        newIndex = index(multiDragElement, ':not(.' + _this3.options.selectedClass + ')');
                    } else {
                        newIndex = index(multiDragElement);
                    }
                    newIndicies.push({
                        multiDragElement: multiDragElement,
                        index: newIndex
                    });
                });
                return {
                    items: _toConsumableArray(multiDragElements),
                    clones: [].concat(multiDragClones),
                    oldIndicies: oldIndicies,
                    newIndicies: newIndicies
                };
            },
            optionListeners: {
                multiDragKey: function multiDragKey(key) {
                    key = key.toLowerCase();
                    if (key === 'ctrl') {
                        key = 'Control';
                    } else if (key.length > 1) {
                        key = key.charAt(0).toUpperCase() + key.substr(1);
                    }
                    return key;
                }
            }
        });
    }

    function insertMultiDragElements(clonesInserted, rootEl) {
        multiDragElements.forEach(function (multiDragElement, i) {
            var target = rootEl.children[multiDragElement.sortableIndex + (clonesInserted ? Number(i) : 0)];
            if (target) {
                rootEl.insertBefore(multiDragElement, target);
            } else {
                rootEl.appendChild(multiDragElement);
            }
        });
    }
    /**
     * Insert multi-drag clones
     * @param  {[Boolean]} elementsInserted  Whether the multi-drag elements are inserted
     * @param  {HTMLElement} rootEl
     */
    function insertMultiDragClones(elementsInserted, rootEl) {
        multiDragClones.forEach(function (clone, i) {
            var target = rootEl.children[clone.sortableIndex + (elementsInserted ? Number(i) : 0)];
            if (target) {
                rootEl.insertBefore(clone, target);
            } else {
                rootEl.appendChild(clone);
            }
        });
    }

    function removeMultiDragElements() {
        multiDragElements.forEach(function (multiDragElement) {
            if (multiDragElement === dragEl$1) return;
            multiDragElement.parentNode && multiDragElement.parentNode.removeChild(multiDragElement);
        });
    }
    Sortable.mount(new AutoScrollPlugin());
    Sortable.mount(Remove, Revert);
    Sortable.instances = [];
    Sortable.mount(new MultiDragPlugin());

    function onChange$3(init) {
        var instance;
        while (instance = Sortable.instances.pop()) {
            instance.destroy();
        }
        var sources = getElements('.can\\:sort:not(.not\\:active)');
        sources && toCount(sources) && sources.forEach(function (source) {
            var batch = getDatum(source, 'batch'),
                handle;
            if (hasClass(source, 'content:columns') || hasClass(source, 'lot:columns'));
            if (hasClass(source, 'content:fields') || hasClass(source, 'lot:fields')) {
                handle = 'label[for]';
            }
            if (hasClass(source, 'content:files') || hasClass(source, 'lot:files'));
            if (hasClass(source, 'content:folders') || hasClass(source, 'lot:folders'));
            if (hasClass(source, 'content:pages') || hasClass(source, 'lot:pages'));
            if (hasClass(source, 'content:rows') || hasClass(source, 'lot:rows'));
            if (hasClass(source, 'content:stacks') || hasClass(source, 'lot:stacks'));
            if (hasClass(source, 'content:tabs') || hasClass(source, 'lot:tabs'));
            var sortable = new Sortable(source, {
                animation: 150,
                avoidImplicitDeselect: false,
                dataIdAttr: 'data-value',
                emptyInsertThreshold: 5,
                fallbackOnBody: true,
                fallbackTolerance: 3,
                filter: '.not\\:active,:disabled,[aria-disabled=true],[disabled],input[type=hidden]',
                group: batch,
                handle: handle,
                // multiDrag: true,
                onSort: onSort,
                swapThreshold: 0.5
            });
            Sortable.instances.push(sortable);
        });
        1 === init && W._.on('change', onChange$3);
    }

    function onSort(e) {
        var t = e.item;
        W._.fire.apply(t, ['sort', [getDatum(t, 'value'), getDatum(t, 'name')]]);
    }
    W.Sortable = Sortable;
    var targets$2 = 'a[target^="stack:"]:not(.not\\:active)';
    var STACK_INPUT = 0;
    var STACK_OF = 1;
    var STACK_STACKS = 2;

    function onChange$2(init) {
        var sources = getElements('.lot\\:stacks[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var stackCurrent,
                stacks = [].slice.call(getChildren(source)).filter(function (v) {
                    return hasClass(v, 'lot:stack');
                }),
                input = setElement('input'),
                name,
                target;
            input.type = 'hidden';
            input.name = name = getDatum(source, 'name');
            if (name) {
                getElements('input[name="' + name + '"]', source).forEach(function (v) {
                    return letElement(v);
                });
                setChildLast(source, input);
            }
            stacks.forEach(function (stack, index) {
                if (!(target = getElement(targets$2, stack))) {
                    return;
                }
                target._ = target._ || {};
                target._[STACK_INPUT] = input;
                target._[STACK_OF] = index;
                target._[STACK_STACKS] = stacks;
                onEventOnly('click', target, onClickStack);
                onEventOnly('keydown', target, onKeyDownStack);
            });
            stackCurrent = stacks.find(function (value, key) {
                return 0 !== key && hasClass(value, 'is:current');
            });
            if (stackCurrent) {
                input.value = getDatum(stackCurrent, 'value');
            }
            onEventOnly('keydown', source, onKeyDownStacks);
        });
        1 === init && W._.on('change', onChange$2);
    }

    function onClickStack(e) {
        var t = this,
            parent = getParent(getParent(t)),
            self = getParent(parent, '.lot\\:stacks'),
            current,
            value;
        var name = t._[STACK_INPUT].name;
        if (!hasClass(parent, 'has:link')) {
            t._[STACK_STACKS].forEach(function (stack) {
                if (stack !== parent) {
                    letClass(current = getElement('a[target^="stack:"]', stack), 'is:current');
                    letClass(stack, 'is:current');
                    setAttribute(current, 'aria-expanded', 'false');
                }
            });
            if (hasClass(parent, 'can:toggle')) {
                setAttribute(t, 'aria-expanded', getAttribute(t, 'aria-expanded') ? 'false' : 'true');
                toggleClass$1(parent, 'is:current');
                toggleClass$1(t, 'is:current');
            } else {
                setAttribute(t, 'aria-expanded', 'true');
                setClass(parent, 'is:current');
                setClass(t, 'is:current');
            }
            current = hasClass(t, 'is:current');
            t._[STACK_INPUT].value = value = current ? getDatum(parent, 'value') : null;
            toggleClass$1(self, 'has:current', current);
            var pathname = theLocation.pathname,
                search = theLocation.search;
            var query = fromQuery(search);
            var q = fromQuery(name + '=' + value);
            query = fromStates(query, q.query || {});
            if (null === value) {
                query = removeNull(query);
            }
            theHistory.replaceState({}, "", pathname + (false !== query ? toQuery(query) : ""));
            W._.fire('change.stack', [value, name], parent);
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
                    fireFocus(getChildFirst(current));
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
                    fireFocus(getChildFirst(current));
                }
                stop = true;
            } else if (' ' === key || 'Enter' === key) {
                if (hasClass(getParent(getParent(t)), 'can:toggle')) {
                    fireEvent('click', t), fireFocus(t);
                }
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:stacks[tabindex]')) {
                    any = [].slice.call(getElements(targets$2, parent));
                    if (current = any.pop()) {
                        fireEvent('click', current), fireFocus(current);
                    }
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:stacks[tabindex]')) {
                    if (current = getElement(targets$2, parent)) {
                        fireEvent('click', current), fireFocus(current);
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
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if ('PageUp' === key) {
                prev = current && getPrev(current);
                if (current = prev && getElement(targets$2, prev)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            }
        } else if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (t !== e.target) {
                return;
            }
            if ('ArrowDown' === key || 'ArrowRight' === key || 'Home' === key || 'PageDown' === key) {
                if (current = getElement(targets$2, t)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'End' === key || 'PageUp' === key) {
                any = [].slice.call(getElements(targets$2, t));
                if (current = any.pop()) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets$1 = 'a[target^="tab:"]:not(.not\\:active)';
    var TAB_INPUT = 0;
    var TAB_OF = 1;
    var TAB_PANES = 2;
    var TAB_TABS = 3;

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
            if (name) {
                getElements('input[name="' + name + '"]', source).forEach(function (v) {
                    return letElement(v);
                });
                setChildLast(source, input);
            }
            tabs.forEach(function (tab, index) {
                tab._ = tab._ || {};
                tab._[TAB_INPUT] = input;
                tab._[TAB_OF] = index;
                tab._[TAB_PANES] = panes;
                tab._[TAB_TABS] = tabs;
                onEventOnly('click', tab, onClickTab);
                onEventOnly('keydown', tab, onKeyDownTab);
            });
            tabCurrent = tabs.find(function (value, key) {
                return 0 !== key && hasClass(getParent(value), 'is:current');
            });
            if (tabCurrent) {
                input.value = getDatum(tabCurrent, 'value');
            }
            onEventOnly('keydown', source, onKeyDownTabs);
        });
        1 === init && W._.on('change', onChange$1);
    }

    function onClickTab(e) {
        var t = this,
            pane = t._[TAB_PANES][t._[TAB_OF]],
            parent = getParent(t),
            self = getParent(parent, '.lot\\:tabs'),
            current,
            value;
        var name = t._[TAB_INPUT].name;
        if (!hasClass(parent, 'has:link')) {
            t._[TAB_TABS].forEach(function (tab) {
                if (tab !== t) {
                    letClass(getParent(tab), 'is:current');
                    letClass(tab, 'is:current');
                    setAttribute(tab, 'aria-selected', 'false');
                    setAttribute(tab, 'tabindex', '-1');
                    var _pane = t._[TAB_PANES][tab._[TAB_OF]];
                    _pane && letClass(_pane, 'is:current');
                }
            });
            if (hasClass(parent, 'can:toggle')) {
                toggleClass$1(parent, 'is:current');
                toggleClass$1(t, 'is:current');
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
                t._[TAB_INPUT].value = value = current ? getDatum(t, 'value') : null;
                toggleClass$1(pane, 'is:current', current);
                toggleClass$1(self, 'has:current', current);
                var pathname = theLocation.pathname,
                    search = theLocation.search;
                var query = fromQuery(search);
                var q = fromQuery(name + '=' + value);
                query = fromStates(query, q.query || {});
                if (null === value) {
                    query = removeNull(query);
                }
                theHistory.replaceState({}, "", pathname + (false !== query ? toQuery(query) : ""));
                W._.fire('change.tab', [value, name], pane);
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
                    fireEvent('click', current), fireFocus(current);
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
                    fireEvent('click', current), fireFocus(current);
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
                    fireEvent('click', current), fireFocus(current);
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
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if (' ' === key || 'Enter' === key) {
                if (hasClass(t, 'can:toggle')) {
                    fireEvent('click', t), fireFocus(t);
                }
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:tabs[tabindex]')) {
                    any = [].slice.call(getElements(targets$1, parent));
                    if (current = any.pop()) {
                        fireEvent('click', current), fireFocus(current);
                    }
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:tabs[tabindex]')) {
                    if (current = getElement(targets$1, parent)) {
                        fireEvent('click', current), fireFocus(current);
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
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if ('PageUp' === key) {
                prev = current && getPrev(current);
                if (current = prev && getChildFirst(prev)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            }
        } else if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (t !== e.target) {
                return;
            }
            if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
                if (current = getElement(targets$1 + '.is\\:current', t)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
                if (current = getElement(targets$1 + '.is\\:current', t)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if ('Home' === key) {
                if (current = getElement(targets$1, t)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if ('End' === key) {
                any = [].slice.call(getElements(targets$1, t));
                if (current = any.pop()) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    var targets = ':scope>:where(a,button,input,select,textarea,[tabindex]):not(:disabled):not([tabindex="-1"]):not(.not\\:active)';

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
                if (stop = !(hasState(t, 'selectionStart') && 0 !== t.selectionStart)) {
                    fireFocus(prev), fireSelect(prev);
                }
            } else if ('ArrowRight' === key) {
                if (stop = !(hasState(t, 'selectionEnd') && t.selectionEnd < toCount(t.value || ""))) {
                    fireFocus(next), fireSelect(next);
                }
            } else if ('End' === key) {
                stop = !(hasState(t, 'selectionEnd') && toCount(t.value || ""));
                if (stop && (parent = getParent(t, '.lot\\:tasks[tabindex]'))) {
                    any = [].slice.call(getElements(targets, parent));
                    if (current = any.pop()) {
                        fireFocus(current), fireSelect(current);
                    }
                }
            } else if ('Home' === key) {
                stop = !(hasState(t, 'selectionStart') && toCount(t.value || ""));
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
    hook(_$1);
    W.Key = Key;
    W._ = _$1;
    onEvent('beforeload', D, function () {
        return _$1.fire('let');
    });
    onEvent('load', D, function () {
        return _$1.fire('get');
    });
    onEvent('DOMContentLoaded', D, function () {
        return _$1.fire('set');
    });
    onChange$d(1);
    Dialog();
    Fields();
    onChange$9(1);
    onChange$8(1);
    onChange$7(1);
    onChange$6(1);
    onChange$5(1);
    onChange$4();
    onChange$3(1);
    onChange$2(1);
    onChange$1(1);
    onChange(1);
})();