var _ = function () {
  'use strict';

  var isArray = function isArray(x) {
    return Array.isArray(x);
  };

  var isDefined = function isDefined(x) {
    return 'undefined' !== typeof x;
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

  var fromJSON = function fromJSON(x) {
    var value = null;

    try {
      value = JSON.parse(x);
    } catch (e) {}

    return value;
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

  var toJSON = function toJSON(x) {
    return JSON.stringify(x);
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
  var R = D.documentElement;

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

  var getState = function getState(node, state) {
    return hasState(node, state) && node[state] || null;
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

  var letDatum = function letDatum(node, datum) {
    return letAttribute(node, 'data-' + datum);
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

  var setDatum = function setDatum(node, datum, value) {
    if (isArray(value) || isObject(value)) {
      value = toJSON(value);
    }

    return setAttribute(node, 'data-' + datum, value);
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

  var toggleClass = function toggleClass(node, name) {
    return node.classList.toggle(name), node;
  };

  var theHistory = W.history;
  var theLocation = W.location;

  var eventPreventDefault = function eventPreventDefault(e) {
    return e && e.preventDefault();
  };

  var eventStopPropagation = function eventStopPropagation(e) {
    return e && e.stopPropagation();
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

  function context($) {
    var hooks = {};

    function fire(name, data) {
      if (!isSet(hooks[name])) {
        return $;
      }

      hooks[name].forEach(function (then) {
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

  var $ = context({});
  var fire = $.fire;
  var off$1 = $.off;
  var on$1 = $.on;
  var hooks = $.hooks;

  var toCount = function toCount(x) {
    return x.length;
  };

  function hook() {
    for (var key in TP.instances) {
      TP.instances[key].pop(); // Destroy!

      delete TP.instances[key];
    }

    var sources = getElements('.field\\:query .input');
    toCount(sources) && sources.forEach(function (source) {
      var _getDatum;

      var c = getClasses(source);
      var picker = new TP(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
      setClasses(picker.self, c);
    });
  }

  function hook$1() {
    for (var key in TE.instances) {
      TE.instances[key].pop(); // Destroy!

      delete TE.instances[key];
    }

    var sources = getElements('.field\\:source .textarea');
    toCount(sources) && sources.forEach(function (source) {
      var _getDatum;

      var editor = new TE(source, (_getDatum = getDatum(source, 'state')) != null ? _getDatum : {});
    });
  }

  function doHideMenus(but) {
    getElements('.lot\\:menu.is\\:enter').forEach(function (node) {
      if (but !== node) {
        letClass(node, 'is:enter');
        letClass(getParent(node), 'is:active');
        letClass(getPrev(node), 'is:active');
      }
    });
  }

  function onClickHideMenus() {
    doHideMenus(0);
  }

  function onClickShowMenu(e) {
    var t = this,
        current = getNext(t);
    doHideMenus(current);
    W.setTimeout(function () {
      toggleClass(t, 'is:active');
      toggleClass(getParent(t), 'is:active');
      toggleClass(current, 'is:enter');
    }, 1);
    eventPreventDefault(e);
    eventStopPropagation(e);
  }

  function hook$2() {
    off('click', D, onClickHideMenus);
    var menuParents = getElements('.has\\:menu');

    if (toCount(menuParents)) {
      menuParents.forEach(function (menuParent) {
        var menu = getElement('.lot\\:menu', menuParent),
            a = getPrev(menu);

        if (menu && a) {
          on('click', a, onClickShowMenu);
        }
      });
      on('click', D, onClickHideMenus);
    }
  }

  function hook$3() {
    var sources = getElements('.lot\\:tabs'),
        hasReplaceState = ('replaceState' in theHistory),
        doSetFormAction = function doSetFormAction(node) {
      var href = node.href,
          form = getParentForm(node);
      form && (form.action = href);
    };

    if (toCount(sources)) {
      sources.forEach(function (source) {
        var panes = [].slice.call(getChildren(source)),
            buttons = getElements('a', panes.shift());

        function onClickShowTab(e) {
          var t = this;

          if (!hasClass(getParent(t), 'has:link')) {
            if (!hasClass(t, 'not:active')) {
              buttons.forEach(function (button) {
                letClass(getParent(button), 'is:current');

                if (panes[button._tabIndex]) {
                  letClass(panes[button._tabIndex], 'is:current');
                }
              });
              setClass(getParent(t), 'is:current');

              if (panes[t._tabIndex]) {
                setClass(panes[t._tabIndex], 'is:current');
              }

              hasReplaceState && theHistory.replaceState({}, "", t.href);
              doSetFormAction(t);
            }

            eventPreventDefault(e);
          }
        }

        buttons.forEach(function (button, index) {
          button._tabIndex = index;
          on('click', button, onClickShowTab);
        });
      });
    }
  }

  var f = F3H.state.is; // Ignore navigation link(s) that has sub-menu(s) in it

  F3H.state.is = function (source, ref) {
    return f(source, ref) && !hasClass(getParent(source), 'has:menu');
  }; // Force response type as `document`


  delete F3H.state.types.CSS;
  delete F3H.state.types.JS;
  delete F3H.state.types.JSON;
  var f3h = null;

  var _contextHook = context({}),
      fire$1 = _contextHook.fire,
      hooks$1 = _contextHook.hooks,
      off$2 = _contextHook.off,
      on$2 = _contextHook.on;

  if (hasClass(R, 'can:fetch')) {
    var title = getElement('title'),
        selectors = 'body>div,body>svg,body>template',
        elements = getElements(selectors);
    f3h = new F3H(false); // Disable cache

    f3h.on('error', function () {
      fire$1('error');
      theLocation.reload();
    });
    f3h.on('exit', function (response, node) {
      if (title) {
        if (node && 'form' === getName(node)) {
          setDatum(title, 'is', 'get' === node.name ? 'search' : 'push');
        } else {
          letDatum(title, 'is');
        }
      }

      fire$1('let');
    });
    f3h.on('success', function (response, node) {
      var status = f3h.status;

      if (200 === status || 404 === status) {
        var responseElements = getElements(selectors, response),
            responseRoot = response.documentElement;
        D.title = response.title;

        if (responseRoot) {
          setAttribute(R, 'class', getAttribute(responseRoot, 'class') + ' can:fetch');
        }

        elements.forEach(function (element, index) {
          if (responseElements[index]) {
            setAttribute(element, 'class', getAttribute(responseElements[index], 'class'));
            setHTML(element, getHTML(responseElements[index]));
          }
        });
        fire$1('change');
      }
    });
    on$2('change', hook$2);
    on$2('change', hook);
    on$2('change', hook$1);
    on$2('change', hook$3);
    on$2('let', function () {
      if (title) {
        var status = getDatum(title, 'is') || 'pull',
            value = getDatum(title, 'is-' + status);
        value && (D.title = value);
      }
    });
  }

  on('beforeload', D, function () {
    return fire$1('let');
  });
  on('load', D, function () {
    return fire$1('get');
  });
  on('DOMContentLoaded', D, function () {
    return fire$1('set');
  });
  hook$2();
  hook();
  hook$1();
  hook$3();
  var index = {
    fire: fire$1,
    hooks: hooks$1,
    off: off$2,
    on: on$2
  };
  return index;
}();
