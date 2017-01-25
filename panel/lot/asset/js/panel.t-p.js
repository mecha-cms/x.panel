/*! Based on <http://joshsalverda.github.io/datepickr> */
(function (win, doc) {
  var _className = 'className'
    , _createElement = 'createElement'
    , _element = 'element'
    , _parentNode = 'parentNode'
    , _addEventListener = 'addEventListener'
    , _removeEventListener = 'removeEventListener'
    , _appendChild = 'appendChild'
    , _removeChild = 'removeChild'
    , _replaceChild = 'replaceChild'
    , _innerHTML = 'innerHTML'
    , _addClass = 'addClass'
    , _removeClass = 'removeClass'
    , _hasClass = 'hasClass'
    , _querySelectorAll = 'querySelectorAll';
  win.TP = function (selector, config) {
    'use strict';
    var elements, createInstance, instances = []
      , i;
    TP.prototype = TP.init.prototype;
    createInstance = function (element) {
      if (element._TP) {
        element._TP.destroy();
      }
      element._TP = new TP.init(element, config);
      return element._TP;
    };
    if (selector.nodeName) {
      return createInstance(selector);
    }
    elements = TP.prototype[_querySelectorAll](selector);
    if (elements.length === 1) {
      return createInstance(elements[0]);
    }
    for (i = 0; i < elements.length; i++) {
      instances.push(createInstance(elements[i]));
    }
    return instances;
  };
  TP.init = function (element, instanceConfig) {
    'use strict';
    var self = this
      , defaultConfig = {
        dateFormat: 'F j, Y'
        , altFormat: null
        , altInput: null
        , minDate: null
        , maxDate: null
        , shortCurrentMonth: false
      }
      , calendarContainer = doc[_createElement]('span')
      , navigationCurrentMonth = doc[_createElement]('span')
      , calendar = doc[_createElement]('table')
      , calendarBody = doc[_createElement]('tbody')
      , wrapperElement, currentDate = new Date()
      , wrap, date, formatDate, monthToStr, isSpecificDay, buildWeekdays, buildDays, updateNavigationCurrentMonth, buildMonthNavigation, handleYearChange, docClick, calendarClick, buildCalendar, getOpenEvent, bind, open, close, destroy, init;
    calendarContainer[_className] = 'time-picker-calendar';
    navigationCurrentMonth[_className] = 'time-picker-month';
    instanceConfig = instanceConfig || {};
    wrap = function () {
      wrapperElement = doc[_createElement]('div');
      wrapperElement[_className] = 'time-picker-input';
      self[_element][_parentNode].insertBefore(wrapperElement, self[_element]);
      wrapperElement[_appendChild](self[_element]);
    };
    date = {
      current: {
        year: function () {
          return currentDate.getFullYear();
        }
        , month: {
          integer: function () {
            return currentDate.getMonth();
          }
          , string: function (short) {
            var month = currentDate.getMonth();
            return monthToStr(month, short);
          }
        }
        , day: function () {
          return currentDate.getDate();
        }
      }
      , month: {
        string: function () {
          return monthToStr(self.currentMonthView, self.config.shortCurrentMonth);
        }
        , numDays: function () {
          // checks to see if february is a leap year otherwise return the respective # of days
          return self.currentMonthView === 1 && (((self.currentYearView % 4 === 0) && (self.currentYearView % 100 !== 0)) || (self.currentYearView % 400 === 0)) ? 29 : self.languages.daysInMonth[self.currentMonthView];
        }
      }
    };
    formatDate = function (dateFormat, milliseconds) {
      var formattedDate = ""
        , dateObj = new Date(milliseconds)
        , formats = {
          d: function () {
            var day = formats.j();
            return (day < 10) ? '0' + day : day;
          }
          , D: function () {
            return self.languages.days.short[formats.w()];
          }
          , j: function () {
            return dateObj.getDate();
          }
          , l: function () {
            return self.languages.days.long[formats.w()];
          }
          , w: function () {
            return dateObj.getDay();
          }
          , F: function () {
            return monthToStr(formats.n() - 1, false);
          }
          , m: function () {
            var month = formats.n();
            return (month < 10) ? '0' + month : month;
          }
          , M: function () {
            return monthToStr(formats.n() - 1, true);
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
        , formatPieces = dateFormat.split("");
      self.forEach(formatPieces, function (formatPiece, index) {
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
      if (short === true) {
        return self.languages.months.short[date];
      }
      return self.languages.months.long[date];
    };
    isSpecificDay = function (day, month, year, comparison) {
      return day === comparison && self.currentMonthView === month && self.currentYearView === year;
    };
    buildWeekdays = function () {
      var weekdayContainer = doc[_createElement]('thead')
        , firstDayOfWeek = self.languages.firstDayOfWeek
        , days = self.languages.days.short;
      if (firstDayOfWeek > 0 && firstDayOfWeek < days.length) {
        days = [].concat(days.splice(firstDayOfWeek, days.length), days.splice(0, firstDayOfWeek));
      }
      weekdayContainer.innerHTML = '<tr><th>' + days.join('</th><th>') + '</th></tr>';
      calendar[_appendChild](weekdayContainer);
    };
    buildDays = function () {
      var firstOfMonth = new Date(self.currentYearView, self.currentMonthView, 1).getDay()
        , numDays = date.month.numDays()
        , calendarFragment = doc.createDocumentFragment()
        , row = doc[_createElement]('tr')
        , dayCount, dayNumber, today = ""
        , selected = ""
        , disabled = ""
        , currentTimestamp;
      // Offset the first day by the specified amount
      firstOfMonth -= self.languages.firstDayOfWeek;
      if (firstOfMonth < 0) {
        firstOfMonth += 7;
      }
      dayCount = firstOfMonth;
      calendarBody.innerHTML = "";
      // Add spacer to line up the first day of the month correctly
      if (firstOfMonth > 0) {
        row.innerHTML += '<td colspan="' + firstOfMonth + '">&nbsp;</td>';
      }
      // Start at 1 since there is no 0th day
      for (dayNumber = 1; dayNumber <= numDays; dayNumber++) {
        // if we have reached the end of a week, wrap to the next line
        if (dayCount === 7) {
          calendarFragment[_appendChild](row);
          row = doc[_createElement]('tr');
          dayCount = 0;
        }
        today = isSpecificDay(date.current.day(), date.current.month.integer(), date.current.year(), dayNumber) ? ' today' : "";
        if (self.selectedDate) {
          selected = isSpecificDay(self.selectedDate.day, self.selectedDate.month, self.selectedDate.year, dayNumber) ? ' active' : "";
        }
        if (self.config.minDate || self.config.maxDate) {
          currentTimestamp = new Date(self.currentYearView, self.currentMonthView, dayNumber).getTime();
          disabled = "";
          if (self.config.minDate && currentTimestamp < self.config.minDate) {
            disabled = ' x';
          }
          if (self.config.maxDate && currentTimestamp > self.config.maxDate) {
            disabled = ' x';
          }
        }
        row.innerHTML += '<td class="' + today + selected + disabled + '"><span class="time-picker-day">' + dayNumber + '</span></td>';
        dayCount++;
      }
      calendarFragment[_appendChild](row);
      calendarBody[_appendChild](calendarFragment);
    };
    updateNavigationCurrentMonth = function () {
      navigationCurrentMonth.innerHTML = date.month.string() + ' ' + self.currentYearView;
    };
    buildMonthNavigation = function () {
      var months = doc[_createElement]('caption')
        , monthNavigation;
      monthNavigation = '<a href="javascript:;" class="time-picker-previous"></a>';
      monthNavigation += '<a href="javascript:;" class="time-picker-next"></a>';
      months[_className] = 'time-picker-caption';
      months.innerHTML = monthNavigation;
      months[_appendChild](navigationCurrentMonth);
      updateNavigationCurrentMonth();
      calendar[_appendChild](months);
    };
    handleYearChange = function () {
      if (self.currentMonthView < 0) {
        self.currentYearView--;
        self.currentMonthView = 11;
      }
      if (self.currentMonthView > 11) {
        self.currentYearView++;
        self.currentMonthView = 0;
      }
    };
    docClick = function (event) {
      var parent;
      if (event.target !== self[_element] && event.target !== wrapperElement) {
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
        if (targetClass === 'time-picker-previous' || targetClass === 'time-picker-next') {
          if (targetClass === 'time-picker-previous') {
            self.currentMonthView--;
          }
          else {
            self.currentMonthView++;
          }
          handleYearChange();
          updateNavigationCurrentMonth();
          buildDays();
        }
        else if (targetClass === 'time-picker-day' && !self.hasClass(target[_parentNode], 'x')) {
          self.selectedDate = {
            day: parseInt(target.innerHTML, 10)
            , month: self.currentMonthView
            , year: self.currentYearView
          };
          currentTimestamp = new Date(self.currentYearView, self.currentMonthView, self.selectedDate.day).getTime();
          if (self.config.altInput) {
            if (self.config.altFormat) {
              self.config.altInput.value = formatDate(self.config.altFormat, currentTimestamp);
            }
            else {
              // I don't know why someone would want to do this... but just in case?
              self.config.altInput.value = formatDate(self.config.dateFormat, currentTimestamp);
            }
          }
          self[_element].value = formatDate(self.config.dateFormat, currentTimestamp);
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
      if (self[_element].nodeName === 'INPUT') {
        return 'focus';
      }
      return 'click';
    };
    bind = function () {
      self[_addEventListener](self[_element], getOpenEvent(), open);
      self[_addEventListener](calendarContainer, 'click', calendarClick);
    };
    open = function () {
      self[_addEventListener](doc, 'click', docClick);
      self[_addClass](wrapperElement, 'active');
    };
    close = function () {
      self[_removeEventListener](doc, 'click', docClick);
      self[_removeClass](wrapperElement, 'active');
    };
    destroy = function () {
      var parent, element;
      self[_removeEventListener](doc, 'click', docClick);
      self[_removeEventListener](self[_element], getOpenEvent(), open);
      parent = self[_element][_parentNode];
      parent[_removeChild](calendarContainer);
      element = parent[_removeChild](self[_element]);
      parent[_parentNode][_replaceChild](element, parent);
    };
    init = function () {
      var config, parsedDate;
      self.config = {};
      self.destroy = destroy;
      for (config in defaultConfig) {
        self.config[config] = instanceConfig[config] || defaultConfig[config];
      }
      self[_element] = element;
      if (self[_element].value) {
        parsedDate = Date.parse(self[_element].value);
      }
      if (parsedDate && !isNaN(parsedDate)) {
        parsedDate = new Date(parsedDate);
        self.selectedDate = {
          day: parsedDate.getDate()
          , month: parsedDate.getMonth()
          , year: parsedDate.getFullYear()
        };
        self.currentYearView = self.selectedDate.year;
        self.currentMonthView = self.selectedDate.month;
        self.currentDayView = self.selectedDate.day;
      }
      else {
        self.selectedDate = null;
        self.currentYearView = date.current.year();
        self.currentMonthView = date.current.month.integer();
        self.currentDayView = date.current.day();
      }
      wrap();
      buildCalendar();
      bind();
    };
    init();
    return self;
  };
  TP.init.prototype = {
    hasClass: function (element, className) {
      return element.classList.contains(className);
    }
    , addClass: function (element, className) {
      element.classList.add(className);
    }
    , removeClass: function (element, className) {
      element.classList.remove(className);
    }
    , forEach: function (items, callback) {
      [].forEach.call(items, callback);
    }
    , querySelectorAll: doc[_querySelectorAll].bind(doc)
    , isArray: Array.isArray
    , addEventListener: function (element, type, listener, useCapture) {
      element[_addEventListener](type, listener, useCapture);
    }
    , removeEventListener: function (element, type, listener, useCapture) {
      element[_removeEventListener](type, listener, useCapture);
    }
    , languages: {
      days: {
        short: ['S', 'M', 'T', 'W', 'T', 'F', 'S']
        , long: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
      }
      , months: {
        short: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        , long: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
      }
      , daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
      , firstDayOfWeek: 0
    }
  };
})(window, document);