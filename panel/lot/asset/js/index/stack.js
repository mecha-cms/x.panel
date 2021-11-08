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
    var toCount = function toCount(x) {
        return x.length;
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
    var fromJSON = function fromJSON(x) {
        var value = null;
        try {
            value = JSON.parse(x);
        } catch (e) {}
        return value;
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
    var getNext = function getNext(node) {
        return node.nextElementSibling || null;
    };
    var getParent = function getParent(node, query) {
        if (query) {
            return node.closest(query) || null;
        }
        return node.parentNode || null;
    };
    var getPrev = function getPrev(node) {
        return node.previousElementSibling || null;
    };
    var hasAttribute = function hasAttribute(node, attribute) {
        return node.hasAttribute(attribute);
    };
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
    };
    var hasState = function hasState(node, state) {
        return state in node;
    };
    var letAttribute = function letAttribute(node, attribute) {
        return node.removeAttribute(attribute), node;
    };
    var letClass = function letClass(node, value) {
        return node.classList.remove(value), node;
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
    var toggleClass = function toggleClass(node, name, force) {
        return node.classList.toggle(name, force), node;
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
    var offEventDefault = function offEventDefault(e) {
        return e && e.preventDefault();
    };
    var offEventPropagation = function offEventPropagation(e) {
        return e && e.stopPropagation();
    };
    var onEvent = function onEvent(name, node, then, options) {
        if (options === void 0) {
            options = false;
        }
        node.addEventListener(name, then, options);
    };
    const targets = 'a[target^="stack:"]:not(.not\\:active)';

    function fireFocus(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function onChange() {
        let sources = getElements('.lot\\:stacks[tabindex]');
        sources && toCount(sources) && sources.forEach(source => {
            let stacks = [].slice.call(getChildren(source)),
                input = setElement('input'),
                name,
                value;
            input.type = 'hidden';
            input.name = name = getDatum(source, 'name');
            setChildLast(source, input);

            function onClick(e) {
                let t = this,
                    parent = getParent(getParent(t)),
                    self = getParent(parent, '.lot\\:stacks'),
                    current;
                if (!hasClass(parent, 'has:link')) {
                    stacks.forEach(stack => {
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
                }
                offEventDefault(e);
            }
            stacks.forEach(stack => {
                let target = getElement(targets, stack);
                onEvent('click', target, onClick);
                onEvent('keydown', target, onKeyDownStack);
            });
            let stackCurrent = stacks.find((value, key) => 0 !== key && hasClass(value, 'is:current'));
            if (stackCurrent) {
                input.value = getDatum(stackCurrent, 'value');
            }
            onEvent('keydown', source, onKeyDownStacks);
        });
    }
    onChange();

    function onKeyDownStack(e) {
        if (e.defaultPrevented) {
            return;
        }
        let t = this,
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
                    any = [].slice.call(getElements(targets, parent));
                    if (current = any.pop()) {
                        fireEvent('click', current), fireFocus(current);
                    }
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = getParent(t, '.lot\\:stacks[tabindex]')) {
                    if (current = getElement(targets, parent)) {
                        fireEvent('click', current), fireFocus(current);
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
        let t = this,
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
            current = getElement(targets + '.is\\:current', t);
            current = current && getParent(getParent(current));
            if ('PageDown' === key) {
                next = current && getNext(current);
                if (current = next && getElement(targets, next)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if ('PageUp' === key) {
                prev = current && getPrev(current);
                if (current = prev && getElement(targets, prev)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            }
        } else if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            if (t !== e.target) {
                return;
            }
            if ('ArrowDown' === key || 'ArrowRight' === key || 'Home' === key || 'PageDown' === key) {
                if (current = getElement(targets, t)) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'End' === key || 'PageUp' === key) {
                any = [].slice.call(getElements(targets, t));
                if (current = any.pop()) {
                    fireEvent('click', current), fireFocus(current);
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    W._.on('change', onChange);
})();