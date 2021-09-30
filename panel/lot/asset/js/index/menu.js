(function() {
    'use strict';
    var isDefined = function isDefined(x) {
        return 'undefined' !== typeof x;
    };
    var isFunction = function isFunction(x) {
        return 'function' === typeof x;
    };
    var isNull = function isNull(x) {
        return null === x;
    };
    var isSet = function isSet(x) {
        return isDefined(x) && !isNull(x);
    };
    var toCount = function toCount(x) {
        return x.length;
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
    var getNext = function getNext(node) {
        return node.nextElementSibling || null;
    };
    var getParent = function getParent(node) {
        return node.parentNode || null;
    };
    var getPrev = function getPrev(node) {
        return node.previousElementSibling || null;
    };
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
    };
    var letClass = function letClass(node, value) {
        return node.classList.remove(value), node;
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

    function doHideMenus(but) {
        getElements('.lot\\:menu.is\\:enter').forEach(node => {
            if (but !== node) {
                letClass(getParent(node), 'is:active');
                letClass(getPrev(node), 'is:active');
                letClass(node, 'is:enter');
            }
        });
    }

    function onChange() {
        offEvent('click', D, onClickDocument);
        let menuParents = getElements('.has\\:menu'),
            menuLinks = getElements('.lot\\:menu a[href]:not(.not\\:active)');
        if (menuParents && toCount(menuParents)) {
            menuParents.forEach(menuParent => {
                let menu = getElement('.lot\\:menu', menuParent),
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
                offEvent('keydown', menuLink, onKeyDownMenu);
                onEvent('keydown', menuLink, onKeyDownMenu);
            });
        }
    }
    onChange();

    function onClickDocument() {
        doHideMenus(0);
    }

    function onClickMenuShow(e) {
        let t = this,
            current = getNext(t);
        doHideMenus(current);
        W.setTimeout(() => {
            toggleClass(current, 'is:enter');
            toggleClass(getParent(t), 'is:active');
            toggleClass(t, 'is:active');
        }, 1);
        offEventDefault(e);
        offEventPropagation(e);
    }

    function onKeyDownMenu(e) {
        let t = this,
            key = e.key,
            current,
            parent,
            next,
            prev;
        if (parent = getParent(t)) {
            next = getNext(parent);
            while (next && (hasClass(next, 'is:separator') || hasClass(next, 'not:active'))) {
                next = getNext(next);
            }
            prev = getPrev(parent);
            while (prev && (hasClass(prev, 'is:separator') || hasClass(prev, 'not:active'))) {
                prev = getPrev(prev);
            }
        }
        if ('ArrowDown' === key) {
            current = next && getChildFirst(next);
            if (current && isFunction(current.focus)) {
                current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowLeft' === key) {
            parent = isFunction(t.closest) && t.closest('.lot\\:menu.is\\:enter'); // Hide menu then focus to the parent menu link
            if (parent && (current = getPrev(parent))) {
                letClass(getParent(t), 'is:active');
                letClass(parent, 'is:enter');
                letClass(t, 'is:active');
                isFunction(current.focus) && current.focus();
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowRight' === key) {
            next = getNext(t);
            if (next && hasClass(next, 'lot:menu')) {
                setClass(getParent(t), 'is:active');
                setClass(next, 'is:enter');
                setClass(t, 'is:active');
                W.setTimeout(() => {
                    if (current = getElement('a[href]:not(.not\\:active)', next)) {
                        // Focus to the first link of child menu
                        isFunction(current.focus) && current.focus();
                    }
                }, 1);
            }
            offEventDefault(e);
            offEventPropagation(e);
        } else if ('ArrowUp' === key) {
            current = prev && getChildFirst(prev);
            if (current && isFunction(current.focus)) {
                current.focus();
            } else {
                if (current = isFunction(t.closest) && t.closest('.is\\:enter')) {
                    // Hide menu then focus to the parent menu link
                    if (current = getPrev(current)) {
                        fireEvent('click', current);
                        isFunction(current.focus) && current.focus();
                    }
                }
            }
            offEventDefault(e);
            offEventPropagation(e);
        }
    }

    function onKeyDownMenuToggle(e) {
        let t = this,
            key = e.key,
            current,
            next = getNext(t),
            parent = getParent(t);
        if (next && parent && hasClass(next, 'lot:menu')) {
            if (' ' === key || 'Enter' === key) {
                fireEvent('click', t);
                offEventDefault(e);
                offEventPropagation(e);
            } else if ('ArrowDown' === key) {
                if (!hasClass(next, 'is:enter')) {
                    fireEvent('click', t);
                }
                W.setTimeout(() => {
                    if (current = getElement('a[href]:not(.not\\:active)', next)) {
                        // Focus to the first link of child menu
                        isFunction(current.focus) && current.focus();
                    }
                }, 1);
                offEventDefault(e);
                offEventPropagation(e);
            } else if ('ArrowUp' === key) {
                // TODO
                offEventDefault(e);
                offEventPropagation(e);
            }
        }
    }
    W._.on('change', onChange);
})();