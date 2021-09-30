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
    var getParent = function getParent(node) {
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

    function onChange() {
        let sources = getElements('.lot\\:stacks');
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
                    parent = getParent(t);
                if (!hasClass(parent, 'has:link')) {
                    if (!hasClass(t, 'not:active')) {
                        stacks.forEach(stack => {
                            if (stack !== parent) {
                                letClass(stack, 'is:current');
                                letClass(getElement('a', stack), 'is:current');
                            }
                        });
                        if (hasClass(parent, 'can:toggle')) {
                            toggleClass(t, 'is:current');
                            toggleClass(parent, 'is:current');
                            input.value = value = hasClass(t, 'is:current') ? getDatum(parent, 'value') : null;
                        } else {
                            setClass(t, 'is:current');
                            setClass(parent, 'is:current');
                            input.value = value = getDatum(parent, 'value');
                        }
                        W._.fire('change.stack', [name, value]);
                    }
                    offEventDefault(e);
                }
            }
            stacks.forEach(stack => {
                let t = getElement('a[target^="stack:"]', stack);
                onEvent('click', t, onClick);
                onEvent('keydown', t, onKeyDownStack);
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
        let t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey;
        if (!keyIsAlt && !keyIsCtrl) {
            let current, next, parent, prev;
            if ('ArrowDown' === key || 'PageDown' === key) {
                if (parent = getParent(t)) {
                    next = getNext(parent);
                    while (next && hasClass(next, 'not:active')) {
                        next = getNext(next);
                    }
                }
                if (current = next && getChildFirst(next)) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
                offEventDefault(e);
                offEventPropagation(e);
            } else if ('ArrowLeft' === key) {
                if (hasClass(getParent(t), 'can:toggle') && hasClass(t, 'is:current')) {
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
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
                offEventDefault(e);
                offEventPropagation(e);
            } else if ('ArrowRight' === key) {
                if (hasClass(getParent(t), 'can:toggle') && hasClass(t, 'is:current')) {
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
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
                offEventDefault(e);
                offEventPropagation(e);
            } else if ('ArrowUp' === key || 'PageUp' === key) {
                if (parent = getParent(t)) {
                    prev = getPrev(parent);
                    while (prev && hasClass(prev, 'not:active')) {
                        prev = getPrev(prev);
                    }
                }
                if (current = prev && getChildFirst(prev)) {
                    fireEvent('click', current);
                    isFunction(current.focus) && current.focus();
                }
                offEventDefault(e);
                offEventPropagation(e);
            } else if (' ' === key || 'Enter' === key) {
                if (hasClass(getParent(t), 'can:toggle')) {
                    fireEvent('click', t);
                    isFunction(t.focus) && t.focus();
                }
                offEventDefault(e);
                offEventPropagation(e);
            } else if ('Escape' === key) {
                if (isFunction(t.closest) && (parent = t.closest('.lot\\:stacks'))) {
                    isFunction(parent.focus) && parent.focus();
                }
                offEventDefault(e);
                offEventPropagation(e);
            }
        }
    }

    function onKeyDownStacks(e) {
        let t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey;
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            let current;
            if ('ArrowDown' === key || 'ArrowRight' === key || 'PageDown' === key) {
                if (current = getElement('a[target^="stack:"]:not(.not\\:active)', t)) {
                    isFunction(current.focus) && current.focus();
                }
                offEventDefault(e);
                offEventPropagation(e);
            } else if ('ArrowUp' === key || 'ArrowLeft' === key || 'PageUp' === key) {
                let links = [].slice.call(getElements('a[target^="stack:"]:not(.not\\:active)', t));
                if (current = links.pop()) {
                    isFunction(current.focus) && current.focus();
                }
                offEventDefault(e);
                offEventPropagation(e);
            }
        }
    }
    W._.on('change', onChange);
})();