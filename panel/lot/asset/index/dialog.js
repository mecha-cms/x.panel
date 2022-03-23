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
    var isString = function isString(x) {
        return 'string' === typeof x;
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
    var B = D.body;
    var getElement = function getElement(query, scope) {
        return (scope || D).querySelector(query);
    };
    var hasState = function hasState(node, state) {
        return state in node;
    };
    var letAttribute = function letAttribute(node, attribute) {
        return node.removeAttribute(attribute), node;
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
    var offEvent = function offEvent(name, node, then) {
        node.removeEventListener(name, then);
    };
    var onEvent = function onEvent(name, node, then, options) {
        if (options === void 0) {
            options = false;
        }
        node.addEventListener(name, then, options);
    };
    let dialog = setElement('dialog');

    function onDialogCancel(e) {
        offEvent(e.type, this, onDialogCancel);
        return this.x(this.returnValue);
    }

    function onDialogSubmit(e) {
        offEvent(e.type, this, onDialogSubmit);
        return this.v(this.returnValue);
    }

    function setDialog(content) {
        setHTML(dialog, '<form method="dialog">' + content + '</form>');
        dialog.showModal();
        let target = getElement('[autofocus]', dialog);
        if (target) {
            isFunction(target.focus) && target.focus();
            isFunction(target.select) && target.select(); // `<input>`
        }
        return new Promise((resolve, reject) => {
            dialog.v = resolve;
            dialog.x = reject;
            onEvent('cancel', dialog, onDialogCancel);
            onEvent('submit', dialog, onDialogSubmit);
        });
    }
    setDialog.alert = function(description) {
        return setDialog('<p>' + description + '</p><p role="group"><button autofocus name="v" type="submit" value="1">OK</button></p>');
    };
    setDialog.confirm = function(description) {
        return setDialog('<p>' + description + '</p><p role="group"><button name="v" type="submit" value="1">OK</button> <button autofocus name="v" type="submit" value="0">Cancel</button></p>');
    };
    setDialog.prompt = function(key, value) {
        value = value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return setDialog('<p>' + key + '</p><p><input autofocus type="text" value="' + value + '"></p><p role="group"><button name="v" type="submit" value="1">OK</button> <button name="v" type="submit" value="0">Cancel</button></p>');
    };
    setChildLast(B, dialog);
    W._.dialog = setDialog;
})();