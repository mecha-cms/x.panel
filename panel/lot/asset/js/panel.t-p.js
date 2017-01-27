/*! Based on <http://joshsalverda.github.io/datepickr> */
(function (win, doc) {
  var _className = 'className'
    , _createElement = 'createElement'
    , _format = 'format'
    , _target = 'target'
    , _config = 'config'
    , _value = 'value'
    , _languages = 'languages'
    , _parentNode = 'parentNode'
    , _addEventListener = 'addEventListener'
    , _removeEventListener = 'removeEventListener'
    , _appendChild = 'appendChild'
    , _removeChild = 'removeChild'
    , _replaceChild = 'replaceChild'
    , _innerHTML = 'innerHTML'
    , _cs = 'cs'
    , _cr = 'cr'
    , _cg = 'cg'
    , _classList = 'classList'
    , _ignite = 'ignite'
    , _prototype = 'prototype'
    , _destroy = 'destroy'
    , _nodeName = 'nodeName'
    , _innerHTML = 'innerHTML'
    , _currentDayView = 'currentDayView'
    , _currentMonthView = 'currentMonthView'
    , _currentYearView = 'currentYearView'
    , _selectedDate = 'selectedDate';
  win.TP = function (selector, config) {
    "use strict";
    var elements, createInstance, instances = []
      , i;
    TP[_prototype] = TP[_ignite][_prototype];
    createInstance = function (element) {
      if (element._TP) {
        element._TP[_destroy]();
      }
      element._TP = new TP[_ignite](element, config);
      return element._TP;
    };
    if (selector[_nodeName]) {
      return createInstance(selector);
    }
    elements = TP[_prototype].$(selector);
    if (elements.length === 1) {
      return createInstance(elements[0]);
    }
    for (i = 0; i < elements.length; i++) {
      instances.push(createInstance(elements[i]));
    }
    return instances;
  };
  TP[_ignite] = function (element, instanceConfig) {
    'use strict';
    var self = this
      , defaultConfig = {
        format: 'F j, Y'
        , min: null
        , max: null
        , languages: {
          days: {
            short: ['S', 'M', 'T', 'W', 'T', 'F', 'S']
            , long: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
          }
          , months: {
            short: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            , long: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
          }
          , DIM: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31] // days in month
            
          , FDOW: 0 // first day of week
        }
      }
      , calendarContainer = doc[_createElement]('span')
      , navigationCurrentMonth = doc[_createElement]('span')
      , calendar = doc[_createElement]('table')
      , calendarBody = doc[_createElement]('tbody')
      , wrapperElement, currentDate = new Date()
      , wrap, date, formatDate, monthToStr, isSpecificDay, buildWeekdays, buildDays, updateNavigationCurrentMonth, buildMonthNavigation, handleYearChange, docClick, calendarClick, buildCalendar, getOpenEvent, bind, open, close, destroy, ignite;
    calendarContainer[_className] = 'time-picker';
    instanceConfig = instanceConfig || {};
    wrap = function () {
      wrapperElement = doc[_createElement]('div');
      wrapperElement[_className] = 'time-picker-target';
      self[_target][_parentNode].insertBefore(wrapperElement, self[_target]);
      wrapperElement[_appendChild](self[_target]);
    };
    date = {
      current: {
        year: function () {
          return currentDate.getFullYear();
        }
        , month: {
          i: function () {
            return currentDate.getMonth();
          }
          , s: function (short) {
            var month = currentDate.getMonth();
            return monthToStr(month, short);
          }
        }
        , day: function () {
          return currentDate.getDate();
        }
      }
      , month: {
        s: function () {
          return monthToStr(self[_currentMonthView]);
        }
        , i: function () {
          // checks to see if february is a leap year otherwise return the respective # of days
          return self[_currentMonthView] === 1 && (((self[_currentYearView] % 4 === 0) && (self[_currentYearView] % 100 !== 0)) || (self[_currentYearView] % 400 === 0)) ? 29 : self[_config][_languages].DIM[self[_currentMonthView]];
        }
      }
    };
    formatDate = function (format, milliseconds) {
      var formattedDate = ""
        , dateObj = new Date(milliseconds)
        , formats = {
          d: function () {
            var day = formats.j();
            return (day < 10) ? '0' + day : day;
          }
          , D: function () {
            return self[_config][_languages].days.short[formats.w()];
          }
          , j: function () {
            return dateObj.getDate();
          }
          , l: function () {
            return self[_config][_languages].days.long[formats.w()];
          }
          , w: function () {
            return dateObj.getDay();
          }
          , F: function () {
            return monthToStr(formats.n() - 1, 0);
          }
          , m: function () {
            var month = formats.n();
            return (month < 10) ? '0' + month : month;
          }
          , M: function () {
            return monthToStr(formats.n() - 1, 1);
          }
          , n: function () {
            return dateObj.getMonth() + 1;
          }
          , U: function () {
            return dateObj.getTime() / 1000;
          }
          , y: function () {
            return String(formats.Y()).substring(2);
          }
          , Y: function () {
            return dateObj.getFullYear();
          }
        }
        , formatPieces = format.split("");
      self.each(formatPieces, function (formatPiece, index) {
        if (formats[formatPiece] && formatPieces[index - 1] !== '\\') {
          formattedDate += formats[formatPiece]();
        }
        else {
          if (formatPiece !== '\\') {
            formattedDate += formatPiece;
          }
        }
      });
      return formattedDate;
    };
    monthToStr = function (date, short) {
      if (short) {
        return self[_config][_languages].months.short[date];
      }
      return self[_config][_languages].months.long[date];
    };
    isSpecificDay = function (day, month, year, comparison) {
      return day === comparison && self[_currentMonthView] === month && self[_currentYearView] === year;
    };
    buildWeekdays = function () {
      var weekdayContainer = doc[_createElement]('thead')
        , FDOW = self[_config][_languages].FDOW
        , days = self[_config][_languages].days.short;
      if (FDOW > 0 && FDOW < days.length) {
        days = [].concat(days.splice(FDOW, days.length), days.splice(0, FDOW));
      }
      weekdayContainer[_innerHTML] = '<tr><th>' + days.join('</th><th>') + '</th></tr>';
      calendar[_appendChild](weekdayContainer);
    };
    buildDays = function () {
      var firstOfMonth = new Date(self[_currentYearView], self[_currentMonthView], 1).getDay()
        , numDays = date.month.i()
        , calendarFragment = doc.createDocumentFragment()
        , row = doc[_createElement]('tr')
        , dayCount, dayNumber, today = ""
        , selected = ""
        , disabled = ""
        , currentTimestamp;
      // Offset the first day by the specified amount
      firstOfMonth -= self[_config][_languages].FDOW;
      if (firstOfMonth < 0) {
        firstOfMonth += 7;
      }
      dayCount = firstOfMonth;
      calendarBody[_innerHTML] = "";
      // Add spacer to line up the first day of the month correctly
      for (dayNumber = 0; dayNumber < firstOfMonth; dayNumber++) {
        row[_innerHTML] += '<td>&nbsp;</td>';
      }
      // Start at 1 since there is no 0th day
      for (dayNumber = 1; dayNumber <= numDays; dayNumber++) {
        // if we have reached the end of a week, wrap to the next line
        if (dayCount === 7) {
          calendarFragment[_appendChild](row);
          row = doc[_createElement]('tr');
          dayCount = 0;
        }
        today = isSpecificDay(date.current.day(), date.current.month.i(), date.current.year(), dayNumber) ? ' today' : "";
        if (self[_selectedDate]) {
          selected = isSpecificDay(self[_selectedDate].day, self[_selectedDate].month, self[_selectedDate].year, dayNumber) ? ' active' : "";
        }
        if (self[_config].min || self[_config].max) {
          currentTimestamp = new Date(self[_currentYearView], self[_currentMonthView], dayNumber).getTime();
          disabled = "";
          if (self[_config].min && currentTimestamp < self[_config].min) {
            disabled = ' x';
          }
          if (self[_config].max && currentTimestamp > self[_config].max) {
            disabled = ' x';
          }
        }
        row[_innerHTML] += '<td class="' + today + selected + disabled + ' day">' + dayNumber + '</td>';
        dayCount++;
      }
      // fill in the rest
      for (dayNumber = 0; dayNumber < 7 - dayCount; dayNumber++) {
        row[_innerHTML] += '<td>&nbsp;</td>';
      }
      calendarFragment[_appendChild](row);
      calendarBody[_appendChild](calendarFragment);
    };
    updateNavigationCurrentMonth = function () {
      navigationCurrentMonth[_innerHTML] = date.month.s() + ' ' + self[_currentYearView];
    };
    buildMonthNavigation = function () {
      var months = doc[_createElement]('caption')
        , monthNavigation;
      monthNavigation = '<a href="javascript:;" class="_p"></a>';
      monthNavigation += '<a href="javascript:;" class="_n"></a>';
      months[_innerHTML] = monthNavigation;
      months[_appendChild](navigationCurrentMonth);
      updateNavigationCurrentMonth();
      calendar[_appendChild](months);
    };
    handleYearChange = function () {
      if (self[_currentMonthView] < 0) {
        self[_currentYearView]--;
        self[_currentMonthView] = 11;
      }
      if (self[_currentMonthView] > 11) {
        self[_currentYearView]++;
        self[_currentMonthView] = 0;
      }
    };
    docClick = function (event) {
      var parent;
      if (event.target !== self[_target] && event.target !== wrapperElement) {
        parent = event.target[_parentNode];
        if (parent !== wrapperElement) {
          while (parent !== wrapperElement) {
            parent = parent[_parentNode];
            if (parent === null) {
              close();
              break;
            }
          }
        }
      }
    };
    calendarClick = function (event) {
      var target = event.target
        , targetClass = target[_className]
        , currentTimestamp;
      if (targetClass) {
        if (targetClass === '_p' || targetClass === '_n') {
          if (targetClass === '_p') {
            self[_currentMonthView]--;
          }
          else {
            self[_currentMonthView]++;
          }
          handleYearChange();
          updateNavigationCurrentMonth();
          buildDays();
        }
        else if (target[_nodeName] === 'TD' && !self.cg(target, 'x')) {
          self[_selectedDate] = {
            day: parseInt(target[_innerHTML], 10)
            , month: self[_currentMonthView]
            , year: self[_currentYearView]
          };
          currentTimestamp = new Date(self[_currentYearView], self[_currentMonthView], self[_selectedDate].day).getTime();
          self[_target][_value] = formatDate(self[_config][_format], currentTimestamp);
          close();
          buildDays();
        }
      }
    };
    buildCalendar = function () {
      buildMonthNavigation();
      buildWeekdays();
      buildDays();
      calendar[_appendChild](calendarBody);
      calendarContainer[_appendChild](calendar);
      wrapperElement[_appendChild](calendarContainer);
    };
    getOpenEvent = function () {
      if (self[_target][_nodeName] === 'INPUT') {
        return "focus";
      }
      return "click";
    };
    bind = function () {
      self.on(self[_target], getOpenEvent(), open);
      self.on(calendarContainer, "click", calendarClick);
    };
    open = function () {
      self.on(doc, "click", docClick);
      self[_cs](wrapperElement, 'active');
    };
    close = function () {
      self.off(doc, "click", docClick);
      self[_cr](wrapperElement, 'active');
    };
    destroy = function () {
      var parent, element;
      self.off(doc, "click", docClick);
      self.off(self[_target], getOpenEvent(), open);
      parent = self[_target][_parentNode];
      parent[_removeChild](calendarContainer);
      element = parent[_removeChild](self[_target]);
      parent[_parentNode][_replaceChild](element, parent);
    };
    ignite = function () {
      var config, parsedDate;
      self[_config] = {};
      self[_destroy] = destroy;
      function extend(a, b) {
          a = a || {};
          for (var i in b) {
              if (typeof b[i] === "object") {
                  a[i] = extend(a[i], b[i]);
              } else {
                  a[i] = b[i];
              }
          }
          return a;
      }
      self[_config] = extend(defaultConfig, instanceConfig);
      self[_target] = element;
      if (self[_target][_value]) {
        parsedDate = Date.parse(self[_target][_value]);
      }
      if (parsedDate && !isNaN(parsedDate)) {
        parsedDate = new Date(parsedDate);
        self[_selectedDate] = {
          day: parsedDate.getDate()
          , month: parsedDate.getMonth()
          , year: parsedDate.getFullYear()
        };
        self[_currentYearView] = self[_selectedDate].year;
        self[_currentMonthView] = self[_selectedDate].month;
        self[_currentDayView] = self[_selectedDate].day;
      }
      else {
        self[_selectedDate] = null;
        self[_currentYearView] = date.current.year();
        self[_currentMonthView] = date.current.month.i();
        self[_currentDayView] = date.current.day();
      }
      wrap();
      buildCalendar();
      bind();
    };
    ignite();
    return self;
  };
  TP[_ignite][_prototype] = {
    cg: function (element, className) {
      return element[_classList].contains(className);
    }
    , cs: function (element, className) {
      element[_classList].add(className);
    }
    , cr: function (element, className) {
      element[_classList].remove(className);
    }
    , each: function (items, callback) {
      [].forEach.call(items, callback);
    }
    , $: doc.querySelectorAll.bind(doc)
    , on: function (element, type, listener, useCapture) {
      element[_addEventListener](type, listener, useCapture);
    }
    , off: function (element, type, listener, useCapture) {
      element[_removeEventListener](type, listener, useCapture);
    }
  };
})(window, document);