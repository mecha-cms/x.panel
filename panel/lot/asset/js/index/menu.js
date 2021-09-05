(function() {
    'use strict';
    var toCount = function toCount(x) {
        return x.length;
    };
    var D = document;
    var W = window;
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
    var letClass = function letClass(node, value) {
        return node.classList.remove(value), node;
    };
    var toggleClass = function toggleClass(node, name, force) {
        return node.classList.toggle(name, force), node;
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

    function _hideMenus(but) {
        getElements('.lot\\:menu.is\\:enter').forEach(node => {
            if (but !== node) {
                letClass(node, 'is:enter');
                letClass(getParent(node), 'is:active');
                letClass(getPrev(node), 'is:active');
            }
        });
    }

    function _clickHideMenus() {
        _hideMenus(0);
    }

    function _clickShowMenu(e) {
        let t = this,
            current = getNext(t);
        _hideMenus(current);
        W.setTimeout(() => {
            toggleClass(t, 'is:active');
            toggleClass(getParent(t), 'is:active');
            toggleClass(current, 'is:enter');
        }, 1);
        offEventDefault(e);
        offEventPropagation(e);
    }

    function onChange() {
        offEvent('click', D, _clickHideMenus);
        let menuParents = getElements('.has\\:menu');
        if (menuParents && toCount(menuParents)) {
            menuParents.forEach(menuParent => {
                let menu = getElement('.lot\\:menu', menuParent),
                    a = getPrev(menu);
                if (menu && a) {
                    onEvent('click', a, _clickShowMenu);
                }
            });
            onEvent('click', D, _clickHideMenus);
        }
    }
    onChange();
    W._.on('change', onChange);
})();