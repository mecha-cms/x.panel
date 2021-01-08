(function () {
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

  var isInstance = function isInstance(x, of) {
    return x && isSet(of) && x instanceof of;
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

  var fromValue = function fromValue(x) {
    if (isArray(x)) {
      return x.map(function (v) {
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

  var toCaseLower = function toCaseLower(x) {
    return x.toLowerCase();
  };

  var toNumber = function toNumber(x, base) {
    if (base === void 0) {
      base = 10;
    }

    return parseInt(x, base);
  };

  var toValue = function toValue(x) {
    if (isArray(x)) {
      return x.map(function (v) {
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

    return {
      'false': false,
      'null': null,
      'true': true
    }[x] || x;
  };

  var D = document;
  var W = window;
  var B = D.body;
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
      return classes.forEach(function (name) {
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

  var letElement = function letElement(node) {
    var parent = getParent(node);
    return node.remove(), parent;
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
      return classes.forEach(function (name) {
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

  var theHistory = W.history;
  var theLocation = W.location;
  var theScript = D.currentScript;

  var eventPreventDefault = function eventPreventDefault(e) {
    return e && e.preventDefault();
  };

  var off = function off(name, node, then) {
    node.removeEventListener(name, then);
  };

  var on = function on(name, node, then, options) {
    if (options === void 0) {
      options = false;
    }

    node.addEventListener(name, then, options);
  };

  var fromStates = function fromStates() {
    for (var _len = arguments.length, lot = new Array(_len), _key = 0; _key < _len; _key++) {
      lot[_key] = arguments[_key];
    }

    return Object.assign.apply(Object, [{}].concat(lot));
  };

  function fire(name, data) {
    var $ = this;

    if (!isSet(hooks[name])) {
      return $;
    }

    hooks[name].forEach(function (then) {
      return then.apply($, data);
    });
    return $;
  }

  var hooks = {};

  function off$1(name, then) {
    var $ = this;

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

  function on$1(name, then) {
    var $ = this;

    if (!isSet(hooks[name])) {
      hooks[name] = [];
    }

    if (isSet(then)) {
      hooks[name].push(then);
    }

    return $;
  }

  var esc = function esc(pattern, extra) {
    if (extra === void 0) {
      extra = "";
    }

    return pattern.replace(toPattern('[' + extra + x.replace(/./g, '\\$&') + ']'), '\\$&');
  };

  var fromPattern = function fromPattern(pattern) {
    if (isPattern(pattern)) {
      // Un-escape `/` in the pattern string
      return pattern.source.replace(/\\\//g, '/');
    }

    return null;
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

  var setScroll = function setScroll(node, data) {
    node.scrollLeft = data[0];
    node.scrollTop = data[1];
    return node;
  };

  var toArrayKey = function toArrayKey(x, data) {
    var i = data.indexOf(x);
    return -1 !== i ? i : null;
  };

  var toCaseLower$1 = function toCaseLower(x) {
    return x.toLowerCase();
  };

  var toCaseUpper = function toCaseUpper(x) {
    return x.toUpperCase();
  };

  var toCount = function toCount(x) {
    return x.length;
  };

  var toNumber$1 = function toNumber(x, base) {
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

  var toValue$1 = function toValue(x) {
    if (isArray(x)) {
      return x.map(function (v) {
        return toValue(v);
      });
    }

    if (isNumeric(x)) {
      return toNumber$1(x);
    }

    if (isObject(x)) {
      for (var k in x) {
        x[k] = toValue(x[k]);
      }

      return x;
    }

    return {
      'false': false,
      'null': null,
      'true': true
    }[x] || x;
  };
  /*!
   *
   * The MIT License (MIT)
   *
   * Copyright © 2020 Taufik Nurrohman
   *
   * <https://github.com/taufik-nurrohman/f3h>
   *
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the “Software”), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   *
   * The above copyright notice and this permission notice shall be included in all
   * copies or substantial portions of the Software.
   *
   * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
   * SOFTWARE.
   *
   */


  var name = 'F3H',
      GET = 'GET',
      POST = 'POST',
      responseTypeHTML = 'document',
      responseTypeJSON = 'json',
      responseTypeTXT = 'text',
      home = '//' + theLocation.hostname,
      B$1,
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
        link,
        links = getElements('link[rel=dns-prefetch],link[rel=preconnect],link[rel=prefetch],link[rel=preload],link[rel=prerender]', scope),
        toSave;

    for (var i = 0, j = toCount(links); i < j; ++i) {
      if (isLinkForF3H(link = links[i])) {
        continue;
      }

      link.id = id = link.id || name + ':' + toID(getAttribute(link, 'href') || getText(link));
      out[id] = toSave = fromElement(link);
      out[id][toCount(toSave) - 1].href = link.href; // Use the resolved URL!
    }

    return out;
  }

  function getRef() {
    return letSlashEnd(theLocation.href);
  }

  function getScripts(scope) {
    var id,
        out = {},
        script,
        scripts = getElements('script', scope),
        toSave;

    for (var i = 0, j = toCount(scripts); i < j; ++i) {
      if (isScriptForF3H(script = scripts[i])) {
        continue;
      }

      script.id = id = script.id || name + ':' + toID(getAttribute(script, 'src') || getText(script));
      out[id] = toSave = fromElement(script);
      out[id][toCount(toSave) - 1].src = script.src; // Use the resolved URL!
    }

    return out;
  }

  function getStyles(scope) {
    var id,
        out = {},
        style,
        styles = getElements('link[rel=stylesheet],style', scope),
        toSave;

    for (var i = 0, j = toCount(styles); i < j; ++i) {
      if (isStyleForF3H(style = styles[i])) {
        continue;
      }

      style.id = id = style.id || name + ':' + toID(getAttribute(style, 'href') || getText(style));
      out[id] = toSave = fromElement(style);

      if ('link' === toSave[0]) {
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
    var n = toCaseLower$1(name); // Exclude `<link rel="*">` tag that contains `data-f3h` or `f3h` attribute

    if (hasAttribute(node, 'data-' + n) || hasAttribute(node, n)) {
      return 1;
    }

    return 0;
  }

  function isScriptForF3H(node) {
    // Exclude this very JavaScript
    if (node.src && theScript.src === node.src) {
      return 1;
    }

    var n = toCaseLower$1(name); // Exclude JavaScript tag that contains `data-f3h` or `f3h` attribute

    if (hasAttribute(node, 'data-' + n) || hasAttribute(node, n)) {
      return 1;
    } // Exclude JavaScript that contains `F3H` instantiation


    if (toPattern('\\b' + name + '\\b').test(getText(node) || "")) {
      return 1;
    }

    return 0;
  }

  function isStyleForF3H(node) {
    var n = toCaseLower$1(name); // Exclude CSS tag that contains `data-f3h` or `f3h` attribute

    if (hasAttribute(node, 'data-' + n) || hasAttribute(node, n)) {
      return 1;
    }

    return 0;
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
      k = toCaseLower$1(h.shift());
      out[k] = toValue$1(h.join(': '));
    } // Use proxy to make case-insensitive response header’s key


    return new Proxy(out, {
      get: function get(o, k) {
        return o[toCaseLower$1(k)] || null;
      },
      set: function set(o, k, v) {
        o[toCaseLower$1(k)] = v;
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


    if (source[name]) {
      return $;
    }

    $.state = state = fromStates(F3H.state, true === state ? {
      cache: state
    } : state || {});
    $.source = source;
    var fire$1 = fire.bind($),
        off$2 = off$1.bind($),
        on$2 = on$1.bind($);

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
        styles = null; // Store current instance to `F3H.instances`

    F3H.instances[source.id || source.name || toObjectCount(F3H.instances)] = $; // Mark current DOM as active to prevent duplicate instance

    source[name] = 1;

    function getSources(sources, root) {
      ref = getRef();
      var froms = getElements(sources, root);

      if (isFunction(state.is)) {
        var to = [];
        froms.forEach(function (from) {
          state.is.call($, from, ref) && to.push(from);
        });
        return to;
      }

      return froms;
    } // Include submit button value to the form data ;)


    function doAppendCurrentButtonValue(node) {
      var buttonValueStorage = setElement('input', {
        type: 'hidden'
      }),
          buttons = getElements('[name][type=submit][value]', node);
      setChildLast(node, buttonValueStorage);
      buttons.forEach(function (button) {
        on('click', button, function () {
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
      fire$1('exit', [D, node]); // Get response from cache if any

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
          fire$1('success', data);
          fire$1(cache[0], data);
          sources = getSources(state.sources); // Update JavaScript after markup change

          cache[3] && (scripts = doUpdateScripts(data[0]));
          onSourcesEventsSet(data);
          fire$1('enter', data);
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

      on('abort', request, function () {
        dataSet(), fire$1('abort', [request.response, node]);
      });
      on('error', request, fn = function fn() {
        dataSet();
        requestIsDocument && !nodeIsWindow && useHistory && doScrollTo(R);
        data = [request.response, node]; // Update `<link rel="*">` data for the next page

        requestIsDocument && (links = doUpdateLinks(data[0])); // Update CSS before markup change

        requestIsDocument && (styles = doUpdateStyles(data[0]));
        fire$1('error', data);
        sources = getSources(state.sources); // Update JavaScript after markup change

        requestIsDocument && (scripts = doUpdateScripts(data[0]));
        onSourcesEventsSet(data);
        fire$1('enter', data);
      });
      on('error', requestAsPush, fn);
      on('load', request, fn = function fn() {
        dataSet();
        data = [request.response, node];
        redirect = request.responseURL; // Handle internal server-side redirection
        // <https://en.wikipedia.org/wiki/URL_redirection#HTTP_status_codes_3xx>

        if (status >= 300 && status < 400) {
          // Redirection should delete a cache related to the response URL
          // This is useful for case(s) like, when you have submitted a
          // comment form and then you will be redirected to the same URL
          var r = letSlashEnd(redirect);
          caches[r] && delete caches[r]; // Trigger hook(s) immediately

          fire$1('success', data);
          fire$1(status, data); // Do the normal fetch

          doFetch(nodeCurrent = W, GET, redirect || ref);
          return;
        } // Just to be sure. Don’t worry, this wouldn’t make a duplicate history
        // if (GET === type) {


        doChangeRef(-1 === ref.indexOf('#') ? redirect || ref : ref); // }
        // Update CSS before markup change

        requestIsDocument && (styles = doUpdateStyles(data[0]));
        fire$1('success', data);
        fire$1(status, data);
        requestIsDocument && useHistory && doScrollTo(R);
        sources = getSources(state.sources); // Update JavaScript after markup change

        requestIsDocument && (scripts = doUpdateScripts(data[0]));
        onSourcesEventsSet(data);
        fire$1('enter', data);
      });
      on('load', requestAsPush, fn);
      on('progress', request, function (e) {
        dataSet(), fire$1('pull', e.lengthComputable ? [e.loaded, e.total] : [0, -1]);
      });
      on('progress', requestAsPush, function (e) {
        dataSet(), fire$1('push', e.lengthComputable ? [e.loaded, e.total] : [0, -1]);
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
        fire$1('focus', data);
        return;
      }

      var target = getElement('[autofocus]', source);
      target && target.focus();
    } // Pre-fetch page and store it into cache


    function doPreFetch(node, ref) {
      var request = doFetchBase(node, GET, ref);
      on('load', request, function () {
        if (200 === (status = request.status)) {
          caches[letSlashEnd(letHash(ref))] = [status, request.response, toHeadersAsProxy(request), responseTypeHTML === request.responseType];
        }
      });
    }

    function doPreFetchElement(node) {
      on('mousemove', node, onHoverOnce);
    }

    function doScrollTo(node) {
      if (!node) {
        return;
      }

      var theOffset = getOffset(node);
      setScroll(B$1, theOffset);
      setScroll(R, theOffset);
    } // Scroll to the first element with `id` or `name` attribute that has the same value as location hash


    function doScrollToElement(data) {
      if (hooks.scroll) {
        fire$1('scroll', data);
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
          letElement(getTarget(id));
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
      return doUpdate(compare, scripts, getScripts, B$1);
    }

    function doUpdateStyles(compare) {
      return doUpdate(compare, styles, getStyles, H);
    }

    function onDocumentReady() {
      // Detect key down/up event
      on('keydown', D, onKeyDown);
      on('keyup', D, onKeyUp); // Set body and head variable value once, on document ready

      B$1 = D.body;
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
      eventPreventDefault(e);
    }

    function onHashChange(e) {
      doScrollTo(getTarget(getHash(getRef()), 1));
      eventPreventDefault(e);
    } // Pre-fetch URL on link hover


    function onHoverOnce() {
      var t = this,
          href = t.href;

      if (!caches[letSlashEnd(letHash(href))]) {
        doPreFetch(t, href);
      }

      off('mousemove', t, onHoverOnce);
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
      sources.forEach(function (source) {
        on(getEventName(source), source, onFetch);
      });
    }

    function onSourcesEventsSet(data) {
      var turbo = state.turbo;
      sources.forEach(function (source) {
        on(getEventName(source), source, onFetch);

        if (isForm(source)) {
          doAppendCurrentButtonValue(source);
        } else {
          turbo && doPreFetchElement(source);
        }
      });
      doFocusToElement(data);
      doScrollToElement(data);
    }

    $.abort = function (request) {
      if (!request) {
        doFetchAbortAll();
      } else if (requests[request]) {
        doFetchAbort(request);
      }

      return $;
    };

    $.caches = caches;

    $.fetch = function (ref, type, from) {
      return doFetchBase(from, type, ref);
    };

    $.fire = fire$1;
    $.hooks = hooks;
    $.links = links;
    $.lot = null;
    $.off = off$2;
    $.on = on$2;
    $.ref = null;
    $.scripts = scripts;
    $.state = state;
    $.styles = styles;
    $.status = null;

    $.pop = function () {
      if (!source[name]) {
        return $; // Already ejected!
      }

      delete source[name];
      onSourcesEventsLet();
      off('DOMContentLoaded', W, onDocumentReady);
      off('hashchange', W, onHashChange);
      off('keydown', D, onKeyDown);
      off('keyup', D, onKeyUp);
      off('popstate', W, onPopState);
      fire$1('pop', [D, W]);
      return $.abort();
    };

    on('DOMContentLoaded', W, onDocumentReady);
    on('hashchange', W, onHashChange);
    on('popstate', W, onPopState);
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
      raw = getAttribute(source, 'href') || getAttribute(source, 'action') || "",
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
      'x-requested-with': name
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
  F3H.version = '1.1.12';

  var hasValue = function hasValue(x, data) {
    return -1 !== data.indexOf(x);
  };
  /*!
   *
   * The MIT License (MIT)
   *
   * Copyright © 2020 Taufik Nurrohman
   *
   * <https://github.com/taufik-nurrohman/tag-picker>
   *
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the “Software”), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   *
   * The above copyright notice and this permission notice shall be included in all
   * copies or substantial portions of the Software.
   *
   * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
   * SOFTWARE.
   *
   */


  var delay = W.setTimeout,
      name$1 = 'TP';
  var KEY_ARROW_LEFT = ['ArrowLeft', 37];
  var KEY_ARROW_RIGHT = ['ArrowRight', 39];
  var KEY_DELETE_LEFT = ['Backspace', 8];
  var KEY_DELETE_RIGHT = ['Delete', 46];
  var KEY_ENTER = ['Enter', 13];
  var KEY_TAB = ['Tab', 9];

  function TP(source, state) {
    if (state === void 0) {
      state = {};
    }

    if (!source) return;
    var $ = this; // Already instantiated, skip!

    if (source[name$1]) {
      return $;
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

    $.state = state = fromStates(TP.state, isString(state) ? {
      join: state
    } : state || {});
    $.source = source;
    var fire$1 = fire.bind($),
        off$2 = off$1.bind($),
        on$2 = on$1.bind($); // Store current instance to `TP.instances`

    TP.instances[source.id || source.name || toObjectCount(TP.instances)] = $; // Mark current DOM as active tag picker to prevent duplicate instance

    source[name$1] = 1;
    var editor = setElement('span', {
      'class': 'editor tag'
    }),
        editorInput = setElement('span', {
      'contenteditable': sourceIsDisabled() ? false : 'true',
      'spellcheck': 'false',
      'style': 'white-space:pre;'
    }),
        editorInputPlaceholder = setElement('span'),
        form = getParentForm(source),
        // Capture the closest `<form>` element
    self = setElement('span', {
      'class': state['class']
    }),
        tags = setElement('span', {
      'class': 'tags'
    });

    function n(text) {
      return $.f(text).replace(toPattern('(' + state.escape.join('|').replace(/\\/g, '\\\\') + ')+'), "").trim();
    }

    function onInput() {
      if (sourceIsDisabled() || sourceIsReadOnly()) {
        return setInput("");
      }

      var tag = n(getText(editorInput)),
          tags = $.tags,
          index;

      if (tag) {
        if (!getTag(tag)) {
          setTagElement(tag), setTag(tag);
          index = toCount(tags);
          fire$1('change', [tag, index]);
          fire$1('set.tag', [tag, index]);
        } else {
          fire$1('has.tag', [tag, toArrayKey(tag, tags)]);
        }

        setInput("");
      }
    }

    function onBlurInput() {
      onInput();
      letClasses(self, ['focus', 'focus.input']);
      fire$1('blur', [$.tags, toCount($.tags)]);
    }

    function onClickInput() {
      fire$1('click', [$.tags]);
    }

    function onFocusInput() {
      setClass(self, 'focus');
      setClass(self, 'focus.input');
      fire$1('focus', [$.tags]);
    }

    function onKeyDownInput(e) {
      var escape = state.escape,
          key = e.key,
          // Modern browser(s)
      keyCode = e.keyCode,
          // Legacy browser(s)
      keyIsCtrl = e.ctrlKey,
          keyIsEnter = KEY_ENTER[0] === key || KEY_ENTER[1] === keyCode,
          keyIsShift = e.shiftKey,
          keyIsTab = KEY_TAB[0] === key || KEY_TAB[1] === keyCode,
          tag,
          theTagLast = getPrev(editor),
          theTagsCount = toCount($.tags),
          theTagsMax = state.max,
          theValueLast = n(getText(editorInput)); // Last value before delay
      // Set preferred key name

      if (keyIsEnter) {
        key = '\n';
      } else if (keyIsTab) {
        key = '\t';
      } // Skip `Tab` key


      if (keyIsTab) ;else if (sourceIsDisabled() || sourceIsReadOnly()) {
        // Submit the closest `<form>` element with `Enter` key
        if (keyIsEnter && sourceIsReadOnly()) {
          doSubmitTry();
        }

        eventPreventDefault(e);
      } else if (hasValue(key, escape) || hasValue(keyCode, escape)) {
        if (theTagsCount < theTagsMax) {
          // Add the tag name found in the tag editor
          onInput();
        } else {
          setInput("");
          fire$1('max.tags', [theTagsMax]);
        }

        eventPreventDefault(e); // Submit the closest `<form>` element with `Enter` key
      } else if (keyIsEnter) {
        doSubmitTry(), eventPreventDefault(e);
      } else {
        delay(function () {
          var text = getText(editorInput) || "",
              value = n(text); // Last try for buggy key detection on mobile device(s)
          // Check for the last typed key in the tag editor

          if (hasValue(text.slice(-1), escape)) {
            if (theTagsCount < theTagsMax) {
              // Add the tag name found in the tag editor
              onInput();
            } else {
              setInput("");
              fire$1('max.tags', [theTagsMax]);
            }

            eventPreventDefault(e); // Escape character only, delete!
          } else if ("" === value && !keyIsCtrl && !keyIsShift) {
            if ("" === theValueLast && (KEY_DELETE_LEFT[0] === key || KEY_DELETE_LEFT[0] === keyCode)) {
              letClass(self, 'focus.tag');
              tag = $.tags[theTagsCount - 1];
              letTagElement(tag), letTag(tag);

              if (theTagLast) {
                fire$1('change', [tag, theTagsCount - 1]);
                fire$1('let.tag', [tag, theTagsCount - 1]);
              }
            } else if (KEY_ARROW_LEFT[0] === key || KEY_ARROW_LEFT[1] === keyCode) {
              // Focus to the last tag
              theTagLast && theTagLast.focus();
            }
          }

          setText(editorInputPlaceholder, value ? "" : thePlaceholder);
        }, 0);
      }
    }

    function setTags(values) {
      // Remove …
      if (hasParent(self)) {
        var prev;

        while (prev = getPrev(editor)) {
          letTagElement(prev.title);
        }
      }

      $.tags = [];
      source.value = ""; // … then add tag(s)

      values = values ? values.split(state.join) : [];

      for (var i = 0, theTagsMax = state.max, value; i < theTagsMax; ++i) {
        if (!values[i]) {
          break;
        }

        if ("" !== (value = n(values[i]))) {
          if (getTag(value)) {
            continue;
          }

          setTagElement(value), setTag(value);
          fire$1('change', [value, i]);
          fire$1('set.tag', [value, i]);
        }
      }
    }

    function onSubmitForm(e) {
      if (sourceIsDisabled()) {
        return;
      }

      var theTagsMin = state.min;
      onInput(); // Force to add the tag name found in the tag editor

      if (theTagsMin > 0 && toCount($.tags) < theTagsMin) {
        setInput("", 1);
        fire$1('min.tags', [theTagsMin]);
        eventPreventDefault(e);
        return;
      } // Do normal `submit` event


      return 1;
    }

    function onPasteInput() {
      delay(function () {
        if (!sourceIsDisabled() && !sourceIsReadOnly()) {
          setTags(getText(editorInput));
        }

        setInput("");
      }, 0);
    }

    function onClickSelf(e) {
      if (e && self === e.target) {
        editorInput.focus(), onClickInput();
      }
    }

    function onFocusSource() {
      editorInput.focus();
    }

    function onBlurTag() {
      var t = this,
          tag = t.title,
          tags = $.tags;
      letClasses(self, ['focus', 'focus.tag']);
      fire$1('blur.tag', [tag, toArrayKey(tag, tags)]);
    }

    function onClickTag() {
      var t = this,
          tag = t.title,
          tags = $.tags;
      fire$1('click.tag', [tag, toArrayKey(tag, tags)]);
    }

    function onFocusTag() {
      var t = this,
          tag = t.title,
          tags = $.tags;
      setClasses(self, ['focus', 'focus.tag']);
      fire$1('focus.tag', [tag, toArrayKey(tag, tags)]);
    }

    function onClickTagX(e) {
      if (!sourceIsDisabled() && !sourceIsReadOnly()) {
        var t = this,
            tag = getParent(t).title,
            _tags = $.tags,
            index = toArrayKey(tag, _tags);
        letTagElement(tag), letTag(tag), setInput("", 1);
        fire$1('change', [tag, index]);
        fire$1('click.tag', [tag, index]);
        fire$1('let.tag', [tag, index]);
      }

      eventPreventDefault(e);
    }

    function onKeyDownTag(e) {
      var key = e.key,
          // Modern browser(s)
      keyCode = e.keyCode,
          // Legacy browser(s)
      keyIsCtrl = e.ctrlKey,
          keyIsShift = e.shiftKey,
          t = this,
          theTagNext = getNext(t),
          theTagPrev = getPrev(t);

      if (!keyIsCtrl && !keyIsShift) {
        // Focus to the previous tag
        if (!sourceIsReadOnly() && (KEY_ARROW_LEFT[0] === key || KEY_ARROW_LEFT[1] === keyCode)) {
          theTagPrev && (theTagPrev.focus(), eventPreventDefault(e)); // Focus to the next tag or to the tag input
        } else if (!sourceIsReadOnly() && (KEY_ARROW_RIGHT[0] === key || KEY_ARROW_RIGHT[1] === keyCode)) {
          theTagNext && theTagNext !== editor ? theTagNext.focus() : setInput("", 1);
          eventPreventDefault(e); // Remove tag with `Backspace` or `Delete` key
        } else if (KEY_DELETE_LEFT[0] === key || KEY_DELETE_LEFT[1] === keyCode || KEY_DELETE_RIGHT[0] === key || KEY_DELETE_RIGHT[1] === keyCode) {
          if (!sourceIsReadOnly()) {
            var tag = t.title,
                _tags2 = $.tags,
                index = toArrayKey(tag, _tags2);
            letClass(self, 'focus.tag');
            letTagElement(tag), letTag(tag); // Focus to the previous tag or to the tag input after remove

            if (KEY_DELETE_LEFT[0] === key || KEY_DELETE_LEFT[1] === keyCode) {
              theTagPrev ? theTagPrev.focus() : setInput("", 1); // Focus to the next tag or to the tag input after remove
            } else
              /* if (
              KEY_DELETE_RIGHT[0] === key ||
              KEY_DELETE_RIGHT[1] === keyCode
              ) */
              {
                theTagNext && theTagNext !== editor ? theTagNext.focus() : setInput("", 1);
              }

            fire$1('change', [tag, index]);
            fire$1('let.tag', [tag, index]);
          }

          eventPreventDefault(e);
        }
      }
    }

    function setInput(value, fireFocus) {
      setText(editorInput, value);
      setText(editorInputPlaceholder, value ? "" : thePlaceholder);
      fireFocus && editorInput.focus();
    }

    setInput("");

    function getTag(tag, fireHooks) {
      var tags = $.tags,
          index = toArrayKey(tag, tags);
      fireHooks && fire$1('get.tag', [tag, index]);
      return isNumber(index) ? tag : null;
    }

    function letTag(tag) {
      var tags = $.tags,
          index = toArrayKey(tag, tags);

      if (isNumber(index) && index >= 0) {
        source.value = tags.join(state.join);
        return $.tags.splice(index, 1), true;
      }

      return false;
    }

    function setTag(tag, index) {
      if (isNumber(index)) {
        index = index < 0 ? 0 : index;
        $.tags.splice(index, 0, tag);
      } else {
        $.tags.push(tag);
      } // Update value


      source.value = $.tags.join(state.join);
    }

    function setTagElement(tag, index) {
      var element = setElement('span', {
        'class': 'tag',
        'tabindex': sourceIsDisabled() ? false : '0',
        'title': tag
      });

      if (state.x) {
        var x = setElement('a', {
          'href': "",
          'tabindex': '-1',
          'target': '_top'
        });
        on('click', x, onClickTagX);
        setChildLast(element, x);
      }

      on('blur', element, onBlurTag);
      on('click', element, onClickTag);
      on('focus', element, onFocusTag);
      on('keydown', element, onKeyDownTag);

      if (hasParent(tags)) {
        if (isNumber(index) && $.tags[index]) {
          setPrev(getChildren(tags, index), element);
        } else {
          setPrev(editor, element);
        }
      }
    }

    function letTagElement(tag) {
      var index = toArrayKey(tag, $.tags),
          element;

      if (isNumber(index) && index >= 0 && (element = getChildren(tags, index))) {
        off('blur', element, onBlurTag);
        off('click', element, onClickTag);
        off('focus', element, onFocusTag);
        off('keydown', element, onKeyDownTag);

        if (state.x) {
          var x = getChildFirst(element);

          if (x) {
            off('click', x, onClickTagX);
            letElement(x);
          }
        }

        letElement(element);
      }
    }

    function doSubmitTry() {
      onSubmitForm() && form && form.submit();
    }

    setChildLast(self, tags);
    setChildLast(tags, editor);
    setChildLast(editor, editorInput);
    setChildLast(editor, editorInputPlaceholder);
    setClass(source, state['class'] + '-source');
    setNext(source, self);
    setElement(source, {
      'tabindex': '-1'
    });
    on('blur', editorInput, onBlurInput);
    on('click', editorInput, onClickInput);
    on('click', self, onClickSelf);
    on('focus', editorInput, onFocusInput);
    on('focus', source, onFocusSource);
    on('keydown', editorInput, onKeyDownInput);
    on('paste', editorInput, onPasteInput);
    form && on('submit', form, onSubmitForm);
    $.blur = function () {
      return !sourceIsDisabled() && (editorInput.blur(), onBlurInput());
    }, $;
    $.click = function () {
      return self.click();
    }, onClickSelf(), $; // Default filter for the tag name

    $.f = function (text) {
      return toCaseLower$1(text || "").replace(/[^ a-z\d-]/g, "");
    };

    $.fire = fire$1;

    $.focus = function () {
      if (!sourceIsDisabled()) {
        editorInput.focus();
        onFocusInput();
      }

      return $;
    };

    $.get = function (tag) {
      return sourceIsDisabled() ? null : getTag(tag, 1);
    };

    $.hooks = hooks;
    $.input = editorInput;

    $.let = function (tag) {
      if (!sourceIsDisabled() && !sourceIsReadOnly()) {
        var theTagsMin = state.min;
        onInput();

        if (theTagsMin > 0 && toCount($.tags) < theTagsMin) {
          fire$1('min.tags', [theTagsMin]);
          return $;
        }

        letTagElement(tag), letTag(tag);
      }

      return $;
    };

    $.off = off$2;
    $.on = on$2;

    $.pop = function () {
      if (!source[name$1]) {
        return $; // Already ejected!
      }

      delete source[name$1];
      var tags = $.tags;
      letClass(source, state['class'] + '-source');
      off('blur', editorInput, onBlurInput);
      off('click', editorInput, onClickInput);
      off('click', self, onClickSelf);
      off('focus', editorInput, onFocusInput);
      off('focus', source, onFocusSource);
      off('keydown', editorInput, onKeyDownInput);
      off('paste', editorInput, onPasteInput);
      form && off('submit', form, onSubmitForm);
      tags.forEach(letTagElement);
      setElement(source, {
        'tabindex': theTabIndex
      });
      return letElement(self), fire$1('pop', [tags]);
    };

    $.self = self;

    $.set = function (tag, index) {
      if (!sourceIsDisabled() && !sourceIsReadOnly()) {
        var _tags3 = $.tags,
            theTagsMax = state.max;

        if (!getTag(tag)) {
          if (toCount(_tags3) < theTagsMax) {
            setTagElement(tag, index), setTag(tag, index);
          } else {
            fire$1('max.tags', [theTagsMax]);
          }
        } else {
          fire$1('has.tag', [tag, toArrayKey(tag, _tags3)]);
        }
      }

      return $;
    };

    $.source = $.output = source;
    $.state = state;
    $.tags = [];
    setTags(source.value); // Fill value(s)

    $.value = function (values) {
      return !sourceIsDisabled() && !sourceIsReadOnly() && setTags(values);
    }, $;
    return $;
  }

  TP.instances = {};
  TP.state = {
    'class': 'tag-picker',
    'escape': [',', 188],
    'join': ', ',
    'max': 9999,
    'min': 0,
    'x': false
  };
  TP.version = '3.1.8';
  /*!
   *
   * The MIT License (MIT)
   *
   * Copyright © 2020 Taufik Nurrohman
   *
   * <https://github.com/taufik-nurrohman/text-editor>
   *
   * Permission is hereby granted, free of charge, to any person obtaining a copy
   * of this software and associated documentation files (the “Software”), to deal
   * in the Software without restriction, including without limitation the rights
   * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
   * copies of the Software, and to permit persons to whom the Software is
   * furnished to do so, subject to the following conditions:
   *
   * The above copyright notice and this permission notice shall be included in all
   * copies or substantial portions of the Software.
   *
   * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
   * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
   * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
   * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
   * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
   * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
   * SOFTWARE.
   *
   */

  var name$2 = 'TE';

  function trim(str, dir) {
    return (str || "")['trim' + (-1 === dir ? 'Left' : 1 === dir ? 'Right' : "")]();
  }

  function TE(source, state) {
    if (state === void 0) {
      state = {};
    }

    if (!source) return;
    var $ = this; // Already instantiated, skip!

    if (source[name$2]) {
      return $;
    } // Return new instance if `F3H` was called without the `new` operator


    if (!isInstance($, TE)) {
      return new TE(source, state);
    }

    $.state = state = fromStates(TE.state, isString(state) ? {
      tab: state
    } : state || {}); // The `<textarea>` element

    $.self = $.source = source; // Store current instance to `TE.instances`

    TE.instances[source.id || source.name || toObjectCount(TE.instances)] = $; // Mark current DOM as active tag picker to prevent duplicate instance

    source[name$2] = 1;

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

    $.get = function () {
      return !sourceIsDisabled() && trim(sourceValue()) || null;
    }; // Reset to the initial value


    $.let = function () {
      return source.value = $.value;
    }, $; // Set value

    $.set = function (value) {
      if (sourceIsDisabled() || sourceIsReadOnly()) {
        return $;
      }

      return source.value = value, $;
    }; // Get selection


    $.$ = function () {
      return new TE.S(source.selectionStart, source.selectionEnd, sourceValue());
    };

    $.focus = function (mode) {
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


    $.blur = function () {
      return source.blur(), $;
    }; // Select value


    $.select = function () {
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

      x = W.pageXOffset || R.scrollLeft || B.scrollLeft;
      y = W.pageYOffset || R.scrollTop || B.scrollTop;
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


    $.match = function (pattern, then) {
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


    $.replace = function (from, to, mode) {
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


    $.insert = function (value, mode, clear) {
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


    $.wrap = function (open, close, wrap) {
      var _$$$4 = $.$(),
          after = _$$$4.after,
          before = _$$$4.before,
          value = _$$$4.value;

      if (wrap) {
        return $.replace(any, open + '$1' + close);
      }

      return $.set(before + open + value + close + after).select(before = toCount(before + open), before + toCount(value));
    }; // Unwrap current selection


    $.peel = function (open, close, wrap) {
      var _$$$5 = $.$(),
          after = _$$$5.after,
          before = _$$$5.before,
          value = _$$$5.value;

      open = fromPattern(open) || esc(open);
      close = fromPattern(close) || esc(close); // Ignore begin and end marker

      open = open.replace(/^\^|\$$/g, "");
      close = close.replace(/^\^|\$$/, "");
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

    $.pull = function (by, includeEmptyLines) {
      if (includeEmptyLines === void 0) {
        includeEmptyLines = true;
      }

      var _$$$6 = $.$(),
          length = _$$$6.length,
          value = _$$$6.value;

      by = isSet(by) ? by : state.tab;
      by = fromPattern(by) || esc(by); // Ignore begin marker

      by = by.replace(/^\^/, "");

      if (length) {
        if (includeEmptyLines) {
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

    $.push = function (by, includeEmptyLines) {
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

    $.trim = function (open, close, start, end, tidy) {
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


    $.pop = function () {
      if (!source[name$2]) {
        return $; // Already ejected!
      }

      return delete source[name$2], $;
    }; // Return the text editor state


    $.state = state;
    return $;
  }

  TE.esc = esc;
  TE.instances = {};
  TE.state = {
    'tab': '\t'
  };

  TE.S = function (a, b, c) {
    var t = this,
        d = c.slice(a, b);
    t.start = a;
    t.end = b;
    t.value = d;
    t.before = c.slice(0, a);
    t.after = c.slice(b);
    t.length = toCount(d);

    t.toString = function () {
      return d;
    };
  };

  TE.version = '3.2.2';
  TE.x = x;
  W.F3H = F3H;
  W.TP = TP;
  W.TE = TE;
})();
