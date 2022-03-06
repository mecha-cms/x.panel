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
    var toCount = function toCount(x) {
        return x.length;
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
    var getChildFirst = function getChildFirst(parent) {
        return parent.firstElementChild || null;
    };
    var getElement = function getElement(query, scope) {
        return (scope || D).querySelector(query);
    };
    var getElements = function getElements(query, scope) {
        return (scope || D).querySelectorAll(query);
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
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
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
    var setClass = function setClass(node, value) {
        return node.classList.add(value), node;
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
    var offEvent = function offEvent(name, node, then) {
        node.removeEventListener(name, then);
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
    const targets = 'a[href]:not(.not\\:active)';

    function fireFocus(node) {
        node && isFunction(node.focus) && node.focus();
    }

    function doHideMenus(but, trigger) {
        getElements('.lot\\:menu[tabindex].is\\:enter').forEach(node => {
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

    function onChange() {
        offEvent('click', D, onClickDocument);
        let menuParents = getElements('.has\\:menu'),
            menuLinks = getElements('.lot\\:menu[tabindex] ' + targets);
        if (menuParents && toCount(menuParents)) {
            menuParents.forEach(menuParent => {
                let menu = getElement('.lot\\:menu[tabindex]', menuParent),
                    a = getPrev(menu);
                if (menu && a) {
                    onEvent('click', a, onClickMenuShow);
                    onEvent('keydown', a, onKeyDownMenuToggle);
                }
            });
            onEvent('click', D, onClickDocument);
        }
        if (menuLinks && toCount(menuLinks)) {
            menuLinks.forEach(menuLink => {
                onEvent('keydown', menuLink, onKeyDownMenu);
            });
        }
        let sources = getElements('.lot\\:menu[tabindex]');
        sources && toCount(sources) && sources.forEach(source => {
            onEvent('keydown', source, onKeyDownMenus);
        });
    }
    onChange();

    function onClickDocument() {
        doHideMenus(0);
    }

    function onClickMenuShow(e) {
        if (e.defaultPrevented) {
            return;
        }
        let t = this,
            current = getNext(t);
        doHideMenus(current, t);
        W.setTimeout(() => {
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
        let t = this,
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
                if ('Tab' !== key) {
                    fireFocus(getPrev(parent));
                } // Focus to the self menu
            } else if ('Escape' === key) {
                fireFocus(getParent(t, '.lot\\:menu[tabindex]'));
            }
            stop = 'Tab' !== key;
        } else if ('ArrowRight' === key) {
            next = getNext(t);
            if (next && hasClass(next, 'lot:menu')) {
                setClass(getParent(t), 'is:active');
                setClass(next, 'is:enter');
                setClass(t, 'is:active');
                W.setTimeout(() => {
                    // Focus to the first link of child menu
                    fireFocus(getElement(targets, next));
                }, 1);
            }
            stop = true;
        } else if ('ArrowUp' === key) {
            current = prev && getChildFirst(prev);
            if (current) {
                fireFocus(current);
            } else {
                if (current = getParent(t, '.lot\\:menu[tabindex].is\\:enter')) {
                    // Hide menu then focus to the parent menu link
                    if (current = getPrev(current)) {
                        fireEvent('click', current), fireFocus(current);
                    }
                }
            }
            stop = true;
        } else if ('End' === key) {
            if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
                any = [].slice.call(getElements(targets, parent));
                fireFocus(any.pop());
            }
            stop = true;
        } else if ('Home' === key) {
            if (parent = getParent(t, '.lot\\:menu[tabindex]')) {
                fireFocus(getElement(targets, parent));
            }
            stop = true;
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownMenus(e) {
        if (e.defaultPrevented) {
            return;
        }
        let t = this,
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
                fireFocus(getElement(targets, t));
                stop = true;
            } else if ('ArrowUp' === key || 'End' === key) {
                any = [].slice.call(getElements(targets, t));
                fireFocus(any.pop());
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownMenuToggle(e) {
        if (e.defaultPrevented) {
            return;
        }
        let t = this,
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
                W.setTimeout(() => {
                    // Focus to the first link of child menu
                    fireFocus(getElement(targets, next));
                }, 1);
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    W._.on('change', onChange);
})();