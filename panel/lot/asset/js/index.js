(function() {
    'use strict';
    var isArray = function isArray(x) {
        return Array.isArray(x);
    };
    var isBoolean = function isBoolean(x) {
        return false === x || true === x;
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
        return Object.assign.apply(Object, [{}].concat(lot));
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
    var toArray = function toArray(x) {
        return isArray(x) ? x : [x];
    };
    var toArrayKey = function toArrayKey(x, data) {
        var i = data.indexOf(x);
        return -1 !== i ? i : null;
    };
    var toCaseCamel = function toCaseCamel(x) {
        return x.replace(/[-_.](\w)/g, function(m0, m1) {
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
    var D = document;
    var W = window;
    var B$1 = D.body;
    var R = D.documentElement;
    var fromElement = function fromElement(node) {
        var attributes = getAttributes(node),
            content = getHTML(node),
            title = getName(node);
        return false !== content ? [title, content, attributes] : [title, attributes];
    };
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
    var getAttributes = function getAttributes(node, parseValue) {
        if (parseValue === void 0) {
            parseValue = true;
        }
        var attributes = node.attributes,
            value,
            values = {};
        for (var i = 0, j = attributes.length; i < j; ++i) {
            value = attributes[i].value;
            values[attributes[i].name] = parseValue ? toValue(value) : value;
        }
        return values;
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
        return toArray ? value.split(/\s+/) : value;
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
    var getNext = function getNext(node) {
        return node.nextElementSibling || null;
    };
    var getParent = function getParent(node) {
        return node.parentNode || null;
    };
    var getParentForm = function getParentForm(node) {
        var state = 'form';
        if (hasState(node, state) && state === getName(node[state])) {
            return node[state];
        }
        var parent = getParent(node);
        while (parent) {
            if (state === getName(parent)) {
                break;
            }
            parent = getParent(parent);
        }
        return parent || null;
    };
    var getPrev = function getPrev(node) {
        return node.previousElementSibling || null;
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
    var hasParent = function hasParent(node) {
        return null !== getParent(node);
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
            return classes.forEach(function(name) {
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
            return classes.forEach(function(name) {
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
        return getParent(current).insertBefore(node, getNext(current)), node;
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
    var toElement = function toElement(fromArray) {
        return setElement.apply(void 0, fromArray);
    };
    var toggleClass = function toggleClass(node, name, force) {
        return node.classList.toggle(name, force), node;
    };
    var theHistory = W.history;
    var theLocation = W.location;
    var theScript = D.currentScript;
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
        names.forEach(function(name) {
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
        names.forEach(function(name) {
            return onEvent(name, node, then, options);
        });
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
        return new RegExp(pattern, isSet(opt) ? opt : 'g');
    };
    var x = "!$^*()+=[]{}|:<>,.?/-";
    var getOffset = function getOffset(node) {
        return [node.offsetLeft, node.offsetTop];
    };
    var getRect = function getRect(node) {
        var h, rect, w, x, y, X, Y;
        if (isWindow(node)) {
            x = node.pageXOffset || R.scrollLeft || B$1.scrollLeft;
            y = node.pageYOffset || R.scrollTop || B$1.scrollTop;
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
    var name$3 = 'F3H',
        GET = 'GET',
        POST = 'POST',
        responseTypeHTML = 'document',
        responseTypeJSON = 'json',
        responseTypeTXT = 'text',
        home = '//' + theLocation.hostname,
        B,
        H;

    function getEventName(node) {
        return isForm(node) ? 'submit' : 'click';
    }

    function getHash(ref) {
        return ref.split('#')[1] || "";
    }

    function getLinks(scope) {
        var id,
            out = {},
            href,
            link,
            links = getElements('link[rel=dns-prefetch],link[rel=preconnect],link[rel=prefetch],link[rel=preload],link[rel=prerender]', scope),
            toSave;
        for (var i = 0, j = toCount(links); i < j; ++i) {
            if (isLinkForF3H(link = links[i])) {
                continue;
            }
            href = getAttribute(link, 'href', false);
            link.id = id = link.id || name$3 + ':' + toID(href || getText(link));
            out[id] = toSave = fromElement(link);
            if (href) {
                out[id][toCount(toSave) - 1].href = link.href; // Use the resolved URL!
            }
        }
        return out;
    }

    function getRef() {
        return letSlashEnd(theLocation.href);
    }

    function getScripts(scope) {
        var id,
            out = {},
            src,
            script,
            scripts = getElements('script', scope),
            toSave;
        for (var i = 0, j = toCount(scripts); i < j; ++i) {
            if (isScriptForF3H(script = scripts[i])) {
                continue;
            }
            src = getAttribute(script, 'src', false);
            script.id = id = script.id || name$3 + ':' + toID(src || getText(script));
            out[id] = toSave = fromElement(script);
            if (src) {
                out[id][toCount(toSave) - 1].src = script.src; // Use the resolved URL!
            }
        }
        return out;
    }

    function getStyles(scope) {
        var id,
            out = {},
            href,
            style,
            styles = getElements('link[rel=stylesheet],style', scope),
            toSave;
        for (var i = 0, j = toCount(styles); i < j; ++i) {
            if (isStyleForF3H(style = styles[i])) {
                continue;
            }
            href = getAttribute(style, 'href', false);
            style.id = id = style.id || name$3 + ':' + toID(href || getText(style));
            out[id] = toSave = fromElement(style);
            if (href) {
                out[id][toCount(toSave) - 1].href = style.href; // Use the resolved URL!
            }
        }
        return out;
    }

    function getTarget(id, orName) {
        return id ? D.getElementById(id) || (orName ? D.getElementsByName(id)[0] : null) : null;
    }

    function isForm(node) {
        return 'form' === getName(node);
    }

    function isLinkForF3H(node) {
        var n = toCaseLower(name$3); // Exclude `<link rel="*">` tag that contains `data-f3h` or `f3h` attribute with `false` value
        return getAttribute(node, 'data-' + n) || getAttribute(node, n) ? 1 : 0;
    }

    function isScriptForF3H(node) {
        // Exclude this very JavaScript
        if (node.src && theScript.src === node.src) {
            return 1;
        }
        var n = toCaseLower(name$3); // Exclude JavaScript tag that contains `data-f3h` or `f3h` attribute with `false` value
        if (getAttribute(node, 'data-' + n) || getAttribute(node, n)) {
            return 1;
        } // Exclude JavaScript that contains `F3H` instantiation
        if (toPattern('\\b' + name$3 + '\\b').test(getText(node) || "")) {
            return 1;
        }
        return 0;
    }

    function isSourceForF3H(node) {
        var n = toCaseLower(name$3);
        if (!hasAttribute(node, 'data-' + n) && !hasAttribute(node, n)) {
            return 1; // Default value is `true`
        } // Exclude anchor tag that contains `data-f3h` or `f3h` attribute with `false` value
        return getAttribute(node, 'data-' + n) || getAttribute(node, n) ? 1 : 0;
    }

    function isStyleForF3H(node) {
        var n = toCaseLower(name$3); // Exclude CSS tag that contains `data-f3h` or `f3h` attribute with `false` value
        return getAttribute(node, 'data-' + n) || getAttribute(node, n) ? 1 : 0;
    }

    function letHash(ref) {
        return ref.split('#')[0];
    } // Ignore trailing `/` character(s) in URL
    function letSlashEnd(ref) {
        return ref.replace(/\/+(?=[?&#]|$)/, "");
    } // <https://stackoverflow.com/a/8831937/1163000>
    function toID(text) {
        var c,
            i,
            j = toCount(text),
            out = 0;
        if (0 === j) {
            return out;
        }
        for (i = 0; i < j; ++i) {
            c = text.charCodeAt(i);
            out = (out << 5) - out + c;
            out = out & out; // Convert to 32bit integer
        } // Force absolute value
        return out < 1 ? out * -1 : out;
    }

    function toHeadersAsProxy(request) {
        var out = {},
            headers = request.getAllResponseHeaders().trim().split(/[\r\n]+/),
            header,
            h,
            k;
        for (header in headers) {
            h = headers[header].split(': ');
            k = toCaseLower(h.shift());
            out[k] = toValue(h.join(': '));
        } // Use proxy to make case-insensitive response header’s key
        return new Proxy(out, {
            get: function get(o, k) {
                return o[toCaseLower(k)] || null;
            },
            set: function set(o, k, v) {
                o[toCaseLower(k)] = v;
            }
        });
    }

    function F3H(source, state) {
        if (source === void 0) {
            source = D;
        }
        if (state === void 0) {
            state = {};
        }
        var $ = this; // Return new instance if `F3H` was called without the `new` operator
        if (!isInstance($, F3H)) {
            return new F3H(source, state);
        }
        if (!isSet(source) || isBoolean(source) || isObject(source)) {
            state = source;
            source = D;
        } // Already instantiated, skip!
        if (source[name$3]) {
            return;
        }
        $.state = state = fromStates(F3H.state, true === state ? {
            cache: state
        } : state || {});
        $.source = source;
        if (state.turbo) {
            state.cache = true; // Enable turbo feature will force enable cache feature
        }
        var caches = {},
            links = null,
            lot = null,
            // Store current node to a variable to be compared to the next node
            nodeCurrent = null,
            // Get current URL to be used as the default state after the last pop state
            ref = getRef(),
            // Store current URL to a variable to be compared to the next URL
            refCurrent = ref,
            requests = {},
            scripts = null,
            sources = getSources(state.sources),
            status = null,
            styles = null;
        var _hook = hook($),
            fire = _hook.fire,
            hooks = _hook.hooks; // Store current instance to `F3H.instances`
        F3H.instances[source.id || source.name || toObjectCount(F3H.instances)] = $; // Mark current DOM as active to prevent duplicate instance
        source[name$3] = 1;

        function getSources(sources, root) {
            ref = getRef();
            var froms = getElements(sources, root),
                to = [];
            if (isFunction(state.is)) {
                froms.forEach(function(from) {
                    state.is.call($, from, ref) && isSourceForF3H(from) && to.push(from);
                });
            } else {
                froms.forEach(function(from) {
                    isSourceForF3H(from) && to.push(from);
                });
            }
            return to;
        } // Include submit button value to the form data ;)
        function doAppendCurrentButtonValue(node) {
            var buttonValueStorage = setElement('input', {
                    type: 'hidden'
                }),
                buttons = getElements('[name][type=submit][value]', node);
            setChildLast(node, buttonValueStorage);
            buttons.forEach(function(button) {
                onEvent('click', button, function() {
                    buttonValueStorage.name = this.name;
                    buttonValueStorage.value = this.value;
                });
            });
        }

        function doChangeRef(ref) {
            if (ref === getRef()) {
                return; // Clicking on the same URL should trigger the AJAX call. Just don’t duplicate it to the history!
            }
            state.history && theHistory.pushState({}, "", ref);
        }

        function doFetch(node, type, ref) {
            var nodeIsWindow = isWindow(node),
                useHistory = state.history,
                data; // Compare currently selected source element with the previously stored source element, unless it is a window.
            // Pressing back/forward button from the window shouldn’t be counted as accidental click(s) on the same source element
            if (GET === type && node === nodeCurrent && !nodeIsWindow) {
                return; // Accidental click(s) on the same source element should cancel the request!
            }
            nodeCurrent = node; // Store currently selected source element to a variable to be compared later
            $.ref = letSlashEnd(refCurrent = ref);
            fire('exit', [D, node]); // Get response from cache if any
            if (state.cache) {
                var cache = caches[letSlashEnd(letHash(ref))]; // `[status, response, lot, requestIsDocument]`
                if (cache) {
                    $.lot = lot = cache[2];
                    $.status = status = cache[0];
                    cache[3] && !nodeIsWindow && useHistory && doScrollTo(R);
                    doChangeRef(ref);
                    data = [cache[1], node]; // Update `<link rel="*">` data for the next page
                    cache[3] && (links = doUpdateLinks(data[0])); // Update CSS before markup change
                    cache[3] && (styles = doUpdateStyles(data[0]));
                    fire('success', data);
                    fire(cache[0], data);
                    sources = getSources(state.sources); // Update JavaScript after markup change
                    cache[3] && (scripts = doUpdateScripts(data[0]));
                    onSourcesEventsSet(data);
                    fire('enter', data);
                    return;
                }
            }
            var fn,
                redirect,
                request = doFetchBase(node, type, ref, state.lot),
                requestAsPush = request.upload,
                requestIsDocument = responseTypeHTML === request.responseType;

            function dataSet() {
                // Store response from GET request(s) to cache
                lot = toHeadersAsProxy(request);
                status = request.status;
                if (GET === type && state.cache) {
                    // Make sure `status` is not `0` due to the request abortion, to prevent `null` response being cached
                    status && (caches[letSlashEnd(letHash(ref))] = [status, request.response, lot, requestIsDocument]);
                }
                $.lot = lot;
                $.status = status;
            }
            onEvent('abort', request, function() {
                dataSet(), fire('abort', [request.response, node]);
            });
            onEvent('error', request, fn = function fn() {
                dataSet();
                requestIsDocument && !nodeIsWindow && useHistory && doScrollTo(R);
                data = [request.response, node]; // Update `<link rel="*">` data for the next page
                requestIsDocument && (links = doUpdateLinks(data[0])); // Update CSS before markup change
                requestIsDocument && (styles = doUpdateStyles(data[0]));
                fire('error', data);
                sources = getSources(state.sources); // Update JavaScript after markup change
                requestIsDocument && (scripts = doUpdateScripts(data[0]));
                onSourcesEventsSet(data);
                fire('enter', data);
            });
            onEvent('error', requestAsPush, fn);
            onEvent('load', request, fn = function fn() {
                dataSet();
                data = [request.response, node];
                redirect = request.responseURL; // Handle internal server-side redirection
                // <https://en.wikipedia.org/wiki/URL_redirection#HTTP_status_codes_3xx>
                if (status >= 300 && status < 400) {
                    // Redirection should delete a cache related to the response URL
                    // This is useful for case(s) like, when you have submitted a
                    // comment form and then you will be redirected to the same URL
                    var r = letSlashEnd(letHash(redirect));
                    caches[r] && delete caches[r]; // Trigger hook(s) immediately
                    fire('success', data);
                    fire(status, data); // Do the normal fetch
                    doFetch(nodeCurrent = W, GET, redirect || ref);
                    return;
                } // Just to be sure. Don’t worry, this wouldn’t make a duplicate history
                // if (GET === type) {
                doChangeRef(-1 === ref.indexOf('#') ? redirect || ref : ref); // }
                // Update CSS before markup change
                requestIsDocument && (styles = doUpdateStyles(data[0]));
                fire('success', data);
                fire(status, data);
                requestIsDocument && useHistory && doScrollTo(R);
                sources = getSources(state.sources); // Update JavaScript after markup change
                requestIsDocument && (scripts = doUpdateScripts(data[0]));
                onSourcesEventsSet(data);
                fire('enter', data);
            });
            onEvent('load', requestAsPush, fn);
            onEvent('progress', request, function(e) {
                dataSet(), fire('pull', e.lengthComputable ? [e.loaded, e.total] : [0, -1]);
            });
            onEvent('progress', requestAsPush, function(e) {
                dataSet(), fire('push', e.lengthComputable ? [e.loaded, e.total] : [0, -1]);
            });
            return request;
        }

        function doFetchAbort(id) {
            if (requests[id] && requests[id][0]) {
                requests[id][0].abort();
                delete requests[id];
            }
        }

        function doFetchAbortAll() {
            for (var request in requests) {
                doFetchAbort(request);
            }
        } // TODO: Change to the modern `window.fetch` function when it is possible to track download and upload progress!
        function doFetchBase(node, type, ref, headers) {
            ref = isFunction(state.ref) ? state.ref.call($, node, ref) : ref;
            var header,
                request = new XMLHttpRequest(); // Automatic response type based on current file extension
            var x = toCaseUpper(ref.split(/[?&#]/)[0].split('/').pop().split('.')[1] || ""),
                responseType = state.types[x] || state.type || responseTypeTXT;
            if (isFunction(responseType)) {
                responseType = responseType.call($, ref);
            }
            request.responseType = responseType;
            request.open(type, ref, true); // if (POST === type) {
            //    request.setRequestHeader('content-type', node.enctype || 'multipart/form-data');
            // }
            if (isObject(headers)) {
                for (header in headers) {
                    request.setRequestHeader(header, headers[header]);
                }
            }
            request.send(POST === type ? new FormData(node) : null);
            return request;
        } // Focus to the first element that has `autofocus` attribute
        function doFocusToElement(data) {
            if (hooks.focus) {
                fire('focus', data);
                return;
            }
            var target = getElement('[autofocus]', source);
            target && target.focus();
        } // Pre-fetch page and store it into cache
        function doPreFetch(node, ref) {
            var request = doFetchBase(node, GET, ref);
            onEvent('load', request, function() {
                if (200 === (status = request.status)) {
                    caches[letSlashEnd(letHash(ref))] = [status, request.response, toHeadersAsProxy(request), responseTypeHTML === request.responseType];
                }
            });
        }

        function doPreFetchElement(node) {
            onEvent('mousemove', node, onHoverOnce);
        }

        function doScrollTo(node) {
            if (!node) {
                return;
            }
            var theOffset = getOffset(node);
            setScroll(B, theOffset);
            setScroll(R, theOffset);
        } // Scroll to the first element with `id` or `name` attribute that has the same value as location hash
        function doScrollToElement(data) {
            if (hooks.scroll) {
                fire('scroll', data);
                return;
            }
            doScrollTo(getTarget(getHash(getRef()), 1));
        }

        function doUpdate(compare, to, getAll, defaultContainer) {
            var id,
                toCompare = getAll(compare),
                node,
                placesToRestore = {},
                v;
            for (id in to) {
                if (node = getElement('#' + id.replace(/[:.]/g, '\\$&'), source)) {
                    placesToRestore[id] = getNext(node);
                }
                if (!toCompare[id]) {
                    delete to[id];
                    var target = getTarget(id);
                    target && letElement(target);
                }
            }
            for (id in toCompare) {
                if (!to[id]) {
                    to[id] = v = toCompare[id];
                    if (placesToRestore[id] && hasParent(placesToRestore[id])) {
                        setPrev(placesToRestore[id], toElement(v));
                    } else if (defaultContainer) {
                        setChildLast(defaultContainer, toElement(v));
                    }
                }
            }
            return to;
        }

        function doUpdateLinks(compare) {
            return doUpdate(compare, links, getLinks, H);
        }

        function doUpdateScripts(compare) {
            return doUpdate(compare, scripts, getScripts, B);
        }

        function doUpdateStyles(compare) {
            return doUpdate(compare, styles, getStyles, H);
        }

        function onDocumentReady() {
            // Detect key down/up event
            onEvent('keydown', D, onKeyDown);
            onEvent('keyup', D, onKeyUp); // Set body and head variable value once, on document ready
            B = D.body;
            H = D.head; // Make sure all element(s) are captured on document ready
            $.links = links = getLinks();
            $.scripts = scripts = getScripts();
            $.styles = styles = getStyles();
            onSourcesEventsSet([D, W]); // Store the initial page into cache
            state.cache && doPreFetch(W, getRef());
        }

        function onFetch(e) {
            doFetchAbortAll(); // Use native web feature when user press the control key
            if (keyIsCtrl) {
                return;
            }
            var t = this,
                q,
                href = t.href,
                action = t.action,
                ref = letSlashEnd(href || action),
                type = toCaseUpper(t.method || GET);
            if (GET === type) {
                if (isForm(t)) {
                    q = new URLSearchParams(new FormData(t)) + "";
                    ref = ref.split(/[?&#]/)[0] + (q ? '?' + q : "");
                } // Immediately change the URL if turbo feature is enabled
                if (state.turbo) {
                    doChangeRef(ref);
                }
            }
            requests[ref] = [doFetch(t, type, ref), t];
            offEventDefault(e);
        }

        function onHashChange(e) {
            doScrollTo(getTarget(getHash(getRef()), 1));
            offEventDefault(e);
        } // Pre-fetch URL on link hover
        function onHoverOnce() {
            var t = this,
                href = t.href;
            if (!caches[letSlashEnd(letHash(href))]) {
                doPreFetch(t, href);
            }
            offEvent('mousemove', t, onHoverOnce);
        } // Check if user is pressing the control key before clicking on a link
        var keyIsCtrl = false;

        function onKeyDown(e) {
            keyIsCtrl = e.ctrlKey;
        }

        function onKeyUp() {
            keyIsCtrl = false;
        }

        function onPopState(e) {
            ref = getRef();
            doFetchAbortAll(); // Updating the hash value shouldn’t trigger the AJAX call!
            if (getHash(ref) && letHash(refCurrent) === letHash(ref)) {
                return;
            }
            requests[ref] = [doFetch(W, GET, ref), W];
        }

        function onSourcesEventsLet() {
            sources.forEach(function(source) {
                onEvent(getEventName(source), source, onFetch);
            });
        }

        function onSourcesEventsSet(data) {
            var turbo = state.turbo;
            sources.forEach(function(source) {
                onEvent(getEventName(source), source, onFetch);
                if (isForm(source)) {
                    doAppendCurrentButtonValue(source);
                } else {
                    turbo && doPreFetchElement(source);
                }
            });
            doFocusToElement(data);
            doScrollToElement(data);
        }
        $.abort = function(request) {
            if (!request) {
                doFetchAbortAll();
            } else if (requests[request]) {
                doFetchAbort(request);
            }
            return $;
        };
        $.caches = caches;
        $.fetch = function(ref, type, from) {
            return doFetchBase(from, type, ref);
        };
        $.kick = function(ref) {
            var trigger = setElement('a', {
                'href': ref
            });
            onEvent('click', trigger, onFetch, {
                once: true
            });
            trigger.click();
            letElement(trigger);
        };
        $.links = links;
        $.lot = null;
        $.ref = null;
        $.scripts = scripts;
        $.state = state;
        $.styles = styles;
        $.status = null;
        $.pop = function() {
            if (!source[name$3]) {
                return $; // Already ejected!
            }
            delete source[name$3];
            onSourcesEventsLet();
            offEvent('DOMContentLoaded', W, onDocumentReady);
            offEvent('hashchange', W, onHashChange);
            offEvent('keydown', D, onKeyDown);
            offEvent('keyup', D, onKeyUp);
            offEvent('popstate', W, onPopState);
            fire('pop', [D, W]);
            return $.abort();
        };
        onEvent('DOMContentLoaded', W, onDocumentReady);
        onEvent('hashchange', W, onHashChange);
        onEvent('popstate', W, onPopState);
        return $;
    }
    F3H.instances = {};
    F3H.state = {
        'cache': false,
        // Store all response body to variable to be used later?
        'history': true,
        'is': function is(source, ref) {
            var target = source.target,
                // Get URL data as-is from the DOM attribute string
                raw = getAttribute(source, 'href', false) || getAttribute(source, 'action', false) || "",
                // Get resolved URL data from the DOM property
                value = source.href || source.action || "";
            if (target && '_self' !== target) {
                return false;
            } // Exclude URL contains hash only, and any URL prefixed by `data:`, `javascript:` and `mailto:`
            if ('#' === raw[0] || /^(data|javascript|mailto):/.test(raw)) {
                return false;
            } // If `value` is the same as current URL excluding the hash, treat `raw` as hash only,
            // so that we don’t break the native hash change event that you may want to add in the future
            if (getHash(value) && letHash(ref) === letHash(value)) {
                return false;
            } // Detect internal link starts from here
            return "" === raw || 0 === raw.search(/[.\/?]/) || 0 === raw.indexOf(home) || 0 === raw.indexOf(theLocation.protocol + home) || -1 === raw.indexOf('://');
        },
        'lot': {
            'x-requested-with': name$3
        },
        'ref': function ref(source, _ref) {
            return _ref;
        },
        // Default URL hook
        'sources': 'a[href],form',
        'turbo': false,
        // Pre-fetch any URL on hover?
        'type': responseTypeHTML,
        'types': {
            "": responseTypeHTML,
            // Default response type for extension-less URL
            'CSS': responseTypeTXT,
            'JS': responseTypeTXT,
            'JSON': responseTypeJSON
        }
    };
    F3H.version = '1.2.1';
    var hasValue = function hasValue(x, data) {
        return -1 !== data.indexOf(x);
    };
    var name$2 = 'OP',
        PROP_INDEX = 'i',
        PROP_SOURCE = '$',
        PROP_VALUE = 'v';
    var KEY_ARROW_DOWN = 'ArrowDown';
    var KEY_ARROW_UP = 'ArrowUp';
    var KEY_END$1 = 'End';
    var KEY_ENTER$1 = 'Enter';
    var KEY_ESCAPE = 'Escape';
    var KEY_START = 'Home';
    var KEY_TAB$1 = 'Tab';

    function OP(source, state) {
        if (state === void 0) {
            state = {};
        }
        if (!source) return;
        var $ = this; // Return new instance if `OP` was called without the `new` operator
        if (!isInstance($, OP)) {
            return new OP(source, state);
        } // Already instantiated, skip!
        if (source[name$2]) {
            return;
        }
        var _hook = hook($),
            fire = _hook.fire;
        _hook.hooks;
        $.state = state = fromStates(OP.state, state);
        $.options = {};
        $.source = source; // Store current instance to `OP.instances`
        OP.instances[source.id || source.name || toObjectCount(OP.instances)] = $; // Mark current DOM as active option picker to prevent duplicate instance
        source[name$2] = 1;

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
            return hasClass(selectBoxFakeOption, classNameOptionM + 'active');
        }

        function letOptionSelected(selectBoxOption) {
            letAttribute(selectBoxOption, 'selected');
            selectBoxOption.selected = false;
        }

        function letOptionFakeSelected(selectBoxFakeOption) {
            letClass(selectBoxFakeOption, classNameOptionM + 'active');
        }

        function setOptionSelected(selectBoxOption) {
            setAttribute(selectBoxOption, 'selected', true);
            selectBoxOption.selected = true;
        }

        function setOptionFakeSelected(selectBoxFakeOption) {
            setClass(selectBoxFakeOption, classNameOptionM + 'active');
        }

        function setLabelContent(content) {
            content = content || "\u200C";
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
            }
        }
        var classNameB = state['class'],
            classNameE = classNameB + '__',
            classNameM = classNameB + '--',
            classNameOptionB = classNameE + 'option',
            classNameOptionM = classNameOptionB + '--',
            classNameOptionsB = classNameE + 'options',
            classNameOptionsM = classNameOptionsB + '--',
            classNameValueB = classNameE + 'value',
            classNameValuesB = classNameE + 'values',
            selectBox = setElement(source, {
                'class': classNameE + 'source',
                'tabindex': '-1'
            }),
            selectBoxIsDisabled = function selectBoxIsDisabled() {
                return selectBox.disabled;
            },
            selectBoxItems = getChildren(selectBox),
            selectBoxMultiple = selectBox.multiple,
            selectBoxOptionIndex = 0,
            selectBoxOptions = selectBox.options,
            selectBoxParent = state.parent || D,
            selectBoxSize = selectBox.size,
            selectBoxTitle = selectBox.title,
            selectBoxValue = getValue(),
            selectBoxFake = setElement('div', {
                'class': classNameB,
                'tabindex': '0',
                'title': selectBoxTitle
            }),
            selectBoxFakeLabel = setElement('div', "\u200C", {
                'class': classNameValuesB
            }),
            selectBoxFakeDropDown = setElement('div', {
                'class': classNameOptionsB,
                'tabindex': '-1'
            }),
            selectBoxFakeOptions = [],
            _keyIsCtrl = false,
            _keyIsShift = false;
        if (selectBoxMultiple && !selectBoxSize) {
            selectBox.size = selectBoxSize = state.size;
        }
        setChildLast(selectBoxFake, selectBoxFakeLabel);
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

        function doToggle(force) {
            toggleClass(selectBoxFake, classNameM + 'open', force);
            var isOpen = isEnter();
            fire(isOpen ? 'enter' : 'exit', getLot());
            return isOpen;
        }

        function doValue(content, value, index, classNames) {
            return '<span class="' + classNameValueB + ' ' + classNames + '" data-index="' + index + '"' + (value ? ' data-value="' + value + '"' : "") + '>' + content + '</span>';
        }

        function isEnter() {
            return hasClass(selectBoxFake, classNameM + 'open');
        }

        function onSelectBoxFocus() {
            selectBoxFake.focus();
        }

        function onSelectBoxFakeOptionClick(e) {
            if (selectBoxIsDisabled()) {
                return;
            }
            var selectBoxFakeOption = this,
                selectBoxOption = selectBoxFakeOption[PROP_SOURCE],
                selectBoxValuePrevious = selectBoxValue;
            selectBoxValue = selectBoxFakeOption[PROP_VALUE];
            var selectBoxFakeLabelContent = [],
                content,
                index,
                value;
            e && e.isTrusted && onSelectBoxFocus();
            offEventDefault(e);
            if (selectBoxMultiple && _keyIsCtrl) {
                if (getOptionFakeSelected(selectBoxFakeOption)) {
                    letOptionSelected(selectBoxOption);
                    letOptionFakeSelected(selectBoxFakeOption);
                } else {
                    setOptionSelected(selectBoxOption);
                    setOptionFakeSelected(selectBoxFakeOption);
                }
                for (var i = 0, _j3 = toCount(selectBoxOptions); i < _j3; ++i) {
                    if (getOptionSelected(selectBoxOptions[i])) {
                        content = getText(selectBoxFakeOptions[i]);
                        index = selectBoxFakeOptions[i][PROP_INDEX];
                        value = selectBoxFakeOptions[i][PROP_VALUE];
                        content = doValue(content, value, index, getClasses(selectBoxFakeOptions[i], false));
                        selectBoxFakeLabelContent.push(content);
                    }
                }
                setLabelContent(selectBoxFakeLabelContent.join('<span>' + state.join + '</span>'));
                fire('change', getLot());
                return;
            }
            content = getText(selectBoxFakeOption);
            index = selectBoxFakeOption[PROP_INDEX];
            value = selectBoxFakeOption[PROP_VALUE];
            content = doValue(content, value, index, getClasses(selectBoxFakeOption, false));
            setLabelContent(content);
            selectBoxFakeOptions.forEach(function(selectBoxFakeOption) {
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
            if (selectBoxSize) {
                return doEnter();
            }
            doToggle() && setSelectBoxFakeOptionsPosition(selectBoxFake);
        }

        function onSelectBoxFakeFocus(e) {
            doFocus();
        }

        function onSelectBoxFakeKeyDown(e) {
            _keyIsCtrl = e.ctrlKey;
            _keyIsShift = e.shiftKey;
            var key = e.key,
                selectBoxOptionIndexCurrent = selectBox.selectedIndex,
                selectBoxFakeOption = selectBoxFakeOptions[selectBoxOptionIndexCurrent],
                selectBoxFakeOptionIsDisabled = function selectBoxFakeOptionIsDisabled(selectBoxFakeOption) {
                    return hasClass(selectBoxFakeOption, classNameOptionM + 'lock');
                },
                doClick = function doClick(selectBoxFakeOption) {
                    return onSelectBoxFakeOptionClick.call(selectBoxFakeOption);
                },
                isOpen = isEnter();
            if (KEY_ARROW_DOWN === key) {
                while (selectBoxFakeOption = selectBoxFakeOptions[++selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption)) {
                        break;
                    }
                }
                selectBoxFakeOption && (doClick(selectBoxFakeOption), doToggle(isOpen));
                offEventDefault(e);
            } else if (KEY_ARROW_UP === key) {
                while (selectBoxFakeOption = selectBoxFakeOptions[--selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption)) {
                        break;
                    }
                }
                selectBoxFakeOption && (doClick(selectBoxFakeOption), doToggle(isOpen));
                offEventDefault(e);
            } else if (KEY_END$1 === key) {
                selectBoxOptionIndexCurrent = toCount(selectBoxOptions);
                while (selectBoxFakeOption = selectBoxFakeOptions[--selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption)) {
                        break;
                    }
                }
                selectBoxFakeOption && (doClick(selectBoxFakeOption), doToggle(isOpen));
                offEventDefault(e);
            } else if (KEY_ENTER$1 === key) {
                doToggle(), offEventDefault(e);
            } else if (KEY_ESCAPE === key) {
                !selectBoxSize && doExit(); // offEventDefault(e);
            } else if (KEY_START === key) {
                selectBoxOptionIndexCurrent = 0;
                while (selectBoxFakeOption = selectBoxFakeOptions[++selectBoxOptionIndexCurrent]) {
                    if (!selectBoxFakeOptionIsDisabled(selectBoxFakeOption)) {
                        break;
                    }
                }
                selectBoxFakeOption && (doClick(selectBoxFakeOption), doToggle(isOpen));
                offEventDefault(e);
            } else if (KEY_TAB$1 === key) {
                selectBoxFakeOption && doClick(selectBoxFakeOption);
                !selectBoxSize && doExit(); // offEventDefault(e);
            }
            isOpen && !_keyIsCtrl && !_keyIsShift && setSelectBoxFakeOptionsPosition(selectBoxFake);
        }

        function onSelectBoxFakeKeyUp() {
            _keyIsCtrl = _keyIsShift = false;
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
            isEnter() && setSelectBoxFakeOptionsPosition(selectBoxFake);
        }

        function setSelectBoxFakeOptions(selectBoxItem, parent) {
            if ('optgroup' === getName(selectBoxItem)) {
                var selectBoxFakeOptionGroup = setElement('span', {
                        'class': classNameE + 'group'
                    }),
                    _selectBoxItems = getChildren(selectBoxItem);
                selectBoxFakeOptionGroup.title = selectBoxItem.label;
                for (var i = 0, _j4 = toCount(_selectBoxItems); i < _j4; ++i) {
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
                setClass(selectBoxFakeOption, classNameOptionM + 'lock');
            } else {
                onEvent('click', selectBoxFakeOption, onSelectBoxFakeOptionClick);
            }
            setChildLast(parent, selectBoxFakeOption);
            selectBoxFakeOptions.push(selectBoxFakeOption);
            if (isArray(selectBoxValue) && hasValue(selectBoxOptionValue, selectBoxValue) || selectBoxOptionValue === selectBoxValue) {
                setClass(selectBoxFakeOption, classNameOptionM + 'active');
                setLabelContent(doValue(selectBoxOptionText, selectBoxOptionValueReal, selectBoxOptionIndex, classNameOptionB + ' ' + (selectBoxOptionIsDisabled ? ' ' + classNameOptionM + 'lock' : "")));
                setOptionSelected(selectBoxItem);
            } else {
                letOptionSelected(selectBoxItem);
            }
            ++selectBoxOptionIndex;
        }

        function setSelectBoxFakeOptionsPosition(selectBoxFake) {
            var selectBoxFakeBorderTopWidth = toNumber(getStyle(selectBoxFake, 'border-top-width')),
                selectBoxFakeBorderBottomWidth = toNumber(getStyle(selectBoxFake, 'border-bottom-width'));
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
                    setClass(selectBoxFakeDropDown, classNameOptionsM + 'flip');
                } else {
                    letClass(selectBoxFakeDropDown, classNameOptionsM + 'flip');
                }
            }
            var selectBoxFakeOption = selectBoxFakeOptions.find(function(selectBoxFakeOption) {
                return hasClass(selectBoxFakeOption, classNameOptionM + 'active');
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
            fire('fit', getLot());
        }
        onEvents(['resize', 'scroll'], W, onSelectBoxWindow);
        onEvent('click', selectBoxParent, onSelectBoxParentClick);
        onEvent('focus', selectBox, onSelectBoxFocus);
        onEvent('blur', selectBoxFake, onSelectBoxFakeBlur);
        onEvent('click', selectBoxFake, onSelectBoxFakeClick);
        onEvent('focus', selectBoxFake, onSelectBoxFakeFocus);
        onEvent('keydown', selectBoxFake, onSelectBoxFakeKeyDown);
        onEvent('keyup', selectBoxFake, onSelectBoxFakeKeyUp);
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
            // Force `open` class
            setClass(selectBoxFake, classNameM + 'open');
        }
        $.get = function(parseValue) {
            if (parseValue === void 0) {
                parseValue = true;
            }
            var value = getValue();
            return parseValue ? toValue(value) : value;
        };
        $.pop = function() {
            if (!source[name$2]) {
                return $; // Already ejected
            }
            delete source[name$2];
            offEvents(['resize', 'scroll'], W, onSelectBoxWindow);
            offEvent('click', selectBoxParent, onSelectBoxParentClick);
            offEvent('focus', selectBox, onSelectBoxFocus);
            letClass(selectBox, classNameE + 'source');
            offEvent('blur', selectBoxFake, onSelectBoxFakeBlur);
            offEvent('click', selectBoxFake, onSelectBoxFakeClick);
            offEvent('focus', selectBoxFake, onSelectBoxFakeFocus);
            offEvent('keydown', selectBoxFake, onSelectBoxFakeKeyDown);
            offEvent('keyup', selectBoxFake, onSelectBoxFakeKeyUp);
            letText(selectBoxFake);
            letElement(selectBoxFake);
            return fire('pop', getLot());
        };
        $.set = function(value) {
            setValue(fromValue(value));
            selectBoxFakeOptions.forEach(function(selectBoxFakeOption, index) {
                var selectBoxOption = selectBoxOptions[index];
                toggleClass(selectBoxFakeOption, classNameOptionM + 'active', selectBoxOption && getOptionSelected(selectBoxOption));
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
    OP.version = '1.1.1';
    var name$1 = 'TE';

    function trim(str, dir) {
        return (str || "")['trim' + (-1 === dir ? 'Left' : 1 === dir ? 'Right' : "")]();
    }

    function TE(source, state) {
        if (state === void 0) {
            state = {};
        }
        if (!source) return;
        var $ = this; // Return new instance if `TE` was called without the `new` operator
        if (!isInstance($, TE)) {
            return new TE(source, state);
        } // Already instantiated, skip!
        if (source[name$1]) {
            return;
        }
        $.state = state = fromStates(TE.state, isString(state) ? {
            tab: state
        } : state || {}); // The `<textarea>` element
        $.self = $.source = source; // Store current instance to `TE.instances`
        TE.instances[source.id || source.name || toObjectCount(TE.instances)] = $; // Mark current DOM as active text editor to prevent duplicate instance
        source[name$1] = 1;
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
        $.get = function() {
            return !sourceIsDisabled() && trim(sourceValue()) || null;
        }; // Reset to the initial value
        $.let = function() {
            return source.value = $.value, $;
        }; // Set value
        $.set = function(value) {
            if (sourceIsDisabled() || sourceIsReadOnly()) {
                return $;
            }
            return source.value = value, $;
        }; // Get selection
        $.$ = function() {
            return new TE.S(source.selectionStart, source.selectionEnd, sourceValue());
        };
        $.focus = function(mode) {
            var x, y;
            if (-1 === mode) {
                x = y = 0; // Put caret at the start of the editor, scroll to the start of the editor
            } else if (1 === mode) {
                x = toCount(sourceValue()); // Put caret at the end of the editor
                y = source.scrollHeight; // Scroll to the end of the editor
            }
            if (isSet(x) && isSet(y)) {
                source.selectionStart = source.selectionEnd = x;
                source.scrollTop = y;
            }
            return source.focus(), $;
        }; // Blur from the editor
        $.blur = function() {
            return source.blur(), $;
        }; // Select value
        $.select = function() {
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
            x = W.pageXOffset || R.scrollLeft || B$1.scrollLeft;
            y = W.pageYOffset || R.scrollTop || B$1.scrollTop;
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
        $.match = function(pattern, then) {
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
        $.replace = function(from, to, mode) {
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
        $.insert = function(value, mode, clear) {
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
        $.wrap = function(open, close, wrap) {
            var _$$$4 = $.$(),
                after = _$$$4.after,
                before = _$$$4.before,
                value = _$$$4.value;
            if (wrap) {
                return $.replace(any, open + '$1' + close);
            }
            return $.set(before + open + value + close + after).select(before = toCount(before + open), before + toCount(value));
        }; // Unwrap current selection
        $.peel = function(open, close, wrap) {
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
        $.pull = function(by, includeEmptyLines) {
            if (includeEmptyLines === void 0) {
                includeEmptyLines = true;
            }
            var _$$$6 = $.$(),
                length = _$$$6.length,
                value = _$$$6.value;
            by = esc(isSet(by) ? by : state.tab);
            if (length) {
                if (includeEmptyLines) {
                    return $.replace(toPattern('^' + by, 'gm'), "");
                }
                return $.insert(value.split('\n').map(function(v) {
                    if (toPattern('^(' + by + ')*$', "").test(v)) {
                        return v;
                    }
                    return v.replace(toPattern('^' + by, ""), "");
                }).join('\n'));
            }
            return $.replace(toPattern(by + '$', ""), "", -1);
        };
        $.push = function(by, includeEmptyLines) {
            if (includeEmptyLines === void 0) {
                includeEmptyLines = false;
            }
            var _$$$7 = $.$(),
                length = _$$$7.length;
            by = isSet(by) ? by : state.tab;
            if (length) {
                return $.replace(toPattern('^' + (includeEmptyLines ? "" : '(?!$)'), 'gm'), by);
            }
            return $.insert(by, -1);
        };
        $.trim = function(open, close, start, end, tidy) {
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
        $.pop = function() {
            if (!source[name$1]) {
                return $; // Already ejected!
            }
            return delete source[name$1], $;
        }; // Return the text editor state
        $.state = state;
        return $;
    }
    TE.esc = esc;
    TE.instances = {};
    TE.state = {
        'tab': '\t'
    };
    TE.S = function(a, b, c) {
        var t = this,
            d = c.slice(a, b);
        t.after = c.slice(b);
        t.before = c.slice(0, a);
        t.end = b;
        t.length = toCount(d);
        t.start = a;
        t.value = d;
        t.toString = function() {
            return d;
        };
    };
    TE.version = '3.3.4';
    TE.x = x;
    var delay = W.setTimeout,
        name = 'TP';
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
        if (!source) return;
        var $ = this; // Already instantiated, skip!
        if (source[name]) {
            return;
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
        $.state = state = fromStates(TP.state, isString(state) ? {
            join: state
        } : state || {});
        $.source = source; // Store current instance to `TP.instances`
        TP.instances[source.id || source.name || toObjectCount(TP.instances)] = $; // Mark current DOM as active tag picker to prevent duplicate instance
        source[name] = 1;
        var classNameB = state['class'],
            classNameE = classNameB + '__',
            classNameM = classNameB + '--',
            form = getParentForm(source),
            // Capture the closest `<form>` element
            self = setElement('span', {
                'class': classNameB,
                'tabindex': sourceIsDisabled() ? false : '-1'
            }),
            text = setElement('span', {
                'class': classNameE + 'tag ' + classNameE + 'text'
            }),
            textCopy = setElement('input', {
                'class': classNameE + 'copy',
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
        var _keyIsShift, _keyIsTab;

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
                if (hasClass(items[i], classNameE + 'tag--focus')) {
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
                'tabindex': sourceIsDisabled() ? false : '0',
                'title': tag
            });
            var x = setElement('a', {
                'class': classNameE + 'tag-x',
                'href': "",
                'tabindex': '-1',
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
            doToTags(exceptThisTag, function() {
                letClass(this, classNameE + 'tag--focus');
            });
        }

        function doFocusTags(exceptThisTag) {
            doToTags(exceptThisTag, function() {
                setClass(this, classNameE + 'tag--focus');
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
            state.escape.forEach(function(char) {
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
                if (!_keyIsShift || _keyIsTab) {
                    doBlurTags(t);
                    letClass(t, classNameTagM + 'focus');
                    letClasses(self, [classNameM + 'focus', classNameM + 'focus-tag']);
                }
            } else {
                setClass(t, classNameTagM + 'focus');
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
            if ('blur' === type) {
                letClass(text, classNameTextM + 'focus');
                letClasses(self, [classNameM + 'focus', classNameM + 'focus-text']);
                doInput();
            } else {
                setClass(text, classNameTextM + 'focus');
                setClasses(self, [classNameM + 'focus', classNameM + 'focus-text']);
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
                delay(function() {
                    return letTextCopy(1);
                });
            } else if ('cut' === type) {
                !sourceIsReadOnly() && setTags("");
                delay(function() {
                    return letTextCopy(1);
                });
            } else if ('paste' === type) {
                delay(function() {
                    !sourceIsReadOnly() && setTags(textCopy.value);
                    letTextCopy(1);
                });
            }
            delay(function() {
                var tags = $.tags;
                fire(type, [tags, toCount(tags)]);
            }, 1);
        }

        function onBlurSelf() {
            doBlurTags(), letClass(self, classNameM + 'focus-self');
        }

        function onFocusSource() {
            textInput.focus();
        }

        function onKeyDownSelf(e) {
            if (sourceIsDisabled()) {
                return;
            }
            $.tags;
            var key = e.key,
                keyIsCtrl = e.ctrlKey,
                keyIsShift = _keyIsShift = e.shiftKey,
                classNameTagM = classNameE + 'tag--';
            _keyIsTab = KEY_TAB === key;
            if (sourceIsReadOnly()) {
                return;
            }
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
                    offEventDefault(e); // Focus to the first tag
                } else if (KEY_BEGIN === key) {
                    if (theTag = getChildren(textOutput, 0)) {
                        theTag.focus(), offEventDefault(e);
                    } // Focus to the last tag
                } else if (KEY_END === key) {
                    if (theTag = getChildren(textOutput, toCount($.tags) - 1)) {
                        theTag.focus(), offEventDefault(e);
                    } // Focus to the previous tag
                } else if (KEY_ARROW_LEFT === key) {
                    if (theTag = getChildren(textOutput, currentTagIndex - 1)) {
                        var theTagWasFocus = hasClass(theTag, classNameTagM + 'focus');
                        theTag.focus(), offEventDefault(e);
                        if (keyIsShift) {
                            theTagNext = getNext(theTag);
                            if (theTagWasFocus) {
                                letClass(theTagNext, classNameTagM + 'focus');
                            }
                        } else {
                            doBlurTags(theTag);
                        }
                    } else if (!keyIsShift) {
                        doBlurTags(getChildren(textOutput, 0));
                    } // Focus to the next tag or to the tag editor
                } else if (KEY_ARROW_RIGHT === key) {
                    if (theTag = getChildren(textOutput, currentTagIndex + 1)) {
                        var _theTagWasFocus = hasClass(theTag, classNameTagM + 'focus');
                        text === theTag && !keyIsShift ? setValue("", 1) : theTag.focus(), offEventDefault(e);
                        if (keyIsShift) {
                            theTagPrev = getPrev(theTag);
                            if (_theTagWasFocus) {
                                letClass(theTagPrev, classNameTagM + 'focus');
                            }
                        } else {
                            doBlurTags(theTag);
                        }
                    }
                }
            } else {
                // Select all tag(s) with `Ctrl+A` key
                if (KEY_A === key) {
                    setTextCopy(1);
                    doFocusTags(), setCurrentTags(), offEventDefault(e);
                }
            }
        }

        function onKeyDownText(e) {
            offEventPropagation(e);
            if (sourceIsReadOnly()) {
                offEventDefault(e);
            }
            var escapes = state.escape,
                theTag,
                theTagLast = getPrev(text),
                theTagsCount = toCount($.tags),
                theTagsMax = state.max,
                theValue = doValidTag(getText(textInput)),
                key = e.key,
                keyIsCtrl = e.ctrlKey,
                keyIsEnter = KEY_ENTER === key;
            e.shiftKey;
            var keyIsTab = KEY_TAB === key;
            if (keyIsEnter) {
                key = '\n';
            }
            if (keyIsTab) {
                key = '\t';
            }
            delay(function() {
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
            }); // Select all tag(s) with `Ctrl+A` key
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
            _keyIsShift = false;
        }

        function onPasteText() {
            delay(function() {
                if (!sourceIsDisabled() && !sourceIsReadOnly()) {
                    getText(textInput).split(state.join).forEach(function(v) {
                        if (!hasValue(v, $.tags)) {
                            setTagElement(v), setTag(v);
                        }
                    });
                }
                setValue("");
            });
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
            'tabindex': '-1'
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
        $.blur = function() {
            return !sourceIsDisabled() && textInput.blur(), $;
        };
        $.click = function() {
            return self.click(), onClickSelf(), $;
        }; // Default filter for the tag name
        $.f = function(v) {
            return toCaseLower(v || "").replace(/[^ a-z\d-]/g, "").trim();
        };
        $.focus = function() {
            if (!sourceIsDisabled()) {
                setValue(getText(textInput), 1);
            }
            return $;
        };
        $.get = function(tag) {
            return sourceIsDisabled() ? null : getTag(tag, 1);
        };
        $.input = textInput;
        $.let = function(tag) {
            if (!sourceIsDisabled() && !sourceIsReadOnly()) {
                var theTagsMin = state.min;
                if (!tag) {
                    setTags("");
                } else if (isArray(tag)) {
                    tag.forEach(function(v) {
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
        $.pop = function() {
            if (!source[name]) {
                return $; // Already ejected!
            }
            delete source[name];
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
        $.set = function(tag, index) {
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
    TP.version = '3.3.1';
    var that$1 = {};
    that$1._history = [];
    that$1._historyState = -1; // Get history data
    that$1.history = function(index) {
        var t = this;
        if (!isSet(index)) {
            return t._history;
        }
        return isSet(t._history[index]) ? t._history[index] : null;
    }; // Remove state from history
    that$1.loss = function(index) {
        var t = this,
            current;
        if (true === index) {
            t._history = [];
            t._historyState = -1;
            return [];
        }
        current = t._history.splice(isSet(index) ? index : t._historyState, 1);
        t._historyState = toEdge(t._historyState - 1, [-1]);
        return current;
    }; // Save current state to history
    that$1.record = function(index) {
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
        return t._history[isSet(index) ? index : t._historyState] = next, t;
    }; // Redo previous state
    that$1.redo = function() {
        var t = this,
            state;
        t._historyState = toEdge(t._historyState + 1, [0, toCount(t._history) - 1]);
        state = t._history[t._historyState];
        return t.set(state[0]).select(state[1], state[2]);
    }; // Undo current state
    that$1.undo = function() {
        var t = this,
            state;
        t._historyState = toEdge(t._historyState - 1, [0, toCount(t._history) - 1]);
        state = t._history[t._historyState];
        return t.set(state[0]).select(state[1], state[2]);
    };
    var debounce = function debounce(then, time) {
        var timer;
        return function() {
            var _arguments = arguments,
                _this = this;
            timer && clearTimeout(timer);
            timer = setTimeout(function() {
                return then.apply(_this, _arguments);
            }, time);
        };
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
    var defaults$1 = {
        source: {
            pairs: pairs
        }
    };
    var pairsValue = toObjectValues(pairs);
    var that = {};
    that.toggle = function(open, close, wrap) {
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
        return t.wrap(open, close, wrap);
    };

    function canKeyDown$1(key, _ref, that) {
        var _that$state$source;
        var a = _ref.a,
            c = _ref.c,
            s = _ref.s;
        var charAfter,
            charBefore,
            charIndent = defaults$1.tab || that.state.tab || '\t',
            charPairs = ((_that$state$source = that.state.source) == null ? void 0 : _that$state$source.pairs) || pairs; // Do nothing
        if (a || c) {
            return true;
        }
        if (' ' === key && !s) {
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
        if ('Enter' === key && !s) {
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
        if ('Backspace' === key && !s) {
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
        charAfter = pairsValue.includes(after[0]) ? after[0] : charPairs[charBefore]; // `|}`
        if (!value && after && before && charAfter && key === charAfter) {
            // Move to the next character
            // `}|`
            that.select(start + 1).record();
            return false;
        }
        for (charBefore in charPairs) {
            charAfter = charPairs[charBefore]; // `{|`
            if (charBefore === key) {
                // Wrap pair or selection
                // `{|}` `{|aaa|}`
                that.wrap(charBefore, charAfter).record();
                return false;
            } // `|}`
            if (charAfter === key) {
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

    function canKeyDownDent(key, _ref2, that) {
        var a = _ref2.a,
            c = _ref2.c;
        _ref2.s;
        var charIndent = that.state.tab || '\t';
        if (!a && c) {
            // Indent with `⌘+]`
            if (']' === key) {
                that.push(charIndent).record();
                return false;
            } // Outdent with `⌘+[`
            if ('[' === key) {
                that.pull(charIndent).record();
                return false;
            }
        }
        return true;
    }

    function canKeyDownHistory(key, _ref3, that) {
        var a = _ref3.a,
            c = _ref3.c;
        _ref3.s;
        if (!a && c) {
            // Redo with `⌘+y`
            if ('y' === key) {
                that.redo();
                return false;
            } // Undo with `⌘+z`
            if ('z' === key) {
                that.undo();
                return false;
            }
        }
        return true;
    }
    var bounce = debounce(function(that) {
        return that.record();
    }, 100);

    function canKeyUp(key, _ref5, that) {
        _ref5.a;
        _ref5.c;
        _ref5.s;
        return bounce(that), true;
    }
    var state$1 = defaults$1;
    var tagComment = '<!--([\\s\\S]*?)-->',
        tagData = '<!((?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^>\'"])*)>',
        tagName = '[\\w:.-]+',
        tagStart = function tagStart(name) {
            return '<(' + name + ')(\\s(?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^/>\'"])*)?>';
        },
        tagEnd = function tagEnd(name) {
            return '</(' + name + ')>';
        },
        tagVoid = function tagVoid(name) {
            return '<(' + name + ')(\\s(?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^/>\'"])*)?/?>';
        },
        tagPreamble = '<\\?((?:\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|[^>\'"])*)\\?>',
        tagTokens = '(?:' + tagComment + '|' + tagData + '|' + tagEnd(tagName) + '|' + tagPreamble + '|' + tagStart(tagName) + '|' + tagVoid(tagName) + ')';
    var defaults = {
        sourceXML: {}
    };

    function canKeyDown(key, _ref, that) {
        var a = _ref.a,
            c = _ref.c,
            s = _ref.s;
        var state = that.state,
            charIndent = state.tab || '\t'; // Do nothing
        if (a || c) {
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
                var tagStartMatch = toPattern(tagStart(tagName) + '$', "").exec(before + '>');
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
                    if ('></' + tagStartMatch[1] + '>' === after.slice(0, toCount(tagStartMatch[1]) + 4)) {
                        that.select(start + 1).record(); // `<div|</div>`
                    } else if ('</' + tagStartMatch[1] + '>' === after.slice(0, toCount(tagStartMatch[1]) + 3)) {
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
            if (' ' === key) {
                // `<!--|-->`
                if (!value && '<!--' === before.slice(-4) && '-->' === after.slice(0, 3)) {
                    that.wrap(' ', ' ').record();
                    return false;
                }
            }
        }
        if ('ArrowLeft' === key && !s) {
            var _that$$2 = that.$(),
                _before = _that$$2.before,
                _start = _that$$2.start,
                _value = _that$$2.value;
            if (!_value) {
                var tagMatch = toPattern(tagTokens + '$', "").exec(_before); // `<foo>|bar`
                if (tagMatch) {
                    that.select(tagMatch.index, _start);
                    return false;
                }
            }
        }
        if ('ArrowRight' === key && !s) {
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
        if ('Enter' === key && !s) {
            var _that$$4 = that.$(),
                _after2 = _that$$4.after,
                _before2 = _that$$4.before,
                _value3 = _that$$4.value,
                lineBefore = _before2.split('\n').pop(),
                lineMatch = lineBefore.match(/^(\s+)/),
                lineMatchIndent = lineMatch && lineMatch[1] || "",
                _tagStartMatch = _before2.match(toPattern(tagStart(tagName) + '$', "")); // `<!--|-->`
            if (!_value3 && /^[ \t]*-->/.test(_after2) && /<!--[ \t]*$/.test(_before2)) {
                that.trim().wrap('\n\n' + lineMatchIndent, '\n\n' + lineMatchIndent).record();
                return false;
            }
            if (!_value3 && _tagStartMatch) {
                if (toPattern('^</' + _tagStartMatch[1] + '>', "").test(_after2)) {
                    that.record().trim().wrap('\n' + lineMatchIndent + charIndent, '\n' + lineMatchIndent).record();
                } else {
                    that.record().wrap('\n' + lineMatchIndent + charIndent, '\n' + lineMatchIndent + '</' + _tagStartMatch[1] + '>').record();
                }
                return false;
            }
        }
        if ('Backspace' === key && !s) {
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
                if ('<?' === _before3.slice(-2)) {
                    that.replace(/<\?$/, "", -1); // `<?|?>`
                    if ('?>' === _after3.slice(0, 2)) {
                        that.replace(/^\?>/, "", 1);
                    }
                    that.record();
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
                        if ('</' + name + '>' === _after3.slice(0, toCount(name) + 3)) {
                            that.replace(toPattern('^</' + name + '>', ""), "", 1);
                        }
                    }
                    that.record();
                    return false;
                }
                if (toPattern(tagStart(tagName) + '\\n(?:' + esc(charIndent) + ')?$', "").test(_before3) && toPattern('^\\s*' + tagEnd(tagName), "").test(_after3)) {
                    that.trim().record(); // Collapse!
                    return false;
                }
            }
        }
        if ('Delete' === key && !s) {
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

    function canMouseDown(that) {
        setTimeout(function() {
            var _that$$7 = that.$(),
                after = _that$$7.after,
                before = _that$$7.before,
                value = _that$$7.value;
            if (!value) {
                var caret = "\uFEFF",
                    tagTokensLocal = tagTokens.split('(' + tagName + ')').join('((?:[\\w:.-]|' + caret + ')+)'),
                    tagTokensLocalPattern = toPattern(tagTokensLocal),
                    content = before + value + caret + after,
                    m,
                    v;
                while (m = tagTokensLocalPattern.exec(content)) {
                    if (-1 !== m[0].indexOf(caret)) {
                        that.select(v = m.index, v + toCount(m[0]) - 1);
                        break;
                    }
                }
            }
        }, 1);
        return true;
    }
    var state = defaults;
    /* Global(s) */
    const _ = {};
    const {
        fire,
        hooks,
        off,
        on
    } = hook(_);
    Object.assign(W, {
        F3H,
        OP,
        TE,
        TP,
        _
    });
    /* Menu(s) */
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

    function onChange_Menu() {
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
    /* Option(s) */
    function onChange_Option() {
        // Destroy!
        let value;
        for (let key in OP.instances) {
            value = OP.instances[key];
            value.pop();
            delete OP.instances[key];
        }
        let sources = getElements('.lot\\:field.type\\:option .select');
        sources && toCount(sources) && sources.forEach(source => {
            let c = getClasses(source);
            let picker = new OP(source, getDatum(source, 'state') ?? {});
            setClasses(picker.self, c);
        });
    }
    /* Query(s) */
    function onChange_Query() {
        // Destroy!
        let value;
        for (let key in TP.instances) {
            value = TP.instances[key];
            value.pop();
            delete TP.instances[key];
        }
        let sources = getElements('.lot\\:field.type\\:query .input');
        sources && toCount(sources) && sources.forEach(source => {
            let c = getClasses(source);
            let picker = new TP(source, getDatum(source, 'state') ?? {});
            setClasses(picker.self, c);
        });
    }
    /* Source(s) */
    Object.assign(TE.prototype, that$1, that);
    Object.assign(TE.state, state$1, state);

    function _onKeyDownSource(e) {
        let editor = this.editor,
            key = e.key,
            keys = {
                a: e.altKey,
                c: e.ctrlKey,
                s: e.shiftKey
            };
        if (canKeyDown(key, keys, editor) && canKeyDown$1(key, keys, editor) && canKeyDownDent(key, keys, editor) && canKeyDownHistory(key, keys, editor));
        else {
            offEventDefault(e);
        }
    }

    function _onMouseDownSource(e) {
        let editor = this.editor;
        canMouseDown(editor);
    }

    function _onKeyUpSource(e) {
        let editor = this.editor,
            key = e.key,
            keys = {
                a: e.altKey,
                c: e.ctrlKey,
                s: e.shiftKey
            };
        canKeyUp(key, keys, editor);
    }

    function _letEditorSource(self) {
        delete self.editor;
        offEvent('keydown', self, _onKeyDownSource);
        offEvent('keyup', self, _onKeyUpSource);
        offEvent('mousedown', self, _onMouseDownSource);
        offEvent('touchstart', self, _onMouseDownSource);
    }

    function _setEditorSource(self, editor) {
        self.editor = editor;
        onEvent('keydown', self, _onKeyDownSource);
        onEvent('keyup', self, _onKeyUpSource);
        onEvent('mousedown', self, _onMouseDownSource);
        onEvent('touchstart', self, _onMouseDownSource);
    }

    function onChange_Source() {
        // Destroy!
        let value;
        for (let key in TE.instances) {
            value = TE.instances[key];
            value.pop();
            _letEditorSource(value.self);
            delete TE.instances[key];
        }
        let sources = getElements('.lot\\:field.type\\:source .textarea');
        sources && toCount(sources) && sources.forEach(source => {
            let editor = new TE(source, getDatum(source, 'state') ?? {});
            _setEditorSource(editor.self, editor);
        });
    }
    /* Tab(s) */
    function onChange_Tab() {
        let sources = getElements('.lot\\:tabs');
        sources && toCount(sources) && sources.forEach(source => {
            let panes = [].slice.call(getChildren(source)),
                input = D.createElement('input'),
                buttons = [].slice.call(getElements('a', panes.shift()));
            input.type = 'hidden';
            input.name = getDatum(source, 'name');
            setChildLast(source, input);

            function onClick(e) {
                let t = this;
                if (!hasClass(getParent(t), 'has:link')) {
                    if (!hasClass(t, 'not:active')) {
                        buttons.forEach(button => {
                            letClass(getParent(button), 'is:current');
                            if (panes[button._tabIndex]) {
                                letClass(panes[button._tabIndex], 'is:current');
                            }
                        });
                        setClass(getParent(t), 'is:current');
                        if (panes[t._tabIndex]) {
                            setClass(panes[t._tabIndex], 'is:current');
                            input.value = getDatum(t, 'name');
                        }
                    }
                    offEventDefault(e);
                }
            }
            buttons.forEach((button, index) => {
                button._tabIndex = index;
                onEvent('click', button, onClick);
            });
            let buttonCurrent = buttons.find((value, key) => 0 !== key && hasClass(getParent(value), 'is:current'));
            if (buttonCurrent) {
                input.value = getDatum(buttonCurrent, 'name');
            }
        });
    }
    /* Fetch(s) */
    // Get default F3H element(s) filter
    let f = F3H.state.is; // Ignore navigation link(s) that has sub-menu(s) in it
    F3H.state.is = (source, ref) => {
        return f(source, ref) && !hasClass(getParent(source), 'has:menu');
    }; // Force response type as `document`
    delete F3H.state.types.CSS;
    delete F3H.state.types.JS;
    delete F3H.state.types.JSON;
    let f3h = null;

    function _setFetchFeature() {
        let title = getElement('title'),
            selectors = 'body>div,body>svg,body>template',
            elements = getElements(selectors);
        f3h = new F3H(false); // Disable cache
        f3h.on('error', () => {
            fire('error');
            theLocation.reload();
        });
        f3h.on('exit', (response, node) => {
            if (title) {
                if (node && 'form' === getName(node)) {
                    setDatum(title, 'is', 'get' === node.name ? 'search' : 'push');
                } else {
                    letDatum(title, 'is');
                }
            }
            fire('let');
        });
        f3h.on('success', (response, node) => {
            let status = f3h.status;
            if (200 === status || 404 === status) {
                let responseElements = getElements(selectors, response),
                    responseRoot = response.documentElement;
                D.title = response.title;
                if (responseRoot) {
                    setAttribute(R, 'class', getAttribute(responseRoot, 'class') + ' can:fetch');
                }
                elements.forEach((element, index) => {
                    if (responseElements[index]) {
                        setAttribute(element, 'class', getAttribute(responseElements[index], 'class'));
                        setHTML(element, getHTML(responseElements[index]));
                    }
                });
                fire('change');
            }
        });
        on('change', onChange_Menu);
        on('change', onChange_Option);
        on('change', onChange_Query);
        on('change', onChange_Source);
        on('change', onChange_Tab);
        on('let', () => {
            if (title) {
                let status = getDatum(title, 'is') || 'pull',
                    value = getDatum(title, 'is-' + status);
                value && (D.title = value);
            }
        });
    }
    hasClass(R, 'can:fetch') && _setFetchFeature();
    onChange_Menu();
    onChange_Option();
    onChange_Query();
    onChange_Source();
    onChange_Tab();
    onEvent('beforeload', D, () => fire('let'));
    onEvent('load', D, () => fire('get'));
    onEvent('DOMContentLoaded', D, () => fire('set'));
})();