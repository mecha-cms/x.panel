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
    var isNull = function isNull(x) {
        return null === x;
    };
    var isSet = function isSet(x) {
        return isDefined(x) && !isNull(x);
    };
    var isString = function isString(x) {
        return 'string' === typeof x;
    };
    var toObjectKeys = function toObjectKeys(x) {
        return Object.keys(x);
    };
    var D = document;
    var W = window;
    var B = D.body;
    var R = D.documentElement;
    var getElement = function getElement(query, scope) {
        return (scope || D).querySelector(query);
    };
    var getFormElement = function getFormElement(nameOrIndex) {
        return D.forms[nameOrIndex] || null;
    };
    var getParent = function getParent(node, query) {
        if (query) {
            return node.closest(query) || null;
        }
        return node.parentNode || null;
    };
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
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

    function hook($) {
        var hooks = {};

        function fire(name, data) {
            if (!isSet(hooks[name])) {
                return $;
            }
            hooks[name].forEach(function(then) {
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

    function K(source) {
        if (source === void 0) {
            source = {};
        }
        var $ = this;
        $.commands = {};
        $.fire = function(command) {
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
            return exist ? isSet(value) ? value : true : null;
        };
        $.key = null;
        $.keys = {};
        $.pull = function(key) {
            $.key = null;
            if (!isSet(key)) {
                return $.queue = {}, $;
            }
            return delete $.queue[key], $;
        };
        $.push = function(key) {
            return $.queue[$.key = key] = 1, $;
        };
        $.queue = {};
        $.source = source;
        $.test = function() {
            var command = $.keys[$.toString()];
            return isSet(command) ? command : false;
        };
        $.toString = function() {
            return toObjectKeys($.queue).join('-');
        };
        return $;
    }
    let map = new K(W);
    onEvent('blur', W, e => map.pull());
    onEvent('keydown', W, e => {
        map.push(e.key);
        let command = map.test();
        if (command) {
            let value = map.fire(command);
            if (false === value) {
                offEventDefault(e);
            } else if (null === value) {
                console.error('Unknown command:', command);
            }
        }
    });
    onEvent('keyup', W, e => map.pull(e.key));
    const _ = {
        commands: map.commands,
        keys: map.keys
    };
    const {
        fire,
        hooks,
        off,
        on
    } = hook(_);
    W.K = K;
    W._ = _;
    onEvent('beforeload', D, () => fire('let'));
    onEvent('load', D, () => fire('get'));
    onEvent('DOMContentLoaded', D, () => fire('set'));
    const mainSearchForm = getFormElement('get');
    const mainSearchFormInput = mainSearchForm && mainSearchForm.q;
    onEvent('keydown', W, function(e) {
        if (e.defaultPrevented) {
            return;
        }
        let target = e.target,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            firstBarFocusable = getElement('.lot\\:bar a:any-link'),
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
})();