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
    , _classList = 'classList'
    , _destroy = 'destroy'
    , _nodeName = 'nodeName'
    , _innerHTML = 'innerHTML'
    , _currentDayView = 'currentDayView'
    , _currentMonthView = 'currentMonthView'
    , _currentYearView = 'currentYearView'
    , _selectedDate = 'selectedDate'
    , _day = 'day'
    , _days = 'days'
    , _month = 'month'
    , _months = 'months'
    , _year = 'year'
    , _current = 'current'
    , _getFullYear = 'getFullYear'
    , _getTime = 'getTime'
    , _getDay = 'getDay'
    , _getMonth = 'getMonth'
    , _getDate = 'getDate'
    , _target = 'target'
    , _long = 'long'
    , _short = 'short';

  function cg(element, className) {
    return element[_classList].contains(className);
  }

  function cs(element, className) {
    element[_classList].add(className);
  }

  function cr(element, className) {
    element[_classList].remove(className);
  }

  function each(items, callback) {
    [].forEach.call(items, callback);
  }

  function on(element, type, listener, useCapture) {
    element[_addEventListener](type, listener, useCapture);
  }

  function off(element, type, listener, useCapture) {
    element[_removeEventListener](type, listener, useCapture);
  }
  win.TP = function (element, instanceConfig) {
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
          return currentDate[_getFullYear]();
        }
        , month: {
          i: function () {
            return currentDate[_getMonth]();
          }
          , s: function (short) {
            var month = currentDate[_getMonth]();
            return monthToStr(month, short);
          }
        }
        , day: function () {
          return currentDate[_getDate]();
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
            return self[_config][_languages][_days][_short][formats.w()];
          }
          , j: function () {
            return dateObj[_getDate]();
          }
          , l: function () {
            return self[_config][_languages][_days][_long][formats.w()];
          }
          , w: function () {
            return dateObj[_getDay]();
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
            return dateObj[_getMonth]() + 1;
          }
          , U: function () {
            return dateObj[_getTime]() / 1000;
          }
          , y: function () {
            return String(formats.Y()).substring(2);
          }
          , Y: function () {
            return dateObj[_getFullYear]();
          }
        }
        , formatPieces = format.split("");
      each(formatPieces, function (formatPiece, index) {
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
        return self[_config][_languages][_months][_short][date];
      }
      return self[_config][_languages][_months][_long][date];
    };
    isSpecificDay = function (day, month, year, comparison) {
      return day === comparison && self[_currentMonthView] === month && self[_currentYearView] === year;
    };
    buildWeekdays = function () {
      var weekdayContainer = doc[_createElement]('thead')
        , FDOW = self[_config][_languages].FDOW
        , days = self[_config][_languages][_days][_short];
      if (FDOW > 0 && FDOW < days.length) {
        days = [].concat(days.splice(FDOW, days.length), days.splice(0, FDOW));
      }
      weekdayContainer[_innerHTML] = '<tr><th>' + days.join('</th><th>') + '</th></tr>';
      calendar[_appendChild](weekdayContainer);
    };
    buildDays = function () {
      var firstOfMonth = new Date(self[_currentYearView], self[_currentMonthView], 1)[_getDay]()
        , numDays = date[_month].i()
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
        today = isSpecificDay(date[_current][_day](), date[_current][_month].i(), date[_current][_year](), dayNumber) ? ' today' : "";
        if (self[_selectedDate]) {
          selected = isSpecificDay(self[_selectedDate][_day], self[_selectedDate][_month], self[_selectedDate][_year], dayNumber) ? ' active' : "";
        }
        if (self[_config].min || self[_config].max) {
          currentTimestamp = new Date(self[_currentYearView], self[_currentMonthView], dayNumber)[_getTime]();
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
      navigationCurrentMonth[_innerHTML] = date[_month].s() + ' ' + self[_currentYearView];
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
      if (event[_target] !== self[_target] && event[_target] !== wrapperElement) {
        parent = event[_target][_parentNode];
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
      var target = event[_target]
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
        else if (target[_nodeName] === 'TD' && !cg(target, 'x')) {
          self[_selectedDate] = {
            day: parseInt(target[_innerHTML], 10)
            , month: self[_currentMonthView]
            , year: self[_currentYearView]
          };
          currentTimestamp = new Date(self[_currentYearView], self[_currentMonthView], self[_selectedDate][_day])[_getTime]();
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
      on(self[_target], getOpenEvent(), open);
      on(calendarContainer, "click", calendarClick);
    };
    open = function () {
      on(doc, "click", docClick);
      cs(wrapperElement, 'active');
      var h = doc.documentElement,
          o = 'offset';
      if (wrapperElement[o + 'Top'] + wrapperElement[o + 'Height'] + calendarContainer[o + 'Height'] > h[o + 'Height']) {
        cs(wrapperElement, 'top');
      } else {
        cr(wrapperElement, 'top');
      }
      if (wrapperElement[o + 'Left'] + wrapperElement[o + 'Width'] + calendarContainer[o + 'Width'] > h[o + 'Width']) {
        cs(wrapperElement, 'left');
      } else {
        cr(wrapperElement, 'left');
      }
    };
    close = function () {
      off(doc, "click", docClick);
      cr(wrapperElement, 'active');
    };
    destroy = function () {
      var parent, element;
      off(doc, "click", docClick);
      off(self[_target], getOpenEvent(), open);
      parent = self[_target][_parentNode];
      parent[_removeChild](calendarContainer);
      element = parent[_removeChild](self[_target]);
      parent[_parentNode][_replaceChild](element, parent);
    };
    var parsedDate, extend;
    extend = function (a, b) {
      a = a || {};
      for (var i in b) {
        if (typeof b[i] === "object") {
          a[i] = extend(a[i], b[i]);
        }
        else {
          a[i] = b[i];
        }
      }
      return a;
    };
    self[_config] = extend(defaultConfig, instanceConfig);
    self[_target] = element;
    self[_destroy] = destroy;
    if (self[_target][_value]) {
      parsedDate = Date.parse(self[_target][_value]);
    }
    if (parsedDate && !isNaN(parsedDate)) {
      parsedDate = new Date(parsedDate);
      self[_selectedDate] = {
        day: parsedDate[_getDate]()
        , month: parsedDate[_getMonth]()
        , year: parsedDate[_getFullYear]()
      };
      self[_currentYearView] = self[_selectedDate][_year];
      self[_currentMonthView] = self[_selectedDate][_month];
      self[_currentDayView] = self[_selectedDate][_day];
    }
    else {
      self[_selectedDate] = null;
      self[_currentYearView] = date[_current][_year]();
      self[_currentMonthView] = date[_current][_month].i();
      self[_currentDayView] = date[_current][_day]();
    }
    wrap();
    buildCalendar();
    bind();
    return self;
  };
})(window, document);