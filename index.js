(function () {
    'use strict';

    function _arrayLikeToArray(r, a) {
        (null == a || a > r.length) && (a = r.length);
        for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e];
        return n;
    }

    function _arrayWithHoles(r) {
        if (Array.isArray(r)) return r;
    }

    function _iterableToArrayLimit(r, l) {
        var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"];
        if (null != t) {
            var e,
                n,
                i,
                u,
                a = [],
                f = true,
                o = false;
            try {
                if (i = (t = t.call(r)).next, 0 === l) {
                    if (Object(t) !== t) return;
                    f = !1;
                } else
                    for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0);
            } catch (r) {
                o = true, n = r;
            } finally {
                try {
                    if (!f && null != t.return && (u = t.return(), Object(u) !== u)) return;
                } finally {
                    if (o) throw n;
                }
            }
            return a;
        }
    }

    function _maybeArrayLike(r, a, e) {
        if (a && !Array.isArray(a) && "number" == typeof a.length) {
            var y = a.length;
            return _arrayLikeToArray(a, void 0 !== e && e < y ? e : y);
        }
        return r(a, e);
    }

    function _nonIterableRest() {
        throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }

    function _slicedToArray(r, e) {
        return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest();
    }

    function _unsupportedIterableToArray(r, a) {
        if (r) {
            if ("string" == typeof r) return _arrayLikeToArray(r, a);
            var t = {}.toString.call(r).slice(8, -1);
            return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0;
        }
    }
    var isArray = function isArray(x) {
        return Array.isArray(x);
    };
    var isBoolean = function isBoolean(x) {
        return false === x || true === x;
    };
    var isDefined = function isDefined(x) {
        return 'undefined' !== typeof x;
    };
    var isFloat = function isFloat(x) {
        return isNumber(x) && 0 !== x % 1;
    };
    var isFunction = function isFunction(x) {
        return 'function' === typeof x;
    };
    var isInstance = function isInstance(x, of, exact) {
        if (!x || 'object' !== typeof x) {
            return false;
        }
        if (exact) {
            return isSet(of) && isSet(x.constructor) && of === x.constructor;
        }
        return isSet(of) && x instanceof of ;
    };
    var isInteger = function isInteger(x) {
        return isNumber(x) && 0 === x % 1;
    };
    var isNull = function isNull(x) {
        return null === x;
    };
    var isNumber = function isNumber(x) {
        return 'number' === typeof x && !Number.isNaN(x);
    };
    var isNumeric = function isNumeric(x) {
        return /^[+-]?(?:\d*\.)?\d+$/.test(x + "");
    };
    var isObject = function isObject(x, isPlain) {
        if (isPlain === void 0) {
            isPlain = true;
        }
        if (!x || 'object' !== typeof x) {
            return false;
        }
        return isPlain ? isInstance(x, Object, 1) : true;
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
    var onEvent = function onEvent(name, node, then, options) {
        if (options === void 0) {
            options = false;
        }
        node.addEventListener(name, then, options);
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
            value = parseValue ? _toValue(value) : value;
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
    var _fromStates = function fromStates() {
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
                    out[k] = _fromStates({
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
    var _fromValue = function fromValue(x) {
        if (isArray(x)) {
            return x.map(function (v) {
                return _fromValue(x);
            });
        }
        if (isObject(x)) {
            for (var k in x) {
                x[k] = _fromValue(x[k]);
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
    var toMapCount = function toMapCount(x) {
        return x.size;
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
                out = _fromStates({}, out, _toQueryDeep(v, key + k + suffix + '%5B'));
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
            v = true !== v ? '=' + toURL(_fromValue(v)) : "";
            list.push(k + v);
        }
        return toCount(list) ? '?' + list.join('&') : null;
    };
    var toSetCount = function toSetCount(x) {
        return x.size;
    };
    var toString = function toString(x, base) {
        return isNumber(x) ? x.toString(base) : "" + x;
    };
    var toURL = function toURL(x) {
        return encodeURIComponent(x);
    };
    var _toValue = function toValue(x) {
        if (isArray(x)) {
            return x.map(function (v) {
                return _toValue(v);
            });
        }
        if (isObject(x)) {
            for (var k in x) {
                x[k] = _toValue(x[k]);
            }
            return x;
        }
        if (isString(x) && isNumeric(x)) {
            if ('0' === x[0] && -1 === x.indexOf('.')) {
                return x;
            }
            return toNumber(x);
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

    function _toIterator(v) {
        return v[Symbol.iterator]();
    }
    var forEachArray = function forEachArray(array, at) {
        for (var i = 0, j = toCount(array), v; i < j; ++i) {
            v = at.call(array, array[i], i);
            if (-1 === v) {
                array.splice(i, 1);
                continue;
            }
            if (0 === v) {
                break;
            }
            if (1 === v) {
                continue;
            }
        }
        return array;
    };
    var forEachMap = function forEachMap(map, at) {
        var items = _toIterator(map),
            item = items.next();
        while (!item.done) {
            var _item$value = _maybeArrayLike(_slicedToArray, item.value, 2),
                k = _item$value[0],
                v = _item$value[1];
            v = at.call(map, v, k);
            if (-1 === v) {
                letValueInMap(k, map);
            } else if (0 === v) {
                break;
            }
            item = items.next();
        }
        return map;
    };
    var forEachObject = function forEachObject(object, at) {
        var v;
        for (var k in object) {
            v = at.call(object, object[k], k);
            if (-1 === v) {
                delete object[k];
                continue;
            }
            if (0 === v) {
                break;
            }
            if (1 === v) {
                continue;
            }
        }
        return object;
    };
    var forEachSet = function forEachSet(set, at) {
        var items = _toIterator(set),
            item = items.next();
        while (!item.done) {
            var k = void 0,
                v = item.value;
            v = at.call(set, v, k = v);
            if (-1 === v) {
                letValueInMap(k, set);
            } else if (0 === v) {
                break;
            }
            item = items.next();
        }
        return set;
    };
    var getPrototype = function getPrototype(of) {
        return of.prototype;
    };
    var getReference$2 = function getReference(key) {
        return getValueInMap$1(key, references$2) || null;
    };
    var getValueInMap$1 = function getValueInMap(k, map) {
        return map.get(k);
    };
    var hasKeyInMap = function hasKeyInMap(k, map) {
        return map.has(k);
    };
    var letReference$1 = function letReference(k) {
        return letValueInMap(k, references$2);
    };
    var letValueInMap = function letValueInMap(k, map) {
        return map.delete(k);
    };
    var onAnimationsEnd = function onAnimationsEnd(node, task) {
        return isFunction(node.getAnimations) ? Promise.all(node.getAnimations().map(function (v) {
            return v.finished;
        })).then(task) : task(), node;
    };
    var setObjectAttributes = function setObjectAttributes(of, attributes, asStaticAttributes) {
        if (!asStaticAttributes) {
            of = getPrototype(of);
        }
        return forEachObject(attributes, function (v, k) {
            Object.defineProperty(of, k, v);
        }), of;
    };
    var setObjectMethods = function setObjectMethods(of, methods, asStaticMethods) {
        {
            of = getPrototype(of);
        }
        return forEachObject(methods, function (v, k) {
            of [k] = v;
        }), of;
    };
    var setReference$2 = function setReference(key, value) {
        return setValueInMap$1(key, value, references$2);
    };
    var setValueInMap$1 = function setValueInMap(k, v, map) {
        return map.set(k, v);
    };
    var toValueFirstFromMap = function toValueFirstFromMap(map) {
        return toValuesFromMap(map).shift();
    };
    var toValueLastFromMap = function toValueLastFromMap(map) {
        return toValuesFromMap(map).pop();
    };
    var toValuesFromMap = function toValuesFromMap(map) {
        var r = [];
        return forEachMap(map, function (v) {
            r.push(v);
        }), r;
    };
    var references$2 = new WeakMap();

    function _toArray$1(iterable) {
        return Array.from(iterable);
    }
    var D = document;
    var W = window;
    var B = D.body;
    var R = D.documentElement;
    var getAria = function getAria(node, aria, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        return getAttribute(node, 'aria-' + aria, parseValue);
    };
    var getAttribute = function getAttribute(node, attribute, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        if (!hasAttribute(node, attribute)) {
            return null;
        }
        var value = node.getAttribute(attribute);
        return parseValue ? _toValue(value) : value;
    };
    var getAttributes = function getAttributes(node, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        var attributes = node.attributes,
            values = {};
        forEachArray(attributes, function (v) {
            var name = v.name,
                value = v.value;
            values[name] = parseValue ? _toValue(value) : value;
        });
        return values;
    };
    var getChildFirst = function getChildFirst(parent, anyNode) {
        return parent['first' + (anyNode ? "" : 'Element') + 'Child'] || null;
    };
    var getChildLast = function getChildLast(parent, anyNode) {
        return parent['last' + (anyNode ? "" : 'Element') + 'Child'] || null;
    };
    var getChildren = function getChildren(parent, index, anyNode) {
        var children = _toArray$1(parent['child' + (anyNode ? 'Nodes' : 'ren')]);
        return isNumber(index) ? children[index] || null : children;
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
    var getElementIndex = function getElementIndex(node, anyNode) {
        if (!node || !getParent(node)) {
            return -1;
        }
        var index = 0;
        while (node = getPrev(node, anyNode)) {
            ++index;
        }
        return index;
    };
    var getElements = function getElements(query, scope) {
        return _toArray$1((scope || D).querySelectorAll(query));
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
    var getID = function getID(node, batch) {
        if (batch === void 0) {
            batch = 'e:';
        }
        if (hasID(node)) {
            return getAttribute(node, 'id');
        }
        if (!isSet(theID[batch])) {
            theID[batch] = 0;
        }
        return batch + toString(Date.now() + (theID[batch] += 1), 16);
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
    var getRole = function getRole(node) {
        return getAttribute(node, 'role');
    };
    var getStyle = function getStyle(node, style, parseValue) {
        var value = W.getComputedStyle(node).getPropertyValue(style);
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
    var getType = function getType(node) {
        return node && node.nodeType || null;
    };
    var getValue$1 = function getValue(node, parseValue) {
        var value = (node.value || "").replace(/\r?\n|\r/g, '\n');
        value = parseValue ? _toValue(value) : value;
        return "" !== value ? value : null;
    };
    var hasAttribute = function hasAttribute(node, attribute) {
        return node.hasAttribute(attribute);
    };
    var hasClass = function hasClass(node, value) {
        return node.classList.contains(value);
    };
    var hasID = function hasID(node) {
        return hasAttribute(node, 'id');
    };
    var hasState = function hasState(node, state) {
        return state in node;
    };
    var isDisabled$1 = function isDisabled(node) {
        return node.disabled;
    };
    var isNode = function isNode(node) {
        return isInstance(node, Node);
    };
    var isReadOnly$1 = function isReadOnly(node) {
        return node.readOnly;
    };
    var isRequired = function isRequired(node) {
        return node.required;
    };
    var isWindow = function isWindow(node) {
        return node === W;
    };
    var letAria = function letAria(node, aria) {
        return letAttribute(node, 'aria-' + aria);
    };
    var letAttribute = function letAttribute(node, attribute) {
        return node.removeAttribute(attribute), node;
    };
    var letClass = function letClass(node, value) {
        return node.classList.remove(value), node;
    };
    var letClasses = function letClasses(node, classes) {
        if (isArray(classes)) {
            return forEachArray(classes, function (k) {
                return letClass(node, k);
            }), node;
        }
        if (isObject(classes)) {
            return forEachObject(function (classes) {
                return function (v, k) {
                    return v && letClass(node, k);
                };
            }), node;
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
    var letHTML = function letHTML(node) {
        var state = 'innerHTML';
        return hasState(node, state) && (node[state] = ""), node;
    };
    var letID = function letID(node) {
        return letAttribute(node, 'id');
    };
    var letStyle = function letStyle(node, style) {
        return node.style[toCaseCamel(style)] = null, node;
    };
    var setAria = function setAria(node, aria, value) {
        return setAttribute(node, 'aria-' + aria, true === value ? 'true' : value);
    };
    var setArias = function setArias(node, data) {
        return forEachObject(data, function (v, k) {
            v || "" === v || 0 === v ? setAria(node, k, v) : letAria(node, k);
        }), node;
    };
    var setAttribute = function setAttribute(node, attribute, value) {
        if (true === value) {
            value = attribute;
        }
        return node.setAttribute(attribute, _fromValue(value)), node;
    };
    var setAttributes = function setAttributes(node, attributes) {
        return forEachObject(attributes, function (v, k) {
            if ('aria' === k && isObject(v)) {
                return setArias(node, v), 1;
            }
            if ('class' === k) {
                return setClasses(node, v), 1;
            }
            if ('data' === k && isObject(v)) {
                return setData(node, v), 1;
            }
            if ('style' === k && isObject(v)) {
                return setStyles(node, v), 1;
            }
            v || "" === v || 0 === v ? setAttribute(node, k, v) : letAttribute(node, k);
        }), node;
    };
    var setChildLast = function setChildLast(parent, node) {
        return parent.append(node), node;
    };
    var setClass = function setClass(node, value) {
        return node.classList.add(value), node;
    };
    var setClasses = function setClasses(node, classes) {
        if (isArray(classes)) {
            return forEachArray(classes, function (k) {
                return setClass(node, k);
            }), node;
        }
        if (isObject(classes)) {
            return forEachObject(classes, function (v, k) {
                return v ? setClass(node, k) : letClass(node, k);
            }), node;
        }
        return node.className = classes, node;
    };
    var setData = function setData(node, data) {
        return forEachObject(data, function (v, k) {
            v || "" === v || 0 === v ? setDatum(node, k, v) : letDatum(node, k);
        }), node;
    };
    var setDatum = function setDatum(node, datum, value) {
        if (isArray(value) || isObject(value)) {
            value = toJSON(value);
        }
        return setAttribute(node, 'data-' + datum, true === value ? 'true' : value);
    };
    var setElement = function setElement(node, content, attributes, options) {
        node = isString(node) ? D.createElement(node, isString(options) ? {
            is: options
        } : options) : node;
        if (isArray(content) && toCount(content)) {
            letHTML(node);
            forEachArray(content, function (v) {
                return setChildLast(isString(v) ? setElementText(v) : v);
            });
        } else if (isObject(content)) {
            attributes = content;
            content = false;
        }
        if (isString(content)) {
            setHTML(node, content);
        }
        if (isObject(attributes)) {
            return setAttributes(node, attributes), node;
        }
        return node;
    };
    var setElementText = function setElementText(text) {
        return isString(text) ? text = D.createTextNode(text) : text, text;
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
    var setID = function setID(node, value, batch) {
        if (batch === void 0) {
            batch = 'e:';
        }
        return setAttribute(node, 'id', isSet(value) ? value : getID(node, batch));
    };
    var setNext = function setNext(current, node) {
        return current.after(node), node;
    };
    var setPrev = function setPrev(current, node) {
        return current.before(node), node;
    };
    var setStyle = function setStyle(node, style, value) {
        if (isNumber(value)) {
            value += 'px';
        }
        return node.style[toCaseCamel(style)] = _fromValue(value), node;
    };
    var setStyles = function setStyles(node, styles) {
        return forEachObject(styles, function (v, k) {
            v || "" === v || 0 === v ? setStyle(node, k, v) : letStyle(node, k);
        }), node;
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
    var setValue$1 = function setValue(node, value) {
        if (null === value) {
            return letAttribute(node, 'value');
        }
        return node.value = _fromValue(value), node;
    };
    var toggleClass = function toggleClass(node, name, force) {
        return node.classList.toggle(name, force), node;
    };
    var theHistory = W.history;
    var theID = {};
    var theLocation = W.location;
    var targets$8 = ':scope>:where([tabindex]):not([tabindex="-1"]):not(.not\\:active)';

    function onChange$c(init) {
        var sources = getElements('.lot\\:bar[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var items = getElements(targets$8, source);
            items.forEach(function (item) {
                onEventOnly('keydown', item, onKeyDownBarItem);
            });
            onEventOnly('keydown', source, onKeyDownBar);
        });
        1 === init && W._.on('change', onChange$c);
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
        t.x(_toValue(t.returnValue));
        isFunction(t.c) && t.c.apply(t, [t.open]);
    }

    function onDialogClose(e) {
        var t = this;
        offEvent('cancel', t, onDialogCancel);
        offEvent('close', t, onDialogClose);
        offEvent('submit', t, onDialogSubmit);
        t.v(_toValue(t.returnValue));
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
    var now = Date.now;
    var history = new WeakMap();
    var historyIndex = new WeakMap();
    var _getSelection = function _getSelection() {
        return D.getSelection();
    };
    var _setRange = function _setRange() {
        return D.createRange();
    };
    var getCharBeforeCaret = function getCharBeforeCaret(node, n, selection) {
        selection = selection || _getSelection();
        if (!hasSelection(node, selection)) {
            return null;
        }
        var range = selection.getRangeAt(0).cloneRange();
        range.collapse(true);
        range.setStart(node, 0);
        return (range + "").slice(-1);
    };
    // The `node` parameter is currently not in use
    var hasSelection = function hasSelection(node, selection) {
        return (selection || _getSelection()).rangeCount > 0;
    };
    // <https://stackoverflow.com/a/6691294/1163000>
    // The `node` parameter is currently not in use
    var insertAtSelection = function insertAtSelection(node, content, mode, selection) {
        selection = selection || _getSelection();
        var from, range, to;
        if (!hasSelection(node, selection)) {
            return false;
        }
        range = selection.getRangeAt(0);
        range.deleteContents();
        to = D.createDocumentFragment();
        var nodeCurrent, nodeFirst, nodeLast;
        if (isString(content)) {
            from = setElement('div');
            setHTML(from, content);
            while (nodeCurrent = getChildFirst(from, 1)) {
                nodeLast = setChildLast(to, nodeCurrent);
            }
        } else if (isArray(content)) {
            forEachArray(content, function (v) {
                return nodeLast = setChildLast(to, v);
            });
        } else {
            nodeLast = setChildLast(to, content);
        }
        nodeFirst = getChildFirst(to, 1);
        range.insertNode(to);
        if (nodeLast) {
            range = range.cloneRange();
            range.setStartAfter(nodeLast);
            range.setStartBefore(nodeFirst);
            {
                range.collapse();
            }
            setSelection(node, range, selectToNone(node, selection));
        }
        return selection;
    };
    // The `node` parameter is currently not in use
    var letSelection = function letSelection(node, selection) {
        selection = selection || _getSelection();
        return selection.empty(), selection;
    };
    var redoState = function redoState(node, selection) {
        var _getValueInMap, _getValueInMap2;
        var h = (_getValueInMap = getValueInMap$1(node, history)) != null ? _getValueInMap : [],
            i = (_getValueInMap2 = getValueInMap$1(node, historyIndex)) != null ? _getValueInMap2 : toCount(h) - 1,
            j;
        if (!(j = h[i + 1])) {
            return restoreSelection(node, h[i][1], selection);
        }
        i++;
        setValueInMap$1(node, i, historyIndex);
        return setHTML(node, j[0]), restoreSelection(node, j[1], selection);
    };
    var resetState = function resetState(node, selection) {
        letValueInMap(node, history);
        letValueInMap(node, historyIndex);
        return saveState(node, selection);
    };
    // <https://stackoverflow.com/a/13950376/1163000>
    var restoreSelection = function restoreSelection(node, store, selection) {
        var index = 0,
            range = _setRange();
        range.setStart(node, 0);
        range.collapse(true);
        var exit,
            hasStart,
            nodeCurrent,
            nodeStack = [node];
        while (!exit && (nodeCurrent = nodeStack.pop())) {
            if (3 === getType(nodeCurrent)) {
                var indexNext = index + toCount(nodeCurrent);
                if (!hasStart && store[0] >= index && store[0] <= indexNext) {
                    range.setStart(nodeCurrent, store[0] - index);
                    hasStart = true;
                }
                if (hasStart && store[1] >= index && store[1] <= indexNext) {
                    exit = true;
                    range.setEnd(nodeCurrent, store[1] - index);
                }
                index = indexNext;
            } else {
                forEachArray(getChildren(nodeCurrent, null, 1), function (v) {
                    return nodeStack.push(v);
                });
            }
        }
        return setSelection(node, range, letSelection(node, selection));
    };
    // <https://stackoverflow.com/a/13950376/1163000>
    var saveSelection = function saveSelection(node, selection) {
        var range = (_getSelection()).getRangeAt(0),
            rangeClone = range.cloneRange();
        rangeClone.selectNodeContents(node);
        rangeClone.setEnd(range.startContainer, range.startOffset);
        var start = toCount(rangeClone + "");
        return [start, start + toCount(range + "")];
    };
    var saveState = function saveState(node, selection) {
        var _getValueInMap3, _getValueInMap4, _getHTML;
        var h = (_getValueInMap3 = getValueInMap$1(node, history)) != null ? _getValueInMap3 : [],
            i = (_getValueInMap4 = getValueInMap$1(node, historyIndex)) != null ? _getValueInMap4 : toCount(h) - 1,
            j,
            v = (_getHTML = getHTML(node)) != null ? _getHTML : "";
        j = hasSelection(node, selection) ? saveSelection(node) : [];
        if (h[i] && v === h[i][0] && j[0] === h[i][1][0] && j[1] === h[i][1][1]) {
            return node; // No change
        }
        // Trim future history if `undoState()` was used
        if (i < toCount(h) - 1) {
            h.splice(i + 1);
        }
        h.push([v, j, now()]);
        setValueInMap$1(node, h, history);
        setValueInMap$1(node, ++i, historyIndex);
        return node;
    };
    var selectTo = function selectTo(node, mode, selection) {
        selection = selection || _getSelection();
        letSelection(node, selection);
        var range = _setRange();
        range.selectNodeContents(node);
        selection = setSelection(node, range, selection);
        if (1 === mode) {
            selection.collapseToEnd();
        } else if (-1 === mode) {
            selection.collapseToStart();
        } else;
    };
    // The `node` parameter is currently not in use
    var selectToNone = function selectToNone(node, selection) {
        selection = selection || _getSelection();
        // selection.removeAllRanges();
        if (selection.rangeCount) {
            selection.removeRange(selection.getRangeAt(0));
        }
        return selection;
    };
    // The `node` parameter is currently not in use
    var setSelection = function setSelection(node, range, selection) {
        selection = selection || _getSelection();
        if (isArray(range)) {
            return restoreSelection(node, range, selection);
        }
        return selection.addRange(range), selection;
    };
    var undoState = function undoState(node, selection) {
        var _getValueInMap5, _getValueInMap6;
        var h = (_getValueInMap5 = getValueInMap$1(node, history)) != null ? _getValueInMap5 : [],
            i = (_getValueInMap6 = getValueInMap$1(node, historyIndex)) != null ? _getValueInMap6 : toCount(h) - 1,
            j;
        if (!(j = h[i - 1])) {
            return restoreSelection(node, h[i][1], selection);
        }
        i--;
        setValueInMap$1(node, i, historyIndex);
        return setHTML(node, j[0]), restoreSelection(node, j[1], selection);
    };

    function _toArray(iterable) {
        return Array.from(iterable);
    }
    var clearTimeout = W.clearTimeout,
        setTimeout = W.setTimeout; // For better minification
    var debounce = function debounce(task, time) {
        var stickyTime = isInteger(time) && time >= 0,
            timer;
        return [function () {
            var _this = this;
            timer && clearTimeout(timer);
            var lot = _toArray(arguments);
            if (!stickyTime) {
                time = lot.shift();
            }
            timer = setTimeout(function () {
                return task.apply(_this, lot);
            }, time);
        }, function () {
            timer = clearTimeout(timer);
        }];
    };
    var delay = function delay(task, time) {
        var stickyTime = isInteger(time) && time >= 0,
            timer;
        return [function () {
            var _this2 = this;
            var lot = _toArray(arguments);
            if (!stickyTime) {
                time = lot.shift();
            }
            timer = setTimeout(function () {
                return task.apply(_this2, lot);
            }, time);
        }, function () {
            timer && clearTimeout(timer);
        }];
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
            return forEachArray(hooks[event], function (v) {
                v.apply(that || $, data);
            }), $;
        };
        $$.off = function (event, task) {
            var $ = this,
                hooks = $.hooks;
            if (!isSet(event)) {
                return hooks = {}, $;
            }
            if (isSet(hooks[event])) {
                if (isSet(task)) {
                    var j = toCount(hooks[event]);
                    // Clean-up empty hook(s)
                    if (0 === j) {
                        delete hooks[event];
                    } else {
                        for (var i = 0; i < j; ++i) {
                            if (task === hooks[event][i]) {
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
        $$.on = function (event, task) {
            var $ = this,
                hooks = $.hooks;
            if (!isSet(hooks[event])) {
                hooks[event] = [];
            }
            if (isSet(task)) {
                hooks[event].push(task);
            }
            return $;
        };
        return $.hooks = {}, $;
    }
    var esc = function esc(pattern, extra) {
        if (extra === void 0) {
            extra = "";
        }
        return pattern.replace(toPattern('[' + extra + x$1.replace(/./g, '\\$&') + ']'), '\\$&');
    };
    var isPattern = function isPattern(pattern) {
        return isInstance(pattern, RegExp);
    };
    var toPattern = function toPattern(pattern, opt) {
        if (isPattern(pattern)) {
            return pattern;
        }
        return new RegExp(pattern, isSet(opt) ? opt : 'g');
    };
    var x$1 = "!$^*()+=[]{}|:<>,.?/-";
    var EVENT_DOWN$2 = 'down';
    var EVENT_MOVE$1 = 'move';
    var EVENT_UP$2 = 'up';
    var EVENT_BLUR$1 = 'blur';
    var EVENT_CUT$1 = 'cut';
    var EVENT_FOCUS$1 = 'focus';
    var EVENT_INPUT$1 = 'input';
    var EVENT_INVALID$1 = 'invalid';
    var EVENT_KEY$1 = 'key';
    var EVENT_KEY_DOWN$1 = EVENT_KEY$1 + EVENT_DOWN$2;
    var EVENT_MOUSE$2 = 'mouse';
    var EVENT_MOUSE_DOWN$2 = EVENT_MOUSE$2 + EVENT_DOWN$2;
    var EVENT_MOUSE_MOVE$1 = EVENT_MOUSE$2 + EVENT_MOVE$1;
    var EVENT_MOUSE_UP$1 = EVENT_MOUSE$2 + EVENT_UP$2;
    var EVENT_PASTE$1 = 'paste';
    var EVENT_RESET$1 = 'reset';
    var EVENT_RESIZE = 'resize';
    var EVENT_SCROLL = 'scroll';
    var EVENT_SUBMIT$1 = 'submit';
    var EVENT_TOUCH$2 = 'touch';
    var EVENT_TOUCH_END$1 = EVENT_TOUCH$2 + 'end';
    var EVENT_TOUCH_MOVE$1 = EVENT_TOUCH$2 + EVENT_MOVE$1;
    var EVENT_TOUCH_START$2 = EVENT_TOUCH$2 + 'start';
    var EVENT_WHEEL = 'wheel';
    var KEY_DOWN = 'Down';
    var KEY_LEFT$1 = 'Left';
    var KEY_RIGHT$1 = 'Right';
    var KEY_UP = 'Up';
    var KEY_ARROW$1 = 'Arrow';
    var KEY_ARROW_DOWN$1 = KEY_ARROW$1 + KEY_DOWN;
    var KEY_ARROW_LEFT$2 = KEY_ARROW$1 + KEY_LEFT$1;
    var KEY_ARROW_RIGHT$2 = KEY_ARROW$1 + KEY_RIGHT$1;
    var KEY_ARROW_UP$1 = KEY_ARROW$1 + KEY_UP;
    var KEY_BEGIN$1 = 'Home';
    var KEY_DELETE_LEFT$2 = 'Backspace';
    var KEY_DELETE_RIGHT$2 = 'Delete';
    var KEY_END$1 = 'End';
    var KEY_ENTER$2 = 'Enter';
    var KEY_ESCAPE$1 = 'Escape';
    var KEY_PAGE = 'Page';
    var KEY_PAGE_DOWN = KEY_PAGE + KEY_DOWN;
    var KEY_PAGE_UP = KEY_PAGE + KEY_UP;
    var KEY_TAB$1 = 'Tab';
    var KEY_Y$1 = 'y';
    var KEY_Z$1 = 'z';
    var OPTION_SELF = 0;
    var OPTION_TEXT = 1;
    var TOKEN_CONTENTEDITABLE$1 = 'contenteditable';
    var TOKEN_DISABLED$1 = 'disabled';
    var TOKEN_FALSE$1 = 'false';
    var TOKEN_GROUP = 'group';
    var TOKEN_INVALID$1 = 'invalid';
    var TOKEN_OPTGROUP = 'opt' + TOKEN_GROUP;
    var TOKEN_READONLY$1 = 'readonly';
    var TOKEN_READ_ONLY$1 = 'readOnly';
    var TOKEN_REQUIRED$1 = 'required';
    var TOKEN_SELECTED = 'selected';
    var TOKEN_TABINDEX$1 = 'tabindex';
    var TOKEN_TAB_INDEX$1 = 'tabIndex';
    var TOKEN_TEXT = 'text';
    var TOKEN_TRUE$1 = 'true';
    var TOKEN_VALUE$1 = 'value';
    var TOKEN_VALUES$1 = TOKEN_VALUE$1 + 's';
    var TOKEN_VISIBILITY$1 = 'visibility';
    var VALUE_SELF = 0;
    var VALUE_TEXT = 1;
    var VALUE_X = 2;
    var filter = debounce(function ($, input, _options, selectOnly) {
        var query = isString(input) ? input : getText(input) || "",
            q = toCaseLower(query),
            _mask = $._mask,
            mask = $.mask,
            self = $.self,
            state = $.state,
            options = _mask.options,
            pattern = self.pattern,
            strict = state.strict,
            option;
        var count = _options.count();
        if (selectOnly) {
            forEachMap(_options, function (v) {
                if ("" !== q && (q === toCaseLower(getText(v[2]).replace(/\u200C/g, "")).slice(0, toCount(q)) || q === toCaseLower(getOptionValue(v[2])).slice(0, toCount(q))) && !getAria(v[2], TOKEN_DISABLED$1)) {
                    selectToOption(v[2], $);
                    return 0;
                }
                --count;
            });
        } else {
            forEachMap(_options, function (v) {
                if ("" === q || hasValue(q, toCaseLower(getText(v[2]).replace(/\u200C/g, "") + '\t' + getOptionValue(v[2])))) {
                    v[2].hidden = false;
                } else {
                    v[2].hidden = true;
                    --count;
                }
            });
            options.hidden = !count;
            selectToOptionsNone($);
            if (strict) {
                // Silently select the first option without affecting the currently typed query and focus/select state
                if (count && "" !== q && (option = goToOptionFirst($))) {
                    letAria(mask, TOKEN_INVALID$1);
                    setAria(option, TOKEN_SELECTED, true);
                    option.$[OPTION_SELF][TOKEN_SELECTED] = true;
                    setValue$1(self, getOptionValue(option));
                } else {
                    // No other option(s) are available to query
                    if ("" !== q) {
                        setAria(mask, TOKEN_INVALID$1, true);
                    } else {
                        letAria(mask, TOKEN_INVALID$1);
                    }
                    setValue$1(self, "");
                }
            } else {
                letAria(mask, TOKEN_INVALID$1);
                setValue$1(self, query);
                if (pattern) {
                    if (!count && "" !== q && !toPattern('^' + pattern + '$', "").test(query)) {
                        setAria(mask, TOKEN_INVALID$1, true);
                    }
                }
            }
        }
        $.fire('search', [query = "" !== query ? query : null]);
        var call = state.options;
        // Only fetch when no other option(s) are available to query, or when the current search query is empty
        if ((0 === count || "" === q) && isFunction(call)) {
            setAria(mask, 'busy', true);
            call = call.call($, query);
            if (isInstance(call, Promise)) {
                call.then(function (v) {
                    createOptions($, v);
                    letAria(mask, 'busy');
                    $.fire('load', [query, $[TOKEN_VALUES$1]])[goToOptionFirst($) ? 'enter' : 'exit']().fit();
                });
            } else {
                createOptions($, call);
            }
        }
    })[0];
    var _delay$1 = delay(function (picker) {
            letAria(picker.mask, TOKEN_INVALID$1);
        }),
        _delay2$1 = _maybeArrayLike(_slicedToArray, _delay$1, 2),
        letError$1 = _delay2$1[0],
        letErrorAbort$1 = _delay2$1[1];
    var setError$1 = function setError(picker) {
        var mask = picker.mask,
            state = picker.state,
            time = state.time,
            error = time.error;
        if (isInteger(error) && error > 0) {
            setAria(mask, TOKEN_INVALID$1, true);
        }
    };
    var _delay3$1 = delay(function ($) {
            saveState($);
        }, 1),
        _delay4$1 = _maybeArrayLike(_slicedToArray, _delay3$1, 1),
        saveStateLazy$1 = _delay4$1[0];
    var _delay5$1 = delay(function (picker) {
            var _mask = picker._mask,
                input = _mask.input;
            toggleHintByValue$1(picker, getText(input, 0));
        }),
        _delay6$1 = _maybeArrayLike(_slicedToArray, _delay5$1, 1),
        toggleHint$1 = _delay6$1[0];
    var toggleHintByValue$1 = function toggleHintByValue(picker, value) {
        var _mask = picker._mask,
            hint = _mask.hint;
        value ? setStyle(hint, TOKEN_VISIBILITY$1, 'hidden') : letStyle(hint, TOKEN_VISIBILITY$1);
    };
    var name$5 = 'OptionPicker';

    function createOptions($, options) {
        var map = isInstance(options, Map) ? options : new Map();
        if (isArray(options)) {
            forEachArray(options, function (option) {
                if (isArray(option)) {
                    var _option$, _option$2, _option$1$TOKEN_VALUE;
                    option[0] = (_option$ = option[0]) != null ? _option$ : "";
                    option[1] = (_option$2 = option[1]) != null ? _option$2 : {};
                    setValueInMap$1(_toValue((_option$1$TOKEN_VALUE = option[1][TOKEN_VALUE$1]) != null ? _option$1$TOKEN_VALUE : option[0]), option, map);
                } else {
                    setValueInMap$1(_toValue(option), [option, {}], map);
                }
            });
        } else if (isObject(options, 0)) {
            forEachObject(options, function (v, k) {
                if (isArray(v)) {
                    var _v$, _v$2, _v$1$TOKEN_VALUE;
                    options[k][0] = (_v$ = v[0]) != null ? _v$ : "";
                    options[k][1] = (_v$2 = v[1]) != null ? _v$2 : {};
                    setValueInMap$1(_toValue((_v$1$TOKEN_VALUE = v[1][TOKEN_VALUE$1]) != null ? _v$1$TOKEN_VALUE : k), v, map);
                } else {
                    setValueInMap$1(_toValue(k), [v, {}], map);
                }
            });
        }
        var _options = $._options,
            self = $.self,
            state = $.state;
        state.n;
        var r = [],
            value = getValue$1(self);
        // Reset the option(s) data, but leave the typed query in place, and do not fire the `let.options` hook
        _options.let(null, 0, 0);
        forEachMap(map, function (v, k) {
            var _v$1$TOKEN_VALUE3;
            if (isArray(v) && v[1] && (!getState(v[1], 'active') || v[1].active) && v[1].mark) {
                var _v$1$TOKEN_VALUE2;
                r.push((_v$1$TOKEN_VALUE2 = v[1][TOKEN_VALUE$1]) != null ? _v$1$TOKEN_VALUE2 : k);
            }
            // Set the option data, but do not fire the `set.option` hook
            _options.set(_toValue(isArray(v) && v[1] ? (_v$1$TOKEN_VALUE3 = v[1][TOKEN_VALUE$1]) != null ? _v$1$TOKEN_VALUE3 : k : k), v, 0);
        });
        if (!isFunction(state.options)) {
            state.options = map;
        }
        if (0 === toCount(r)) {
            // If there is no selected option(s), get it from the current value
            if (hasKeyInMap(_toValue(value), map)) {
                return [value];
            }
            // Or get it from the first option
            if (value = getOptionSelected($)) {
                return [getOptionValue(value)];
            }
        }
        return r;
    }

    function focusTo$1(node) {
        return node.focus(), node;
    }

    function focusToOption(option, picker) {
        if (option) {
            return focusTo$1(option), option;
        }
    }

    function focusToOptionFirst(picker, k) {
        var option;
        if (option = goToOptionFirst(picker, k)) {
            return focusToOption(option);
        }
    }

    function focusToOptionLast(picker) {
        return focusToOptionFirst(picker, 'Last');
    }

    function getOptionNext(option) {
        var optionNext = getNext(option),
            optionParent;
        // Skip disabled and hidden option(s)…
        while (optionNext && (getAria(optionNext, TOKEN_DISABLED$1) || optionNext.hidden)) {
            optionNext = getNext(optionNext);
        }
        if (optionNext) {
            // Next option is a group?
            if (TOKEN_GROUP === getRole(optionNext)) {
                optionNext = getChildFirst(optionNext);
            }
            // Is the last option?
        } else {
            // Is in a group?
            if ((optionParent = getParent(option)) && TOKEN_GROUP === getRole(optionParent)) {
                optionNext = getNext(optionParent);
            }
            // Next option is a group?
            if (optionNext && TOKEN_GROUP === getRole(optionNext)) {
                optionNext = getChildFirst(optionNext);
            }
        }
        // Skip disabled and hidden option(s)…
        while (optionNext && (getAria(optionNext, TOKEN_DISABLED$1) || optionNext.hidden)) {
            optionNext = getNext(optionNext);
        }
        return optionNext;
    }

    function getOptionPrev(option) {
        var optionParent,
            optionPrev = getPrev(option);
        // Skip disabled and hidden option(s)…
        while (optionPrev && (getAria(optionPrev, TOKEN_DISABLED$1) || optionPrev.hidden)) {
            optionPrev = getPrev(optionPrev);
        }
        if (optionPrev) {
            // Previous option is a group?
            if (TOKEN_GROUP === getRole(optionPrev)) {
                optionPrev = getChildLast(optionPrev);
            }
            // Is the first option?
        } else {
            // Is in a group?
            if ((optionParent = getParent(option)) && TOKEN_GROUP === getRole(optionParent)) {
                optionPrev = getPrev(optionParent);
            }
            // Previous option is a group?
            if (optionPrev && TOKEN_GROUP === getRole(optionPrev)) {
                optionPrev = getChildLast(optionPrev);
            }
        }
        // Skip disabled and hidden option(s)…
        while (optionPrev && (getAria(optionPrev, TOKEN_DISABLED$1) || optionPrev.hidden)) {
            optionPrev = getPrev(optionPrev);
        }
        return optionPrev;
    }

    function getOptionSelected($, strict) {
        var _options = $._options,
            self = $.self,
            selected;
        forEachMap(_options, function (v, k) {
            if (isArray(v) && v[2] && !getAria(v[2], TOKEN_DISABLED$1) && getAria(v[2], TOKEN_SELECTED)) {
                return selected = v[2], 0;
            }
        });
        if (!isSet(selected) && (strict || !isInput(self))) {
            // Select the first option
            forEachMap(_options, function (v, k) {
                return selected = v[2], 0;
            });
        }
        return selected;
    }

    function getOptionValue(option, parseValue) {
        return getValue$1(option, parseValue);
    }

    function getOptions(self) {
        var map = new Map();
        var item,
            items,
            itemsParent,
            selected = [],
            value = getValue$1(self);
        if (isInput(self)) {
            items = (itemsParent = self.list) ? getChildren(itemsParent) : [];
        } else {
            items = getChildren(itemsParent = self);
        }
        forEachArray(items, function (v, k) {
            var attributes = getAttributes(v);
            attributes.active = true;
            attributes.mark = false;
            if (hasState(attributes, TOKEN_DISABLED$1)) {
                attributes.active = "" === attributes[TOKEN_DISABLED$1] ? false : !!attributes[TOKEN_DISABLED$1];
                delete attributes[TOKEN_DISABLED$1];
            } else if (hasState(attributes, TOKEN_SELECTED)) {
                attributes.mark = "" === attributes[TOKEN_SELECTED] ? true : !!attributes[TOKEN_SELECTED];
                delete attributes[TOKEN_SELECTED];
            }
            if (TOKEN_OPTGROUP === getName(v)) {
                forEachMap(getOptions(v), function (vv, kk) {
                    vv[1]['&'] = v.label;
                    setValueInMap$1(_toValue(kk), vv, map);
                });
            } else {
                setValueInMap$1(_toValue(v[TOKEN_VALUE$1]), [getText(v) || v[TOKEN_VALUE$1], attributes, null, v], map);
            }
        });
        // If there is no selected option(s), get it from the current value
        if (0 === toCount(selected) && (item = getValueInMap$1(value = _toValue(value), map))) {
            item[1].mark = true;
            setValueInMap$1(value, item, map);
        }
        return map;
    }

    function getOptionsValues(options, parseValue) {
        return options.map(function (v) {
            return getOptionValue(v, parseValue);
        });
    }

    function getOptionsSelected($) {
        var _options = $._options,
            selected = [];
        return forEachMap(_options, function (v, k) {
            if (isArray(v) && v[2] && !getAria(v[2], TOKEN_DISABLED$1) && getAria(v[2], TOKEN_SELECTED)) {
                selected.push(v[2]);
            }
        }), selected;
    }

    function goToOptionFirst(picker, k) {
        var _options = picker._options,
            option;
        if (option = toValuesFromMap(_options)['find' + (k || "")](function (v) {
                return !getAria(v[2], TOKEN_DISABLED$1) && !v[2].hidden;
            })) {
            return option[2];
        }
    }

    function goToOptionLast(picker) {
        return goToOptionFirst(picker, 'Last');
    }

    function isInput(self) {
        return 'input' === getName(self);
    }

    function onBlurTextInput$1() {
        var $ = this,
            picker = getReference$2($),
            _mask = picker._mask,
            mask = picker.mask,
            state = picker.state,
            options = _mask.options,
            strict = state.strict,
            time = state.time,
            error = time.error,
            option;
        onEvent(EVENT_MOUSE_DOWN$2, mask, onPointerDownMask$1);
        onEvent(EVENT_TOUCH_START$2, mask, onPointerDownMask$1);
        if (strict) {
            if (!options.hidden && (option = getOptionSelected(picker, 1))) {
                selectToOption(option, picker);
            } else {
                letError$1(isInteger(error) && error > 0 ? error : 0, picker);
                options.hidden = false;
                selectToOptionsNone(picker, 1);
            }
        }
    }

    function onCutTextInput$1() {
        var $ = this,
            picker = getReference$2($),
            self = picker.self,
            state = picker.state,
            strict = state.strict;
        delay(function () {
            if (!strict) {
                setValue$1(self, getText($));
            }
        })[0](1);
        saveState($), toggleHint$1(1, picker), saveStateLazy$1($);
    }

    function onFocusOption() {
        selectToNone();
    }
    // Focus on the “visually hidden” self will move its focus to the mask, maintains the natural flow of the tab(s)!
    function onFocusSelf$1() {
        focusTo$1(getReference$2(this));
    }

    function onFocusTextInput$1() {
        letErrorAbort$1();
        var $ = this,
            picker = getReference$2($),
            mask = picker.mask,
            options = picker.options;
        if (options.open) {
            offEvent(EVENT_MOUSE_DOWN$2, mask, onPointerDownMask$1);
            offEvent(EVENT_TOUCH_START$2, mask, onPointerDownMask$1);
            return;
        }
        getText($, 0) ? selectTo($) : picker.enter().fit();
    }

    function onInvalidSelf$1(e) {
        e && offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            state = picker.state,
            time = state.time,
            error = time.error;
        letError$1(isInteger(error) && error > 0 ? error : 0, picker), setError$1(picker);
    }
    var searchQuery = "";

    function onInputTextInput$1(e) {
        var $ = this,
            inputType = e.inputType,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix;
        if (!_active || _fix) {
            return offEventDefault(e);
        }
        if ('deleteContent' === inputType.slice(0, 13) && !getText($, 0)) {
            toggleHintByValue$1(picker, 0), saveStateLazy$1($);
        } else if ('insertText' === inputType) {
            toggleHintByValue$1(picker, 1), saveStateLazy$1($);
        }
    }

    function onKeyDownArrow(e) {
        var $ = this,
            picker = getReference$2($),
            options = picker.options,
            key = e.key,
            exit;
        if (KEY_ENTER$2 === key || ' ' === key) {
            picker[options.open ? 'exit' : 'enter'](!(exit = true)).fit();
        } else if (KEY_ESCAPE$1 === key) {
            picker.exit(exit = true);
        } else if (KEY_ARROW_DOWN$1 === key || KEY_ARROW_UP$1 === key || KEY_TAB$1 === key) {
            picker.enter(exit = true);
        }
        exit && offEventDefault(e);
    }

    function onKeyDownTextInput$1(e) {
        var $ = this,
            exit,
            key = e.key,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix;
        if (!_active || _fix) {
            return;
        }
        var _options = picker._options,
            mask = picker.mask,
            self = picker.self,
            state = picker.state,
            strict = state.strict,
            time = state.time,
            error = time.error,
            search = time.search;
        if (KEY_DELETE_LEFT$2 === key || KEY_DELETE_RIGHT$2 === key || 1 === toCount(key) && !keyIsCtrl) {
            picker.enter().fit();
            searchQuery = 0; // This will make a difference and force the filter to execute
        }
        if (KEY_ARROW_DOWN$1 === key || KEY_ARROW_UP$1 === key || KEY_ENTER$2 === key) {
            var currentOption = _options.at(getValue$1(self));
            currentOption = currentOption ? currentOption[2] : 0;
            if (!currentOption || currentOption.hidden) {
                currentOption = toValueFirstFromMap(_options);
                currentOption = currentOption ? currentOption[2] : 0;
                while (currentOption && (getAria(currentOption, TOKEN_DISABLED$1) || currentOption.hidden)) {
                    currentOption = getNext(currentOption);
                }
            }
            exit = true;
            if (!getAria(mask, 'expanded')) {
                picker.enter(false).fit();
                currentOption && focusTo$1(currentOption);
            } else if (strict && KEY_ENTER$2 === key) {
                // Automatically select the first option!
                selectToOptionFirst(picker) && picker.exit(exit);
            } else {
                currentOption && focusTo$1(currentOption);
            }
        } else if (KEY_TAB$1 === key) {
            letError$1(isInteger(error) && error > 0 ? error : 0, picker);
            selectToNone(), picker.exit();
        } else if (keyIsCtrl) {
            if (!keyIsShift && KEY_Z$1 === toCaseLower(key)) {
                exit = true;
                undoState($);
            } else if (keyIsShift && KEY_Z$1 === toCaseLower(key) || KEY_Y$1 === toCaseLower(key)) {
                exit = true;
                redoState($);
            }
        } else {
            delay(function () {
                // Only execute the filter if the previous search query is different from the current search query
                if ("" === searchQuery || searchQuery !== getText($) + "") {
                    filter(search[0], picker, $, _options);
                    searchQuery = getText($) + "";
                }
            })[0](1);
        }
        exit && offEventDefault(e);
    }
    var searchTerm = "",
        searchTermClear = debounce(function () {
            return searchTerm = "";
        })[0];

    function onKeyDownOption(e) {
        var $ = this,
            exit,
            key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            keyIsShift = e.shiftKey,
            picker = getReference$2($),
            _mask = picker._mask,
            max = picker.max,
            self = picker.self,
            value = _mask.value,
            optionNext,
            optionParent,
            optionPrev,
            valueCurrent;
        if (KEY_DELETE_LEFT$2 === key || KEY_DELETE_RIGHT$2 === key) {
            exit = true;
            if (value && (valueCurrent = getElement('[value="' + (getOptionValue($) + "").replace(/"/g, '\\"') + '"]', getParent(value)))) {
                focusTo$1(valueCurrent);
            } else {
                picker.exit(exit);
            }
        } else if (KEY_ENTER$2 === key || KEY_ESCAPE$1 === key || KEY_TAB$1 === key || ' ' === key) {
            if (max > 1) {
                if (KEY_ESCAPE$1 === key) {
                    picker.exit(exit = true);
                } else if (KEY_TAB$1 === key) {
                    picker.exit(exit = false);
                } else {
                    exit = true;
                    toggleToOption($, picker);
                }
            } else {
                if (KEY_ESCAPE$1 !== key) {
                    selectToOption($, picker);
                }
                picker.exit(exit = KEY_TAB$1 !== key);
            }
        } else if (KEY_ARROW_DOWN$1 === key || KEY_PAGE_DOWN === key) {
            exit = true;
            if (KEY_PAGE_DOWN === key && TOKEN_GROUP === getRole(optionParent = getParent($))) {
                optionNext = getOptionNext(optionParent);
            } else {
                optionNext = getOptionNext($);
            }
            optionNext ? focusToOption(optionNext) : focusToOptionFirst(picker);
        } else if (KEY_ARROW_UP$1 === key || KEY_PAGE_UP === key) {
            exit = true;
            if (KEY_PAGE_UP === key && TOKEN_GROUP === getRole(optionParent = getParent($))) {
                optionPrev = getOptionPrev(optionParent);
            } else {
                optionPrev = getOptionPrev($);
            }
            optionPrev ? focusToOption(optionPrev) : focusToOptionLast(picker);
        } else if (KEY_BEGIN$1 === key) {
            exit = true;
            focusToOptionFirst(picker);
        } else if (KEY_END$1 === key) {
            exit = true;
            focusToOptionLast(picker);
        } else {
            if (!keyIsCtrl) {
                if (1 === toCount(key) && !keyIsAlt) {
                    if (isInput(self)) {
                        toggleHintByValue$1(picker, key);
                    } else {
                        searchTerm += key; // Initialize search term, right before exit
                    }
                }!keyIsShift && picker.exit(!(exit = false));
            }
        }
        exit && offEventDefault(e);
    }

    function onKeyDownValue(e) {
        var $ = this,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix;
        if (!_active || _fix) {
            return;
        }
        var key = e.key,
            keyIsAlt = e.altKey,
            keyIsCtrl = e.ctrlKey,
            _mask = picker._mask,
            _options = picker._options,
            max = picker.max,
            min = picker.min,
            self = picker.self,
            state = picker.state,
            arrow = _mask.arrow,
            options = _mask.options,
            values = _mask.values,
            time = state.time,
            search = time.search,
            exit,
            valueCurrent,
            valueNext,
            valuePrev;
        searchTermClear(search[1]);
        if (KEY_ARROW_DOWN$1 === key || KEY_ARROW_UP$1 === key || KEY_ENTER$2 === key || KEY_PAGE_DOWN === key || KEY_PAGE_UP === key || "" === searchTerm && ' ' === key) {
            var focus = exit = true;
            if (KEY_ENTER$2 === key || ' ' === key) {
                if (valueCurrent = _options.at(getOptionValue($))) {
                    focus = false;
                    onAnimationsEnd(options, function () {
                        return focusTo$1(valueCurrent[2]);
                    }, scrollTo(valueCurrent[2]));
                }
            }
            if (picker.size < 2) {
                setStyle(options, 'max-height', 0);
            }
            picker.enter(focus).fit();
        } else if (KEY_ARROW_LEFT$2 === key) {
            exit = true;
            if ((valuePrev = getPrev($)) && hasKeyInMap(valuePrev, values)) {
                focusTo$1(valuePrev);
            }
        } else if (KEY_ARROW_RIGHT$2 === key) {
            exit = true;
            if ((valueNext = getNext($)) && hasKeyInMap(valueNext, values)) {
                focusTo$1(valueNext);
            }
        } else if (KEY_BEGIN$1 === key) {
            exit = true;
            forEachSet(values, function (v) {
                valueCurrent = v;
                return 0; // Break
            });
            valueCurrent && focusTo$1(valueCurrent);
        } else if (KEY_DELETE_LEFT$2 === key) {
            exit = true;
            searchTerm = "";
            var countValues = toSetCount(values);
            if (min >= countValues) {
                onInvalidSelf$1.call(self);
                picker.fire('min.options', [countValues, min]);
            } else if (valueCurrent = _options.at(getOptionValue($))) {
                letAria(valueCurrent[2], TOKEN_SELECTED);
                valueCurrent[3][TOKEN_SELECTED] = false;
                if ((valuePrev = getPrev($)) && hasKeyInMap(valuePrev, values) || (valueNext = getNext($)) && hasKeyInMap(valueNext, values)) {
                    focusTo$1(_mask[TOKEN_VALUE$1] = valuePrev || valueNext);
                    offEvent(EVENT_KEY_DOWN$1, $, onKeyDownValue);
                    offEvent(EVENT_MOUSE_DOWN$2, $, onPointerDownValue);
                    offEvent(EVENT_MOUSE_DOWN$2, $.$[VALUE_X], onPointerDownValueX);
                    offEvent(EVENT_TOUCH_START$2, $, onPointerDownValue);
                    offEvent(EVENT_TOUCH_START$2, $.$[VALUE_X], onPointerDownValueX);
                    letValueInMap($, values), letElement($);
                    // Do not remove the only option value
                } else {
                    letAttribute(_mask[TOKEN_VALUE$1] = $, TOKEN_VALUE$1);
                    setHTML($.$[VALUE_TEXT], "");
                    // No option(s) selected
                    if (0 === min) {
                        selectToOptionsNone(picker, 1);
                    }
                }
                if (max !== Infinity && max > countValues) {
                    forEachMap(_options, function (v, k) {
                        if (!v[3][TOKEN_DISABLED$1]) {
                            letAria(v[2], TOKEN_DISABLED$1);
                            setAttribute(v[2], TOKEN_TABINDEX$1, 0);
                        }
                    });
                }
            }
        } else if (KEY_DELETE_RIGHT$2 === key) {
            exit = true;
            searchTerm = "";
            var _countValues = toSetCount(values);
            if (min >= _countValues) {
                onInvalidSelf$1.call(self);
                picker.fire('min.options', [_countValues, min]);
            } else if (valueCurrent = _options.at(getOptionValue($))) {
                letAria(valueCurrent[2], TOKEN_SELECTED);
                valueCurrent[3][TOKEN_SELECTED] = false;
                if ((valueNext = getNext($)) && hasKeyInMap(valueNext, values) || (valuePrev = getPrev($)) && hasKeyInMap(valuePrev, values)) {
                    focusTo$1(_mask[TOKEN_VALUE$1] = valueNext && valueNext !== arrow ? valueNext : valuePrev);
                    offEvent(EVENT_KEY_DOWN$1, $, onKeyDownValue);
                    offEvent(EVENT_MOUSE_DOWN$2, $, onPointerDownValue);
                    offEvent(EVENT_MOUSE_DOWN$2, $.$[VALUE_X], onPointerDownValueX);
                    offEvent(EVENT_TOUCH_START$2, $, onPointerDownValue);
                    offEvent(EVENT_TOUCH_START$2, $.$[VALUE_X], onPointerDownValueX);
                    letValueInMap($, values), letElement($);
                    // Do not remove the only option value
                } else {
                    letAttribute(_mask[TOKEN_VALUE$1] = $, TOKEN_VALUE$1);
                    setHTML($.$[VALUE_TEXT], "");
                    // No option(s) selected
                    if (0 === min) {
                        selectToOptionsNone(picker, 1);
                    }
                }
                if (max !== Infinity && max > _countValues) {
                    forEachMap(_options, function (v, k) {
                        if (!v[3][TOKEN_DISABLED$1]) {
                            letAria(v[2], TOKEN_DISABLED$1);
                            setAttribute(v[2], TOKEN_TABINDEX$1, -1);
                        }
                    });
                }
            }
        } else if (KEY_END$1 === key) {
            exit = true;
            forEachSet(values, function (v) {
                return valueCurrent = v;
            });
            valueCurrent && focusTo$1(valueCurrent);
        } else if (KEY_ESCAPE$1 === key) {
            searchTerm = "";
            picker.exit(exit = true);
        } else if (KEY_TAB$1 === key) {
            searchTerm = "";
            picker.exit(exit = false);
        } else if (1 === toCount(key) && !keyIsAlt) {
            if (keyIsCtrl);
            else {
                exit = true;
                searchTerm += key;
            }
        }
        if ("" !== searchTerm) {
            filter(search[0], picker, searchTerm, _options, true);
        }
        exit && offEventDefault(e);
    }

    function onPointerDownValue(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            _mask = picker._mask,
            _options = picker._options,
            options = _mask.options,
            option;
        if (_options.open) {
            focusTo$1($);
        } else {
            if (option = _options.at(getOptionValue($))) {
                onAnimationsEnd(options, function () {
                    return delay(function () {
                        return focusTo$1(option[2]), scrollTo(option[2]);
                    })[0](1);
                });
            }
        }
    }

    function onPointerDownValueX(e) {
        var $ = this,
            value = getParent($),
            picker = getReference$2(value),
            _options = picker._options,
            option = _options.at(getOptionValue(value))[2];
        option && toggleToOption(option, picker);
        picker.enter(true).fit(), offEventDefault(e), offEventPropagation(e);
    }

    function onPasteTextInput$1(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            self = picker.self,
            state = picker.state,
            strict = state.strict;
        delay(function () {
            if (!strict) {
                setValue$1(self, getText($));
            }
        })[0](1);
        saveState($), toggleHint$1(1, picker), insertAtSelection($, e.clipboardData.getData('text/plain')), saveStateLazy$1($);
    }
    // The default state is `0`. When the pointer is pressed on the option mask, its value will become `1`. This check is
    // done to distinguish between a “touch only” and a “touch move” on touch device(s). It is also checked on pointer
    // device(s) and should not give a wrong result.
    var currentPointerState = 0,
        touchTop = false,
        touchTopCurrent = false;

    function onPointerDownMask$1(e) {
        // This is necessary for device(s) that support both pointer and touch control so that they will not execute both
        // `mousedown` and `touchstart` event(s), causing the option picker’s option(s) to open and then close immediately.
        // Note that this will also disable the native pane scrolling feature on touch device(s).
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix;
        if (_fix) {
            return focusTo$1(picker);
        }
        if (!_active || getDatum($, 'size')) {
            return;
        }
        var _mask = picker._mask,
            _options = picker._options,
            max = picker.max,
            self = picker.self,
            arrow = _mask.arrow,
            options = _mask.options,
            target = e.target,
            focusToArrow;
        if (arrow === target) {
            focusToArrow = 1;
        }
        // The user is likely browsing through the available option(s) by dragging the scroll bar
        if (options === target) {
            return;
        }
        while ($ !== target) {
            target = getParent(target);
            if (arrow === target) {
                focusToArrow = 1;
                break;
            }
            if (!target || options === target) {
                return;
            }
        }
        forEachMap(_options, function (v) {
            return v[2].hidden = false;
        });
        if (getReference$2(R) !== picker) {
            if (picker.size < 2) {
                setStyle(options, 'max-height', 0);
            }
            picker.enter(!focusToArrow).fit();
            if (focusToArrow) {
                focusTo$1(arrow);
            }
        } else {
            picker.exit(!focusToArrow ? 1 === max || isInput(self) : 0);
            if (focusToArrow) {
                focusTo$1(arrow);
            }
        }
    }

    function onPointerDownOption(e) {
        var $ = this;
        // Add an “active” effect on `touchstart` to indicate which option is about to be selected. We don’t need this
        // indication on `mousedown` because pointer device(s) already have a hover state that is clear enough to indicate
        // which option is about to be selected.
        if (EVENT_TOUCH_START$2 === e.type && !getAria($, TOKEN_DISABLED$1)) {
            setAria($, TOKEN_SELECTED, true);
        }
        currentPointerState = 1; // Pointer is “down”
    }

    function onPointerDownRoot(e) {
        if (EVENT_TOUCH_START$2 === e.type) {
            touchTop = e.touches[0].clientY;
        }
        var $ = this,
            picker = getReference$2($);
        if (!picker) {
            return;
        }
        var mask = picker.mask,
            state = picker.state,
            n = state.n,
            target = e.target;
        if (mask !== target && mask !== getParent(target, '.' + n)) {
            picker.exit();
        }
    }

    function onPointerMoveRoot(e) {
        touchTopCurrent = EVENT_TOUCH_MOVE$1 === e.type ? e.touches[0].clientY : false;
        var $ = this,
            picker = getReference$2($);
        if (!picker) {
            return;
        }
        var _mask = picker._mask,
            lot = _mask.lot,
            v;
        if (false !== touchTop && false !== touchTopCurrent) {
            if (1 === currentPointerState && touchTop !== touchTopCurrent) {
                ++currentPointerState;
            }
            // Programmatically re-enable the swipe feature in the option(s) list because the default `touchstart` event
            // has been disabled. It does not have the innertia effect as in the native after-swipe reaction, but it is
            // still better than doing nothing :\
            v = getScroll(lot);
            v[1] -= touchTopCurrent - touchTop;
            setScroll(lot, v);
            touchTop = touchTopCurrent;
        }
    }
    // The actual option selection happens when the pointer is released, to clearly identify whether we want to select an
    // option or just want to scroll through the option(s) list by swiping over the option on touch device(s).
    function onPointerUpOption() {
        var $ = this,
            picker = getReference$2($);
        // A “touch only” event is valid only if the pointer has not been “move(d)” up to this event
        if (1 === currentPointerState) {
            if (!getAria($, TOKEN_DISABLED$1)) {
                if (picker.max > 1) {
                    toggleToOption($, picker), focusTo$1($);
                } else {
                    selectToOption($, picker), picker.size < 2 ? picker.exit(true) : focusTo$1($);
                }
            }
        } else {
            // Remove the “active” effect that was previously added on `touchstart`
            letAria($, TOKEN_SELECTED);
        }
        currentPointerState = 0; // Reset current pointer state
    }

    function onPointerUpRoot() {
        currentPointerState = 0; // Reset current pointer state
        touchTop = false;
    }

    function onResetForm$1() {
        forEachSet(getReference$2(this), function ($) {
            return $.reset();
        });
    }

    function onSubmitForm$1(e) {
        forEachSet(getReference$2(this), function (picker) {
            var max = picker.max,
                min = picker.min,
                self = picker.self,
                count = toCount(getOptionsSelected(picker)),
                exit;
            if (count < min) {
                exit = true;
                picker.fire('min.options', [count, min]);
            } else if (count > max) {
                exit = true;
                picker.fire('max.options', [count, max]);
            }
            exit && (onInvalidSelf$1.call(self), offEventDefault(e));
        });
    }

    function onResizeWindow() {
        var picker = getReference$2(R),
            tick;
        if (picker) {
            if (!tick) {
                W.requestAnimationFrame(function () {
                    picker.fit(), tick = 0;
                }), tick = 1;
            }
        }
    }

    function onScrollWindow() {
        onResizeWindow.call(this);
    }

    function onWheelMask(e) {
        var $ = this,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix,
            max = picker.max;
        if (!_active || _fix || max > 1) {
            return;
        }
        var _mask = picker._mask,
            options = _mask.options,
            deltaY = e.deltaY,
            target = e.target,
            optionCurrent,
            optionNext,
            optionPrev;
        if (options === target) {
            return;
        }
        while ($ !== target) {
            target = getParent(target);
            if (options === target) {
                return;
            }
        }
        if (!(optionCurrent = getOptionSelected(picker) || goToOptionFirst(picker))) {
            return;
        }
        offEventDefault(e);
        if (deltaY < 0) {
            if (optionPrev = getOptionPrev(optionCurrent)) {
                focusTo$1(selectToOption(optionPrev, picker));
            } else {
                focusTo$1(selectToOptionLast(picker));
            }
        } else {
            if (optionNext = getOptionNext(optionCurrent)) {
                focusTo$1(selectToOption(optionNext, picker));
            } else {
                focusTo$1(selectToOptionFirst(picker));
            }
        }
    }

    function scrollTo(node) {
        node.scrollIntoView({
            block: 'nearest'
        });
    }

    function selectToOption(option, picker) {
        var _mask = picker._mask,
            mask = picker.mask,
            self = picker.self,
            input = _mask.input,
            value = _mask.value,
            optionReal,
            v;
        if (option) {
            optionReal = option.$[OPTION_SELF];
            selectToOptionsNone(picker);
            optionReal[TOKEN_SELECTED] = true;
            setAria(option, TOKEN_SELECTED, true);
            setValue$1(self, v = getOptionValue(option));
            if (isInput(self)) {
                letAria(mask, TOKEN_INVALID$1);
                setAria(input, 'activedescendant', getID(option));
                setText(input, getText(option.$[OPTION_TEXT]));
                toggleHintByValue$1(picker, 1);
            } else {
                setHTML(value.$[VALUE_TEXT], getHTML(option.$[OPTION_TEXT]));
                setValue$1(value, v);
            }
            return picker.fire('change', ["" !== v ? v : null]), option;
        }
    }

    function selectToOptionFirst(picker) {
        var option;
        if (option = goToOptionFirst(picker)) {
            return selectToOption(option, picker);
        }
    }

    function selectToOptionLast(picker) {
        var option;
        if (option = goToOptionLast(picker)) {
            return selectToOption(option, picker);
        }
    }

    function selectToOptionsNone(picker, fireValue) {
        var _mask = picker._mask,
            _options = picker._options,
            self = picker.self,
            input = _mask.input,
            value = _mask.value,
            v;
        forEachMap(_options, function (v) {
            letAria(v[2], TOKEN_SELECTED);
            v[3][TOKEN_SELECTED] = false;
        });
        if (fireValue) {
            setValue$1(self, v = "");
            if (isInput(self)) {
                letAria(input, 'activedescendant');
                setText(input, "");
                toggleHintByValue$1(picker, 0);
            } else {
                letAttribute(value, TOKEN_VALUE$1);
                setHTML(value.$[VALUE_TEXT], v);
                if (v = value.$[VALUE_X]) {
                    letElement(v);
                }
            }
        }
    }

    function toggleToOption(option, picker) {
        var _mask = picker._mask,
            _options = picker._options,
            max = picker.max,
            min = picker.min,
            self = picker.self,
            state = picker.state,
            value = _mask.value,
            values = _mask.values,
            n = state.n,
            selected,
            selectedFirst,
            valueCurrent,
            valueNext,
            valueNextX;
        if (option) {
            var optionReal = option.$[OPTION_SELF],
                a = getOptionsValues(getOptionsSelected(picker)),
                b,
                c;
            if (getAria(option, TOKEN_SELECTED) && optionReal[TOKEN_SELECTED]) {
                if (min > 0 && (c = toCount(a)) <= min) {
                    onInvalidSelf$1.call(self);
                    picker.fire('min.options', [c, min]);
                } else {
                    letAria(option, TOKEN_SELECTED);
                    optionReal[TOKEN_SELECTED] = false;
                }
            } else {
                setAria(option, TOKEN_SELECTED, true);
                optionReal[TOKEN_SELECTED] = true;
            }
            if (!isInput(self)) {
                b = getOptionsValues(getOptionsSelected(picker));
                if (max !== Infinity && (c = toCount(b)) === max) {
                    forEachMap(_options, function (v, k) {
                        if (!getAria(v[2], TOKEN_SELECTED)) {
                            letAttribute(v[2], TOKEN_TABINDEX$1);
                            setAria(v[2], TOKEN_DISABLED$1, true);
                        }
                    });
                } else if (c > max) {
                    letAria(option, TOKEN_SELECTED);
                    optionReal[TOKEN_SELECTED] = false;
                    forEachMap(_options, function (v, k) {
                        if (!getAria(v[2], TOKEN_SELECTED)) {
                            letAttribute(v[2], TOKEN_TABINDEX$1);
                            setAria(v[2], TOKEN_DISABLED$1, true);
                        }
                    });
                    onInvalidSelf$1.call(self);
                    picker.fire('max.options', [c, max]);
                } else {
                    forEachMap(_options, function (v, k) {
                        if (!v[3][TOKEN_DISABLED$1]) {
                            letAria(v[2], TOKEN_DISABLED$1);
                            setAttribute(v[2], TOKEN_TABINDEX$1, -1);
                        }
                    });
                }
                selected = getOptionsSelected(picker);
                selectedFirst = selected.shift();
                if (selectedFirst) {
                    setChildLast(value, value.$[VALUE_X]);
                    setHTML(value.$[VALUE_TEXT], getHTML(selectedFirst.$[OPTION_TEXT]));
                    setValue$1(value, getOptionValue(selectedFirst));
                    letValueInMap(value, values);
                    forEachSet(values, function (v) {
                        offEvent(EVENT_KEY_DOWN$1, v, onKeyDownValue);
                        offEvent(EVENT_MOUSE_DOWN$2, v, onPointerDownValue);
                        offEvent(EVENT_MOUSE_DOWN$2, v.$[VALUE_X], onPointerDownValueX);
                        offEvent(EVENT_TOUCH_START$2, v, onPointerDownValue);
                        offEvent(EVENT_TOUCH_START$2, v.$[VALUE_X], onPointerDownValueX);
                        letReference$1(v), letElement(v);
                        return -1; // Remove
                    });
                    values.add(valueCurrent = value); // Add the only value to the set
                    forEachArray(selected, function (v, k) {
                        valueNext = setID(letID(value.cloneNode(true)));
                        valueNext[TOKEN_TAB_INDEX$1] = -1;
                        valueNext.$ = {};
                        valueNext.$[VALUE_SELF] = null;
                        valueNext.$[VALUE_TEXT] = getElement('.' + n + '__v', valueNext);
                        valueNext.$[VALUE_X] = valueNextX = getElement('.' + n + '__x', valueNext);
                        onEvent(EVENT_KEY_DOWN$1, valueNext, onKeyDownValue);
                        onEvent(EVENT_MOUSE_DOWN$2, valueNext, onPointerDownValue);
                        onEvent(EVENT_MOUSE_DOWN$2, valueNextX, onPointerDownValueX);
                        onEvent(EVENT_TOUCH_START$2, valueNext, onPointerDownValue);
                        onEvent(EVENT_TOUCH_START$2, valueNextX, onPointerDownValueX);
                        setHTML(valueNext.$[VALUE_TEXT], getHTML(v.$[OPTION_TEXT]));
                        setReference$2(valueNext, picker), values.add(setNext(valueCurrent, valueNext));
                        setValue$1(valueNext, getOptionValue(v));
                        valueCurrent = valueNext;
                    });
                } else {
                    selectToOptionsNone(picker, 1);
                }
            }
            return picker.fire('change', [b]), option;
        }
    }

    function OptionPicker(self, state) {
        var $ = this;
        if (!self) {
            return $;
        }
        // Return new instance if `OptionPicker` was called without the `new` operator
        if (!isInstance($, OptionPicker)) {
            return new OptionPicker(self, state);
        }
        setReference$2(self, hook($, OptionPicker._));
        return $.attach(self, _fromStates({}, OptionPicker.state, isBoolean(state) ? {
            strict: state
        } : state || {}));
    }

    function OptionPickerOptions(of, options) {
        var $ = this;
        // Return new instance if `OptionPickerOptions` was called without the `new` operator
        if (!isInstance($, OptionPickerOptions)) {
            return new OptionPickerOptions(of, options);
        }
        $.of = of;
        $[TOKEN_VALUES$1] = new Map();
        if (options) {
            createOptions(of, options);
        }
        return $;
    }
    OptionPicker.from = function (self, state) {
        return new OptionPicker(self, state);
    };
    OptionPicker.of = getReference$2;
    OptionPicker.state = {
        'max': null,
        'min': null,
        'n': 'option-picker',
        'options': null,
        'size': null,
        'strict': false,
        'time': {
            'error': 1000,
            'search': [10, 500]
        },
        'with': []
    };
    OptionPicker.version = '2.2.10';
    setObjectAttributes(OptionPicker, {
        name: {
            value: name$5
        }
    }, 1);
    setObjectAttributes(OptionPicker, {
        active: {
            get: function get() {
                return this._active;
            },
            set: function set(value) {
                selectToNone();
                var $ = this,
                    _mask = $._mask,
                    mask = $.mask,
                    self = $.self,
                    input = _mask.input,
                    inputReadOnly = _mask.value,
                    v = !!value;
                self[TOKEN_DISABLED$1] = !($._active = v);
                if (v) {
                    letAria(mask, TOKEN_DISABLED$1);
                    if (input) {
                        letAria(input, TOKEN_DISABLED$1);
                        setAttribute(input, TOKEN_CONTENTEDITABLE$1, "");
                    } else if (inputReadOnly) {
                        setAttribute(inputReadOnly, TOKEN_TABINDEX$1, 0);
                    }
                } else {
                    setAria(mask, TOKEN_DISABLED$1, true);
                    if (input) {
                        setAria(input, TOKEN_DISABLED$1, true);
                        letAttribute(input, TOKEN_CONTENTEDITABLE$1);
                    } else if (inputReadOnly) {
                        letAttribute(inputReadOnly, TOKEN_TABINDEX$1);
                    }
                }
                return $;
            }
        },
        fix: {
            get: function get() {
                return this._fix;
            },
            set: function set(value) {
                selectToNone();
                var $ = this,
                    _mask = $._mask,
                    mask = $.mask,
                    self = $.self,
                    input = _mask.input,
                    v = !!value;
                $._fix = v;
                if (!isInput(self)) {
                    return $;
                }
                self[TOKEN_READ_ONLY$1] = v;
                if (v) {
                    letAttribute(input, TOKEN_CONTENTEDITABLE$1);
                    setAria(input, TOKEN_READONLY$1, true);
                    setAria(mask, TOKEN_READONLY$1, true);
                    setAttribute(input, TOKEN_TABINDEX$1, 0);
                } else {
                    letAria(input, TOKEN_READONLY$1);
                    letAria(mask, TOKEN_READONLY$1);
                    letAttribute(input, TOKEN_TABINDEX$1);
                    setAttribute(input, TOKEN_CONTENTEDITABLE$1, "");
                }
                return $;
            }
        },
        max: {
            get: function get() {
                var $ = this,
                    state = $.state,
                    max = state.max;
                return Infinity === max || isInteger(max) && max > 0 ? max : 1;
            },
            set: function set(value) {
                var $ = this,
                    self = $.self;
                if (isInput(self)) {
                    return $;
                }
                var mask = $.mask,
                    state = $.state;
                value = (Infinity === value || isInteger(value)) && value > 0 ? value : 0;
                self.multiple = value > 1;
                state.max = value;
                value > 1 ? setAria(mask, 'multiselectable', true) : letAria(mask, 'multiselectable');
                return $;
            }
        },
        min: {
            get: function get() {
                var $ = this,
                    state = $.state,
                    min = state.min;
                return !isInteger(min) || min < 0 ? 0 : min;
            },
            set: function set(value) {
                var $ = this,
                    state = $.state;
                state.min = isInteger(value) && value > 0 ? value : 0;
                return $;
            }
        },
        options: {
            get: function get() {
                return this._options;
            },
            set: function set(options) {
                selectToNone();
                var $ = this,
                    _active = $._active,
                    _fix = $._fix;
                if (!_active || _fix) {
                    return $;
                }
                var max = $.max,
                    selected;
                if (isFloat(options) || isInteger(options) || isString(options)) {
                    options = [options];
                }
                if (toCount(selected = createOptions($, options))) {
                    var isMultipleSelect = max > 1;
                    $[TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = $['_' + TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = isMultipleSelect ? selected : selected[0];
                }
                var optionsValues = [];
                forEachMap($._options, function (v) {
                    return optionsValues.push(getOptionValue(v[2], 1));
                });
                return $.fire('set.options', [optionsValues]);
            }
        },
        size: {
            get: function get() {
                var _self$size;
                var $ = this,
                    self = $.self,
                    state = $.state,
                    size;
                if (isInput(self)) {
                    return null;
                }
                size = (_self$size = self.size) != null ? _self$size : state.size || 1;
                return !isInteger(size) || size < 1 ? 1 : size; // <https://html.spec.whatwg.org#attr-select-size>
            },
            set: function set(value) {
                selectToNone();
                var $ = this,
                    self = $.self;
                if (isInput(self)) {
                    return $;
                }
                var _active = $._active,
                    _mask = $._mask,
                    mask = $.mask,
                    state = $.state,
                    options = _mask.options,
                    size = !isInteger(value) || value < 1 ? 1 : value;
                self.size = state.size = size;
                if (1 === size) {
                    letDatum(mask, 'size');
                    letStyle(options, 'max-height');
                    _active && letReference$1(R);
                } else {
                    var option = goToOptionFirst($);
                    if (option) {
                        var _ref, _getStyle;
                        var optionsBorderBottom = getStyle(options, 'border-bottom-width'),
                            optionsBorderTop = getStyle(options, 'border-top-width'),
                            optionsGap = getStyle(options, 'gap'),
                            optionHeight = (_ref = (_getStyle = getStyle(option, 'height')) != null ? _getStyle : getStyle(option, 'min-height')) != null ? _ref : getStyle(option, 'line-height');
                        setDatum(mask, 'size', size);
                        setStyle(options, 'max-height', 'calc(' + optionsBorderTop + ' + ' + optionsBorderBottom + ' + (' + optionHeight + '*' + size + ') + calc(' + optionsGap + '*' + size + '))');
                        _active && setReference$2(R, $);
                    }
                }
                return $;
            }
        },
        text: {
            get: function get() {
                var $ = this,
                    _mask = $._mask,
                    input = _mask.input,
                    text = _mask.text;
                return text ? getText(input) : null;
            },
            set: function set(value) {
                var $ = this,
                    _active = $._active,
                    _fix = $._fix;
                if (!_active || _fix) {
                    return $;
                }
                var _mask = $._mask,
                    text = _mask.text;
                if (!text) {
                    return $;
                }
                var input = _mask.input,
                    v;
                return setText(input, v = _fromValue(value)), toggleHintByValue$1($, v), $;
            }
        },
        value: {
            get: function get() {
                var value = getValue$1(this.self);
                return "" !== value ? value : null;
            },
            set: function set(value) {
                var $ = this,
                    _active = $._active,
                    self = $.self;
                if (!_active) {
                    return $;
                }
                var _options = $._options,
                    option;
                if (option = _options.at(value)) {
                    selectToOption(option[2], $);
                } else if (isInput(self) && null === value) {
                    selectToOptionsNone($, 1);
                }
                return $;
            }
        },
        values: {
            get: function get() {
                return getOptionsValues(getOptionsSelected(this));
            },
            set: function set(values) {
                var $ = this,
                    _active = $._active;
                if (!_active || $.max < 2) {
                    return $;
                }
                selectToOptionsNone($);
                var _options = $._options,
                    option;
                if (isFloat(values) || isInteger(values) || isString(values)) {
                    values = [values];
                }
                if (isArray(values)) {
                    forEachArray(values, function (v) {
                        if (option = _options.at(v)) {
                            toggleToOption(option[2], $);
                        }
                    });
                }
                return $;
            }
        },
        vital: {
            get: function get() {
                return this._vital;
            },
            set: function set(value) {
                selectToNone();
                var $ = this,
                    _mask = $._mask,
                    mask = $.mask,
                    min = $.min,
                    self = $.self,
                    input = _mask.input,
                    v = !!value;
                self[TOKEN_REQUIRED$1] = v;
                if (v) {
                    if (0 === min) {
                        $.min = 1;
                    }
                    input && setAria(input, TOKEN_REQUIRED$1, true);
                    setAria(mask, TOKEN_REQUIRED$1, true);
                } else {
                    $.min = 0;
                    input && letAria(input, TOKEN_REQUIRED$1);
                    letAria(mask, TOKEN_REQUIRED$1);
                }
                return $;
            }
        }
    });
    OptionPicker._ = setObjectMethods(OptionPicker, {
        attach: function attach(self, state) {
            var _state$size;
            var $ = this;
            self = self || $.self;
            state = state || $.state;
            $._options = new OptionPickerOptions($);
            $._value = null;
            $._values = [];
            $.self = self;
            $.state = state;
            var _state = state,
                max = _state.max,
                min = _state.min,
                n = _state.n,
                isDisabledSelf = isDisabled$1(self),
                isInputSelf = isInput(self),
                isMultipleSelect = max && max > 1 || !isInputSelf && self.multiple,
                isReadOnlySelf = isReadOnly$1(self),
                isRequiredSelf = isRequired(self),
                theInputID = self.id,
                theInputName = self.name,
                theInputPlaceholder = self.placeholder;
            $._active = !isDisabledSelf;
            $._fix = isInputSelf && isReadOnlySelf;
            $._vital = isRequiredSelf;
            if (isRequiredSelf && min < 1) {
                state.min = min = 1; // Force minimum option(s) to select to be `1`
            }
            var arrow = setElement('span', {
                'aria': {
                    'hidden': TOKEN_TRUE$1
                },
                'class': n + '__arrow',
                'tabindex': -1
            });
            var form = getParentForm(self);
            var mask = setElement('div', {
                'aria': {
                    'disabled': isDisabledSelf ? TOKEN_TRUE$1 : false,
                    'expanded': TOKEN_FALSE$1,
                    'haspopup': 'listbox',
                    'multiselectable': isMultipleSelect ? TOKEN_TRUE$1 : false,
                    'readonly': isInputSelf && isReadOnlySelf ? TOKEN_TRUE$1 : false,
                    'required': isRequiredSelf ? TOKEN_TRUE$1 : false
                },
                'class': n,
                'role': 'combobox'
            });
            $.mask = mask;
            var maskFlex = setElement('div', {
                'class': n + '__flex',
                'role': TOKEN_GROUP
            });
            var maskOptions = setElement('div', {
                'class': n + '__options',
                'role': 'listbox'
            });
            var maskOptionsLot = setElement('div', {
                'class': n + '__options-lot',
                'role': 'none'
            });
            var textOrValue = setElement(isInputSelf ? 'span' : 'data', {
                'class': n + '__' + (isInputSelf ? TOKEN_TEXT : TOKEN_VALUE$1),
                'tabindex': isInputSelf ? false : 0
            });
            var textInput = setElement('span', {
                'aria': {
                    'autocomplete': 'list',
                    'disabled': isDisabledSelf ? TOKEN_TRUE$1 : false,
                    'multiline': TOKEN_FALSE$1,
                    'placeholder': isInputSelf ? theInputPlaceholder : false,
                    'readonly': isReadOnlySelf ? TOKEN_TRUE$1 : false,
                    'required': isRequiredSelf ? TOKEN_TRUE$1 : false
                },
                'autocapitalize': 'off',
                'contenteditable': isDisabledSelf || isReadOnlySelf || !isInputSelf ? false : "",
                'role': 'searchbox',
                'spellcheck': !isInputSelf ? false : TOKEN_FALSE$1,
                'tabindex': isReadOnlySelf && isInputSelf ? 0 : false
            });
            var textInputHint = setElement('span', isInputSelf ? theInputPlaceholder + "" : "", {
                'aria': {
                    'hidden': TOKEN_TRUE$1
                }
            });
            var valueX = setElement('span', {
                'aria': {
                    'hidden': TOKEN_TRUE$1
                },
                'class': n + '__x',
                'tabindex': -1
            });
            setChildLast(mask, maskFlex);
            setChildLast(mask, maskOptions);
            setChildLast(maskOptions, maskOptionsLot);
            setChildLast(maskFlex, textOrValue);
            setChildLast(maskFlex, arrow);
            if (isInputSelf) {
                onEvent(EVENT_BLUR$1, textInput, onBlurTextInput$1);
                onEvent(EVENT_CUT$1, textInput, onCutTextInput$1);
                onEvent(EVENT_FOCUS$1, textInput, onFocusTextInput$1);
                onEvent(EVENT_INPUT$1, textInput, onInputTextInput$1);
                onEvent(EVENT_KEY_DOWN$1, textInput, onKeyDownTextInput$1);
                onEvent(EVENT_PASTE$1, textInput, onPasteTextInput$1);
                setChildLast(textOrValue, textInput);
                setChildLast(textOrValue, textInputHint);
                setReference$2(textInput, $);
            } else {
                onEvent(EVENT_KEY_DOWN$1, textOrValue, onKeyDownValue);
                onEvent(EVENT_MOUSE_DOWN$2, textOrValue, onPointerDownValue);
                onEvent(EVENT_TOUCH_START$2, textOrValue, onPointerDownValue);
                setReference$2(textOrValue, $);
            }
            setClass(self, n + '__self');
            setNext(self, mask);
            setChildLast(mask, self);
            if (form) {
                var set = getReference$2(form) || new Set();
                set.add($);
                onEvent(EVENT_RESET$1, form, onResetForm$1);
                onEvent(EVENT_SUBMIT$1, form, onSubmitForm$1);
                setID(form);
                setReference$2(form, set);
            }
            onEvent(EVENT_FOCUS$1, self, onFocusSelf$1);
            onEvent(EVENT_INVALID$1, self, onInvalidSelf$1);
            onEvent(EVENT_KEY_DOWN$1, arrow, onKeyDownArrow);
            onEvent(EVENT_MOUSE_DOWN$2, mask, onPointerDownMask$1);
            onEvent(EVENT_TOUCH_START$2, mask, onPointerDownMask$1);
            onEvent(EVENT_WHEEL, mask, onWheelMask);
            self[TOKEN_TAB_INDEX$1] = -1;
            setReference$2(arrow, $);
            setReference$2(mask, $);
            var _mask = {
                arrow: arrow,
                flex: maskFlex,
                hint: isInputSelf ? textInputHint : null,
                input: isInputSelf ? textInput : null,
                lot: maskOptionsLot,
                of: self,
                options: maskOptions,
                self: mask,
                values: new Set()
            };
            _mask[isInputSelf ? TOKEN_TEXT : TOKEN_VALUE$1] = textOrValue;
            // Re-assign some state value(s) using the setter to either normalize or reject the initial value
            $.max = max = isMultipleSelect ? max != null ? max : Infinity : 1;
            $.min = min = isInputSelf ? 0 : min != null ? min : 1;
            if (!isInputSelf) {
                textOrValue.$ = {};
                textOrValue.$[VALUE_SELF] = null;
                setChildLast(textOrValue, textOrValue.$[VALUE_TEXT] = setID(setElement('span', {
                    'class': n + '__v',
                    'role': 'none'
                })));
                if (max > 1) {
                    onEvent(EVENT_MOUSE_DOWN$2, valueX, onPointerDownValueX);
                    onEvent(EVENT_TOUCH_START$2, valueX, onPointerDownValueX);
                    setChildLast(textOrValue, textOrValue.$[VALUE_X] = valueX);
                }
                _mask[TOKEN_VALUES$1].add(textOrValue); // Add the only value to the set
            }
            $._mask = _mask;
            var _active = $._active,
                _state2 = state,
                options = _state2.options,
                selected;
            // Force the `this._active` value to `true` to set the initial value
            $._active = true;
            if (isFunction(options)) {
                setAria(mask, 'busy', true);
                options = options.call($, null);
                if (isInstance(options, Promise)) {
                    options.then(function (options) {
                        letAria(mask, 'busy');
                        if (toCount(selected = createOptions($, options))) {
                            $[TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = $['_' + TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = isMultipleSelect ? selected : selected[0];
                        } else if (selected = getOptionSelected($, 1)) {
                            selected = getOptionValue(selected);
                            $[TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = $['_' + TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = isMultipleSelect ? [selected] : selected;
                        }
                        $.fire('load', [null, $[TOKEN_VALUES$1]])[$.options.open ? 'enter' : 'exit']().fit();
                    });
                } else {
                    if (toCount(selected = createOptions($, options))) {
                        $[TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = $['_' + TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = isMultipleSelect ? selected : selected[0];
                    }
                }
            } else {
                if (toCount(selected = createOptions($, options || getOptions(self)))) {
                    $[TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = $['_' + TOKEN_VALUE$1 + (isMultipleSelect ? 's' : "")] = isMultipleSelect ? selected : selected[0];
                }
            }
            // After the initial value has been set, restore the previous `this._active` value
            $._active = _active;
            // Has to be set after the option(s) are set, because from that point on we want to get the computed size of the
            // option to set the correct height for the option(s) based on the `size` attribute value.
            $.size = (_state$size = state.size) != null ? _state$size : isInputSelf ? 1 : self.size;
            // Force `id` attribute(s)
            setAria(mask, 'controls', getID(setID(maskOptions)));
            setAria(mask, 'labelledby', getID(setID(textOrValue)));
            setAria(self, 'hidden', true);
            setAria(textInput, 'controls', getID(maskOptions));
            setID(arrow);
            setID(mask);
            setID(maskFlex);
            setID(maskOptionsLot);
            setID(self);
            setID(textInput);
            setID(textInputHint);
            setID(valueX);
            theInputID && setDatum(mask, 'id', theInputID);
            theInputName && setDatum(mask, 'name', theInputName);
            // Attach extension(s)
            if (isSet(state) && isArray(state.with)) {
                forEachArray(state.with, function (v, k) {
                    if (isString(v)) {
                        v = OptionPicker[v];
                    }
                    // `const Extension = function (self, state = {}) {}`
                    if (isFunction(v)) {
                        v.call($, self, state);
                        // `const Extension = {attach: function (self, state = {}) {}, detach: function (self, state = {}) {}}`
                    } else if (isObject(v) && isFunction(v.attach)) {
                        v.attach.call($, self, state);
                    }
                });
            }
            return resetState(textInput), $;
        },
        blur: function blur() {
            var $ = this,
                _mask = $._mask,
                mask = $.mask,
                input = _mask.input;
            if (input) {
                selectToNone();
            }
            return (input || mask).blur(), $.exit();
        },
        detach: function detach() {
            var $ = this,
                _mask = $._mask,
                mask = $.mask,
                self = $.self,
                state = $.state,
                arrow = _mask.arrow,
                input = _mask.input,
                value = _mask.value;
            $.exit();
            var form = getParentForm(self);
            $._active = false;
            $._options = new OptionPickerOptions($);
            $._value = null;
            $._values = [];
            if (form) {
                offEvent(EVENT_RESET$1, form, onResetForm$1);
                offEvent(EVENT_SUBMIT$1, form, onSubmitForm$1);
            }
            if (input) {
                offEvent(EVENT_BLUR$1, input, onBlurTextInput$1);
                offEvent(EVENT_CUT$1, input, onCutTextInput$1);
                offEvent(EVENT_FOCUS$1, input, onFocusTextInput$1);
                offEvent(EVENT_INPUT$1, input, onInputTextInput$1);
                offEvent(EVENT_KEY_DOWN$1, input, onKeyDownTextInput$1);
                offEvent(EVENT_PASTE$1, input, onPasteTextInput$1);
            }
            if (value) {
                offEvent(EVENT_KEY_DOWN$1, value, onKeyDownValue);
                offEvent(EVENT_MOUSE_DOWN$2, value, onPointerDownValue);
                offEvent(EVENT_TOUCH_START$2, value, onPointerDownValue);
                var valueX = value.$[VALUE_X];
                if (valueX) {
                    offEvent(EVENT_MOUSE_DOWN$2, valueX, onPointerDownValueX);
                    offEvent(EVENT_TOUCH_START$2, valueX, onPointerDownValueX);
                }
            }
            offEvent(EVENT_FOCUS$1, self, onFocusSelf$1);
            offEvent(EVENT_INVALID$1, self, onInvalidSelf$1);
            offEvent(EVENT_KEY_DOWN$1, arrow, onKeyDownArrow);
            offEvent(EVENT_MOUSE_DOWN$2, mask, onPointerDownMask$1);
            offEvent(EVENT_TOUCH_START$2, mask, onPointerDownMask$1);
            offEvent(EVENT_WHEEL, mask, onWheelMask);
            // Detach extension(s)
            if (isArray(state.with)) {
                forEachArray(state.with, function (v, k) {
                    if (isString(v)) {
                        v = OptionPicker[v];
                    }
                    if (isObject(v) && isFunction(v.detach)) {
                        v.detach.call($, self, state);
                    }
                });
            }
            self[TOKEN_TAB_INDEX$1] = null;
            letAria(self, 'hidden');
            letClass(self, state.n + '__self');
            setNext(mask, self);
            letElement(mask);
            $._mask = {
                of: self
            };
            $.mask = null;
            return $;
        },
        enter: function enter(focus, mode) {
            var $ = this,
                _active = $._active,
                _fix = $._fix,
                _mask = $._mask,
                self = $.self,
                input = _mask.input,
                isInputSelf = isInput(self);
            if (_fix && focus && isInputSelf) {
                return focusTo$1(input), selectTo(input, mode), $;
            }
            if (!_active || _fix) {
                return $;
            }
            var _options = $._options,
                mask = $.mask,
                lot = _mask.lot,
                options = _mask.options,
                value = _mask.value,
                option;
            setAria(mask, 'expanded', toCount(getChildren(lot)) > 0);
            var theRootReference = getReference$2(R);
            if (theRootReference && $ !== theRootReference) {
                theRootReference.exit(); // Exit other(s)
            }
            setReference$2(R, $); // Link current picker to the root target
            $.fire('enter');
            if (focus) {
                if (isInputSelf) {
                    focusTo$1(input), selectTo(input, mode);
                } else if (option = _options.at(getValue$1(self))) {
                    onAnimationsEnd(options, function () {
                        return focusTo$1(option[2]);
                    }, scrollTo(option[2]));
                } else if (option = goToOptionFirst($)) {
                    onAnimationsEnd(options, function () {
                        return focusTo$1(option);
                    }, scrollTo(option));
                } else {
                    focusTo$1(value);
                }
            }
            onEvent(EVENT_MOUSE_DOWN$2, R, onPointerDownRoot);
            onEvent(EVENT_MOUSE_MOVE$1, R, onPointerMoveRoot);
            onEvent(EVENT_MOUSE_UP$1, R, onPointerUpRoot);
            onEvent(EVENT_RESIZE, W, onResizeWindow, {
                passive: true
            });
            onEvent(EVENT_SCROLL, W, onScrollWindow, {
                passive: true
            });
            onEvent(EVENT_TOUCH_END$1, R, onPointerUpRoot);
            onEvent(EVENT_TOUCH_MOVE$1, R, onPointerMoveRoot, {
                passive: true
            });
            onEvent(EVENT_TOUCH_START$2, R, onPointerDownRoot);
            return $;
        },
        exit: function exit(focus, mode) {
            var $ = this,
                _active = $._active,
                _fix = $._fix,
                _mask = $._mask,
                self = $.self,
                input = _mask.input,
                isInputSelf = isInput(self);
            if (_fix && focus && isInputSelf) {
                return focusTo$1(input), selectTo(input, mode), $;
            }
            if (!_active || _fix) {
                return $;
            }
            var _options = $._options,
                mask = $.mask,
                value = _mask.value;
            forEachMap(_options, function (v) {
                return v[2].hidden = false;
            });
            setAria(mask, 'expanded', false);
            letReference$1(R);
            $.fire('exit');
            if (focus) {
                if (isInputSelf) {
                    focusTo$1(input), selectTo(input, mode);
                } else {
                    focusTo$1(value);
                }
            }
            offEvent(EVENT_MOUSE_DOWN$2, R, onPointerDownRoot);
            offEvent(EVENT_MOUSE_MOVE$1, R, onPointerMoveRoot);
            offEvent(EVENT_MOUSE_UP$1, R, onPointerUpRoot);
            offEvent(EVENT_RESIZE, W, onResizeWindow);
            offEvent(EVENT_SCROLL, W, onScrollWindow);
            offEvent(EVENT_TOUCH_END$1, R, onPointerUpRoot);
            offEvent(EVENT_TOUCH_MOVE$1, R, onPointerMoveRoot);
            offEvent(EVENT_TOUCH_START$2, R, onPointerDownRoot);
            return $;
        },
        fit: function fit() {
            var $ = this,
                _active = $._active,
                _fix = $._fix,
                mask = $.mask;
            if (!_active || _fix || !getAria(mask, 'expanded') || getDatum(mask, 'size')) {
                return $;
            }
            var _mask = $._mask,
                options = _mask.options;
            setStyle(options, 'max-height', 0);
            var borderMaskBottom = getStyle(mask, 'border-bottom-width'),
                borderMaskTop = getStyle(mask, 'border-top-width'),
                rectMask = getRect(mask),
                rectWindow = getRect(W);
            if (rectMask[1] + rectMask[3] / 2 > rectWindow[3] / 2) {
                setStyles(options, {
                    'bottom': '100%',
                    'max-height': 'calc(' + rectMask[1] + 'px + ' + borderMaskBottom + ')',
                    'top': 'auto'
                });
            } else {
                setStyles(options, {
                    'bottom': 'auto',
                    'max-height': 'calc(' + (rectWindow[3] - rectMask[1] - rectMask[3]) + 'px + ' + borderMaskTop + ')',
                    'top': '100%'
                });
            }
            return $.fire('fit');
        },
        focus: function focus(mode) {
            var $ = this,
                _active = $._active;
            if (!_active) {
                return $;
            }
            var _mask = $._mask,
                input = _mask.input,
                value = _mask.value;
            if (input) {
                focusTo$1(input), selectTo(input, mode);
            } else {
                focusTo$1(value);
            }
            return $;
        },
        reset: function reset(focus, mode) {
            var $ = this,
                _active = $._active;
            if (!_active) {
                return $;
            }
            var _value = $._value,
                _values = $._values,
                max = $.max;
            if (max > 1) {
                $[TOKEN_VALUES$1] = _values;
            } else {
                $[TOKEN_VALUE$1] = _value;
            }
            return focus ? $.focus(mode) : $;
        }
    });
    setObjectAttributes(OptionPickerOptions, {
        name: {
            value: name$5 + 'Options'
        }
    }, 1);
    setObjectAttributes(OptionPickerOptions, {
        open: {
            get: function get() {
                var $ = this,
                    of = $.of,
                    mask = of.mask;
                return getAria(mask, 'expanded');
            }
        }
    });
    OptionPickerOptions._ = setObjectMethods(OptionPickerOptions, {
        at: function at(key) {
            return getValueInMap$1(_toValue(key), this[TOKEN_VALUES$1]);
        },
        count: function count() {
            return toMapCount(this[TOKEN_VALUES$1]);
        },
        // To be used by the `letValueInMap()` function
        delete: function _delete(key, _fireHook, _fireValue) {
            if (_fireHook === void 0) {
                _fireHook = 1;
            }
            if (_fireValue === void 0) {
                _fireValue = 1;
            }
            var $ = this,
                of = $.of,
                values = $.values,
                _active = of._active;
            if (!_active) {
                return false;
            }
            var _mask = of._mask,
                self = of.self,
                state = of.state,
                lot = _mask.lot,
                options = _mask.options,
                r;
            if (!isSet(key)) {
                forEachMap(values, function (v, k) {
                    return $.let(k, 0, 0);
                });
                selectToOptionsNone(of, _fireValue);
                options.hidden = true;
                return _fireHook && of.fire('let.options', [
                    []
                ]), 0 === $.count();
            }
            if (!(r = getValueInMap$1(key = _toValue(key), values))) {
                return _fireHook && of.fire('not.option', [key]), false;
            }
            var parent = getParent(r[2]),
                parentReal = getParent(r[3]),
                value = getOptionValue(r[2]),
                valueReal = of [TOKEN_VALUE$1];
            offEvent(EVENT_FOCUS$1, r[2], onFocusOption);
            offEvent(EVENT_KEY_DOWN$1, r[2], onKeyDownOption);
            offEvent(EVENT_MOUSE_DOWN$2, r[2], onPointerDownOption);
            offEvent(EVENT_MOUSE_UP$1, r[2], onPointerUpOption);
            offEvent(EVENT_TOUCH_END$1, r[2], onPointerUpOption);
            offEvent(EVENT_TOUCH_START$2, r[2], onPointerDownOption);
            letElement(r[2]), letElement(r[3]);
            r = letValueInMap(key, values);
            // Remove empty group(s)
            parent && TOKEN_GROUP === getRole(parent) && 0 === toCount(getChildren(parent)) && letElement(parent);
            parentReal && TOKEN_OPTGROUP === getName(parentReal) && 0 === toCount(getChildren(parentReal)) && letElement(parentReal);
            // Clear value if there are no option(s)
            if (0 === toCount(getChildren(lot))) {
                selectToOptionsNone(of, !isInput(self));
                options.hidden = true;
                // Reset value to the first option if removed option is the selected option
            } else {
                value === valueReal && selectToOptionFirst(of);
            }
            if (!isFunction(state.options)) {
                state.options = values;
            }
            return _fireHook && of.fire('let.option', [key]), r;
        },
        get: function get(key) {
            var $ = this,
                values = $.values,
                value = getValueInMap$1(_toValue(key), values),
                parent;
            if (value && (parent = getParent(value[2])) && TOKEN_GROUP === getRole(parent)) {
                return [getElementIndex(value[2]), getElementIndex(parent)];
            }
            return value ? getElementIndex(value[2]) : -1;
        },
        has: function has(key) {
            return hasKeyInMap(_toValue(key), this[TOKEN_VALUES$1]);
        },
        let: function _let(key, _fireHook, _fireValue) {
            if (_fireHook === void 0) {
                _fireHook = 1;
            }
            if (_fireValue === void 0) {
                _fireValue = 1;
            }
            return this.delete(key, _fireHook, _fireValue);
        },
        set: function set(key, value, _fireHook) {
            var _getState3, _getState4, _getState5;
            if (_fireHook === void 0) {
                _fireHook = 1;
            }
            var $ = this,
                of = $.of,
                values = $.values,
                _active = of._active;
            if (!_active) {
                return false;
            }
            if ($.has(key = _toValue(key))) {
                return _fireHook && of.fire('has.option', [key]), false;
            }
            var _mask = of._mask,
                self = of.self,
                state = of.state,
                lot = _mask.lot,
                options = _mask.options,
                n = state.n,
                itemsParent,
                option,
                optionGroup,
                optionGroupReal,
                optionReal,
                optionText;
            if (isInput(self)) {
                (itemsParent = self.list) ? getChildren(itemsParent): [];
            } else {
                getChildren(itemsParent = self);
            }
            options.hidden = false;
            // Force `id` attribute(s)
            setID(itemsParent);
            // `picker.options.set('asdf')`
            if (!isSet(value)) {
                value = [key, {}];
                // `picker.options.set('asdf', 'asdf')`
            } else if (isFloat(value) || isInteger(value) || isString(value)) {
                value = [value, {}];
                // `picker.options.set('asdf', [ … ])`
            } else;
            if (hasState(value[1], '&')) {
                var _getState;
                optionGroup = getElement('.' + n + '__options-batch[value="' + _fromValue(value[1]['&']).replace(/"/g, '\\"') + '"]', lot);
                optionGroupReal = getElement(TOKEN_OPTGROUP + '[label="' + _fromValue(value[1]['&']).replace(/"/g, '\\"') + '"]', self) || setElement(TOKEN_OPTGROUP, {
                    'label': value[1]['&'],
                    'title': (_getState = getState(value[1], 'title')) != null ? _getState : false
                });
                if (!optionGroup || getOptionValue(optionGroup) !== value[1]['&']) {
                    var _getState2;
                    setChildLast(lot, optionGroup = setElement('data', {
                        'class': n + '__options-batch',
                        'role': TOKEN_GROUP,
                        'title': (_getState2 = getState(value[1], 'title')) != null ? _getState2 : false,
                        'value': value[1]['&']
                    }));
                    setChildLast(itemsParent, optionGroupReal);
                    // Force `id` attribute(s)
                    setID(optionGroup);
                    setID(optionGroupReal);
                }
            } else {
                optionGroup = optionGroupReal = false;
            }
            var _value$ = value[1],
                active = _value$.active,
                mark = _value$.mark,
                v = _value$.value;
            if (!isSet(active)) {
                active = true;
            }
            v = _fromValue(v || key);
            option = value[2] || setElement('data', {
                'aria': {
                    'disabled': active ? false : TOKEN_TRUE$1,
                    'selected': mark ? TOKEN_TRUE$1 : false
                },
                'class': n + '__option',
                'data': {
                    'batch': (_getState3 = getState(value[1], '&')) != null ? _getState3 : false
                },
                'role': 'option',
                'tabindex': active ? -1 : false,
                'title': (_getState4 = getState(value[1], 'title')) != null ? _getState4 : false,
                'value': v
            });
            optionReal = value[3] || setElement('option', _fromValue(value[0]), {
                'disabled': active ? false : "",
                'selected': mark ? "" : false,
                'title': (_getState5 = getState(value[1], 'title')) != null ? _getState5 : false,
                'value': v
            });
            optionText = value[2] ? value[2].$[OPTION_TEXT] : setElement('span', _fromValue(value[0]), {
                'class': n + '__v',
                'role': 'none'
            });
            // Force `id` attribute(s)
            setID(option);
            setID(optionReal);
            setID(optionText);
            option.$ = {};
            option.$[OPTION_SELF] = optionReal;
            option.$[OPTION_TEXT] = optionText;
            if (active && !value[2]) {
                onEvent(EVENT_FOCUS$1, option, onFocusOption);
                onEvent(EVENT_KEY_DOWN$1, option, onKeyDownOption);
                onEvent(EVENT_MOUSE_DOWN$2, option, onPointerDownOption);
                onEvent(EVENT_MOUSE_UP$1, option, onPointerUpOption);
                onEvent(EVENT_TOUCH_END$1, option, onPointerUpOption);
                onEvent(EVENT_TOUCH_START$2, option, onPointerDownOption);
            }
            setChildLast(option, optionText);
            setChildLast(optionGroup || lot, option);
            setChildLast(optionGroupReal || itemsParent, optionReal);
            setReference$2(option, of);
            value[2] = option;
            value[3] = optionReal;
            _fireHook && of.fire('is.option', [key]);
            setValueInMap$1(key, value, values);
            if (!isFunction(state.options)) {
                state.options = values;
            }
            return _fireHook && of.fire('set.option', [key]), true;
        }
    });
    // In order for an object to be iterable, it must have a `Symbol.iterator` key
    getPrototype(OptionPickerOptions)[Symbol.iterator] = function () {
        return this[TOKEN_VALUES$1][Symbol.iterator]();
    };
    OptionPicker.Options = OptionPickerOptions;

    function onChange$b(init) {
        var sources = getElements('input[list]:not([type=hidden]),select');
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            var c = getClasses(source);
            letClasses(source);
            var $ = new OptionPicker(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            setClasses($.mask, c);
        });
        1 === init && W._.on('change', onChange$b);
    }
    W.OP = W.OptionPicker = OptionPicker;
    var EVENT_DOWN$1 = 'down';
    var EVENT_UP$1 = 'up';
    var EVENT_BLUR = 'blur';
    var EVENT_COPY = 'copy';
    var EVENT_CUT = 'cut';
    var EVENT_FOCUS = 'focus';
    var EVENT_INPUT = 'input';
    var EVENT_INPUT_START = 'before' + EVENT_INPUT;
    var EVENT_INVALID = 'invalid';
    var EVENT_KEY = 'key';
    var EVENT_KEY_DOWN = EVENT_KEY + EVENT_DOWN$1;
    var EVENT_KEY_UP = EVENT_KEY + EVENT_UP$1;
    var EVENT_MOUSE$1 = 'mouse';
    var EVENT_MOUSE_DOWN$1 = EVENT_MOUSE$1 + EVENT_DOWN$1;
    var EVENT_PASTE = 'paste';
    var EVENT_RESET = 'reset';
    var EVENT_SUBMIT = 'submit';
    var EVENT_TOUCH$1 = 'touch';
    var EVENT_TOUCH_START$1 = EVENT_TOUCH$1 + 'start';
    var KEY_LEFT = 'Left';
    var KEY_RIGHT = 'Right';
    var KEY_A = 'a';
    var KEY_ARROW = 'Arrow';
    var KEY_ARROW_LEFT$1 = KEY_ARROW + KEY_LEFT;
    var KEY_ARROW_RIGHT$1 = KEY_ARROW + KEY_RIGHT;
    var KEY_BEGIN = 'Home';
    var KEY_DELETE_LEFT$1 = 'Backspace';
    var KEY_DELETE_RIGHT$1 = 'Delete';
    var KEY_END = 'End';
    var KEY_ENTER$1 = 'Enter';
    var KEY_ESCAPE = 'Escape';
    var KEY_TAB = 'Tab';
    var KEY_Y = 'y';
    var KEY_Z = 'z';
    var TOKEN_CONTENTEDITABLE = 'contenteditable';
    var TOKEN_DISABLED = 'disabled';
    var TOKEN_FALSE = 'false';
    var TOKEN_INVALID = EVENT_INVALID;
    var TOKEN_PRESSED = 'pressed';
    var TOKEN_READONLY = 'readonly';
    var TOKEN_READ_ONLY = 'readOnly';
    var TOKEN_REQUIRED = 'required';
    var TOKEN_TABINDEX = 'tabindex';
    var TOKEN_TAB_INDEX = 'tabIndex';
    var TOKEN_TRUE = 'true';
    var TOKEN_VALUE = 'value';
    var TOKEN_VALUES = TOKEN_VALUE + 's';
    var TOKEN_VISIBILITY = 'visibility';
    var _delay = delay(function (picker) {
            letAria(picker.mask, TOKEN_INVALID);
        }),
        _delay2 = _maybeArrayLike(_slicedToArray, _delay, 2),
        letError = _delay2[0],
        letErrorAbort = _delay2[1];
    var setError = function setError(picker) {
        var mask = picker.mask,
            state = picker.state,
            time = state.time,
            error = time.error;
        if (isInteger(error) && error > 0) {
            setAria(mask, TOKEN_INVALID, true);
        }
    };
    var _delay3 = delay(function ($) {
            saveState($);
        }, 1),
        _delay4 = _maybeArrayLike(_slicedToArray, _delay3, 1),
        saveStateLazy = _delay4[0];
    var _delay5 = delay(function (picker) {
            var _mask = picker._mask,
                input = _mask.input;
            toggleHintByValue(picker, getText(input, 0));
        }),
        _delay6 = _maybeArrayLike(_slicedToArray, _delay5, 1),
        toggleHint = _delay6[0];
    var toggleHintByValue = function toggleHintByValue(picker, value) {
        var _mask = picker._mask,
            hint = _mask.hint;
        value ? setStyle(hint, TOKEN_VISIBILITY, 'hidden') : letStyle(hint, TOKEN_VISIBILITY);
    };
    var name$4 = 'TagPicker';
    var _keyIsCtrl, _keyIsShift, _keyOverTag;

    function createTags($, tags) {
        var map = isInstance(tags, Map) ? tags : new Map();
        if (isArray(tags)) {
            forEachArray(tags, function (tag) {
                if (isArray(tag)) {
                    var _tag$, _tag$2, _tag$1$TOKEN_VALUE;
                    tag[0] = (_tag$ = tag[0]) != null ? _tag$ : "";
                    tag[1] = (_tag$2 = tag[1]) != null ? _tag$2 : {};
                    setValueInMap$1(_toValue((_tag$1$TOKEN_VALUE = tag[1][TOKEN_VALUE]) != null ? _tag$1$TOKEN_VALUE : tag[0]), tag, map);
                } else {
                    setValueInMap$1(_toValue(tag), [tag, {}], map);
                }
            });
        } else if (isObject(tags, 0)) {
            forEachObject(tags, function (v, k) {
                if (isArray(v)) {
                    var _v$, _v$2, _v$1$TOKEN_VALUE;
                    tags[k][0] = (_v$ = v[0]) != null ? _v$ : "";
                    tags[k][1] = (_v$2 = v[1]) != null ? _v$2 : {};
                    setValueInMap$1(_toValue((_v$1$TOKEN_VALUE = v[1][TOKEN_VALUE]) != null ? _v$1$TOKEN_VALUE : k), v, map);
                } else {
                    setValueInMap$1(_toValue(k), [v, {}], map);
                }
            });
        }
        var _tags = $._tags,
            r = [];
        // Reset the tag(s) data, but do not fire the `let.tags` hook
        _tags.let(null, 0);
        forEachMap(map, function (v, k) {
            var _v$1$TOKEN_VALUE3;
            if (isArray(v) && v[1]) {
                var _v$1$TOKEN_VALUE2;
                r.push((_v$1$TOKEN_VALUE2 = v[1][TOKEN_VALUE]) != null ? _v$1$TOKEN_VALUE2 : k);
            }
            // Set the tag data, but do not fire the `set.tag` hook
            _tags.set(_toValue(isArray(v) && v[1] ? (_v$1$TOKEN_VALUE3 = v[1][TOKEN_VALUE]) != null ? _v$1$TOKEN_VALUE3 : k : k), v, 0);
        });
        return r;
    }

    function focusTo(node) {
        return node.focus(), node;
    }

    function getTagValue(tag, parseValue) {
        return getValue$1(tag, parseValue);
    }
    // Do not allow user(s) to edit the tag text
    function onBeforeInputTag(e) {
        offEventDefault(e);
    }
    // Better mobile support
    function onBeforeInputTextInput(e) {
        var $ = this,
            data = e.data,
            inputType = e.inputType,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix;
        if (!_active || _fix) {
            return offEventDefault(e);
        }
        var _tags = picker._tags,
            state = picker.state,
            escape = state.escape,
            exit,
            key,
            tagLast,
            v;
        key = isString(data) && 1 === toCount(data) ? data : 0;
        if (KEY_ENTER$1 === key && (hasValue('\n', escape) || hasValue(13, escape)) || KEY_TAB === key && (hasValue('\t', escape) || hasValue(9, escape)) || 0 !== key && hasValue(key, escape)) {
            exit = true;
            setValueInMap$1(_toValue(v = getText($)), v, _tags);
            focusTo(picker).text = "";
        } else if ('deleteContentBackward' === inputType && !getText($, 0)) {
            if (tagLast = toValueLastFromMap(_tags)) {
                exit = true;
                letValueInMap(getTagValue(tagLast[2]), _tags);
            }
        }
        exit && offEventDefault(e);
    }

    function onBlurTag() {
        var $ = this,
            picker = getReference$2($),
            _tags = picker._tags;
        if (!_keyIsCtrl && !_keyIsShift) {
            forEachMap(_tags, function (v) {
                return letAria(v[2], TOKEN_PRESSED);
            });
        }
    }

    function onBlurTextInput() {
        var $ = this,
            picker = getReference$2($),
            mask = picker.mask,
            state = picker.state,
            time = state.time,
            error = time.error;
        letError(isInteger(error) && error > 0 ? error : 0, picker);
        onEvent(EVENT_MOUSE_DOWN$1, mask, onPointerDownMask);
        onEvent(EVENT_TOUCH_START$1, mask, onPointerDownMask);
    }

    function onCopyTag(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            _tags = picker._tags,
            state = picker.state,
            join = state.join,
            selected = [];
        setAria($, TOKEN_PRESSED, true);
        forEachMap(_tags, function (v) {
            if (getAria(v[2], TOKEN_PRESSED)) {
                selected.push(getTagValue(v[2]));
            }
        });
        e.clipboardData.setData('text/plain', selected.join(join));
        if (EVENT_CUT !== e.type && toCount(selected) < 2) {
            letAria($, TOKEN_PRESSED);
        }
    }

    function onCutTag(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            _tags = picker._tags;
        onCopyTag.call($, e);
        forEachMap(_tags, function (v) {
            if (getAria(v[2], TOKEN_PRESSED)) {
                letValueInMap(getTagValue(v[2]), _tags);
            }
        });
        focusTo(picker.fire('change', [picker[TOKEN_VALUE]]));
    }

    function onCutTextInput() {
        var $ = this;
        saveState($), toggleHint(1, getReference$2($)), saveStateLazy($);
    }

    function onFocusSelf() {
        focusTo(getReference$2(this));
    }
    // Select the tag text on focus to hide the text cursor
    function onFocusTag() {
        selectTo(this);
    }

    function onFocusTextInput() {
        var $ = this,
            picker = getReference$2($),
            mask = picker.mask,
            state = picker.state,
            pattern = state.pattern,
            value = getText($);
        if (value && isString(pattern) && !toPattern(pattern).test(value)) {
            letErrorAbort(), setError(picker);
        }
        selectTo($);
        offEvent(EVENT_MOUSE_DOWN$1, mask, onPointerDownMask);
        offEvent(EVENT_TOUCH_START$1, mask, onPointerDownMask);
    }
    // Better mobile support
    function onInputTextInput(e) {
        var $ = this,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix;
        if (!_active || _fix) {
            return offEventDefault(e);
        }
        var state = picker.state,
            pattern = state.pattern,
            inputType = e.inputType,
            v = getText($, 0);
        if ('deleteContent' === inputType.slice(0, 13) && !v) {
            toggleHintByValue(picker, 0), saveStateLazy($);
        } else if ('insertText' === inputType) {
            toggleHintByValue(picker, 1), saveStateLazy($);
        }
        if (isString(pattern) && !toPattern(pattern).test(v)) {
            letErrorAbort(), setError(picker);
        } else {
            letError(0, picker);
        }
    }

    function onInvalidSelf(e) {
        e && offEventDefault(e);
        var $ = this;
        onBlurTextInput.call($), setError(getReference$2($));
    }

    function onKeyDownTag(e) {
        var $ = _keyOverTag = this,
            key = e.key,
            keyIsCtrl = _keyIsCtrl = e.ctrlKey,
            keyIsShift = _keyIsShift = e.shiftKey,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix;
        if (!_active || _fix) {
            return offEventDefault(e);
        }
        var _mask = picker._mask,
            _tags = picker._tags,
            text = _mask.text,
            exit,
            tagFirst,
            tagLast,
            tagNext,
            tagPrev;
        if (keyIsShift) {
            exit = true;
            setAria($, TOKEN_PRESSED, true);
            if (KEY_ARROW_LEFT$1 === key) {
                if (tagPrev = getPrev($)) {
                    if (getAria(tagPrev, TOKEN_PRESSED)) {
                        letAria($, TOKEN_PRESSED);
                    } else {
                        setAria(tagPrev, TOKEN_PRESSED, true);
                    }
                    focusTo(tagPrev);
                }
            } else if (KEY_ARROW_RIGHT$1 === key) {
                if ((tagNext = getNext($)) && tagNext !== text) {
                    if (getAria(tagNext, TOKEN_PRESSED)) {
                        letAria($, TOKEN_PRESSED);
                    } else {
                        setAria(tagNext, TOKEN_PRESSED, true);
                    }
                    focusTo(tagNext);
                }
            } else if (KEY_TAB === key) {
                selectToNone();
            }
        } else if (keyIsCtrl) {
            if (KEY_A === key) {
                exit = true;
                forEachMap(_tags, function (v) {
                    return setAria(v[2], TOKEN_PRESSED, true), focusTo(v[2]), selectTo(v[2]);
                });
            } else if (KEY_ARROW_LEFT$1 === key) {
                exit = true;
                if (tagPrev = getPrev($)) {
                    focusTo(tagPrev);
                }
            } else if (KEY_ARROW_RIGHT$1 === key) {
                exit = true;
                if ((tagNext = getNext($)) && tagNext !== text) {
                    focusTo(tagNext);
                }
            } else if (KEY_BEGIN === key) {
                exit = true;
                tagFirst = toValueFirstFromMap(_tags);
                tagFirst && focusTo(tagFirst[2]);
            } else if (KEY_END === key) {
                exit = true;
                tagLast = toValueLastFromMap(_tags);
                tagLast && focusTo(tagLast[2]);
            } else if (KEY_ENTER$1 === key || ' ' === key) {
                exit = true;
                getAria($, TOKEN_PRESSED) ? letAria($, TOKEN_PRESSED) : setAria($, TOKEN_PRESSED, true);
            } else {
                setAria($, TOKEN_PRESSED, true);
            }
        } else {
            if (KEY_ARROW_LEFT$1 === key) {
                exit = true;
                if (tagPrev = getPrev($)) {
                    focusTo(tagPrev);
                }
            } else if (KEY_ARROW_RIGHT$1 === key) {
                exit = true;
                focusTo((tagNext = getNext($)) && tagNext !== text ? tagNext : picker);
            } else if (KEY_BEGIN === key) {
                exit = true;
                tagFirst = toValueFirstFromMap(_tags);
                tagFirst && focusTo(tagFirst[2]);
            } else if (KEY_END === key) {
                exit = true;
                tagLast = toValueLastFromMap(_tags);
                tagLast && focusTo(tagLast[2]);
            } else if (KEY_DELETE_LEFT$1 === key) {
                exit = true;
                tagPrev = getPrev($);
                letValueInMap(getTagValue($), _tags);
                forEachMap(_tags, function (v) {
                    if (getAria(v[2], TOKEN_PRESSED)) {
                        tagPrev = getPrev(v[2]);
                        letValueInMap(getTagValue(v[2]), _tags);
                    }
                });
                focusTo(tagPrev || picker), picker.fire('change', [picker[TOKEN_VALUE]]);
            } else if (KEY_DELETE_RIGHT$1 === key) {
                exit = true;
                tagNext = getNext($);
                letValueInMap(getTagValue($), _tags);
                forEachMap(_tags, function (v) {
                    if (getAria(v[2], TOKEN_PRESSED)) {
                        tagNext = getNext(v[2]);
                        letValueInMap(getTagValue(v[2]), _tags);
                    }
                });
                focusTo(tagNext && tagNext !== text ? tagNext : picker), picker.fire('change', [picker[TOKEN_VALUE]]);
            } else if (KEY_ENTER$1 === key || ' ' === key) {
                exit = true;
                getAria($, TOKEN_PRESSED) ? letAria($, TOKEN_PRESSED) : setAria($, TOKEN_PRESSED, true);
            } else if (KEY_ESCAPE === key || KEY_TAB === key) {
                exit = true;
                selectToNone(), focusTo(picker);
                // Any type-able key
            } else if (1 === toCount(key)) {
                forEachMap(_tags, function (v) {
                    if (getAria(v[2], TOKEN_PRESSED)) {
                        letValueInMap(getTagValue(v[2]), _tags);
                    }
                });
                selectToNone(), focusTo(picker).fire('change', [picker[TOKEN_VALUE]]);
            }
        }
        exit && offEventDefault(e);
    }

    function onKeyDownTextInput(e) {
        var $ = this,
            key = e.key,
            keyCode = e.keyCode,
            keyIsCtrl = _keyIsCtrl = e.ctrlKey,
            keyIsShift = _keyIsShift = e.shiftKey,
            picker = getReference$2($),
            _active = picker._active,
            _fix = picker._fix;
        if (!_active || _fix) {
            return;
        }
        var _tags = picker._tags,
            self = picker.self,
            state = picker.state,
            escape = state.escape,
            exit,
            form,
            submit,
            v;
        if (KEY_ENTER$1 === key && (hasValue('\n', escape) || hasValue(13, escape)) || KEY_TAB === key && (hasValue('\t', escape) || hasValue(9, escape)) || hasValue(key, escape) || hasValue(keyCode, escape)) {
            setValueInMap$1(_toValue(v = getText($)), v, _tags);
            return focusTo(picker).text = "", offEventDefault(e);
        }
        toggleHint(1, picker);
        var caretIsToTheFirst = "" === getCharBeforeCaret($),
            tagFirst,
            tagLast,
            textIsVoid = !getText($, 0);
        if (keyIsShift) {
            if (KEY_ARROW_LEFT$1 === key) {
                if (caretIsToTheFirst || textIsVoid) {
                    exit = true;
                    selectToNone();
                    tagLast = toValueLastFromMap(_tags);
                    tagLast && focusTo(tagLast[2]) && setAria(tagLast[2], TOKEN_PRESSED, true);
                }
            } else if (KEY_ENTER$1 === key) {
                exit = true;
            } else if (KEY_TAB === key) {
                selectToNone();
            }
        } else if (keyIsCtrl) {
            if (KEY_A === toCaseLower(key) && textIsVoid && _tags.count()) {
                exit = true;
                forEachMap(_tags, function (v) {
                    return setAria(v[2], TOKEN_PRESSED, true), focusTo(v[2]), selectTo(v[2]);
                });
            } else if (KEY_ARROW_LEFT$1 === key) {
                exit = true;
                tagLast = toValueLastFromMap(_tags);
                tagLast && focusTo(tagLast[2]);
            } else if (KEY_BEGIN === key) {
                exit = true;
                tagFirst = toValueFirstFromMap(_tags);
                tagFirst && focusTo(tagFirst[2]);
            } else if (KEY_ENTER$1 === key) {
                exit = true;
            } else if (!keyIsShift && KEY_Z === toCaseLower(key)) {
                exit = true;
                undoState($);
            } else if (keyIsShift && KEY_Z === toCaseLower(key) || KEY_Y === toCaseLower(key)) {
                exit = true;
                redoState($);
            }
        } else {
            if (KEY_BEGIN === key) {
                exit = true;
                tagFirst = toValueFirstFromMap(_tags);
                tagFirst && focusTo(tagFirst[2]);
            } else if (KEY_ENTER$1 === key) {
                exit = true;
                if ((form = getParentForm(self)) && isFunction(form.requestSubmit)) {
                    // <https://developer.mozilla.org/en-US/docs/Glossary/Submit_button>
                    submit = getElement('button:not([type]),button[type=submit],input[type=image],input[type=submit]', form);
                    submit ? form.requestSubmit(submit) : form.requestSubmit();
                }
            } else if (KEY_TAB === key) {
                selectToNone();
            } else if (caretIsToTheFirst || textIsVoid) {
                if (KEY_ARROW_LEFT$1 === key) {
                    exit = true;
                    selectToNone();
                    tagLast = toValueLastFromMap(_tags);
                    tagLast && focusTo(tagLast[2]);
                } else if (KEY_DELETE_LEFT$1 === key) {
                    if (textIsVoid) {
                        exit = true;
                        tagLast = toValueLastFromMap(_tags);
                        tagLast && letValueInMap(getTagValue(tagLast[2]), _tags);
                    }
                }
            }
        }
        exit && offEventDefault(e);
    }

    function onKeyUpTag(e) {
        _keyOverTag = 0;
        var $ = this,
            key = e.key,
            picker = getReference$2($),
            _tags = picker._tags,
            selected = 0;
        forEachMap(_tags, function (v) {
            if (getAria(v[2], TOKEN_PRESSED)) {
                ++selected;
            }
        });
        _keyIsCtrl = e.ctrlKey;
        _keyIsShift = e.shiftKey;
        if (selected < 2 && !_keyIsCtrl && !_keyIsShift && KEY_ENTER$1 !== key && ' ' !== key) {
            letAria($, TOKEN_PRESSED);
        }
    }

    function onKeyUpTextInput(e) {
        _keyIsCtrl = e.ctrlKey;
        _keyIsShift = e.shiftKey;
    }

    function onPasteTag(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            _tags = picker._tags,
            state = picker.state,
            join = state.join;
        forEachArray(e.clipboardData.getData('text/plain').split(join), function (v) {
            if (!hasKeyInMap(v = _toValue(v.trim()), _tags)) {
                setValueInMap$1(v, v, _tags);
            }
        });
        forEachMap(_tags, function (v) {
            return letAria(v[2], TOKEN_PRESSED);
        });
        focusTo(picker.fire('change', [picker[TOKEN_VALUE]]));
    }

    function onPasteTextInput(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            _tags = picker._tags,
            self = picker.self,
            state = picker.state,
            join = state.join,
            v;
        saveState($), toggleHint(1, picker), insertAtSelection($, v = e.clipboardData.getData('text/plain')), saveStateLazy($);
        if (v !== getText($));
        else {
            forEachArray((getText($) + "").split(join), function (v) {
                if (!hasKeyInMap(v = _toValue(v.trim()), _tags)) {
                    setValueInMap$1(v, v, _tags);
                } else {
                    onInvalidSelf.call(self);
                    picker.fire('has.tag', [_toValue(v)]);
                }
            });
            forEachMap(_tags, function (v) {
                return letAria(v[2], TOKEN_PRESSED);
            });
            picker.fire('change', [picker[TOKEN_VALUE]]).text = "";
        }
    }

    function onPointerDownMask(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            target = e.target;
        // Is it focused on a tag mask?
        if (target && 'option' === getRole(target)) {
            return; // Yes it is!
        }
        // Is it focused on a node in the tag mask?
        while (target && $ !== target) {
            target = getParent(target);
            if (target && 'option' === getRole(target)) {
                return; // Yes it is!
            }
        }
        // It focuses on something else in the root mask. The default is to execute `picker.focus()`
        focusTo(picker);
    }

    function onPointerDownTag$1(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            _tags = picker._tags;
        focusTo($), selectTo($);
        if (!_keyIsCtrl) {
            forEachMap(_tags, function (v) {
                return letAria(v[2], TOKEN_PRESSED);
            });
        }
        if (_keyIsCtrl) {
            setAria($, TOKEN_PRESSED, true);
        } else if (_keyIsShift && _keyOverTag) {
            var tagEndIndex = getElementIndex($),
                tagStartIndex = getElementIndex(_keyOverTag),
                tagCurrent = _keyOverTag,
                tagNext,
                tagPrev;
            setAria($, TOKEN_PRESSED, true);
            setAria(_keyOverTag, TOKEN_PRESSED, true);
            // Select to the right
            if (tagEndIndex > tagStartIndex) {
                while (tagNext = getNext(tagCurrent)) {
                    if ($ === tagNext) {
                        break;
                    }
                    setAria(tagCurrent = tagNext, TOKEN_PRESSED, true);
                }
                // Select to the left
            } else if (tagEndIndex < tagStartIndex) {
                while (tagPrev = getPrev(tagCurrent)) {
                    if ($ === tagPrev) {
                        break;
                    }
                    setAria(tagCurrent = tagPrev, TOKEN_PRESSED, true);
                }
            }
        }
    }

    function onPointerDownTagX(e) {
        offEventDefault(e);
        var $ = this,
            tag = getParent($),
            picker = getReference$2(tag),
            _active = picker._active,
            _fix = picker._fix;
        if (!_active || _fix) {
            return focusTo(picker);
        }
        var _tags = picker._tags;
        letValueInMap(getTagValue(tag), _tags);
        focusTo(picker);
    }

    function onResetForm() {
        forEachSet(getReference$2(this), function ($) {
            return $.reset();
        });
    }

    function onSubmitForm(e) {
        forEachSet(getReference$2(this), function (picker) {
            var _tags = picker._tags,
                max = picker.max,
                min = picker.min,
                self = picker.self,
                count = _tags.count(),
                exit;
            if (count > max) {
                exit = true;
                focusTo(picker.fire('max.tags', [count, max]));
            } else if (count < min) {
                exit = true;
                focusTo(picker.fire('min.tags', [count, min]));
            }
            exit && (onInvalidSelf.call(self), offEventDefault(e));
        });
    }

    function TagPicker(self, state) {
        var $ = this;
        if (!self) {
            return $;
        }
        // Return new instance if `TagPicker` was called without the `new` operator
        if (!isInstance($, TagPicker)) {
            return new TagPicker(self, state);
        }
        setReference$2(self, hook($, TagPicker._));
        var newState = _fromStates({}, TagPicker.state, isString(state) ? {
            join: state
        } : state || {});
        // Special case for `state.escape`: replace instead of join the value(s)
        if (isObject(state) && state.escape) {
            newState.escape = state.escape;
        }
        return $.attach(self, newState);
    }

    function TagPickerTags(of, tags) {
        var $ = this;
        // Return new instance if `TagPickerTags` was called without the `new` operator
        if (!isInstance($, TagPickerTags)) {
            return new TagPickerTags(of, tags);
        }
        $.of = of;
        $[TOKEN_VALUES] = new Map();
        if (tags) {
            createTags(of, tags);
        }
        return $;
    }
    TagPicker.from = function (self, state) {
        return new TagPicker(self, state);
    };
    TagPicker.of = getReference$2;
    TagPicker.state = {
        'escape': [','],
        'join': ', ',
        'max': Infinity,
        'min': 0,
        'n': 'tag-picker',
        'pattern': null,
        'time': {
            'error': 1000
        },
        'with': []
    };
    TagPicker.version = '4.2.8';
    setObjectAttributes(TagPicker, {
        name: {
            value: name$4
        }
    }, 1);
    setObjectAttributes(TagPicker, {
        active: {
            get: function get() {
                return this._active;
            },
            set: function set(value) {
                selectToNone();
                var $ = this,
                    _mask = $._mask,
                    _tags = $._tags,
                    mask = $.mask,
                    self = $.self,
                    input = _mask.input,
                    v = !!value;
                self[TOKEN_DISABLED] = !($._active = v);
                if (v) {
                    letAria(input, TOKEN_DISABLED);
                    letAria(mask, TOKEN_DISABLED);
                    setAttribute(input, TOKEN_CONTENTEDITABLE, "");
                    forEachMap(_tags, function (v) {
                        setAttribute(v[2], TOKEN_CONTENTEDITABLE, "");
                        setAttribute(v[2], TOKEN_TABINDEX, -1);
                    });
                } else {
                    letAttribute(input, TOKEN_CONTENTEDITABLE);
                    setAria(input, TOKEN_DISABLED, true);
                    setAria(mask, TOKEN_DISABLED, true);
                    forEachMap(_tags, function (v) {
                        letAttribute(v[2], TOKEN_CONTENTEDITABLE);
                        letAttribute(v[2], TOKEN_TABINDEX);
                    });
                }
                return $;
            }
        },
        fix: {
            get: function get() {
                return this._fix;
            },
            set: function set(value) {
                selectToNone();
                var $ = this,
                    _mask = $._mask,
                    _tags = $._tags,
                    mask = $.mask,
                    self = $.self,
                    input = _mask.input,
                    v = !!value;
                self[TOKEN_READ_ONLY] = $._fix = v;
                if (v) {
                    letAttribute(input, TOKEN_CONTENTEDITABLE);
                    setAria(input, TOKEN_READONLY, true);
                    setAria(mask, TOKEN_READONLY, true);
                    setAttribute(input, TOKEN_TABINDEX, 0);
                    forEachMap(_tags, function (v) {
                        letAttribute(v[2], TOKEN_CONTENTEDITABLE);
                        letAttribute(v[2], TOKEN_TABINDEX);
                    });
                } else {
                    letAria(input, TOKEN_READONLY);
                    letAria(mask, TOKEN_READONLY);
                    letAttribute(input, TOKEN_TABINDEX);
                    setAttribute(input, TOKEN_CONTENTEDITABLE, "");
                    forEachMap(_tags, function (v) {
                        setAttribute(v[2], TOKEN_CONTENTEDITABLE, "");
                        setAttribute(v[2], TOKEN_TABINDEX, -1);
                    });
                }
                return $;
            }
        },
        max: {
            get: function get() {
                var max = this.state.max;
                return Infinity === max || isInteger(max) && max >= 0 ? max : Infinity;
            },
            set: function set(value) {
                var $ = this;
                return $.state.max = isInteger(value) && value >= 0 ? value : Infinity, $;
            }
        },
        min: {
            get: function get() {
                var min = this.state.min;
                return isInteger(min) && min >= 0 ? min : 0;
            },
            set: function set(value) {
                var $ = this;
                return $.state.min = isInteger(value) && value >= 0 ? value : 0, $;
            }
        },
        tags: {
            get: function get() {
                return this._tags;
            },
            set: function set(tags) {
                selectToNone();
                var $ = this,
                    tagsValues = [];
                createTags($, tags);
                forEachMap($._tags, function (v) {
                    return tagsValues.push(getTagValue(v[2], 1));
                });
                return $.fire('set.tags', [tagsValues]);
            }
        },
        text: {
            get: function get() {
                return getText(this._mask.input);
            },
            set: function set(value) {
                var $ = this,
                    _active = $._active,
                    _fix = $._fix;
                if (!_active || _fix) {
                    return $;
                }
                var _mask = $._mask,
                    input = _mask.input,
                    v;
                return setText(input, v = _fromValue(value)), toggleHintByValue($, v), resetState(input), $;
            }
        },
        value: {
            get: function get() {
                var value = getValue$1(this.self);
                return "" !== value ? value : null;
            },
            set: function set(value) {
                var $ = this,
                    _active = $._active;
                if (!_active) {
                    return $;
                }
                var _tags = $._tags,
                    state = $.state,
                    join = state.join;
                $[TOKEN_VALUE] && forEachArray($[TOKEN_VALUE].split(join), function (v) {
                    return letValueInMap(v, _tags);
                });
                value && forEachArray(value.split(join), function (v) {
                    return setValueInMap$1(v, v, _tags);
                });
                return $.fire('change', [$[TOKEN_VALUE]]);
            }
        },
        vital: {
            get: function get() {
                return this._vital;
            },
            set: function set(value) {
                selectToNone();
                var $ = this,
                    _mask = $._mask,
                    mask = $.mask,
                    min = $.min,
                    self = $.self,
                    input = _mask.input,
                    v = !!value;
                self[TOKEN_REQUIRED] = $._vital = v;
                if (v) {
                    if (0 === min) {
                        $.min = 1;
                    }
                    setAria(input, TOKEN_REQUIRED, true);
                    setAria(mask, TOKEN_REQUIRED, true);
                } else {
                    $.min = 0;
                    letAria(input, TOKEN_REQUIRED);
                    letAria(mask, TOKEN_REQUIRED);
                }
                return $;
            }
        }
    });
    TagPicker._ = setObjectMethods(TagPicker, {
        attach: function attach(self, state) {
            var $ = this;
            self = self || $.self;
            if (state && isString(state)) {
                state = {
                    join: state
                };
            }
            state = _fromStates({}, $.state, state || {});
            $._tags = new TagPickerTags($);
            $.self = self;
            $.state = state;
            var _state = state,
                max = _state.max,
                min = _state.min,
                n = _state.n,
                isDisabledSelf = isDisabled$1(self),
                isReadOnlySelf = isReadOnly$1(self),
                isRequiredSelf = isRequired(self),
                theInputID = self.id,
                theInputName = self.name,
                theInputPlaceholder = self.placeholder,
                theInputValue = getValue$1(self);
            $._active = !isDisabledSelf;
            $._fix = isReadOnlySelf;
            $._vital = isRequiredSelf;
            if (isRequiredSelf && min < 1) {
                state.min = min = 1; // Force minimum tag(s) to insert to be `1`
            }
            var form = getParentForm(self);
            var mask = setElement('div', {
                'aria': {
                    'disabled': isDisabledSelf ? TOKEN_TRUE : false,
                    'multiselectable': TOKEN_TRUE,
                    'readonly': isReadOnlySelf ? TOKEN_TRUE : false,
                    'required': isRequiredSelf ? TOKEN_TRUE : false
                },
                'class': n,
                'role': 'listbox'
            });
            $.mask = mask;
            var maskFlex = setElement('span', {
                'class': n + '__flex',
                'role': 'none'
            });
            var text = setElement('span', {
                'class': n + '__text',
                'role': 'none'
            });
            var textInput = setElement('span', {
                'aria': {
                    'disabled': isDisabledSelf ? TOKEN_TRUE : false,
                    'multiline': TOKEN_FALSE,
                    'placeholder': theInputPlaceholder,
                    'readonly': isReadOnlySelf ? TOKEN_TRUE : false,
                    'required': isRequiredSelf ? TOKEN_TRUE : false
                },
                'autocapitalize': 'off',
                'contenteditable': isDisabledSelf || isReadOnlySelf ? false : "",
                'role': 'textbox',
                'spellcheck': TOKEN_FALSE,
                'tabindex': isReadOnlySelf ? 0 : false
            });
            var textInputHint = setElement('span', theInputPlaceholder + "", {
                'aria': {
                    'hidden': TOKEN_TRUE
                }
            });
            setChildLast(mask, maskFlex);
            setChildLast(maskFlex, text);
            setChildLast(text, textInput);
            setChildLast(text, textInputHint);
            setAria(self, 'hidden', true);
            setClass(self, n + '__self');
            setReference$2(textInput, $);
            setNext(self, mask);
            setChildLast(mask, self);
            if (form) {
                var set = getReference$2(form) || new Set();
                set.add($);
                onEvent(EVENT_RESET, form, onResetForm);
                onEvent(EVENT_SUBMIT, form, onSubmitForm);
                setID(form);
                setReference$2(form, set);
            }
            onEvent(EVENT_BLUR, textInput, onBlurTextInput);
            onEvent(EVENT_CUT, textInput, onCutTextInput);
            onEvent(EVENT_FOCUS, self, onFocusSelf);
            onEvent(EVENT_FOCUS, textInput, onFocusTextInput);
            onEvent(EVENT_INPUT, textInput, onInputTextInput);
            onEvent(EVENT_INPUT_START, textInput, onBeforeInputTextInput);
            onEvent(EVENT_INVALID, self, onInvalidSelf);
            onEvent(EVENT_KEY_DOWN, textInput, onKeyDownTextInput);
            onEvent(EVENT_KEY_UP, textInput, onKeyUpTextInput);
            onEvent(EVENT_MOUSE_DOWN$1, mask, onPointerDownMask);
            onEvent(EVENT_PASTE, textInput, onPasteTextInput);
            onEvent(EVENT_TOUCH_START$1, mask, onPointerDownMask);
            self[TOKEN_TAB_INDEX] = -1;
            setReference$2(mask, $);
            $._mask = {
                flex: maskFlex,
                hint: textInputHint,
                input: textInput,
                of: self,
                self: mask,
                text: text
            };
            // Re-assign some state value(s) using the setter to either normalize or reject the initial value
            $.max = max = Infinity === max || isInteger(max) && max >= 0 ? max : Infinity;
            $.min = min = isInteger(min) && min >= 0 ? min : 0;
            var _active = $._active,
                _state2 = state,
                join = _state2.join,
                tagsValues;
            // Force the `this._active` value to `true` to set the initial value
            $._active = true;
            // Attach the current tag(s)
            tagsValues = createTags($, theInputValue ? theInputValue.split(join) : []);
            $['_' + TOKEN_VALUE] = tagsValues.join(join);
            // After the initial value has been set, restore the previous `this._active` value
            $._active = _active;
            // Force `id` attribute(s)
            setAria(textInput, 'controls', getID(setID(maskFlex)));
            setID(mask);
            setID(self);
            setID(textInput);
            setID(textInputHint);
            theInputID && setDatum(mask, 'id', theInputID);
            theInputName && setDatum(mask, 'name', theInputName);
            // Attach extension(s)
            if (isSet(state) && isArray(state.with)) {
                forEachArray(state.with, function (v, k) {
                    if (isString(v)) {
                        v = TagPicker[v];
                    }
                    // `const Extension = function (self, state = {}) {}`
                    if (isFunction(v)) {
                        v.call($, self, state);
                        // `const Extension = {attach: function (self, state = {}) {}, detach: function (self, state = {}) {}}`
                    } else if (isObject(v) && isFunction(v.attach)) {
                        v.attach.call($, self, state);
                    }
                });
            }
            return resetState(textInput), $;
        },
        blur: function blur() {
            selectToNone();
            var $ = this,
                _mask = $._mask,
                _tags = $._tags,
                input = _mask.input;
            forEachMap(_tags, function (v) {
                return v[2].blur();
            });
            return input.blur(), $;
        },
        detach: function detach() {
            var $ = this,
                _mask = $._mask,
                mask = $.mask,
                self = $.self,
                state = $.state,
                input = _mask.input;
            var form = getParentForm(self);
            $._active = false;
            $._tags = new TagPickerTags($);
            $['_' + TOKEN_VALUE] = null;
            if (form) {
                offEvent(EVENT_RESET, form, onResetForm);
                offEvent(EVENT_SUBMIT, form, onSubmitForm);
            }
            offEvent(EVENT_BLUR, input, onBlurTextInput);
            offEvent(EVENT_CUT, input, onCutTextInput);
            offEvent(EVENT_FOCUS, input, onFocusTextInput);
            offEvent(EVENT_FOCUS, self, onFocusSelf);
            offEvent(EVENT_INPUT, input, onInputTextInput);
            offEvent(EVENT_INPUT_START, input, onBeforeInputTextInput);
            offEvent(EVENT_INVALID, self, onInvalidSelf);
            offEvent(EVENT_KEY_DOWN, input, onKeyDownTextInput);
            offEvent(EVENT_KEY_UP, input, onKeyUpTextInput);
            offEvent(EVENT_MOUSE_DOWN$1, mask, onPointerDownMask);
            offEvent(EVENT_PASTE, input, onPasteTextInput);
            offEvent(EVENT_TOUCH_START$1, mask, onPointerDownMask);
            // Detach extension(s)
            if (isArray(state.with)) {
                forEachArray(state.with, function (v, k) {
                    if (isString(v)) {
                        v = TagPicker[v];
                    }
                    if (isObject(v) && isFunction(v.detach)) {
                        v.detach.call($, self, state);
                    }
                });
            }
            self[TOKEN_TAB_INDEX] = null;
            letAria(self, 'hidden');
            letClass(self, state.n + '__self');
            setNext(mask, self);
            letElement(mask);
            $._mask = {
                of: self
            };
            $.mask = null;
            return $;
        },
        focus: function focus(mode) {
            var $ = this,
                _active = $._active;
            if (!_active) {
                return $;
            }
            var _mask = $._mask,
                input = _mask.input;
            return focusTo(input), selectTo(input, mode), $;
        },
        reset: function reset(focus, mode) {
            var $ = this,
                _active = $._active;
            if (!_active) {
                return $;
            }
            $[TOKEN_VALUE] = ""; // Clear before reset
            $[TOKEN_VALUE] = $['_' + TOKEN_VALUE];
            return focus ? $.focus(mode) : $;
        }
    });
    setObjectAttributes(TagPickerTags, {
        name: {
            value: name$4 + 'Tags'
        }
    }, 1);
    TagPickerTags._ = setObjectMethods(TagPickerTags, {
        at: function at(key) {
            return getValueInMap$1(_toValue(key), this[TOKEN_VALUES]);
        },
        count: function count() {
            return toMapCount(this[TOKEN_VALUES]);
        },
        // To be used by the `letValueInMap()` function
        delete: function _delete(key, _fireHook) {
            if (_fireHook === void 0) {
                _fireHook = 1;
            }
            var $ = this,
                of = $.of,
                values = $.values,
                _active = of._active;
            if (!_active) {
                return false;
            }
            var min = of.min,
                self = of.self,
                state = of.state,
                join = state.join,
                n = state.n,
                count,
                r,
                tagsValues = [];
            if ((count = $.count()) <= min) {
                _fireHook && onInvalidSelf.call(self);
                return _fireHook && of.fire('min.tags', [count, min]), false;
            }
            if (!isSet(key)) {
                forEachMap(values, function (v, k) {
                    return $.let(k, 0);
                });
                return _fireHook && of.fire('let.tags', [
                    []
                ]).fire('change', [null]), 0 === $.count();
            }
            if (!(r = getValueInMap$1(key = _toValue(key), values))) {
                onInvalidSelf.call(self);
                return _fireHook && of.fire('not.tag', [key]), false;
            }
            var tag = r[2],
                tagX = getElement('.' + n + '__x', tag);
            offEvent(EVENT_BLUR, tag, onBlurTag);
            offEvent(EVENT_COPY, tag, onCopyTag);
            offEvent(EVENT_CUT, tag, onCutTag);
            offEvent(EVENT_FOCUS, tag, onFocusTag);
            offEvent(EVENT_INPUT_START, tag, onBeforeInputTag);
            offEvent(EVENT_KEY_DOWN, tag, onKeyDownTag);
            offEvent(EVENT_KEY_UP, tag, onKeyUpTag);
            offEvent(EVENT_MOUSE_DOWN$1, tag, onPointerDownTag$1);
            offEvent(EVENT_MOUSE_DOWN$1, tagX, onPointerDownTagX);
            offEvent(EVENT_PASTE, tag, onPasteTag);
            offEvent(EVENT_TOUCH_START$1, tag, onPointerDownTag$1);
            offEvent(EVENT_TOUCH_START$1, tagX, onPointerDownTagX);
            letElement(tagX), letElement(tag);
            r = letValueInMap(key, values);
            forEachMap(values, function (v, k) {
                return tagsValues.push(_fromValue(k));
            });
            setValue$1(self, tagsValues = tagsValues.join(join));
            return _fireHook && of.fire('let.tag', [key]).fire('change', ["" !== tagsValues ? tagsValues : null]), r;
        },
        get: function get(key) {
            var $ = this,
                values = $.values,
                value = getValueInMap$1(_toValue(key), values);
            return value ? getElementIndex(value[2]) : -1;
        },
        has: function has(key) {
            return hasKeyInMap(_toValue(key), this[TOKEN_VALUES]);
        },
        let: function _let(key, _fireHook) {
            if (_fireHook === void 0) {
                _fireHook = 1;
            }
            return this.delete(key, _fireHook);
        },
        set: function set(key, value, _fireHook) {
            var _value$1$active, _value$1$mark, _getState;
            if (_fireHook === void 0) {
                _fireHook = 1;
            }
            var $ = this,
                of = $.of,
                values = $.values,
                _active = of._active;
            if (!_active) {
                return false;
            }
            var _fix = of._fix,
                _mask = of._mask,
                max = of.max,
                self = of.self,
                state = of.state,
                text = _mask.text,
                join = state.join,
                n = state.n,
                pattern = state.pattern,
                count,
                r,
                tag,
                tagText,
                tagX,
                tagsValues = [];
            if ((count = $.count()) >= max) {
                _fireHook && onInvalidSelf.call(self);
                return _fireHook && of.fire('max.tags', [count, max]), false;
            }
            // `picker.tags.set('asdf')`
            if (!isSet(value)) {
                value = [key, {
                    active: true,
                    mark: false
                }];
                // `picker.tags.set('asdf', 'asdf')`
            } else if (isFloat(value) || isInteger(value) || isString(value)) {
                value = [value, {
                    active: true,
                    mark: false
                }];
                // `picker.tags.set('asdf', [ … ])`
            } else;
            // All tag(s) act as selected option(s) that complement the mask root. The mask root functions as a `listbox`
            // with active `aria-multiselectable` attribute. For visually impaired user(s), this element should be described
            // as a multiple selection control with all option(s) selected. There is no point in having a way to disable a
            // tag to exclude it from the selection. The best strategy is to simply remove the tag. That’s why the `active`
            // option is always `true` and every tag has an active `aria-selected` attribute.
            value[1].active = (_value$1$active = value[1].active) != null ? _value$1$active : true;
            // This `mark` option is used to determine the state of the `aria-pressed` attribute.
            value[1].mark = (_value$1$mark = value[1].mark) != null ? _value$1$mark : false;
            var _value$ = value[1],
                mark = _value$.mark,
                v = _value$.value;
            if (null === key || "" === (v = _fromValue(v || key).trim()) || isString(pattern) && !toPattern(pattern).test(v)) {
                onInvalidSelf.call(self);
                return _fireHook && of.fire('not.tag', [key]), false;
            }
            if (isFunction(pattern)) {
                if (isArray(r = pattern.call(of, v))) {
                    var _r$1$TOKEN_VALUE;
                    key = v = r[1] ? (_r$1$TOKEN_VALUE = r[1][TOKEN_VALUE]) != null ? _r$1$TOKEN_VALUE : r[0] : r[0];
                    value = r;
                } else if (isString(r)) {
                    key = v = r;
                    value[0] = r;
                }
            }
            if ($.has(key = _toValue(key))) {
                onInvalidSelf.call(self);
                return _fireHook && of.fire('has.tag', [key]), false;
            }
            tag = value[2] || setElement('data', {
                'aria': {
                    'pressed': mark ? TOKEN_TRUE : false,
                    'selected': TOKEN_TRUE
                },
                'class': n + '__tag',
                // Make the tag “content editable”, so that the “Cut” option is available in the context menu, but do not
                // allow user(s) to edit the tag text. We just want to make sure that the “Cut” option is available.
                'contenteditable': TOKEN_TRUE,
                // <https://html.spec.whatwg.org/multipage/interaction.html#attr-inputmode-keyword-none>
                'inputmode': 'none',
                'role': 'option',
                'spellcheck': TOKEN_FALSE,
                'tabindex': -1,
                'title': (_getState = getState(value[1], 'title')) != null ? _getState : false,
                'value': v,
                // <https://www.w3.org/TR/virtual-keyboard#dom-elementcontenteditable-virtualkeyboardpolicy>
                'virtualkeyboardpolicy': 'manual'
            });
            // Disable focus on “read-only” tag picker
            if (_fix) {
                letAttribute(tag, TOKEN_CONTENTEDITABLE);
                letAttribute(tag, TOKEN_TABINDEX);
            }
            tagText = value[2] ? getElement('.' + n + '__v', value[2]) : setElement('span', _fromValue(value[0]), {
                'class': n + '__v',
                'role': 'none'
            });
            n += '__x';
            tagX = value[2] ? getElement('.' + n, value[2]) : setElement('span', {
                'aria': {
                    'hidden': TOKEN_TRUE
                },
                'class': n,
                'tabindex': -1
            });
            // Force `id` attribute(s)
            setID(tagText);
            setID(tagX);
            setAria(tagX, 'controls', getID(setID(tag)));
            if (!value[2]) {
                onEvent(EVENT_BLUR, tag, onBlurTag);
                onEvent(EVENT_COPY, tag, onCopyTag);
                onEvent(EVENT_CUT, tag, onCutTag);
                onEvent(EVENT_FOCUS, tag, onFocusTag);
                onEvent(EVENT_INPUT_START, tag, onBeforeInputTag);
                onEvent(EVENT_KEY_DOWN, tag, onKeyDownTag);
                onEvent(EVENT_KEY_UP, tag, onKeyUpTag);
                onEvent(EVENT_MOUSE_DOWN$1, tag, onPointerDownTag$1);
                onEvent(EVENT_MOUSE_DOWN$1, tagX, onPointerDownTagX);
                onEvent(EVENT_PASTE, tag, onPasteTag);
                onEvent(EVENT_TOUCH_START$1, tag, onPointerDownTag$1);
                onEvent(EVENT_TOUCH_START$1, tagX, onPointerDownTagX);
            }
            setChildLast(tag, tagText);
            setChildLast(tag, tagX);
            setPrev(text, tag);
            setReference$2(tag, of);
            value[2] = tag;
            _fireHook && of.fire('is.tag', [key]);
            setValueInMap$1(key, value, values);
            forEachMap(values, function (v, k) {
                return tagsValues.push(_fromValue(k));
            });
            setValue$1(self, tagsValues = tagsValues.join(join));
            return _fireHook && of.fire('set.tag', [key]).fire('change', ["" !== tagsValues ? tagsValues : null]), true;
        }
    });
    // In order for an object to be iterable, it must have a `Symbol.iterator` key
    getPrototype(TagPickerTags)[Symbol.iterator] = function () {
        return this[TOKEN_VALUES][Symbol.iterator]();
    };
    TagPicker.Tags = TagPickerTags;
    var name$3 = 'TagPicker.Sort';
    var EVENT_DOWN = 'down';
    var EVENT_MOVE = 'move';
    var EVENT_UP = 'up';
    var EVENT_MOUSE = 'mouse';
    var EVENT_MOUSE_DOWN = EVENT_MOUSE + EVENT_DOWN;
    var EVENT_MOUSE_MOVE = EVENT_MOUSE + EVENT_MOVE;
    var EVENT_MOUSE_UP = EVENT_MOUSE + EVENT_UP;
    var EVENT_TOUCH = 'touch';
    var EVENT_TOUCH_END = EVENT_TOUCH + 'end';
    var EVENT_TOUCH_MOVE = EVENT_TOUCH + EVENT_MOVE;
    var EVENT_TOUCH_START = EVENT_TOUCH + 'start';

    function attach$2(self, state) {
        var $ = this,
            $$ = $.constructor._,
            _tags = $._tags;
        forEachMap(_tags, function (v) {
            v = v[2];
            onEvent(EVENT_MOUSE_DOWN, v, onPointerDownTag);
            onEvent(EVENT_TOUCH_START, v, onPointerDownTag);
            setReference$2(v, $);
        });
        !isFunction($$.reverse) && ($$.reverse = function () {
            var $ = this,
                state = $.state,
                value = $.value,
                join = state.join;
            if (value) {
                value = value.split(join).reverse();
                $.value = value.join(join);
                return $.fire('sort.tags', [value]);
            }
            return $;
        });
        !isFunction($$.sort) && ($$.sort = function (method) {
            var $ = this,
                state = $.state,
                value = $.value,
                join = state.join,
                v;
            method = (method || function (a, b) {
                return a.localeCompare(b, undefined, {
                    numeric: true,
                    sensitivity: 'base'
                });
            }).bind($);
            if (v = value) {
                value = value.split(join).sort(method);
                if (v !== value.join(join)) {
                    $.value = value.join(join);
                    return $.fire('sort.tags', [value]);
                }
            }
            return $;
        });
        return $.on('let.tag', onLetTag).on('set.tag', onSetTag);
    }

    function detach$2() {
        var $ = this,
            $$ = $.constructor._,
            _tags = $._tags;
        forEachMap(_tags, function (v) {
            v = v[2];
            letReference$1(v);
            offEvent(EVENT_MOUSE_DOWN, v, onPointerDownTag);
            offEvent(EVENT_TOUCH_START, v, onPointerDownTag);
        });
        delete $$.reverse;
        delete $$.sort;
        return $.off('let.tag', onLetTag).off('set.tag', onSetTag);
    }
    var copy,
        left,
        rect,
        top,
        x = 0,
        y = 0;

    function isBefore(a, b) {
        var c;
        for (c = getPrev(a, 1); c; c = getPrev(c, 1)) {
            if (c === b) {
                return 1;
            }
        }
        return 0;
    }

    function onPointerDownTag(e) {
        offEventDefault(e);
        var $ = this,
            picker = getReference$2($),
            _mask = picker._mask,
            state = picker.state,
            flex = _mask.flex,
            n = state.n,
            _e = e,
            target = _e.target,
            type = _e.type;
        if (hasClass(target, n + '__x') || getParent(target, '.' + n + '__x')) {
            return;
        }
        $.blur();
        onEvent(EVENT_MOUSE_MOVE, D, onPointerMoveDocument);
        onEvent(EVENT_MOUSE_UP, D, onPointerUpDocument);
        onEvent(EVENT_TOUCH_END, D, onPointerUpDocument);
        onEvent(EVENT_TOUCH_MOVE, D, onPointerMoveDocument);
        if (EVENT_TOUCH_START === type) {
            e = e.touches[0];
        }
        left = e.clientX - x;
        top = e.clientY - y;
        letID(copy = $.cloneNode(true));
        rect = getRect($);
        setReference$2(copy, $);
        setStyle($, 'visibility', 'hidden');
        setStyles(copy, {
            'height': rect[3],
            'left': rect[0],
            'pointer-events': 'none',
            'position': 'fixed',
            'top': rect[1],
            'transform': false,
            'transition': false,
            'width': rect[2],
            'z-index': 9999
        });
        setChildLast(flex, copy);
        var current = $,
            parent;
        while (parent = getParent(current)) {
            setStyle(current = parent, 'cursor', 'move');
            if (B === current) {
                break;
            }
        }
    }

    function onPointerMoveDocument(e) {
        offEventDefault(e);
        if (!copy) {
            return;
        }
        var copyOf = getReference$2(copy),
            picker = getReference$2(copyOf),
            _mask = picker._mask,
            state = picker.state,
            flex = _mask.flex,
            n = state.n,
            current,
            parent;
        if (EVENT_TOUCH_MOVE === e.type) {
            e = e.touches[0];
        }
        x = e.clientX - left;
        y = e.clientY - top;
        current = D.elementFromPoint(e.clientX, e.clientY);
        if (hasClass(current, n + '__tag'));
        else if (parent = getParent(current, '.' + n + '__tag')) {
            current = parent;
        } else {
            current = 0;
        }
        translate(copy, x, y);
        if (current && current !== copyOf && flex === getParent(current)) {
            isBefore(copyOf, current) ? setNext(copyOf, current) : setPrev(copyOf, current);
        }
    }

    function onPointerUpDocument(e) {
        offEvent(EVENT_MOUSE_MOVE, D, onPointerMoveDocument);
        offEvent(EVENT_MOUSE_UP, D, onPointerUpDocument);
        offEvent(EVENT_TOUCH_END, D, onPointerUpDocument);
        offEvent(EVENT_TOUCH_MOVE, D, onPointerMoveDocument);
        if (copy) {
            var current, parent, picker, value;
            letStyle(current = getReference$2(copy), 'visibility');
            picker = getReference$2(current);
            value = current.value;
            if (EVENT_TOUCH_END !== e.type) {
                current.focus();
            }
            while (parent = getParent(current)) {
                letStyle(current = parent, 'cursor');
                if (B === current) {
                    break;
                }
            }
            letReference$1(copy), letElement(copy);
            if (picker) {
                var map = new Map(),
                    _picker = picker,
                    _mask = _picker._mask,
                    _tags = _picker._tags,
                    self = _picker.self,
                    state = _picker.state,
                    flex = _mask.flex,
                    join = state.join,
                    key,
                    values = [];
                forEachArray(getElements('data[value]', flex), function (v) {
                    setValueInMap$1(key = v.value, _tags.at(key), map);
                    values.push(key);
                });
                setValue$1(self, values.join(join));
                _tags.values = map;
                picker.fire('sort.tag', [value]);
            }
        }
        copy = x = y = 0;
    }

    function onLetTag(name) {
        var $ = this,
            at = $.tags.at(name);
        if (at = at && at[2]) {
            letReference$1(at);
            offEvent(EVENT_MOUSE_DOWN, at, onPointerDownTag);
            offEvent(EVENT_TOUCH_START, at, onPointerDownTag);
        }
    }

    function onSetTag(name) {
        var $ = this,
            at = $.tags.at(name);
        if (at = at && at[2]) {
            onEvent(EVENT_MOUSE_DOWN, at, onPointerDownTag);
            onEvent(EVENT_TOUCH_START, at, onPointerDownTag);
            setReference$2(at, $);
        }
    }

    function translate(node, x, y) {
        setStyle(node, 'transform', 'translate(' + x + 'px,' + y + 'px)');
    }
    var TagPickerSort = {
        attach: attach$2,
        detach: detach$2,
        name: name$3
    };
    TagPicker.state.with.push(TagPickerSort);

    function onChange$a(init) {
        var sources = getElements('.lot\\:field.type\\:query input:not([type=hidden])');
        sources && toCount(sources) && sources.forEach(function (source) {
            var _getDatum;
            var c = getClasses(source);
            letClasses(source);
            var $ = new TagPicker(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
            setClasses($.mask, c);
        });
        1 === init && W._.on('change', onChange$a);
    }
    W.TP = W.TagPicker = TagPicker;
    var events = {
        beforeinput: 'put.down',
        blur: 0,
        click: 0,
        copy: 0,
        cut: 0,
        focus: 0,
        input: 'put.up',
        keydown: 'key.down',
        keyup: 'key.up',
        mousedown: 'caret.down',
        mouseenter: 'caret.enter',
        mouseleave: 'caret.exit',
        mousemove: 'caret.move',
        mouseup: 'caret.up',
        paste: 0,
        scroll: 0,
        select: 0,
        touchend: 'caret.up',
        touchmove: 'caret.move',
        touchstart: 'caret.down',
        wheel: 'scroll'
    };
    var name$2 = 'TextEditor';
    var references$1 = new WeakMap();

    function getReference$1(key) {
        return getValueInMap(key, references$1) || null;
    }

    function getValue(self) {
        return (self.value || "").replace(/\r/g, "");
    }

    function getValueInMap(k, map) {
        return map.get(k);
    }

    function isDisabled(self) {
        return self.disabled;
    }

    function isReadOnly(self) {
        return self.readOnly;
    }

    function setReference$1(key, value) {
        return setValueInMap(key, value, references$1);
    }

    function setValue(self, value) {
        self.value = value;
    }

    function setValueInMap(k, v, map) {
        return map.set(k, v);
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
        setReference$1(self, hook($, TextEditor._));
        return $.attach(self, _fromStates({}, TextEditor.state, isInteger(state) || isString(state) ? {
            tab: state
        } : state || {}));
    }
    TextEditor.esc = esc;
    TextEditor.from = function (self, state) {
        return new TextEditor(self, state);
    };
    TextEditor.of = getReference$1;
    TextEditor.state = {
        'n': 'text-editor',
        'tab': '\t',
        'with': []
    };
    TextEditor.version = '4.2.9';
    TextEditor.x = x$1;
    var S = function S(start, end, value) {
        var $ = this,
            current = value.slice(start, end);
        $.after = value.slice(end);
        $.before = value.slice(0, start);
        $.end = end;
        $.length = toCount(current);
        $.start = start;
        $.value = current;
    };
    S.prototype.toString = function () {
        return this.value;
    };
    TextEditor.S = S;
    Object.defineProperty(TextEditor, 'name', {
        value: name$2
    });
    var theValuePrevious;

    function theEvent(e) {
        var self = this,
            $ = getReference$1(self),
            value = getValue(self);
        e.data;
        var type = e.type;
        $._event = e;
        if (value !== theValuePrevious) {
            isString(theValuePrevious) && $.fire('change', [e]);
            theValuePrevious = value;
        }
        $.fire(events[type] || type, [e]);
    }
    var $$$1 = TextEditor._ = TextEditor.prototype;
    $$$1.$ = function () {
        var self = this.self;
        return new S(self.selectionStart, self.selectionEnd, getValue(self));
    };
    $$$1.attach = function (self, state) {
        var $ = this;
        self = self || $.self;
        if (state && (isInteger(state) || isString(state))) {
            state = {
                tab: state
            };
        }
        state = _fromStates({}, $.state, state || {});
        if (hasClass(self, state.n + '__self')) {
            return $;
        }
        $._active = !isDisabled(self) && !isReadOnly(self);
        $._event = null;
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
        return setValue(self, $._value), $;
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
        return setValue(self, value), $;
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
            return getValue(this.self);
        },
        set: function set(value) {
            setValue(this.self, value);
        }
    });

    function History() {
        var $ = this;
        var $$ = $.constructor._;
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
        $.self = self || $;
        $.set = new Set();
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
    $$.fire = function (command, data) {
        var $ = this;
        var self = $.self || $,
            value,
            exist;
        data = data || [];
        if (isFunction(command)) {
            value = command.apply(self, data);
            exist = true;
        } else if (isString(command) && (command = $.commands[command])) {
            value = command.apply(self, data);
            exist = true;
        } else if (isArray(command)) {
            if (isArray(command[1])) {
                command[1].forEach(function (v, k) {
                    return isSet(v) && (data[k] = v);
                });
            }
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
            return $.set = new Set(), $;
        }
        return $.set.delete(key), $;
    };
    $$.push = function (key) {
        var $ = this;
        return $.set.add($.key = key, 1), $;
    };
    $$.toArray = function () {
        return Array.from(this.set);
    };
    $$.toString = function () {
        return this.toArray().join('-');
    };
    Object.defineProperty(Key, 'name', {
        value: 'Key'
    });
    var bounce$2 = debounce(function (map, e) {
        // Remove all key(s)
        map.pull();
        // Make the `Alt`, `Control`, `Meta`, and `Shift` key(s) sticky (does not require the user to release all key(s) first to repeat or change the current key combination).
        e.altKey && map.push('Alt');
        e.ctrlKey && map.push('Control');
        e.metaKey && map.push('Meta');
        e.shiftKey && map.push('Shift');
    }, 1000);
    var name$1 = 'TextEditor.Key';
    var references = new WeakMap();

    function getReference(key) {
        return references.get(key) || null;
    }

    function letReference(key) {
        return references.delete(key);
    }

    function onBlur(e) {
        var $ = this,
            map = getReference($);
        map.pull(); // Reset all key(s)
    }

    function onFocus(e) {
        onBlur.call(this, e);
    }

    function onKeyDownOrPutDown(e) {
        var map = getReference(this),
            command,
            v,
            data = e.data,
            inputType = e.inputType,
            key = e.key,
            type = e.type;
        if ('keydown' === type) {
            // Make the `Alt`, `Control`, `Meta`, and `Shift` key(s) sticky (does not require the user to release all key(s) first to repeat or change the current key combination).
            map[e.altKey ? 'push' : 'pull']('Alt');
            map[e.ctrlKey ? 'push' : 'pull']('Control');
            map[e.metaKey ? 'push' : 'pull']('Meta');
            map[e.shiftKey ? 'push' : 'pull']('Shift');
            // Add the actual key to the queue. Don’t worry, this will not mistakenly add a key that already exists in the queue.
            key && map.push(key);
        } else {
            if ('deleteContentBackward' === inputType) {
                map.pull().push('Backspace'); // Simulate `Backspace` key
            } else if ('deleteContentForward' === inputType) {
                map.pull().push('Delete'); // Simulate `Delete` key
            } else if ('deleteWordBackward' === inputType) {
                map.pull().push('Control').push('Backspace'); // Simulate `Control-Backspace` keys
            } else if ('deleteWordForward' === inputType) {
                map.pull().push('Control').push('Delete'); // Simulate `Control-Delete` keys
            } else if ('insertLineBreak' === inputType) {
                map.pull().push('Enter'); // Simulate `Enter` key
            } else if ('insertText' === inputType && data) {
                // One character at a time
                map.toArray().forEach(function (key) {
                    return 1 === toCount(key) && map.pull(key);
                });
                map.push(data);
            }
        }
        if (command = map.command()) {
            v = map.fire(command);
            if (false === v) {
                offEventDefault(e);
                offEventPropagation(e);
            } else if (null === v) {
                console.warn('Unknown command:', command);
            }
        }
        bounce$2(map, e); // Reset all key(s) after 1 second idle.
    }

    function onKeyUpOrPutUp(e) {
        var map = getReference(this),
            data = e.data,
            inputType = e.inputType,
            key = e.key,
            type = e.type;
        if ('keyup' === type) {
            map[e.altKey ? 'push' : 'pull']('Alt');
            map[e.ctrlKey ? 'push' : 'pull']('Control');
            map[e.metaKey ? 'push' : 'pull']('Meta');
            map[e.shiftKey ? 'push' : 'pull']('Shift');
            key && map.pull(key);
        } else {
            if ('deleteContentBackward' === inputType) {
                map.pull('Backspace');
            } else if ('deleteContentForward' === inputType) {
                map.pull('Delete');
            } else if ('deleteWordBackward' === inputType) {
                map.pull('Control').pull('Backspace');
            } else if ('deleteWordForward' === inputType) {
                map.pull('Control').pull('Delete');
            } else if ('insertLineBreak' === inputType) {
                map.pull('Enter');
            } else if ('insertText' === inputType && data) {
                map.pull(data);
            }
        }
    }

    function setReference(key, value) {
        return references.set(key, value);
    }

    function attach$1() {
        var $ = this;
        var $$ = $.constructor._;
        var map = new Key($);
        $.commands = _fromStates($.commands = map.commands, $.state.commands || {});
        $.keys = _fromStates($.keys = map.keys, $.state.keys || {});
        !isFunction($$.command) && ($$.command = function (command, of) {
            var $ = this;
            return $.commands[command] = of, $;
        });
        !isFunction($$.k) && ($$.k = function (join) {
            var $ = this,
                map = getReference($),
                keys = map.toArray();
            return false === join ? keys : keys.join(join || '-');
        });
        !isFunction($$.key) && ($$.key = function (key, of) {
            var $ = this;
            return $.keys[key] = of, $;
        });
        $.on('blur', onBlur);
        $.on('focus', onFocus);
        $.on('key.down', onKeyDownOrPutDown);
        $.on('key.up', onKeyUpOrPutUp);
        $.on('put.down', onKeyDownOrPutDown);
        $.on('put.up', onKeyUpOrPutUp);
        return setReference($, map), $;
    }

    function detach$1() {
        var $ = this,
            map = getReference($);
        map.pull();
        $.off('blur', onBlur);
        $.off('focus', onFocus);
        $.off('key.down', onKeyDownOrPutDown);
        $.off('key.up', onKeyUpOrPutUp);
        $.off('put.down', onKeyDownOrPutDown);
        $.off('put.up', onKeyUpOrPutUp);
        return letReference($), $;
    }
    var TextEditorKey = {
        attach: attach$1,
        detach: detach$1,
        name: name$1
    };
    var ALT_PREFIX = 'Alt-';
    var CTRL_PREFIX = 'Control-';
    var SHIFT_PREFIX = 'Shift-';
    var KEY_ARROW_DOWN = 'ArrowDown';
    var KEY_ARROW_LEFT = 'ArrowLeft';
    var KEY_ARROW_RIGHT = 'ArrowRight';
    var KEY_ARROW_UP = 'ArrowUp';
    var KEY_DELETE_LEFT = 'Backspace';
    var KEY_DELETE_RIGHT = 'Delete';
    var KEY_ENTER = 'Enter';
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
        if (CTRL_PREFIX + SHIFT_PREFIX + KEY_ENTER === keys) {
            if (before || after) {
                // Insert line above with `⎈⇧↵`
                offEventDefault(e);
                return $.select(start - toCount(lineBefore)).wrap(lineMatchIndent, '\n').insert(value).record(), false;
            }
            return;
        }
        if (CTRL_PREFIX + KEY_ENTER === keys) {
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
        if (KEY_DELETE_LEFT === keys || KEY_DELETE_RIGHT === keys) {
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
            if (KEY_DELETE_RIGHT !== keys && lineBefore.endsWith(charIndent)) {
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
        if (KEY_ENTER === keys || SHIFT_PREFIX + KEY_ENTER === keys) {
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
            if (CTRL_PREFIX + KEY_ARROW_LEFT === keys) {
                offEventDefault(e);
                if (m = toPattern('(' + tokens.join('|') + ')$', "").exec(before)) {
                    return $.insert("").select(start - toCount(m[0])).insert(value).record();
                }
                return $.select();
            }
            if (CTRL_PREFIX + KEY_ARROW_RIGHT === keys) {
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
        if (CTRL_PREFIX + KEY_ARROW_UP === keys) {
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
        if (CTRL_PREFIX + KEY_ARROW_DOWN === keys) {
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
    // Partial mobile support
    function onPutDown(e) {
        onKeyDown.call(this, e);
    }

    function attach() {
        var $ = this;
        var $$ = $.constructor._;
        $.state = _fromStates({
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
        !isFunction($$.insertLine) && ($$.insertLine = function (value, mode) {
            var $ = this,
                _$$$2 = $.$(),
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
        !isFunction($$.peelLine) && ($$.peelLine = function (open, close, wrap, withSpaces) {
            if (withSpaces === void 0) {
                withSpaces = false;
            }
            return this.selectLine(withSpaces).peel(open, close, wrap);
        });
        !isFunction($$.prompt) && ($$.prompt = function (hint, value, then) {
            return isFunction(then) && then.call(this, W.prompt ? W.prompt(hint, value) : false);
        });
        !isFunction($$.selectLine) && ($$.selectLine = function (withSpaces) {
            if (withSpaces === void 0) {
                withSpaces = true;
            }
            var $ = this,
                m,
                _$$$3 = $.$(),
                after = _$$$3.after,
                before = _$$$3.before,
                end = _$$$3.end,
                start = _$$$3.start,
                lineAfter = after.split('\n').shift(),
                lineAfterCount = toCount(lineAfter),
                lineBefore = before.split('\n').pop(),
                lineBeforeCount = toCount(lineBefore);
            $.select(start - lineBeforeCount, end + lineAfterCount);
            if (!withSpaces) {
                var _$$$4 = $.$(),
                    _end = _$$$4.end,
                    _start = _$$$4.start,
                    value = _$$$4.value;
                if (m = /^(\s+)?[\s\S]*?(\s+)?$/.exec(value)) {
                    return $.select(_start + toCount(m[1] || ""), _end - toCount(m[2] || ""));
                }
            }
            return $;
        });
        !isFunction($$.toggle) && ($$.toggle = function (open, close, wrap) {
            var $ = this,
                _$$$5 = $.$(),
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
        !isFunction($$.toggleLine) && ($$.toggleLine = function (open, close, wrap, withSpaces) {
            if (withSpaces === void 0) {
                withSpaces = false;
            }
            var $ = this.selectLine(withSpaces),
                _$$$6 = $.$();
            _$$$6.after;
            _$$$6.before;
            var value = _$$$6.value,
                closeCount = toCount(close),
                openCount = toCount(open);
            if (!wrap && close === value.slice(-closeCount) && open === value.slice(0, openCount)) {
                var _$$$7 = $.$(),
                    end = _$$$7.end,
                    start = _$$$7.start;
                $.select(start + openCount, end - closeCount);
            }
            return $.toggle(open, close, wrap);
        });
        !isFunction($$.wrapLine) && ($$.wrapLine = function (open, close, wrap, withSpaces) {
            if (withSpaces === void 0) {
                withSpaces = false;
            }
            return this.selectLine(withSpaces).wrap(open, close, wrap);
        });
        return $.on('key.down', onKeyDown).on('put.down', onPutDown).record();
    }

    function detach() {
        return this.off('key.down', onKeyDown).off('put.down', onPutDown);
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

    function onChange$9(init) {
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
            W._.on('change', onChange$9);
        }
    }
    W.TextEditor = TextEditor;

    function Fields() {
        onChange$b(1);
        onChange$a(1);
        onChange$9(1);
    }
    var targets$7 = ':scope>:where(.lot\\:file[tabindex],.lot\\:folder[tabindex]):not([tabindex="-1"]):not(.not\\:active)';

    function onChange$8(init) {
        var sources = getElements(':where(.lot\\:files,.lot\\:folders)[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var files = getElements(targets$7, source);
            files.forEach(function (file) {
                onEventOnly('keydown', file, onKeyDownFile);
            });
            onEventOnly('keydown', source, onKeyDownFiles);
        });
        1 === init && W._.on('change', onChange$8);
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

    function onChange$7(init) {
        var sources = getElements('.lot\\:links[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var links = getElements(targets$6, source);
            links && toCount(links) && links.forEach(function (link) {
                onEventOnly('keydown', link, onKeyDownLink);
            });
            onEventOnly('keydown', source, onKeyDownLinks);
        });
        1 === init && W._.on('change', onChange$7);
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

    function onChange$6(init) {
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
        1 === init && W._.on('change', onChange$6);
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
            toggleClass(current, 'is:enter');
            toggleClass(getParent(t), 'is:active');
            toggleClass(t, 'is:active');
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

    function onChange$5(init) {
        var sources = getElements('.lot\\:menus[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var menus = getElements(targets$4, source);
            menus && toCount(menus) && menus.forEach(function (menu) {
                onEventOnly('keydown', menu, onKeyDownMenu);
            });
            onEventOnly('keydown', source, onKeyDownMenus);
        });
        1 === init && W._.on('change', onChange$5);
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

    function onChange$4(init) {
        var sources = getElements('.lot\\:pages[tabindex]');
        sources && toCount(sources) && sources.forEach(function (source) {
            var pages = getElements(targets$3, source);
            pages.forEach(function (page) {
                onEventOnly('keydown', page, onKeyDownPage);
            });
            onEventOnly('keydown', source, onKeyDownPages);
        });
        1 === init && W._.on('change', onChange$4);
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

    function getDefaultExportFromCjs(x) {
        return x && x.__esModule && Object.prototype.hasOwnProperty.call(x, 'default') ? x['default'] : x;
    }
    var siema_min$1 = {
        exports: {}
    };
    var siema_min = siema_min$1.exports;
    var hasRequiredSiema_min;

    function requireSiema_min() {
        if (hasRequiredSiema_min) return siema_min$1.exports;
        hasRequiredSiema_min = 1;
        (function (module, exports) {
            ! function (e, t) {
                module.exports = t();
            }("undefined" != typeof self ? self : siema_min, function () {
                return function (e) {
                    function t(r) {
                        if (i[r]) return i[r].exports;
                        var n = i[r] = {
                            i: r,
                            l: false,
                            exports: {}
                        };
                        return e[r].call(n.exports, n, n.exports, t), n.l = true, n.exports;
                    }
                    var i = {};
                    return t.m = e, t.c = i, t.d = function (e, i, r) {
                        t.o(e, i) || Object.defineProperty(e, i, {
                            configurable: false,
                            enumerable: true,
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
                        value: true
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
                                    r.enumerable = r.enumerable || false, r.configurable = true, "value" in r && (r.writable = true), Object.defineProperty(e, r.key, r);
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
                                    window.addEventListener("resize", this.resizeHandler), this.config.draggable && (this.pointerDown = false, this.drag = {
                                        startX: 0,
                                        endX: 0,
                                        startY: 0,
                                        letItGo: null,
                                        preventClick: false
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
                                            var n = this.buildSliderFrameItem(this.innerElements[r].cloneNode(true));
                                            i.appendChild(n);
                                        }
                                    for (var s = 0; s < this.innerElements.length; s++) {
                                        var l = this.buildSliderFrameItem(this.innerElements[s]);
                                        i.appendChild(l);
                                    }
                                    if (this.config.loop)
                                        for (var o = 0; o < this.perPage; o++) {
                                            var a = this.buildSliderFrameItem(this.innerElements[o].cloneNode(true));
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
                                    -1 !== ["TEXTAREA", "OPTION", "INPUT", "SELECT"].indexOf(e.target.nodeName) || (e.stopPropagation(), this.pointerDown = true, this.drag.startX = e.touches[0].pageX, this.drag.startY = e.touches[0].pageY);
                                }
                            }, {
                                key: "touchendHandler",
                                value: function value(e) {
                                    e.stopPropagation(), this.pointerDown = false, this.enableTransition(), this.drag.endX && this.updateAfterDrag(), this.clearDrag();
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
                                    -1 !== ["TEXTAREA", "OPTION", "INPUT", "SELECT"].indexOf(e.target.nodeName) || (e.preventDefault(), e.stopPropagation(), this.pointerDown = true, this.drag.startX = e.pageX);
                                }
                            }, {
                                key: "mouseupHandler",
                                value: function value(e) {
                                    e.stopPropagation(), this.pointerDown = false, this.selector.style.cursor = "-webkit-grab", this.enableTransition(), this.drag.endX && this.updateAfterDrag(), this.clearDrag();
                                }
                            }, {
                                key: "mousemoveHandler",
                                value: function value(e) {
                                    if (e.preventDefault(), this.pointerDown) {
                                        "A" === e.target.nodeName && (this.drag.preventClick = true), this.drag.endX = e.pageX, this.selector.style.cursor = "-webkit-grabbing", this.sliderFrame.style.webkitTransition = "all 0ms " + this.config.easing, this.sliderFrame.style.transition = "all 0ms " + this.config.easing;
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
                                    this.pointerDown && (this.pointerDown = false, this.selector.style.cursor = "-webkit-grab", this.drag.endX = e.pageX, this.drag.preventClick = false, this.enableTransition(), this.updateAfterDrag(), this.clearDrag());
                                }
                            }, {
                                key: "clickHandler",
                                value: function value(e) {
                                    this.drag.preventClick && e.preventDefault(), this.drag.preventClick = false;
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
                                            draggable: true,
                                            multipleDrag: true,
                                            threshold: 20,
                                            loop: false,
                                            rtl: false,
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
        }(siema_min$1));
        return siema_min$1.exports;
    }
    var siema_minExports = requireSiema_min();
    var Siema = /*@__PURE__*/ getDefaultExportFromCjs(siema_minExports);
    Siema.instances = [];
    var SIEMA_INTERVAL = 0;

    function onChange$3(init) {
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
                toggleClass(parent, 'is:current');
                toggleClass(t, 'is:current');
            } else {
                setAttribute(t, 'aria-expanded', 'true');
                setClass(parent, 'is:current');
                setClass(t, 'is:current');
            }
            current = hasClass(t, 'is:current');
            t._[STACK_INPUT].value = value = current ? getDatum(parent, 'value') : null;
            toggleClass(self, 'has:current', current);
            var pathname = theLocation.pathname,
                search = theLocation.search;
            var query = fromQuery(search);
            var q = fromQuery(name + '=' + value);
            query = _fromStates(query, q.query || {});
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
                t._[TAB_INPUT].value = value = current ? getDatum(t, 'value') : null;
                toggleClass(pane, 'is:current', current);
                toggleClass(self, 'has:current', current);
                var pathname = theLocation.pathname,
                    search = theLocation.search;
                var query = fromQuery(search);
                var q = fromQuery(name + '=' + value);
                query = _fromStates(query, q.query || {});
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
    var _debounce = debounce(function (map) {
            return map.pull();
        }, 1000),
        _debounce2 = _maybeArrayLike(_slicedToArray, _debounce, 1),
        bounce = _debounce2[0];
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
    onChange$c(1);
    Dialog();
    Fields();
    onChange$8(1);
    onChange$7(1);
    onChange$6(1);
    onChange$5(1);
    onChange$4(1);
    onChange$3();
    onChange$2(1);
    onChange$1(1);
    onChange(1);
})();