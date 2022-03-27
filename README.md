GUI Extension for [Mecha](https://github.com/mecha-cms/mecha)
=============================================================

![Code Size](https://img.shields.io/github/languages/code-size/mecha-cms/x.panel?color=%23444&style=for-the-badge)

![Panel Skin Default Dark](/panel.skin.default/lot/asset/index.png)

![Panel Skin Default Light](/panel.skin.default/lot/asset/index/1.png)

![Panel Skin](/panel/lot/asset/index.png)

To activate the control panel feature, you will need to install the [user](https://github.com/mecha-cms/x.user "User") extension as the generic log-in/out system.

---

Release Notes
-------------

### 3.0.0

 - [ ] Added ability to update core, extension and layout directly from the panel.
 - [ ] Updated for Mecha 3.0.0.
 - [x] Added `chunk`, `deep` and `x` filter for query string in URL.
 - [x] Added ability to store file in different folder from the current folder by adding an `<input name="path" value="foo/bar/baz">` element in the HTML form. This applies only to the `set` task.
 - [x] Changed URL pattern from `http://127.0.0.1/panel/::g::/foo/bar/baz/1` to `http://127.0.0.1/panel/get/foo/bar/baz/1` (#23)
 - [x] Changed `info` key to `status`.
 - [x] Changed `locked` property to `fix` property.
 - [x] Moved AJAX feature to a [separate extension](https://github.com/mecha-cms/x.panel.f3h).
 - [x] Renamed `height` and `width` class to `has:height` and `has:width`.
 - [x] Renamed `is:locked` and `not:locked` class to `is:fix` and `not:fix`.

### 2.8.1

 - Added CSS variables to support theming by third-party extension and layout.
 - Added `card` and `cards` type.
 - Added `column` and `columns` type so we can have sidebars.
 - Added `description` and `title` option for `menu`.
 - Added `gap` option for `tabs`.
 - Added `row` and `rows` type.
 - Added `stack` and `stacks` type.
 - Added `toggle` option for `tab` and `tabs`.
 - Added version update check feature.
 - Improved keyboard accessibility by adding some navigation shortcuts:
   - Press <kbd>ArrowDown</kbd> or <kbd>ArrowRight</kbd> or <kbd>Home</kbd> or <kbd>PageDown</kbd> to focus to the first stack when focusing on the stacks block.
   - Press <kbd>ArrowDown</kbd> or <kbd>ArrowRight</kbd> or <kbd>Home</kbd> or <kbd>PageDown</kbd> to focus to the first tab when focusing on the tabs block.
   - Press <kbd>ArrowDown</kbd> or <kbd>ArrowUp</kbd> to switch between menu links when focusing on the menu link.
   - Press <kbd>ArrowDown</kbd> or <kbd>ArrowUp</kbd> to toggle the tab panel visibility if `toggle` option is enabled. If tab panel is already hidden, this navigation will works as the way <kbd>ArrowLeft</kbd> and <kbd>ArrowRight</kbd> works.
   - Press <kbd>ArrowDown</kbd> or <kbd>Home</kbd> to focus to the first menu link when focusing on the menu block.
   - Press <kbd>ArrowDown</kbd> to show the menu and then focus to the first menu link when focusing on the menu trigger.
   - Press <kbd>ArrowLeft</kbd> or <kbd>ArrowRight</kbd> to switch between links when focusing on the links list.
   - Press <kbd>ArrowLeft</kbd> or <kbd>ArrowRight</kbd> to switch between stacks when focusing on the stack trigger.
   - Press <kbd>ArrowLeft</kbd> or <kbd>ArrowRight</kbd> to switch between tabs when focusing on the tab trigger.
   - Press <kbd>ArrowLeft</kbd> or <kbd>ArrowRight</kbd> to switch between tasks when focusing on the tasks list.
   - Press <kbd>ArrowLeft</kbd> or <kbd>ArrowRight</kbd> to toggle the stack panel visibility if `toggle` option is enabled. If stack panel is already hidden, this navigation will works as the way <kbd>ArrowDown</kbd> and <kbd>ArrowUp</kbd> works.
   - Press <kbd>ArrowLeft</kbd> or <kbd>End</kbd> to focus to the last link when focusing on the links list.
   - Press <kbd>ArrowLeft</kbd> or <kbd>End</kbd> to focus to the last task when focusing on the tasks list.
   - Press <kbd>ArrowLeft</kbd> or <kbd>Escape</kbd> to hide the current menu and then focus to the nearest parent menu link.
   - Press <kbd>ArrowRight</kbd> or <kbd>Home</kbd> to focus to the first link when focusing on the links list.
   - Press <kbd>ArrowRight</kbd> or <kbd>Home</kbd> to focus to the first task when focusing on the tasks list.
   - Press <kbd>ArrowRight</kbd> to show the child menu and then focus to the first child menu link.
   - Press <kbd>ArrowUp</kbd> or <kbd>ArrowLeft</kbd> or <kbd>End</kbd> or <kbd>PageUp</kbd> to focus to the last stack when focusing on the stacks block.
   - Press <kbd>ArrowUp</kbd> or <kbd>ArrowLeft</kbd> or <kbd>End</kbd> or <kbd>PageUp</kbd> to focus to the last tab when focusing on the tabs block.
   - Press <kbd>ArrowUp</kbd> or <kbd>End</kbd> to focus to the last menu link when focusing on the menu block.
   - Press <kbd>Control</kbd><kbd>Alt</kbd><kbd>PageDown</kbd> to go to the next stack when focusing anywhere in the stacks block.
   - Press <kbd>Control</kbd><kbd>Alt</kbd><kbd>PageDown</kbd> to go to the next tab when focusing anywhere in the tabs block.
   - Press <kbd>Control</kbd><kbd>Alt</kbd><kbd>PageUp</kbd> to go to the previous stack when focusing anywhere in the stacks block.
   - Press <kbd>Control</kbd><kbd>Alt</kbd><kbd>PageUp</kbd> to go to the previous tab when focusing anywhere in the tabs block.
   - Press <kbd>Enter</kbd> or <kbd>Space</kbd> to toggle the menu visibility when focusing on the menu trigger.
   - Press <kbd>Enter</kbd> or <kbd>Space</kbd> to toggle the stack panel visibility if `toggle` option is enabled.
   - Press <kbd>Enter</kbd> or <kbd>Space</kbd> to toggle the tab panel visibility if `toggle` option is enabled.
   - Press <kbd>Escape</kbd> to focus to the parent block if possible.
   - Press <kbd>F10</kbd> to focus to the first main navigation item if any.
   - Press <kbd>F3</kbd> to focus to the main search form if any.
 - Moved `_.alert()`, `_.confirm()`, and `_.prompt()` to the `_.dialog` namespace. In the future, users should be able to set custom dialog window via `_.dialog()`.
 - Moved the default skin to a separate extension.

### 2.7.2

 - Added [@taufik-nurrohman/option-picker](https://github.com/taufik-nurrohman/option-picker) for consistent select box style.
 - Added _Data_ tab next to the _Pages_ tab for pages layout.
 - Added `$_['id']` property that stores the first `$_['path']` value.
 - Added `$_['skin']` property that stores the skin title and CSS path.
 - Added `_.alert()`, `_.confirm()`, and `_.prompt()` methods that return promises, as alternative to the native `alert()`, `confirm()`, and `prompt()` functions.
 - Added `data` property to `field/*` type to quickly add hidden fields.
 - Added `data` property to `form/*` type to quickly add hidden fields.
 - Added `field/button` and `field/buttons` type. Currently useless. Would be useful when combined with certain JavaScript actions, such as to open a pop-up window.
 - Added `flex` and `field/flex` type.
 - Added ability to reply comment from the panel interface.
 - Added badge GUI for `link` type via `info` key.
 - Added comments counter in the _Comment_ menu.
 - Added files/folders counter in the _Trash_ menu.
 - Added typography test.
 - Fixed bug of tags data that is not stored consistently on edit mode.
 - Improved `title` type to allow icons in `file`, `folder`, `field/item`, `field/items`, and `field/toggle` type.
 - Improved extension page. It is now possible to filter extensions by searching and/or paginating.
 - Improved performance of files/folders listing by deferring the GUI creation.
 - Renamed `before` and `after` key for field to `value-before` and `value-after`.
 - Renamed `field/combo` type with `field/option`.
 - The `$_['/']` data now will includes the `$url` as prefix.
 - [@mecha-cms/mecha#96](https://github.com/mecha-cms/mecha/issues/96)

### 2.6.0

 - Added GUI tests menu that will be visible only if `DEBUG` mode is enabled by user.
 - Added `$_['icon']` property to store the SVG path icons globally.
 - Added `description`, `icon`, `separator`, `title`, `field/description`, `field/name`, `field/path`, `field/title` type.
 - Added `files/*` and `pages/*` type variants.
 - Added `sort` option key to allow user to sort the `lot` data using custom key reference other than `stack`.
 - Added `width` option key for `desk` type to allow user to set custom desk width.
 - Renamed `$_['chops']` to `$_['chop']`.

### 2.5.1, 2.5.2

 - Bug fixes.

### 2.5.0

 - Added `$_['asset']` property to easily load/unload assets in the control panel (#18)
 - Added `do.task.*` hooks.
 - Added simple Node.js build tool (thanks to [@igoynawamreh](https://github.com/igoynawamreh)). Maybe not for everybody.
 - Added user action limiter API.
 - Changed default layout from _Construction_ to _Dark_. _Construction_ layout will be available as separate extension in the future.
 - Moved `$_['form']` data to the `$_['form']['lot']` context (#20)
 - Moved all GUI function from under namespace `_\lot\x\panel` to `_\lot\x\panel\type` (#21)
 - Narrowed down the panel editor maximum width.
 - Property `tags` now accept associative array.
 - Removed `peek` option globally.
 - Removed user notification feature. This is something that can be created by pushing new alert message to the session easily without the need to store junk files. The right navigation icon is now just a log-out button.
 - Renamed `$_['layout']` with `$_['type']`. The `$_['layout']` property is still there, but it is now will be used as a way to swap current layout with another layout file (#19)
 - Renamed `hidden` key with `skip` for a shorter name, and to prevent me from being too perfect, by adding another key named `visible`, as an alternative for `hidden`.

### 2.4.3

 - Hide search form in the default license page.
 - Improved info tab of extension and layout, to automatically convert example URL in the page content into usable URL.
 - State hook should not replace-recursive but override the current state data, to make sure that all boolean value in it will be removed if some toggle fields are not checked.

### 2.4.2

 - Fixed typos in SCSS file.
 - Renamed panel form name from `search` to `get`, `edit` to `set`.

### 2.4.1

 - Added `invoke` property for every item in pages to be invoked within `_\lot\x\panel\page()` that would returns other properties to be merged to the current properties.
 - Added ability to set custom panel route through static functions under `_\lot\x\panel\route` namespace.
 - Added ability to set custom panel title.
 - Added end-user license agreement page for the panel extension.
 - Finished the restore feature.
 - Fixed bugs of user creation event which didn&rsquo;t store the pass data correctly.
 - Improved `panel.php` file feature to allow developers to extend data to the `$_` variable directly without using `$GLOBALS`.
 - Improved comments page execution time by storing newly created comments into static array.
 - Updated [F3H](https://github.com/taufik-nurrohman/f3h) version to 1.0.15.

### 2.4.0

 - Added ability to set custom panel definition through `.\lot\layout\index\panel.php` file.
 - Changed `#blob:{code}` language string into a more readable language string as the default language string for every blob response code.
 - Fixed bug where creating a new page does not populate the `time` data automatically.
 - Moved `type` and `x` state data to a separated file, stored in `.\lot\x\panel\state` folder.
 - Removed `$lot` hook parameter and store the form data to `$_['form']` property, for easy form data manipulation during CRUD process.
 - Updated [Tag Picker](https://github.com/taufik-nurrohman/tag-picker) to version 3.0.12.

### 2.3.2

 - Bug fixes and improvements for page properties with multi-dimensional array (#16)

### 2.3.1

 - Improved function naming convention. PHP functions should conform to the snake-case naming convention (e.g. `a_b__c\d`).
 - Updated [F3H](https://github.com/taufik-nurrohman/f3h) script to version `1.0.11`.

### 2.3.0

 - Added ability to set custom panel route and GUI through static functions under `_\lot\x\panel\route` namespace.
 - Changed field API to use lower-case for data types and to separate sub-types with `/` instead of `.`. So that every data type will look like a friendly MIME type format, just like the `type` attribute value alternative for every page file. Example: from `Text.DateTime` to `text/date-time`, from `Form.Post` to `form/post`.
 - Moved from `Pjax` to `F3H`.

### 2.2.1

 - Removed leading `/` in `$_['path']` and trailing `/` in `$_['/']` for consistency with `$_['i']` and global `$url` properties.

### 2.2.0

 - Added AJAX system that allows other JavaScript extensions to listen to the AJAX loading and loaded events.
 - Added generic JavaScript hook system for external extensions that can be used to enhance the core control panel features.
 - Fixed bug where users could not save the file content properly due to the automatic string evaluation by the `e` function.

### 2.1.6

 - Added `Blobs` field type as an alternative to `Blob` field type that can accept multiple file uploads.
 - Added ability to convert RGB color string into HEX color string for `Color` and `Colors` field type.
 - The generic file uploading interface now uses `Blobs` field type as the file picker so that you can now upload multiple files there.

### 2.1.5

 - Added [`Set`](https://user-images.githubusercontent.com/1669261/73904817-dcea6380-48cf-11ea-9c66-25a61e2c1b8e.png) field type.
 - Added optional `title` and `description` attribute for `Fields`.
 - Fixed broken recursive folder delete if `trash` parameter exists.

### 2.1.4

 - Fixed [#13](https://github.com/mecha-cms/x.panel/issues/13), somehow.

### 2.1.3

 - **TODO:** Fix this issue: [#13](https://github.com/mecha-cms/x.panel/issues/13)
 - Added [error log notification](https://user-images.githubusercontent.com/1669261/72618638-24836c80-396e-11ea-8705-434506abe2d8.png) in the GUI when debug mode is enabled by the author.
 - Added [notification tab](https://user-images.githubusercontent.com/1669261/72582860-ba8ba880-3916-11ea-90b7-c7c3322e8925.png) in the state editor to give a set of fields for author to store their contact details. By default, you will have an email address field there. Developers may add more contact details there to be used on certain extensions.

### 2.1.2

 - Refactor.