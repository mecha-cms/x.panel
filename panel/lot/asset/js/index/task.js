(function() {
    'use strict';
    var isFunction = function isFunction(x) {
        return 'function' === typeof x;
    };
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
    var getPrev = function getPrev(node) {
        return node.previousElementSibling || null;
    };
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
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
    const targets = 'a[href]:not(.not\\:active),button:not(:disabled):not(.not\\:active),input:not(:disabled):not(.not\\:active),select:not(:disabled):not(.not\\:active)';

    function onChange() {
        let tasks = getElements('.lot\\:tasks');
        tasks && toCount(tasks) && tasks.forEach(task => {
            let taskButtons = getElements(targets, task);
            taskButtons && toCount(taskButtons) && taskButtons.forEach(taskButton => {
                offEvent('keydown', taskButton, onKeyDownTask);
                onEvent('keydown', taskButton, onKeyDownTask);
            });
            offEvent('keydown', task, onKeyDownTasks);
            onEvent('keydown', task, onKeyDownTasks);
        });
    }
    onChange();

    function onKeyDownTask(e) {
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
                prev && isFunction(prev.focus) && prev.focus();
                stop = true;
            } else if ('ArrowRight' === key) {
                next && isFunction(next.focus) && next.focus();
                stop = true;
            } else if ('End' === key) {
                if (parent = t.closest('.lot\\:tasks')) {
                    any = [].slice.call(getElements(targets, parent));
                    if (current = any.pop()) {
                        isFunction(current.focus) && current.focus();
                    }
                }
                stop = true;
            } else if ('Home' === key) {
                if (parent = t.closest('.lot\\:tasks')) {
                    if (current = getElement(targets, parent)) {
                        isFunction(current.focus) && current.focus();
                    }
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }

    function onKeyDownTasks(e) {
        if (e.defaultPrevented) {
            return;
        }
        let t = this,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            stop;
        if (t !== e.target) {
            return;
        }
        if (!keyIsAlt && !keyIsCtrl && !keyIsShift) {
            let any, current;
            if ('ArrowLeft' === key || 'End' === key) {
                any = [].slice.call(getElements(targets, t));
                if (current = any.pop()) {
                    isFunction(current.focus) && current.focus();
                }
                stop = true;
            } else if ('ArrowRight' === key || 'Home' === key) {
                if (current = getElement(targets, t)) {
                    isFunction(current.focus) && current.focus();
                }
                stop = true;
            }
        }
        stop && (offEventDefault(e), offEventPropagation(e));
    }
    W._.on('change', onChange);
})();