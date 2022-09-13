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
        return x && isSet$1(of) && x instanceof of ;
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
    var isSet$1 = function isSet(x) {
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
        if (isSet$1(edges[0]) && x < edges[0]) {
            return edges[0];
        }
        if (isSet$1(edges[1]) && x > edges[1]) {
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
    var fromHTML = function fromHTML(x) {
        return x.replace(/&/g, '&amp;').replace(/>/g, '&gt;').replace(/</g, '&lt;');
    };
    var fromJSON = function fromJSON(x) {
        var value = null;
        try {
            value = JSON.parse(x);
        } catch (e) {}
        return value;
    };
    var fromStates = function fromStates() {
        for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
            lot[_key] = arguments[_key];
        }
        var out = lot.shift();
        for (var i = 0, j = toCount(lot); i < j; ++i) {
            for (var k in lot[i]) {
                // Assign value
                if (!isSet$1(out[k])) {
                    out[k] = lot[i][k];
                    continue;
                } // Merge array
                if (isArray(out[k]) && isArray(lot[i][k])) {
                    out[k] = [
                        /* Clone! */
                    ].concat(out[k]);
                    for (var ii = 0, jj = toCount(lot[i][k]); ii < jj; ++ii) {
                        if (!hasValue(lot[i][k][ii], out[k])) {
                            out[k].push(lot[i][k][ii]);
                        }
                    } // Merge object recursive
                } else if (isObject(out[k]) && isObject(lot[i][k])) {
                    out[k] = fromStates({
                        /* Clone! */
                    }, out[k], lot[i][k]); // Replace value
                } else {
                    out[k] = lot[i][k];
                }
            }
        }
        return out;
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
        } // if (isString(classes)) {
        node.className = classes; // }
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
    var theLocation = W.location;
    var event = function event(name, options, cache) {
        if (cache && isSet$1(events[name])) {
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
    var targets$7 = ':scope>[tabindex]:not(.not\\:active)';

    function fireFocus$7(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange$a() {
        var sources = getElements('.lot\\:bar[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var items = getElements(targets$7, source);
            items.forEach(function (item) {
                onEvent('keydown', item, onKeyDownBarItem);
            });
            onEvent('keydown', source, onKeyDownBar);
        });
    }

    function onKeyDownBar(e) {
        if (e.defaultPrevented) {
            return;
        }
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
                any = [].slice.call(getElements(targets$7, t));
                fireFocus$7(any.pop());
                stop = true;
            } else if ('ArrowRight' === key || 'Home' === key) {
                fireFocus$7(getElement(targets$7, t));
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownBarItem(e) {
        // TODO: Prevent conflict with `link.js.mjs` key binding.
        if (e.defaultPrevented) {
            return;
        }
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
            fireFocus$7(prev);
            stop = true;
        } else if ('ArrowRight' === key) {
            fireFocus$7(next);
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

    function Bar() {
        W._.on('change', onChange$a), onChange$a();
    }
    var dialog = setElement('dialog'),
        dialogForm = setElement('form', "", {
            method: 'dialog'
        }),
        dialogTemplate = setElement('div');
    setChildLast(B, dialog);
    setChildLast(dialog, dialogForm);

    function onDialogCancel(e) {
        var t = this,
            value;
        offEvent(e.type, t, onDialogCancel);
        value = t.x(toValue(t.returnValue));
        isFunction(t.c) && t.c.apply(t, [t.open]);
        return value;
    }

    function onDialogSubmit(e) {
        var t = this,
            value;
        offEvent(e.type, t, onDialogSubmit);
        value = t.v(toValue(t.returnValue));
        isFunction(t.c) && t.c.apply(t, [t.open]);
        return value;
    }

    function setDialog(content, then) {
        setHTML(dialogForm, "");
        if (isString(content)) {
            setHTML(dialogTemplate, content.trim());
            content = dialogTemplate.childNodes;
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
        return new Promise(function (yes, no) {
            dialog.c = then;
            dialog.v = yes;
            dialog.x = no;
            onEvent('cancel', dialog, onDialogCancel);
            onEvent('submit', dialog, onDialogSubmit);
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
    var dialogConfirmDescription = setElement('p'),
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
    var dialogPromptKey = setElement('p'),
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

    function Dialog() {
        W._.dialog = setDialog;
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
            if (!isSet$1(hooks[name])) {
                return $;
            }
            hooks[name].forEach(function (then) {
                return then.apply($, data);
            });
            return $;
        }

        function off(name, then) {
            if (!isSet$1(name)) {
                return hooks = {}, $;
            }
            if (isSet$1(hooks[name])) {
                if (isSet$1(then)) {
                    for (var i = 0, _j = hooks[name].length; i < _j; ++i) {
                        if (then === hooks[name][i]) {
                            hooks[name].splice(i, 1);
                            break;
                        }
                    } // Clean-up empty hook(s)
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
            if (!isSet$1(hooks[name])) {
                hooks[name] = [];
            }
            if (isSet$1(then)) {
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
    var name$2 = 'OP',
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
        } // Already instantiated, skip!
        if (source[name$2]) {
            return source[name$2];
        } // Return new instance if `OP` was called without the `new` operator
        if (!isInstance($, OP)) {
            return new OP(source, state);
        }
        var _hook = hook($),
            fire = _hook.fire;
        _hook.hooks;
        $.state = state = fromStates({}, OP.state, state);
        $.options = {};
        $.source = source; // Store current instance to `OP.instances`
        OP.instances[source.id || source.name || toObjectCount(OP.instances)] = $; // Mark current DOM as active option picker to prevent duplicate instance
        source[name$2] = $;

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
                selectBoxOptionIndexCurrent = toCount(selectBoxOptions); // Continue walking up until it finds an option that is not disabled and not hidden
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
                !selectBoxSize && doExit(); // offEventDefault(e);
            } else if (KEY_START === key) {
                // Start from the first option position - 1
                selectBoxOptionIndexCurrent = -1; // Continue walking up until it finds an option that is not disabled and not hidden
                while (selectBoxFakeOption = selectBoxFakeOptions[++selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption) && !selectBoxFakeOption.hidden) {
                        break;
                    }
                }
                selectBoxFakeOption && (doClick(selectBoxFakeOption), doToggle(isOpen));
                offEventDefault(e);
            } else if (KEY_TAB$1 === key) {
                !selectBoxFakeInput && selectBoxFakeOption && doClick(selectBoxFakeOption);
                !selectBoxSize && doExit(); // offEventDefault(e);
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
                    } // Always select the first match, but do not update the value
                    if (first) {
                        selectBoxOptionIndex = first[PROP_INDEX];
                        setOptionSelected(first[PROP_SOURCE]);
                        setOptionFakeSelected(first);
                        selectBoxFakeDropDown.hidden = false; // No match!
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
                    } // Reset all filter(s) if there is only one or none option marked
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
            if (!source[name$2]) {
                return $; // Already ejected
            }
            delete source[name$2];
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

    function onChange$9(init) {
        // Destroy!
        var $;
        for (var key in OP.instances) {
            $ = OP.instances[key];
            $.pop();
            delete OP.instances[key];
        }
        var sources = getElements('input[list],select');
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            var c = getClasses(source);
            var $ = new OP(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            setClasses($.self, c);
        });
        1 === init && W._.on('change', onChange$9);
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
        } // No need to escape `/` in the pattern string
        pattern = pattern.replace(/\//g, '\\/');
        return new RegExp(pattern, isSet$1(opt) ? opt : 'g');
    };
    var x = "!$^*()+=[]{}|:<>,.?/-";
    var name$1 = 'TP';
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
        } // Already instantiated, skip!
        if (source[name$1]) {
            return source[name$1];
        } // Return new instance if `TP` was called without the `new` operator
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
        $.source = source; // Store current instance to `TP.instances`
        TP.instances[source.id || source.name || toObjectCount(TP.instances)] = $; // Mark current DOM as active tag picker to prevent duplicate instance
        source[name$1] = $;
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
            values = values ? values.split(state.join) : []; // Remove all tag(s) …
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
            source.value = ""; // … then add tag(s)
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
            if (selectTextInput) {
                setValue("", 1);
            }
        }

        function setTextCopy(selectTextCopy) {
            setChildLast(self, textCopy);
            textCopy.value = $.tags.join(state.join);
            if (selectTextCopy) {
                textCopy.focus();
                textCopy.select();
            }
        }

        function setValue(value, fireFocus) {
            setText(textInput, value);
            setText(textInputHint, value ? "" : thePlaceholder);
            if (fireFocus) {
                textInput.focus(); // Move caret to the end!
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
                    return letTextCopy(1);
                })();
            } else if ('cut' === type) {
                !sourceIsReadOnly() && setTags("");
                delay(function () {
                    return letTextCopy(1);
                })();
            } else if ('paste' === type) {
                delay(function () {
                    !sourceIsReadOnly() && setTags(textCopy.value);
                    letTextCopy(1);
                })();
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
                } // Focus to the first tag
                if (KEY_BEGIN === key) {
                    if (theTag = getChildren(textOutput, 0)) {
                        theTag.focus(), offEventDefault(e);
                    }
                    return;
                } // Focus to the last tag
                if (KEY_END === key) {
                    if (theTag = getChildren(textOutput, toCount($.tags) - 1)) {
                        theTag.focus(), offEventDefault(e);
                        return;
                    }
                } // Focus to the previous tag
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
                } // Focus to the next tag or to the tag editor
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
            } // Select all tag(s) with `Ctrl+A` key
            if (KEY_A === key) {
                setTextCopy(1);
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
                theValue = doValidTag(getText(textInput)),
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
                var theValueAfter = doValidTag(getText(textInput));
                setText(textInputHint, theValueAfter ? "" : thePlaceholder); // Try to add support for browser(s) without `KeyboardEvent.prototype.key` feature
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
            })(); // Focus to the first tag
            if ("" === theValue && KEY_BEGIN === key) {
                if (theTag = getChildren(textOutput, 0)) {
                    theTag.focus(), offEventDefault(e);
                    return;
                }
            } // Focus to the last tag
            if ("" === theValue && KEY_END === key) {
                if (theTag = getChildren(textOutput, toCount($.tags) - 1)) {
                    theTag.focus(), offEventDefault(e);
                    return;
                }
            } // Select all tag(s) with `Ctrl+A` key
            if (keyIsCtrl && "" === theValue && KEY_A === key) {
                setTextCopy(1);
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
            } // Skip `Tab` key
            if (keyIsTab) {
                return; // :)
            } // Submit the closest `<form>` element with `Enter` key
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
            })();
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
            } // Do normal `submit` event
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
        }; // Default filter for the tag name
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
            if (!source[name$1]) {
                return $; // Already ejected!
            }
            delete source[name$1];
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
    TP.version = '3.4.14';

    function onChange$8(init) {
        // Destroy!
        var $;
        for (var key in TP.instances) {
            $ = TP.instances[key];
            $.pop();
            delete TP.instances[key];
        }
        var sources = getElements('.lot\\:field.type\\:query input');
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            var c = getClasses(source);
            var $ = new TP(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            setClasses($.self, c);
        });
        if (1 === init) {
            W._.on('change', onChange$8);
            W.TP = TP;
        }
    }
    var name = 'TE';

    function trim(str, dir) {
        return (str || "")['trim' + (-1 === dir ? 'Left' : 1 === dir ? 'Right' : "")]();
    }

    function TE(source, state) {
        if (state === void 0) {
            state = {};
        }
        var $ = this;
        if (!source) {
            return $;
        } // Already instantiated, skip!
        if (source[name]) {
            return source[name];
        } // Return new instance if `TE` was called without the `new` operator
        if (!isInstance($, TE)) {
            return new TE(source, state);
        }
        $.state = state = fromStates({}, TE.state, isString(state) ? {
            tab: state
        } : state || {}); // The `<textarea>` element
        $.self = $.source = source; // Store current instance to `TE.instances`
        TE.instances[source.id || source.name || toObjectCount(TE.instances)] = $; // Mark current DOM as active text editor to prevent duplicate instance
        source[name] = $;
        var any = /^([\s\S]*?)$/,
            // Any character(s)
            sourceIsDisabled = function sourceIsDisabled() {
                return source.disabled;
            },
            sourceIsReadOnly = function sourceIsReadOnly() {
                return source.readOnly;
            },
            sourceValue = function sourceValue() {
                return source.value.replace(/\r/g, "");
            }; // The initial value
        $.value = sourceValue(); // Get value
        $.get = function () {
            return !sourceIsDisabled() && trim(sourceValue()) || null;
        }; // Reset to the initial value
        $.let = function () {
            return source.value = $.value, $;
        }; // Set value
        $.set = function (value) {
            if (sourceIsDisabled() || sourceIsReadOnly()) {
                return $;
            }
            return source.value = value, $;
        }; // Get selection
        $.$ = function () {
            return new TE.S(source.selectionStart, source.selectionEnd, sourceValue());
        };
        $.focus = function (mode) {
            var x, y;
            if (-1 === mode) {
                x = y = 0; // Put caret at the start of the editor, scroll to the start of the editor
            } else if (1 === mode) {
                x = toCount(sourceValue()); // Put caret at the end of the editor
                y = source.scrollHeight; // Scroll to the end of the editor
            }
            if (isSet$1(x) && isSet$1(y)) {
                source.selectionStart = source.selectionEnd = x;
                source.scrollTop = y;
            }
            return source.focus(), $;
        }; // Blur from the editor
        $.blur = function () {
            return source.blur(), $;
        }; // Select value
        $.select = function () {
            if (sourceIsDisabled() || sourceIsReadOnly()) {
                return source.focus(), $;
            }
            for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
                lot[_key] = arguments[_key];
            }
            var count = toCount(lot),
                _$$$ = $.$(),
                start = _$$$.start,
                end = _$$$.end,
                x,
                y,
                X,
                Y;
            x = W.pageXOffset || R.scrollLeft || B.scrollLeft;
            y = W.pageYOffset || R.scrollTop || B.scrollTop;
            X = source.scrollLeft;
            Y = source.scrollTop;
            if (0 === count) {
                // Restore selection with `$.select()`
                lot[0] = start;
                lot[1] = end;
            } else if (1 === count) {
                // Move caret position with `$.select(7)`
                if (true === lot[0]) {
                    // Select all with `$.select(true)`
                    return source.focus(), source.select(), $;
                }
                lot[1] = lot[0];
            }
            source.focus(); // Default `$.select(7, 100)`
            source.selectionStart = lot[0];
            source.selectionEnd = lot[1];
            source.scrollLeft = X;
            source.scrollTop = Y;
            return W.scroll(x, y), $;
        }; // Match at selection
        $.match = function (pattern, then) {
            var _$$$2 = $.$(),
                after = _$$$2.after,
                before = _$$$2.before,
                value = _$$$2.value;
            if (isArray(pattern)) {
                var _m = [before.match(pattern[0]), value.match(pattern[1]), after.match(pattern[2])];
                return isFunction(then) ? then.call($, _m[0] || [], _m[1] || [], _m[2] || []) : [!!_m[0], !!_m[1], !!_m[2]];
            }
            var m = value.match(pattern);
            return isFunction(then) ? then.call($, m || []) : !!m;
        }; // Replace at selection
        $.replace = function (from, to, mode) {
            var _$$$3 = $.$(),
                after = _$$$3.after,
                before = _$$$3.before,
                value = _$$$3.value;
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
        }; // Insert/replace at caret
        $.insert = function (value, mode, clear) {
            var from = any;
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
        }; // Wrap current selection
        $.wrap = function (open, close, wrap) {
            var _$$$4 = $.$(),
                after = _$$$4.after,
                before = _$$$4.before,
                value = _$$$4.value;
            if (wrap) {
                return $.replace(any, open + '$1' + close);
            }
            return $.set(before + open + value + close + after).select(before = toCount(before + open), before + toCount(value));
        }; // Unwrap current selection
        $.peel = function (open, close, wrap) {
            var _$$$5 = $.$(),
                after = _$$$5.after,
                before = _$$$5.before,
                value = _$$$5.value;
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
        $.pull = function (by, includeEmptyLines) {
            if (includeEmptyLines === void 0) {
                includeEmptyLines = true;
            }
            var _$$$6 = $.$(),
                length = _$$$6.length,
                value = _$$$6.value;
            by = esc(isSet$1(by) ? by : state.tab);
            if (length) {
                if (includeEmptyLines) {
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
        $.push = function (by, includeEmptyLines) {
            if (includeEmptyLines === void 0) {
                includeEmptyLines = false;
            }
            var _$$$7 = $.$(),
                length = _$$$7.length;
            by = isSet$1(by) ? by : state.tab;
            if (length) {
                return $.replace(toPattern('^' + (includeEmptyLines ? "" : '(?!$)'), 'gm'), by);
            }
            return $.insert(by, -1);
        };
        $.trim = function (open, close, start, end, tidy) {
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
            var _$$$8 = $.$(),
                before = _$$$8.before,
                value = _$$$8.value,
                after = _$$$8.after,
                beforeClean = trim(before, 1),
                afterClean = trim(after, -1);
            before = false !== open ? trim(before, 1) + (beforeClean || !tidy ? open : "") : before;
            after = false !== close ? (afterClean || !tidy ? close : "") + trim(after, -1) : after;
            if (false !== start) value = trim(value, -1);
            if (false !== end) value = trim(value, 1);
            return $.set(before + value + after).select(before = toCount(before), before + toCount(value));
        }; // Destructor
        $.pop = function () {
            if (!source[name]) {
                return $; // Already ejected!
            }
            return delete source[name], $;
        }; // Return the text editor state
        $.state = state;
        return $;
    }
    TE.esc = esc;
    TE.instances = {};
    TE.state = {
        'tab': '\t'
    };
    TE.S = function (a, b, c) {
        var t = this,
            d = c.slice(a, b);
        t.after = c.slice(b);
        t.before = c.slice(0, a);
        t.end = b;
        t.length = toCount(d);
        t.start = a;
        t.value = d;
        t.toString = function () {
            return d;
        };
    };
    TE.version = '3.3.13';
    TE.x = x;
    var that$2 = {};
    that$2._history = [];
    that$2._historyState = -1; // Get history data
    that$2.history = function (index) {
        var t = this;
        if (!isSet$1(index)) {
            return t._history;
        }
        return isSet$1(t._history[index]) ? t._history[index] : null;
    }; // Remove state from history
    that$2.loss = function (index) {
        var t = this,
            current;
        if (true === index) {
            t._history = [];
            t._historyState = -1;
            return [];
        }
        current = t._history.splice(isSet$1(index) ? index : t._historyState, 1);
        t._historyState = toEdge(t._historyState - 1, [-1]);
        return current;
    }; // Save current state to history
    that$2.record = function (index) {
        var t = this,
            _t$$ = t.$(),
            end = _t$$.end,
            start = _t$$.start,
            current = t._history[t._historyState] || [],
            next = [t.self.value, start, end];
        if (next[0] === current[0] && next[1] === current[1] && next[2] === current[2]) {
            return t; // Do not save duplicate
        }
        ++t._historyState;
        return t._history[isSet$1(index) ? index : t._historyState] = next, t;
    }; // Redo previous state
    that$2.redo = function () {
        var t = this,
            state;
        t._historyState = toEdge(t._historyState + 1, [0, toCount(t._history) - 1]);
        state = t._history[t._historyState];
        return t.set(state[0]).select(state[1], state[2]);
    }; // Undo current state
    that$2.undo = function () {
        var t = this,
            state;
        t._historyState = toEdge(t._historyState - 1, [0, toCount(t._history) - 1]);
        state = t._history[t._historyState];
        return t.set(state[0]).select(state[1], state[2]);
    };
    var pairs = {
        '`': '`',
        '(': ')',
        '{': '}',
        '[': ']',
        '"': '"',
        "'": "'",
        '<': '>'
    };

    function promisify(type, lot) {
        return new Promise(function (resolve, reject) {
            var r = W[type].apply(W, lot);
            return r ? resolve(r) : reject(r);
        });
    }
    var defaults$2 = {
        source: {
            pairs: pairs,
            type: null
        }
    };
    ['alert', 'confirm', 'prompt'].forEach(function (type) {
        defaults$2.source[type] = function () {
            for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
                lot[_key] = arguments[_key];
            }
            return promisify(type, lot);
        };
    });
    var that$1 = {};
    that$1.toggle = function (open, close, wrap, tidy) {
        if (tidy === void 0) {
            tidy = false;
        }
        if (!close && "" !== close) {
            close = open;
        }
        var t = this,
            _t$$ = t.$(),
            after = _t$$.after,
            before = _t$$.before,
            value = _t$$.value,
            closeCount = toCount(close),
            openCount = toCount(open);
        if (wrap && close === value.slice(-closeCount) && open === value.slice(0, openCount) || close === after.slice(0, closeCount) && open === before.slice(-openCount)) {
            return t.peel(open, close, wrap);
        }
        if (false !== tidy) {
            if (isString(tidy)) {
                tidy = [tidy, tidy];
            } else if (!isArray(tidy)) {
                tidy = ["", ""];
            }
            if (!isSet(tidy[1])) {
                tidy[1] = tidy[0];
            }
            t.trim(tidy[0], tidy[1]);
        }
        return t.wrap(open, close, wrap);
    };
    var CTRL_PREFIX = 'Control-';

    function canKeyDown$2(map, that) {
        var charAfter,
            charBefore,
            charIndent = that.state.source.tab || that.state.tab || '\t',
            charPairs = that.state.source.pairs || {},
            charPairsValues = toObjectValues(charPairs),
            key = map.key,
            queue = map.queue,
            keyValue = map + ""; // Do nothing
        if (queue.Alt || queue.Control) {
            return true;
        }
        if (' ' === keyValue) {
            var _that$$ = that.$(),
                _after = _that$$.after,
                _before = _that$$.before,
                _value = _that$$.value;
            charAfter = charPairs[charBefore = _before.slice(-1)];
            if (!_value && charAfter && charBefore && charAfter === _after[0]) {
                that.wrap(' ', ' ');
                return false;
            }
            return true;
        }
        if ('Enter' === keyValue) {
            var _that$$2 = that.$(),
                _after2 = _that$$2.after,
                _before2 = _that$$2.before,
                _value2 = _that$$2.value,
                lineBefore = _before2.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "";
            if (!_value2) {
                if (_after2 && _before2 && (charAfter = charPairs[charBefore = _before2.slice(-1)]) && charAfter === _after2[0]) {
                    that.wrap('\n' + lineMatchIndent + (charBefore !== charAfter ? charIndent : ""), '\n' + lineMatchIndent).record();
                    return false;
                }
                if (lineMatchIndent) {
                    that.insert('\n' + lineMatchIndent, -1).record();
                    return false;
                }
            }
            return true;
        }
        if ('Backspace' === keyValue) {
            var _that$$3 = that.$(),
                _after3 = _that$$3.after,
                _before3 = _that$$3.before,
                _value3 = _that$$3.value;
            _after3.split('\n')[0];
            var _lineBefore = _before3.split('\n').pop(),
                _lineMatch = _lineBefore.match(/^(\s+)/),
                _lineMatchIndent = _lineMatch && _lineMatch[1] || "";
            charAfter = charPairs[charBefore = _before3.slice(-1)]; // Do nothing on escape
            if ('\\' === charBefore) {
                return true;
            }
            if (_value3) {
                if (_after3 && _before3 && charAfter && charAfter === _after3[0] && !_before3.endsWith('\\' + charBefore)) {
                    that.record().peel(charBefore, charAfter).record();
                    return false;
                }
                return true;
            }
            charAfter = charPairs[charBefore = _before3.trim().slice(-1)];
            if (charAfter && charBefore) {
                if (_after3.startsWith(' ' + charAfter) && _before3.endsWith(charBefore + ' ') || _after3.startsWith('\n' + _lineMatchIndent + charAfter) && _before3.endsWith(charBefore + '\n' + _lineMatchIndent)) {
                    // Collapse bracket(s)
                    that.trim("", "").record();
                    return false;
                }
            } // Outdent
            if (_lineBefore.endsWith(charIndent)) {
                that.pull(charIndent).record();
                return false;
            }
            if (_after3 && _before3 && !_before3.endsWith('\\' + charBefore)) {
                if (charAfter === _after3[0] && charBefore === _before3.slice(-1)) {
                    // Peel pair
                    that.peel(charBefore, charAfter).record();
                    return false;
                }
            }
            return true;
        }
        var _that$$4 = that.$(),
            after = _that$$4.after,
            before = _that$$4.before,
            start = _that$$4.start,
            value = _that$$4.value; // Do nothing on escape
        if ('\\' === (charBefore = before.slice(-1))) {
            return true;
        }
        charAfter = hasValue(after[0], charPairsValues) ? after[0] : charPairs[charBefore]; // `|}`
        if (!value && after && before && charAfter && key === charAfter) {
            // Move to the next character
            // `}|`
            that.select(start + 1).record();
            return false;
        }
        for (charBefore in charPairs) {
            charAfter = charPairs[charBefore]; // `{|`
            if (key === charBefore && charAfter) {
                // Wrap pair or selection
                // `{|}` `{|aaa|}`
                that.wrap(charBefore, charAfter).record();
                return false;
            } // `|}`
            if (key === charAfter) {
                if (value) {
                    // Wrap selection
                    // `{|aaa|}`
                    that.record().wrap(charBefore, charAfter).record();
                    return false;
                }
                break;
            }
        }
        return true;
    }

    function canKeyDownDent(map, that) {
        var charIndent = that.state.source.tab || that.state.tab || '\t';
        map.key;
        map.queue;
        var keyValue = map + ""; // Indent with `⎈]`
        if (CTRL_PREFIX + ']' === keyValue) {
            that.push(charIndent).record();
            return false;
        } // Outdent with `⎈[`
        if (CTRL_PREFIX + '[' === keyValue) {
            that.pull(charIndent).record();
            return false;
        }
        return true;
    }

    function canKeyDownEnter(map, that) {
        map.key;
        var queue = map.queue;
        if (queue.Control && queue.Enter) {
            var _that$$5 = that.$(),
                after = _that$$5.after,
                before = _that$$5.before,
                end = _that$$5.end,
                start = _that$$5.start,
                value = _that$$5.value,
                lineAfter = after.split('\n').shift(),
                lineBefore = before.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "";
            if (before || after) {
                if (queue.Shift) {
                    // Insert line over with `⎈⇧↵`
                    return that.select(start - toCount(lineBefore)).wrap(lineMatchIndent, '\n').insert(value).record(), false;
                } // Insert line below with `⎈↵`
                return that.select(end + toCount(lineAfter)).wrap('\n' + lineMatchIndent, "").insert(value).record(), false;
            }
        }
        return true;
    }

    function canKeyDownHistory(map, that) {
        var keyValue = map + ""; // Redo with `⎈y`
        if (CTRL_PREFIX + 'y' === keyValue) {
            return that.redo(), false;
        } // Undo with `⎈z`
        if (CTRL_PREFIX + 'z' === keyValue) {
            return that.undo(), false;
        }
        return true;
    }

    function canKeyDownMove(map, that) {
        map.key;
        var queue = map.queue,
            keyValue = map + "";
        if (!queue.Control) {
            return true;
        }
        var _that$$6 = that.$(),
            after = _that$$6.after,
            before = _that$$6.before,
            end = _that$$6.end,
            start = _that$$6.start,
            value = _that$$6.value,
            charPair,
            charPairValue,
            charPairs = that.state.source.pairs || {},
            boundaries = [],
            m;
        if (value) {
            for (charPair in charPairs) {
                if (!(charPairValue = charPairs[charPair])) {
                    continue;
                }
                boundaries.push('(?:\\' + charPair + '(?:\\\\.|[^\\' + charPair + (charPairValue !== charPair ? '\\' + charPairValue : "") + '])*\\' + charPairValue + ')');
            }
            boundaries.push('\\w+'); // Word(s)
            boundaries.push('\\s+'); // White-space(s)
            boundaries.push('[\\s\\S]'); // Last try!
            if (CTRL_PREFIX + 'ArrowLeft' === keyValue) {
                if (m = before.match(toPattern('(' + boundaries.join('|') + ')$', ""))) {
                    that.insert("").select(start - toCount(m[0])).insert(value);
                    return that.record(), false;
                }
                return that.select(), false;
            }
            if (CTRL_PREFIX + 'ArrowRight' === keyValue) {
                if (m = after.match(toPattern('^(' + boundaries.join('|') + ')', ""))) {
                    that.insert("").select(end + toCount(m[0]) - toCount(value)).insert(value);
                    return that.record(), false;
                }
                return that.select(), false;
            }
        }
        var lineAfter = after.split('\n').shift(),
            lineBefore = before.split('\n').pop(),
            lineMatch = lineBefore.match(/^(\s+)/);
        lineMatch && lineMatch[1] || ""; // Force to select the current line if there is no selection
        end += toCount(lineAfter);
        start -= toCount(lineBefore);
        value = lineBefore + value + lineAfter;
        if (CTRL_PREFIX + 'ArrowUp' === keyValue) {
            if (!hasValue('\n', before)) {
                return that.select(), false;
            }
            that.insert("");
            that.replace(/^([^\n]*?)(\n|$)/, '$2', 1);
            that.replace(/(^|\n)([^\n]*?)$/, "", -1);
            var $ = that.$();
            before = $.before;
            start = $.start;
            lineBefore = before.split('\n').pop();
            that.select(start = start - toCount(lineBefore)).wrap(value, '\n');
            that.select(start, start + toCount(value));
            return that.record(), false;
        }
        if (CTRL_PREFIX + 'ArrowDown' === keyValue) {
            if (!hasValue('\n', after)) {
                return that.select(), false;
            }
            that.insert("");
            that.replace(/^([^\n]*?)(\n|$)/, "", 1);
            that.replace(/(^|\n)([^\n]*?)$/, '$1', -1);
            var _$ = that.$();
            after = _$.after;
            end = _$.end;
            lineAfter = after.split('\n').shift();
            that.select(end = end + toCount(lineAfter)).wrap('\n', value);
            end += 1;
            that.select(end, end + toCount(value));
            return that.record(), false;
        }
        return true;
    }
    var bounce = debounce(function (that) {
        return that.record();
    }, 100);

    function canKeyUp(map, that) {
        return bounce(that), true;
    }
    var state$2 = defaults$2;
    var tagComment = '<!--([\\s\\S](?!-->)*)-->',
        tagData = '<!((?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^>\'"])*)>',
        tagName$1 = '[\\w:.-]+',
        tagStart$1 = function tagStart(name) {
            return '<(' + name + ')(\\s(?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^/>\'"])*)?>';
        },
        tagEnd$1 = function tagEnd(name) {
            return '</(' + name + ')>';
        },
        tagVoid = function tagVoid(name) {
            return '<(' + name + ')(\\s(?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^/>\'"])*)?/?>';
        },
        tagPreamble = '<\\?((?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^>\'"])*)\\?>',
        tagTokens = '(?:' + tagComment + '|' + tagData + '|' + tagEnd$1(tagName$1) + '|' + tagPreamble + '|' + tagStart$1(tagName$1) + '|' + tagVoid(tagName$1) + ')';
    var defaults$1 = {
        source: {
            type: 'XML'
        },
        sourceXML: {}
    };
    var that = {};

    function toAttributes(attributes) {
        if (!attributes) {
            return "";
        } // Sort object by key(s)
        attributes = toObjectKeys(attributes).sort().reduce(function (r, k) {
            return r[k] = attributes[k], r;
        }, {});
        var attribute,
            v,
            out = "";
        for (attribute in attributes) {
            v = attributes[attribute];
            if (false === v || null === v) {
                continue;
            }
            out += ' ' + attribute;
            if (true !== v) {
                out += '="' + fromHTML(fromValue(v)) + '"';
            }
        }
        return out;
    }

    function toTidy$1(tidy) {
        if (false !== tidy) {
            if (isString(tidy)) {
                tidy = [tidy, tidy];
            } else if (!isArray(tidy)) {
                tidy = ["", ""];
            }
            if (!isSet$1(tidy[1])) {
                tidy[1] = tidy[0];
            }
        }
        return tidy; // Return `[…]` or `false`
    }
    that.insert = function (name, content, attributes, tidy) {
        if (content === void 0) {
            content = "";
        }
        if (attributes === void 0) {
            attributes = {};
        }
        if (tidy === void 0) {
            tidy = false;
        }
        var t = this;
        if (false !== (tidy = toTidy$1(tidy))) {
            t.trim(tidy[0], "");
        }
        return t.insert('<' + name + toAttributes(attributes) + (false !== content ? '>' + content + '</' + name + '>' : ' />') + (false !== tidy ? tidy[1] : ""), -1, true);
    };
    that.toggle = function (name, content, attributes, tidy) {
        if (content === void 0) {
            content = "";
        }
        if (attributes === void 0) {
            attributes = {};
        }
        if (tidy === void 0) {
            tidy = false;
        }
        var t = this,
            _t$$ = t.$(),
            after = _t$$.after,
            before = _t$$.before,
            value = _t$$.value,
            tagStartLocal = tagStart$1(name),
            tagEndLocal = tagEnd$1(name),
            tagStartLocalPattern = toPattern(tagStartLocal + '$', ""),
            tagEndLocalPattern = toPattern('^' + tagEndLocal, ""),
            tagStartLocalMatch = tagStartLocalPattern.test(before),
            tagEndLocalMatch = tagEndLocalPattern.test(after);
        if (tagEndLocalMatch && tagStartLocalMatch) {
            return t.replace(tagEndLocalPattern, "", 1).replace(tagStartLocalPattern, "", -1);
        }
        tagStartLocalPattern = toPattern('^' + tagStartLocal, "");
        tagEndLocalPattern = toPattern(tagEndLocal + '$', "");
        tagStartLocalMatch = tagStartLocalPattern.test(value);
        tagEndLocalMatch = tagEndLocalPattern.test(value);
        if (tagEndLocalMatch && tagStartLocalMatch) {
            t.insert(value = value.replace(tagEndLocalPattern, "").replace(tagStartLocalPattern, ""));
        }
        if (!value && content) {
            t.insert(content);
        }
        if (false !== (tidy = toTidy$1(tidy))) {
            t.trim(tidy[0], tidy[1]);
        }
        return t.wrap('<' + name + toAttributes(attributes) + '>', '</' + name + '>');
    };
    that.wrap = function (name, content, attributes, tidy) {
        if (content === void 0) {
            content = "";
        }
        if (attributes === void 0) {
            attributes = {};
        }
        if (tidy === void 0) {
            tidy = false;
        }
        var t = this,
            _t$$2 = t.$();
        _t$$2.after;
        _t$$2.before;
        var value = _t$$2.value;
        if (!value && content) {
            t.insert(content);
        }
        if (false !== (tidy = toTidy$1(tidy))) {
            t.trim(tidy[0], tidy[1]);
        }
        return t.wrap('<' + name + toAttributes(attributes) + '>', '</' + name + '>');
    };

    function canKeyDown$1(map, that) {
        var state = that.state,
            charIndent = state.sourceXML.tab || state.tab || '\t',
            key = map.key,
            queue = map.queue,
            keyValue = map + ""; // Do nothing
        if (queue.Alt || queue.Control) {
            return true;
        }
        if (['-', '>', '/', '?', ' '].includes(key)) {
            var _that$$ = that.$(),
                after = _that$$.after,
                before = _that$$.before,
                value = _that$$.value,
                start = _that$$.start;
            if ('-' === key) {
                // `<!-|`
                if (!value && '<!-' === before.slice(-3)) {
                    that.wrap('- ', ' --' + ('>' === after[0] ? "" : '>')).record();
                    return false;
                }
            }
            if ('>' === key || '/' === key) {
                var tagStartMatch = toPattern(tagStart$1(tagName$1) + '$', "").exec(before + '>');
                if (!value && tagStartMatch) {
                    // `<div|`
                    if ('/' === key) {
                        // `<div|>`
                        if ('>' === after[0]) {
                            that.trim("", false).insert(' /', -1).select(that.$().start + 1).record();
                            return false;
                        }
                        that.trim("", false).insert(' />', -1).record();
                        return false;
                    } // `<div|></div>`
                    if (after.startsWith('></' + tagStartMatch[1] + '>')) {
                        that.select(start + 1).record(); // `<div|</div>`
                    } else if (after.startsWith('</' + tagStartMatch[1] + '>')) {
                        that.insert('>', -1).record(); // `<div|`
                    } else {
                        that.wrap('>', '</' + tagStartMatch[1] + ('>' === after[0] ? "" : '>')).record();
                    }
                    return false;
                }
            }
            if ('?' === key) {
                // `<|`
                if (!value && '<' === before.slice(-1)) {
                    that.wrap('?', '?' + ('>' === after[0] ? "" : '>')).record();
                    return false;
                }
            }
            if (' ' === keyValue) {
                if (!value) {
                    if ( // `<!--|-->`
                        '-->' === after.slice(0, 3) && '<!--' === before.slice(-4) || // `<?foo|?>`
                        '?>' === after.slice(0, 2) && '<?' === before.slice(0, 2) && /<\?\S*$/.test(before)) {
                        that.wrap(' ', ' ').record();
                        return false;
                    }
                }
            }
        }
        if ('ArrowLeft' === keyValue) {
            var _that$$2 = that.$(),
                _before = _that$$2.before,
                _start = _that$$2.start,
                _value = _that$$2.value;
            if (!_value) {
                var tagMatch = toPattern(tagTokens + '$', "").exec(_before); // `<foo>|bar`
                if (tagMatch) {
                    that.select(_start - toCount(tagMatch[0]), _start);
                    return false;
                }
            }
        }
        if ('ArrowRight' === keyValue) {
            var _that$$3 = that.$(),
                _after = _that$$3.after,
                _start2 = _that$$3.start,
                _value2 = _that$$3.value;
            if (!_value2) {
                var _tagMatch = toPattern('^' + tagTokens, "").exec(_after); // `foo|<bar>`
                if (_tagMatch) {
                    that.select(_start2, _start2 + toCount(_tagMatch[0]));
                    return false;
                }
            }
        }
        if ('Enter' === keyValue) {
            var _that$$4 = that.$(),
                _after2 = _that$$4.after,
                _before2 = _that$$4.before,
                _value3 = _that$$4.value,
                lineBefore = _before2.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "",
                _tagStartMatch = _before2.match(toPattern(tagStart$1(tagName$1) + '$', ""));
            if (!_value3) {
                if ( // `<!--|-->`
                    /^[ \t]*-->/.test(_after2) && /<!--[ \t]*$/.test(_before2) || // `<?foo|?>`
                    /^[ \t]*\?>/.test(_after2) && /<\?\S*[ \t]*$/.test(_before2)) {
                    that.trim().wrap('\n' + lineMatchIndent, '\n' + lineMatchIndent).record();
                    return false;
                }
                if (_tagStartMatch) {
                    if (_after2.startsWith('</' + _tagStartMatch[1] + '>')) {
                        that.record().trim().wrap('\n' + lineMatchIndent + charIndent, '\n' + lineMatchIndent).record();
                    } else {
                        that.record().wrap('\n' + lineMatchIndent + charIndent, '\n' + lineMatchIndent + '</' + _tagStartMatch[1] + '>').record();
                    }
                    return false;
                }
            }
        }
        if ('Backspace' === keyValue) {
            var _that$$5 = that.$(),
                _after3 = _that$$5.after,
                _before3 = _that$$5.before,
                _value4 = _that$$5.value;
            if (!_value4) {
                // `<!--|`
                if ('<!--' === _before3.slice(-4)) {
                    that.replace(/<!--$/, "", -1); // `<!--|-->`
                    if ('-->' === _after3.slice(0, 3)) {
                        that.replace(/^-->/, "", 1);
                    }
                    that.record();
                    return false;
                }
                if (/^\s+-->/.test(_after3) && /<!--\s+$/.test(_before3)) {
                    that.trim(' ' === _before3.slice(-1) ? "" : ' ', ' ' === _after3[0] ? "" : ' ').record();
                    return false;
                } // `<?|`
                if (/<\?\S*$/.test(_before3)) {
                    that.replace(/<\?\S*$/, "", -1); // `<?|?>`
                    if ('?>' === _after3.slice(0, 2)) {
                        that.replace(/^\?>/, "", 1);
                    }
                    that.record();
                    return false;
                }
                if (/^\s+\?>/.test(_after3) && /<\?\S*\s+$/.test(_before3)) {
                    that.trim(' ' === _before3.slice(-1) ? "" : ' ', ' ' === _after3[0] ? "" : ' ').record();
                    return false;
                }
                var tagPattern = toPattern(tagTokens + '$', ""),
                    _tagMatch2 = tagPattern.exec(_before3);
                if (_tagMatch2) {
                    // `<div />|`
                    if (' />' === _before3.slice(-3)) {
                        that.replace(/ \/>$/, '/>', -1).record();
                        return false;
                    } // `<div/>|`
                    if ('/>' === _before3.slice(-2)) {
                        that.replace(/\/>$/, '>', -1).record();
                        return false;
                    }
                    that.replace(tagPattern, "", -1);
                    var name = _tagMatch2[0].slice(1).split(/\s+|>/)[0];
                    if (_tagMatch2[0] && '/' !== _tagMatch2[0][1]) {
                        if (_after3.startsWith('</' + name + '>')) {
                            that.replace(toPattern('^</' + name + '>', ""), "", 1);
                        }
                    }
                    that.record();
                    return false;
                }
                if (toPattern(tagStart$1(tagName$1) + '\\n(?:' + esc(charIndent) + ')?$', "").test(_before3) && toPattern('^\\s*' + tagEnd$1(tagName$1), "").test(_after3)) {
                    that.trim().record(); // Collapse!
                    return false;
                }
            }
        }
        if ('Delete' === keyValue) {
            var _that$$6 = that.$(),
                _after4 = _that$$6.after,
                _value5 = _that$$6.value;
            if (!_value5) {
                // `|-->`
                if ('-->' === _after4.slice(0, 3)) {
                    that.replace(/^-->/, "", 1).record();
                    return false;
                } // `|?>`
                if ('?>' === _after4.slice(0, 2)) {
                    that.replace(/^\?>/, "", 1).record();
                    return false;
                }
                var _tagPattern = toPattern('^' + tagTokens, ""),
                    _tagMatch3 = _tagPattern.exec(_after4);
                if (_tagMatch3) {
                    that.replace(_tagPattern, "", 1).record();
                    return false;
                }
            }
        }
        return true;
    }

    function canMouseDown(map, that) {
        map.key;
        var queue = map.queue;
        if (!queue.Control) {
            W.setTimeout(function () {
                var _that$$7 = that.$(),
                    after = _that$$7.after,
                    before = _that$$7.before,
                    value = _that$$7.value;
                if (!value) {
                    var caret = "\uFEFF",
                        tagTokensLocal = tagTokens.split('(' + tagName$1 + ')').join('((?:[\\w:.-]|' + caret + ')+)'),
                        tagTokensLocalPattern = toPattern(tagTokensLocal),
                        content = before + value + caret + after,
                        m,
                        v;
                    while (m = tagTokensLocalPattern.exec(content)) {
                        if (hasValue(caret, m[0])) {
                            that.select(v = m.index, v + toCount(m[0]) - 1);
                            break;
                        }
                    }
                }
            }, 1);
        }
        return true;
    }
    var state$1 = defaults$1;
    var protocol = theLocation.protocol;
    var defaults = {
        source: {
            type: 'HTML'
        },
        sourceHTML: {
            elements: {
                "": ["", 'text goes here…', {}, ""],
                a: ['a', 'link text goes here…', {}],
                area: ['area', false, {}],
                b: ['strong', 'text goes here…', {}],
                base: ['base', false, {
                    href: ""
                }, '\n'],
                blockquote: ['blockquote', "", {}, '\n'],
                br: ['br', false, {},
                    ["", '\n']
                ],
                button: ['button', 'text goes here…', {
                    name: "",
                    type: 'submit'
                }, ' '],
                caption: ['caption', 'caption goes here…', {}, '\n'],
                code: ['code', 'code goes here…', {}, ' '],
                col: ['col', false, {}, '\n'],
                em: ['em', 'text goes here…', {}],
                figcaption: ['figcaption', 'caption goes here…', {}, '\n'],
                figure: ['figure', "", {}, '\n'],
                h1: ['h1', 'title goes here…', {}, '\n'],
                h2: ['h2', 'title goes here…', {}, '\n'],
                h3: ['h3', 'title goes here…', {}, '\n'],
                h4: ['h4', 'title goes here…', {}, '\n'],
                h5: ['h5', 'title goes here…', {}, '\n'],
                h6: ['h6', 'title goes here…', {}, '\n'],
                hr: ['hr', false, {}, '\n'],
                i: ['em', 'text goes here…', {}],
                img: ['img', false, {
                    alt: "",
                    src: ""
                }, ' '],
                input: ['input', false, {
                    name: "",
                    type: 'text'
                }, ' '],
                li: ['li', 'list item goes here…', {}, '\n'],
                link: ['link', false, {
                    href: ""
                }, '\n'],
                meta: ['meta', false, {}, '\n'],
                ol: ['ol', "", {}, '\n'],
                option: ['option', 'option goes here…', {}, '\n'],
                p: ['p', 'paragraph goes here…', {}, '\n'],
                param: ['param', false, {
                    name: ""
                }, '\n'],
                pre: ['pre', 'text goes here…', {}, '\n'],
                q: ['q', 'quote goes here…', {}, ' '],
                script: ['script', "", {}, '\n'],
                select: ['select', "", {
                    name: ""
                }, ' '],
                source: ['source', false, {
                    src: ""
                }, '\n'],
                strong: ['strong', 'text goes here…', {}],
                style: ['style', "", {}, '\n'],
                td: ['td', 'data goes here…', {}, '\n'],
                textarea: ['textarea', 'text goes here…', {
                    name: ""
                }, ' '],
                th: ['th', 'title goes here…', {}, '\n'],
                tr: ['tr', "", {}, '\n'],
                track: ['track', false, {}, '\n'],
                u: ['u', 'text goes here…', {}],
                ul: ['ul', "", {}, '\n'],
                wbr: ['wbr', false, {},
                    ["", '\n']
                ]
            }
        }
    };
    var toggle = that.toggle;
    var tagName = '[\\w:.-]+',
        tagStart = function tagStart(name) {
            return '<(' + name + ')(\\s(?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^/>\'"])*)?>';
        },
        tagEnd = function tagEnd(name) {
            return '</(' + name + ')>';
        };

    function toTidy(tidy) {
        if (false !== tidy) {
            if (isString(tidy)) {
                tidy = [tidy, tidy];
            } else if (!isArray(tidy)) {
                tidy = ["", ""];
            }
            if (!isSet$1(tidy[1])) {
                tidy[1] = tidy[0];
            }
        }
        return tidy; // Return `[…]` or `false`
    }

    function toggleBlocks(that) {
        var patternBefore = /<(?:h([1-6])|p)(\s[^>]*)?>$/,
            patternAfter = /^<\/(?:h[1-6]|p)>/;
        that.match([patternBefore, /.*/, patternAfter], function (before, value, after) {
            var t = this,
                h = +(before[1] || 0),
                attr = before[2] || "",
                elements = that.state.sourceHTML.elements || {},
                element = before[0] ? elements[before[0].slice(1, -1).split(/\s/)[0]] : ["", "", {}];
            if (!attr && element[2]) {
                attr = toAttributes(element[2]);
            } // ``
            t.replace(patternBefore, "", -1);
            t.replace(/\n+/g, ' ');
            t.replace(patternAfter, "", 1);
            var tidy = element[3] || elements.h1[3];
            if (false !== (tidy = toTidy(tidy))) {
                t.trim(tidy[0], tidy[1]);
            }
            if (!h) {
                // `<h1>`
                t.wrap('<' + elements.h1[0] + (attr || toAttributes(elements.h1[2])) + '>', '</' + elements.h1[0] + '>');
                if (!value[0] || value[0] === elements.p[1]) {
                    t.insert(elements.h1[1]);
                }
            } else {
                ++h;
                if (h > 6) {
                    // `<p>`
                    t.wrap('<' + elements.p[0] + (attr || toAttributes(elements.p[2])) + '>', '</' + elements.p[0] + '>');
                    if (!value[0] || value[0] === elements.h6[1]) {
                        t.insert(elements.p[1]);
                    }
                } else {
                    // `<h1>`, `<h2>`, `<h3>`, `<h4>`, `<h5>`, `<h6>`
                    t.wrap('<' + elements['h' + h][0] + (attr || toAttributes(elements['h' + h][2])) + '>', '</' + elements['h' + h][0] + '>');
                    if (!value[0] || value[0] === elements.p[1]) {
                        t.insert(elements['h' + h][1]);
                    }
                }
            }
        });
    }

    function toggleCodes(that) {
        var patternBefore = /<(?:pre|code)(?:\s[^>]*)?>(?:\s*<code(?:\s[^>]*)?>)?$/,
            patternAfter = /^(?:<\/code>\s*)?<\/(?:pre|code)>/;
        that.match([patternBefore, /.*/, patternAfter], function (before, value, after) {
            var t = this,
                tidy,
                elements = that.state.sourceHTML.elements; // ``
            t.replace(patternBefore, "", -1);
            t.replace(patternAfter, "", 1);
            if (after[0]) {
                // ``
                if (/^(?:<\/code>\s*)?<\/pre>/.test(after[0])) {
                    tidy = elements[""][3];
                    if (false !== (tidy = toTidy(tidy))) {
                        t.trim(tidy[0], tidy[1]);
                    }
                    t.insert(decode(value[0])); // `<pre><code>…</code></pre>`
                } else if (after[0].slice(0, 7) === '</' + elements.code[0] + '>') {
                    tidy = elements.pre[3];
                    if (false !== (tidy = toTidy(tidy))) {
                        t.trim(tidy[0], tidy[1]);
                    }
                    t.wrap('<' + elements.pre[0] + toAttributes(elements.pre[2]) + '><' + elements.code[0] + toAttributes(elements.code[2]) + '>', '</' + elements.code[0] + '></' + elements.pre[0] + '>');
                } // `<code>…</code>`
            } else {
                tidy = elements.code[3];
                if (false !== (tidy = toTidy(tidy))) {
                    t.trim(tidy[0], tidy[1]);
                }
                t.wrap('<' + elements.code[0] + toAttributes(elements.code[2]) + '>', '</' + elements.code[0] + '>').insert(encode(value[0] || elements.code[1]));
            }
        });
    }

    function toggleQuotes(that) {
        var patternBefore = /<(blockquote|q)(?:\s[^>]*)?>\s*$/,
            patternAfter = /^\s*<\/(blockquote|q)>/;
        that.match([patternBefore, /.*/, patternAfter], function (before, value, after) {
            var t = this,
                tidy,
                state = that.state,
                charIndent = state.sourceHTML.tab || state.source.tab || state.tab || '\t',
                elements = that.state.sourceHTML.elements || {}; // ``
            t.replace(patternBefore, "", -1);
            t.replace(patternAfter, "", 1);
            if (after[0]) {
                // ``
                if (elements.blockquote[0] === after[1]) {
                    if (false !== (tidy = toTidy(elements[""][3]))) {
                        t.trim(tidy[0], tidy[1]);
                    } // `<blockquote>…</blockquote>`
                } else if (elements.q[0] === after[1]) {
                    if (false !== (tidy = toTidy(elements.blockquote[3]))) {
                        t.trim(tidy[0], tidy[1]);
                    }
                    t.wrap('<' + elements.blockquote[0] + toAttributes(elements.blockquote[2]) + '>\n', '\n</' + elements.blockquote[0] + '>').insert(value[0] || elements.blockquote[1]);
                    t.replace(toPattern('(^|\\n)'), '$1' + charIndent);
                } // `<q>…</q>`
            } else {
                if (false !== (tidy = toTidy(elements.q[3]))) {
                    t.trim(tidy[0], tidy[1]);
                }
                t.wrap('<' + elements.q[0] + toAttributes(elements.q[2]) + '>', '</' + elements.q[0] + '>').insert(value[0] || elements.q[1]);
                t.replace(toPattern('(^|\\n)' + charIndent), '$1');
            }
        });
    }

    function encode(x) {
        return x.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function decode(x) {
        return x.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');
    }
    var commands = {};
    commands.blocks = function () {
        var that = this;
        return that.record(), toggleBlocks(that), that.record(), false;
    };
    commands.bold = function () {
        var that = this,
            state = that.state,
            elements = state.sourceHTML.elements || {};
        return that.record(), toggle.apply(this, elements.b), false;
    };
    commands.code = function () {
        var that = this;
        return that.record(), toggleCodes(that), that.record(), false;
    };
    commands.image = function (label, placeholder) {
        if (label === void 0) {
            label = 'URL:';
        }
        var that = this,
            _that$$ = that.$(),
            after = _that$$.after,
            before = _that$$.before,
            value = _that$$.value,
            state = that.state,
            elements = state.sourceHTML.elements || {},
            charIndent = state.sourceHTML.tab || state.source.tab || state.tab || '\t',
            lineBefore = before.split('\n').pop(),
            lineMatch = lineBefore.match(/^(\s+)/),
            lineMatchIndent = lineMatch && lineMatch[1] || "",
            prompt = state.source.prompt;
        if (isFunction(prompt)) {
            prompt(label, value && /^https?:\/\/\S+$/.test(value) ? value : placeholder || protocol + '//').then(function (src) {
                if (!src) {
                    that.focus();
                    return;
                }
                var element = elements.img;
                if (value) {
                    element[2].alt = value;
                    that.record(); // Record selection
                }
                var tidy = element[3] || false;
                if (false !== (tidy = toTidy(tidy))) {
                    that.trim(tidy[0], "");
                }
                element[2].src = src;
                if ((!after || '\n' === after[0]) && (!before || '\n' === before.slice(-1))) {
                    tidy = elements.figure[3] || false;
                    if (false !== (tidy = toTidy(tidy))) {
                        that.trim(tidy[0], tidy[1]);
                    }
                    that.insert("");
                    that.wrap(lineMatchIndent + '<' + elements.figure[0] + toAttributes(elements.figure[2]) + '>\n' + lineMatchIndent + charIndent, lineMatchIndent + '\n</' + elements.figure[0] + '>');
                    that.insert('<' + element[0] + toAttributes(element[2]) + '>\n' + lineMatchIndent + charIndent, -1);
                    that.wrap('<' + elements.figcaption[0] + toAttributes(elements.figcaption[2]) + '>', '</' + elements.figcaption[0] + '>').insert(elements.figcaption[1]);
                } else {
                    that.insert('<' + element[0] + toAttributes(element[2]) + '>' + (false !== tidy ? tidy[1] : ""), -1, true);
                }
            });
        }
        return that.record(), false;
    };
    commands.italic = function () {
        var that = this,
            state = that.state,
            elements = state.sourceHTML.elements || {};
        return that.record(), toggle.apply(this, elements.i), false;
    };
    commands.link = function (label, placeholder) {
        if (label === void 0) {
            label = 'URL:';
        }
        var that = this,
            _that$$2 = that.$(),
            value = _that$$2.value,
            state = that.state,
            elements = state.sourceHTML.elements || {},
            prompt = state.source.prompt;
        if (isFunction(prompt)) {
            prompt(label, value && /^https?:\/\/\S+$/.test(value) ? value : placeholder || protocol + '//').then(function (href) {
                if (!href) {
                    that.focus();
                    return;
                }
                var element = elements.a;
                if (value) {
                    that.record(); // Record selection
                }
                element[2].href = href;
                var local = /[.\/?&#]/.test(href[0]) || /^(data|javascript|mailto):/.test(href) || -1 === href.indexOf('://'),
                    extras = {};
                if (!local) {
                    extras.rel = 'nofollow';
                    extras.target = '_blank';
                }
                var tidy = toTidy(element[3] || false);
                if (false === tidy && !value) {
                    // Tidy link with a space if there is no selection
                    tidy = [' ', ' '];
                }
                toggle.apply(that, [element[0], element[1], fromStates(extras, element[2]), tidy]);
            });
        }
        return that.record(), false;
    };
    commands.quote = function () {
        var that = this;
        return that.record(), toggleQuotes(that), that.record(), false;
    };
    commands.underline = function () {
        var that = this,
            state = that.state,
            elements = state.sourceHTML.elements || {};
        return that.record(), toggle.apply(this, elements.u), false;
    };

    function canKeyDown(map, that) {
        var state = that.state,
            charIndent = state.sourceHTML.tab || state.source.tab || state.tab || '\t',
            elements = state.sourceHTML.elements || {},
            key = map.key,
            queue = map.queue;
        if (queue.Control) {
            var _that$$3 = that.$(),
                after = _that$$3.after,
                before = _that$$3.before,
                end = _that$$3.end,
                start = _that$$3.start;
            _that$$3.value;
            var lineAfter = after.split('\n').shift(),
                lineBefore = before.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "";
            if ('Enter' === key) {
                var _m = lineAfter.match(toPattern(tagEnd(tagName) + '\\s*$', "")),
                    element = elements[_m && _m[1] || 'p'] || elements.p;
                element[3] = ['\n' + lineMatchIndent, '\n' + lineMatchIndent];
                that.select(queue.Shift ? start - toCount(lineBefore) : end + toCount(lineAfter));
                toggle.apply(that, element);
                return that.record(), false;
            }
        } // Do nothing
        if (queue.Alt || queue.Control) {
            return true;
        }
        if ('>' === key) {
            var _that$$4 = that.$(),
                _after = _that$$4.after,
                _before = _that$$4.before,
                _end = _that$$4.end,
                _lineBefore = _before.split('\n').pop(),
                _m2 = (_lineBefore + '>').match(toPattern(tagStart(tagName) + '$', "")),
                _n,
                _element = elements[_n = _m2 && _m2[1] || ""];
            if (!_n) {
                return true;
            }
            if (_element) {
                if (false !== _element[1]) {
                    if ('>' === _after[0]) {
                        that.select(_end + 1);
                    } else {
                        that.insert('>', -1);
                    }
                    that.insert('</' + _n + '>', 1).insert(_element[1]);
                } else {
                    if ('>' === _after[0]) {
                        that.insert(' /', -1).select(_end + 3);
                    } else {
                        that.insert(' />', -1);
                    }
                }
            } else {
                if ('>' === _after[0]) {
                    that.select(_end + 1);
                } else {
                    that.insert('>', -1);
                }
                that.insert('</' + _n + '>', 1);
            }
            return that.record(), false;
        }
        if ('Enter' === key) {
            var _that$$5 = that.$(),
                _after2 = _that$$5.after,
                _before2 = _that$$5.before,
                _value = _that$$5.value,
                _lineAfter = _after2.split('\n').shift(),
                _lineBefore2 = _before2.split('\n').pop(),
                _lineMatch = _lineBefore2.match(/^(\s+)/),
                _lineMatchIndent = _lineMatch && _lineMatch[1] || "",
                _m3,
                _n2;
            var continueOnEnterTags = ['li', 'option', 'p', 'td', 'th'],
                noIndentOnEnterTags = ['script', 'style'];
            if (_m3 = _lineBefore2.match(toPattern(tagStart(tagName) + '$', ""))) {
                var _element2 = elements[_m3[1]];
                if (_element2 && false === _element2[1]) {
                    return that.insert('\n' + _lineMatchIndent, -1).record(), false;
                }
            }
            if (_after2 && _before2) {
                for (var i = 0, j = toCount(continueOnEnterTags); i < j; ++i) {
                    _n2 = continueOnEnterTags[i];
                    if (toPattern('^' + tagEnd(_n2), "").test(_lineAfter) && (_m3 = _lineBefore2.match(toPattern('^\\s*' + tagStart(_n2), "")))) {
                        // `<foo>|</foo>`
                        if (_m3[0] === _lineBefore2) {
                            if (elements[_n2] && _value && elements[_n2][1] === _value) {
                                that.insert("").wrap('\n' + _lineMatchIndent + charIndent, '\n' + _lineMatchIndent); // Unwrap if empty!
                            } else {
                                toggle.apply(that, [_n2]);
                            }
                            return that.record(), false;
                        } // `<foo>bar|</foo>`
                        return that.insert('</' + _n2 + '>\n' + _lineMatchIndent + '<' + _n2 + (_m3[2] || "") + '>', -1).insert(elements[_n2] ? elements[_n2][1] || "" : "").record(), false;
                    }
                }
                for (var _i = 0, _j = toCount(noIndentOnEnterTags); _i < _j; ++_i) {
                    _n2 = noIndentOnEnterTags[_i];
                    if (toPattern('^' + tagEnd(_n2), "").test(_lineAfter) && toPattern(tagStart(_n2) + '$', "").test(_lineBefore2)) {
                        return that.wrap('\n' + _lineMatchIndent, '\n' + _lineMatchIndent).insert(elements[_n2] ? elements[_n2][1] || "" : "").record(), false;
                    }
                }
                for (var _i2 = 1; _i2 < 7; ++_i2) {
                    if (_lineAfter.startsWith('</' + elements['h' + _i2][0] + '>') && _lineBefore2.match(toPattern('^\\s*' + tagStart(elements['h' + _i2][0]), ""))) {
                        if (elements['h' + _i2] && _value && elements['h' + _i2][1] === _value) {
                            that.insert("").wrap('\n' + _lineMatchIndent + charIndent, '\n' + _lineMatchIndent); // Insert paragraph below!
                        } else {
                            that.insert('</' + elements['h' + _i2][0] + '>\n' + _lineMatchIndent + '<' + elements.p[0] + '>', -1).replace(toPattern('^' + tagEnd(elements['h' + _i2][0])), '</' + elements.p[0] + '>', 1).insert(elements.p[1]);
                        }
                        return that.record(), false;
                    }
                }
            }
        }
        return true;
    }
    var state = defaults;
    Object.assign(TE.prototype, that$2, that$1);
    TE.state = fromStates({}, TE.state, state$2, state$1, state); // Be sure to remove the default source type
    delete TE.state.source.type;

    function _onBlurSource(e) {
        this.K.pull();
    }

    function _onInputSource(e) {
        this.K.pull();
    }

    function _onKeyDownSource(e) {
        var editor = this.TE,
            map = this.K,
            key = e.key,
            type = editor.state.source.type,
            command,
            value;
        offEventPropagation(e);
        map.push(key);
        if (command = map.command()) {
            value = map.fire(command);
            if (false === value) {
                offEventDefault(e);
            } else if (null === value) {
                console.error('Unknown command:', command);
            }
        } else {
            if ('HTML' === type) {
                if (canKeyDown(map, editor) && canKeyDown$1(map, editor) && canKeyDown$2(map, editor) && canKeyDownDent(map, editor) && canKeyDownEnter(map, editor) && canKeyDownHistory(map, editor) && canKeyDownMove(map, editor));
                else {
                    offEventDefault(e);
                }
                return;
            }
            if ('XML' === type) {
                if (canKeyDown$1(map, editor) && canKeyDown$2(map, editor) && canKeyDownDent(map, editor) && canKeyDownEnter(map, editor) && canKeyDownHistory(map, editor) && canKeyDownMove(map, editor));
                else {
                    offEventDefault(e);
                }
                return;
            } // Default
            if (canKeyDown$2(map, editor) && canKeyDownDent(map, editor) && canKeyDownEnter(map, editor) && canKeyDownHistory(map, editor) && canKeyDownMove(map, editor));
            else {
                offEventDefault(e);
            }
        }
    }

    function _onKeyUpSource(e) {
        var editor = this.TE,
            map = this.K,
            key = e.key;
        canKeyUp(map, editor);
        map.pull(key);
    }

    function _onMouseDownSource(e) {
        var editor = this.TE,
            map = this.K;
        canMouseDown(map, editor);
    }

    function _letEditorSource(self) {
        offEvent('blur', self, _onBlurSource);
        offEvent('input', self, _onInputSource);
        offEvent('keydown', self, _onKeyDownSource);
        offEvent('keyup', self, _onKeyUpSource);
        offEvent('mousedown', self, _onMouseDownSource);
        offEvent('touchstart', self, _onMouseDownSource);
    }

    function _setEditorSource(self) {
        onEvent('blur', self, _onBlurSource);
        onEvent('input', self, _onInputSource);
        onEvent('keydown', self, _onKeyDownSource);
        onEvent('keyup', self, _onKeyUpSource);
        onEvent('mousedown', self, _onMouseDownSource);
        onEvent('touchstart', self, _onMouseDownSource);
        self.TE.record();
    }

    function onChange$7(init) {
        // Destroy!
        var $;
        for (var key in TE.instances) {
            $ = TE.instances[key];
            $.loss().pop();
            delete $.self.K;
            delete TE.instances[key];
            _letEditorSource($.self);
        }
        var sources = getElements('.lot\\:field.type\\:source textarea'),
            editor,
            map,
            state,
            type;
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            editor = new TE(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            state = editor.state;
            type = state.source.type;
            map = new W.K(editor);
            map.keys['Escape'] = function () {
                var parent = getParent(this.source, '[tabindex]:not(.not\\:active)');
                if (parent) {
                    return parent.focus(), false;
                }
                return true;
            };
            if ('HTML' === type) {
                map.commands = commands;
                map.keys['Control-b'] = 'bold';
                map.keys['Control-e'] = 'code';
                map.keys['Control-h'] = 'blocks';
                map.keys['Control-i'] = 'italic';
                map.keys['Control-k'] = 'link';
                map.keys['Control-o'] = 'image';
                map.keys['Control-q'] = 'quote';
                map.keys['Control-u'] = 'underline';
            }
            state.commands = map.commands;
            state.keys = map.keys;
            source.K = map;
            _setEditorSource(source);
        });
        if (1 === init) {
            W._.on('change', onChange$7);
            W.TE = TE;
            ['alert', 'confirm', 'prompt'].forEach(function (type) {
                W._.dialog[type] && (TE.state.source[type] = W._.dialog[type]);
            });
        }
    }

    function Field() {
        onChange$9();
        onChange$8();
        onChange$7();
    }
    var targets$6 = ':scope>.lot\\:file[tabindex]:not(.not\\:active),:scope>.lot\\:folder[tabindex]:not(.not\\:active)';

    function fireFocus$6(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange$6() {
        var sources = getElements('.lot\\:files[tabindex],.lot\\:folders[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var files = getElements(targets$6, source);
            files.forEach(function (file) {
                onEvent('keydown', file, onKeyDownFile);
            });
            onEvent('keydown', source, onKeyDownFiles);
        });
    }

    function onKeyDownFile(e) {
        if (e.defaultPrevented) {
            return;
        }
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
            fireFocus$6(next);
            stop = true;
        } else if ('ArrowUp' === key) {
            fireFocus$6(prev);
            stop = true;
        } else if ('End' === key) {
            any = [].slice.call(getElements(targets$6, getParent(t)));
            fireFocus$6(any.pop());
            stop = true;
        } else if ('Home' === key) {
            fireFocus$6(getElement(targets$6, getParent(t)));
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownFiles(e) {
        if (e.defaultPrevented) {
            return;
        }
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
                fireFocus$6(getElement(targets$6, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$6, t));
                fireFocus$6(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function File() {
        W._.on('change', onChange$6), onChange$6();
    }
    var targets$5 = ':scope>ul>li>a[href]:not(.not\\:active)';

    function fireFocus$5(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange$5() {
        var sources = getElements('.lot\\:links[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var links = getElements(targets$5, source);
            links && toCount(links) && links.forEach(function (link) {
                onEvent('keydown', link, onKeyDownLink);
            });
            onEvent('keydown', source, onKeyDownLinks);
        });
    }

    function onKeyDownLink(e) {
        if (e.defaultPrevented) {
            return;
        }
        var t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            any,
            parent,
            next,
            prev,
            stop;
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
            if ('ArrowLeft' === key) {
                fireFocus$5(prev && getChildFirst(prev));
                stop = true;
            } else if ('ArrowRight' === key) {
                fireFocus$5(next && getChildFirst(next));
                stop = true;
            } else if ('End' === key) {
                if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                    any = [].slice.call(getElements(targets$5, parent));
                    fireFocus$5(any.pop());
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:links[tabindex]')) {
                    fireFocus$5(getElement(targets$5, parent));
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownLinks(e) {
        if (e.defaultPrevented) {
            return;
        }
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
            if ('ArrowRight' === key || 'Home' === key) {
                fireFocus$5(getElement(targets$5, t));
                stop = true;
            } else if ('ArrowLeft' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$5, t));
                fireFocus$5(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function Link() {
        W._.on('change', onChange$5), onChange$5();
    }
    var targets$4 = 'a[href]:not(.not\\:active)';

    function fireFocus$4(node) {
        node && isFunction(node.focus) && node.focus();
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

    function onChange$4() {
        offEvent('click', D, onClickDocument);
        var menuParents = getElements('.has\\:menu'),
            menuLinks = getElements('.lot\\:menu[tabindex] ' + targets$4);
        if (menuParents && toCount(menuParents)) {
            menuParents.forEach(function (menuParent) {
                var menu = getElement('.lot\\:menu[tabindex]', menuParent),
                    a = getPrev(menu);
                if (menu && a) {
                    onEvent('click', a, onClickMenuShow);
                    onEvent('keydown', a, onKeyDownMenuToggle);
                }
            });
            onEvent('click', D, onClickDocument);
        }
        if (menuLinks && toCount(menuLinks)) {
            menuLinks.forEach(function (menuLink) {
                onEvent('keydown', menuLink, onKeyDownMenu);
            });
        }
        var sources = getElements('.lot\\:menu[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            onEvent('keydown', source, onKeyDownMenus);
        });
    }

    function onClickDocument() {
        doHideMenus(0);
    }

    function onClickMenuShow(e) {
        if (e.defaultPrevented) {
            return;
        }
        var t = this,
            current = getNext(t);
        doHideMenus(current, t);
        W.setTimeout(function () {
            toggleClass(current, 'is:enter');
            toggleClass(getParent(t), 'is:active');
            toggleClass(t, 'is:active');
            setAttribute(t, 'aria-expanded', hasClass(t, 'is:active') ? 'true' : 'false');
        }, 1);
        offEventDefault(e);
        offEventPropagation(e);
    }

    function onKeyDownMenu(e) {
        if (e.defaultPrevented) {
            return;
        }
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
            fireFocus$4(next && getChildFirst(next));
            stop = true;
        } else if ('ArrowLeft' === key || 'Escape' === key || 'Tab' === key) {
            // Hide menu then focus to the parent menu link
            if (parent = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
                letClass(getParent(t), 'is:active');
                letClass(parent, 'is:enter');
                letClass(t, 'is:active');
                if ('Tab' !== key) {
                    fireFocus$4(getPrev(parent));
                } // Focus to the self menu
            } else if ('Escape' === key) {
                fireFocus$4(getParent(t, '.lot\\:menu[tabindex]'));
            }
            stop = 'Tab' !== key;
        } else if ('ArrowRight' === key) {
            next = getNext(t);
            if (next && hasClass(next, 'lot:menu')) {
                setClass(getParent(t), 'is:active');
                setClass(next, 'is:enter');
                setClass(t, 'is:active');
                W.setTimeout(function () {
                    // Focus to the first link of child menu
                    fireFocus$4(getElement(targets$4, next));
                }, 1);
            }
            stop = true;
        } else if ('ArrowUp' === key) {
            current = prev && getChildFirst(prev);
            if (current) {
                fireFocus$4(current);
            } else {
                if (current = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
                    // Hide menu then focus to the parent menu link
                    if (current = getPrev(current)) {
                        fireEvent('click', current), fireFocus$4(current);
                    }
                }
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
                any = [].slice.call(getElements(targets$4, parent));
                fireFocus$4(any.pop());
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
                fireFocus$4(getElement(targets$4, parent));
            }
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownMenus(e) {
        if (e.defaultPrevented) {
            return;
        }
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
                fireFocus$4(getElement(targets$4, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets$4, t));
                fireFocus$4(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownMenuToggle(e) {
        if (e.defaultPrevented) {
            return;
        }
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
                    stop = true;
                }
            } else if ('ArrowDown' === key) {
                if (!hasClass(next, 'is:enter')) {
                    fireEvent('click', t);
                }
                W.setTimeout(function () {
                    // Focus to the first link of child menu
                    fireFocus$4(getElement(targets$4, next));
                }, 1);
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function Menu() {
        W._.on('change', onChange$4), onChange$4();
    }
    var targets$3 = ':scope>.lot\\:page[tabindex]:not(.not\\:active)';

    function fireFocus$3(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange$3() {
        var sources = getElements('.lot\\:pages[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var pages = getElements(targets$3, source);
            pages.forEach(function (page) {
                onEvent('keydown', page, onKeyDownPage);
            });
            onEvent('keydown', source, onKeyDownPages);
        });
    }

    function onKeyDownPage(e) {
        if (e.defaultPrevented) {
            return;
        }
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
        if (e.defaultPrevented) {
            return;
        }
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

    function Page() {
        W._.on('change', onChange$3), onChange$3();
    }
    var targets$2 = 'a[target^="stack:"]:not(.not\\:active)';

    function fireFocus$2(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange$2() {
        var sources = getElements('.lot\\:stacks[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var stacks = [].slice.call(getChildren(source)),
                input = setElement('input'),
                name,
                value;
            input.type = 'hidden';
            input.name = name = getDatum(source, 'name');
            name && setChildLast(source, input);

            function onClick(e) {
                var t = this,
                    parent = getParent(getParent(t)),
                    self = getParent(parent, '.lot\\:stacks'),
                    current;
                if (!hasClass(parent, 'has:link')) {
                    stacks.forEach(function (stack) {
                        if (stack !== parent) {
                            letClass(stack, 'is:current');
                            letClass(getElement('a[target^="stack:"]', stack), 'is:current');
                        }
                    });
                    if (hasClass(parent, 'can:toggle')) {
                        toggleClass(t, 'is:current');
                        toggleClass(parent, 'is:current');
                    } else {
                        setClass(t, 'is:current');
                        setClass(parent, 'is:current');
                    }
                    current = hasClass(t, 'is:current');
                    input.value = value = current ? getDatum(parent, 'value') : null;
                    toggleClass(self, 'has:current', current);
                    W._.fire.apply(parent, ['change.stack', [value, name]]);
                    offEventDefault(e);
                }
            }
            stacks.forEach(function (stack) {
                var target = getElement(targets$2, stack);
                onEvent('click', target, onClick);
                onEvent('keydown', target, onKeyDownStack);
            });
            var stackCurrent = stacks.find(function (value, key) {
                return 0 !== key && hasClass(value, 'is:current');
            });
            if (stackCurrent) {
                input.value = getDatum(stackCurrent, 'value');
            }
            onEvent('keydown', source, onKeyDownStacks);
        });
    }

    function onKeyDownStack(e) {
        if (e.defaultPrevented) {
            return;
        }
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
        if (e.defaultPrevented) {
            return;
        }
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

    function Stack() {
        W._.on('change', onChange$2), onChange$2();
    }
    var targets$1 = 'a[target^="tab:"]:not(.not\\:active)';

    function fireFocus$1(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange$1() {
        var sources = getElements('.lot\\:tabs[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var panes = [].slice.call(getChildren(source)),
                tabs = [].slice.call(getElements(targets$1, panes.shift())),
                input = setElement('input'),
                name,
                value;
            input.type = 'hidden';
            input.name = name = getDatum(source, 'name');
            name && setChildLast(source, input);

            function onClick(e) {
                var t = this,
                    pane = panes[t._tabIndex],
                    parent = getParent(t),
                    self = getParent(parent, '.lot\\:tabs'),
                    current;
                if (!hasClass(parent, 'has:link')) {
                    tabs.forEach(function (tab) {
                        if (tab !== t) {
                            letClass(getParent(tab), 'is:current');
                            letClass(tab, 'is:current');
                            setAttribute(tab, 'aria-selected', 'false');
                            setAttribute(tab, 'tabindex', '-1');
                            var _pane = panes[tab._tabIndex];
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
                        input.value = value = current ? getDatum(t, 'value') : null;
                        toggleClass(pane, 'is:current', current);
                        toggleClass(self, 'has:current', current);
                        W._.fire.apply(pane, ['change.tab', [value, name]]);
                    }
                    offEventDefault(e);
                }
            }
            tabs.forEach(function (tab, index) {
                tab._tabIndex = index;
                onEvent('click', tab, onClick);
                onEvent('keydown', tab, onKeyDownTab);
            });
            var tabCurrent = tabs.find(function (value, key) {
                return 0 !== key && hasClass(getParent(value), 'is:current');
            });
            if (tabCurrent) {
                input.value = getDatum(tabCurrent, 'value');
            }
            onEvent('keydown', source, onKeyDownTabs);
        });
    }

    function onKeyDownTab(e) {
        if (e.defaultPrevented) {
            return;
        }
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
        if (e.defaultPrevented) {
            return;
        }
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
            if ('ArrowDown' === key || 'ArrowRight' === key || 'Home' === key || 'PageDown' === key) {
                if (current = getElement(targets$1, t)) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'End' === key || 'PageUp' === key) {
                any = [].slice.call(getElements(targets$1, t));
                if (current = any.pop()) {
                    fireEvent('click', current), fireFocus$1(current);
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function Tab() {
        W._.on('change', onChange$1), onChange$1();
    }
    var targets = 'a[href]:not([tabindex="-1"]):not(.not\\:active),button:not(:disabled):not([tabindex="-1"]):not(.not\\:active),input:not(:disabled):not([tabindex="-1"]):not(.not\\:active),select:not(:disabled):not([tabindex="-1"]):not(.not\\:active),[tabindex]:not([tabindex="-1"]):not(.not\\:active)';

    function fireFocus(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function fireSelect(node) {
        node && isFunction(node.select) && node.select();
    }

    function onChange() {
        var sources = getElements('.lot\\:tasks[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var tasks = getElements(targets, source);
            tasks && toCount(tasks) && tasks.forEach(function (task) {
                onEvent('keydown', task, onKeyDownTask);
            });
            onEvent('keydown', source, onKeyDownTasks);
        });
    }

    function onKeyDownTask(e) {
        if (e.defaultPrevented) {
            return;
        }
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
        if (e.defaultPrevented) {
            return;
        }
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

    function Task() {
        W._.on('change', onChange), onChange();
    }

    function K(source) {
        if (source === void 0) {
            source = {};
        }
        var $ = this;
        $.command = function (v) {
            if (isString(v)) {
                return v === $.toString();
            }
            var command = $.keys[$.toString()];
            return isSet$1(command) ? command : false;
        };
        $.commands = {};
        $.fire = function (command) {
            var context = $.source,
                value,
                exist;
            if (isFunction(command)) {
                value = command.call(context);
                exist = true;
            } else if (isString(command) && (command = $.commands[command])) {
                value = command.call(context);
                exist = true;
            } else if (isArray(command)) {
                var data = command[1] || [];
                if (command = $.commands[command[0]]) {
                    value = command.apply(context, data);
                    exist = true;
                }
            }
            return exist ? isSet$1(value) ? value : true : null;
        };
        $.key = null;
        $.keys = {};
        $.pull = function (key) {
            $.key = null;
            if (!isSet$1(key)) {
                return $.queue = {}, $;
            }
            return delete $.queue[key], $;
        };
        $.push = function (key) {
            return $.queue[$.key = key] = 1, $;
        };
        $.queue = {};
        $.source = source;
        $.toString = function () {
            return toObjectKeys($.queue).join('-');
        };
        return $;
    }
    var map = new K(W);
    onEvent('blur', W, function (e) {
        return map.pull();
    });
    onEvent('keydown', W, function (e) {
        map.push(e.key);
        var command = map.command();
        if (command) {
            var value = map.fire(command);
            if (false === value) {
                offEventDefault(e);
            } else if (null === value) {
                console.error('Unknown command:', command);
            }
        }
    });
    onEvent('keyup', W, function (e) {
        return map.pull(e.key);
    });
    var _ = {
        commands: map.commands,
        keys: map.keys
    };
    var _hook = hook(_),
        fire = _hook.fire;
    _hook.hooks;
    _hook.off;
    _hook.on;
    W.K = K;
    W._ = _;
    onEvent('beforeload', D, function () {
        return fire('let');
    });
    onEvent('load', D, function () {
        return fire('get');
    });
    onEvent('DOMContentLoaded', D, function () {
        return fire('set');
    });
    onEvent('keydown', W, function (e) {
        if (e.defaultPrevented) {
            return;
        }
        var target = e.target,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            firstBarFocusable = getElement('.lot\\:bar a:any-link'),
            mainSearchForm = getFormElement('get'),
            mainSearchFormInput = mainSearchForm && mainSearchForm.query,
            parent,
            stop;
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
    Bar();
    Dialog();
    Field();
    File();
    Link();
    Menu();
    Page();
    Stack();
    Tab();
    Task();
})();