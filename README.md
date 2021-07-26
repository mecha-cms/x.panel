GUI Extension for [Mecha](https://github.com/mecha-cms/mecha)
=============================================================

![Panel](https://user-images.githubusercontent.com/1669261/104103529-31af0f00-52d5-11eb-8e08-fe2c4f2d3b4c.png)

Release Notes
-------------

### main

 - [ ] Added `field/options` type.
 - [x] Added [@taufik-nurrohman/option-picker](https://github.com/taufik-nurrohman/option-picker) for consistent select box style.
 - [x] Added _Data_ tab next to the _Pages_ tab for pages layout.
 - [x] Added `$_['id']` property that store the first `$_['path']` value.
 - [x] Added `field/button` and `field/buttons` type. Currently useless. Would be useful when combined with certain JavaScript actions, such as to open a pop-up window.
 - [x] Added `flex` and `field/flex` type.
 - [x] Added `form` property to `form/*` type to quickly add hidden fields.
 - [x] Added `form` property to `form/*` type to quickly add hidden fields.
 - [x] Added badge GUI for `link` type via `info` key.
 - [x] Added comment notification badge in the main menu.
 - [x] Added comments counter in the _Comment_ menu.
 - [x] Added files/folders counter in the _Trash_ menu.
 - [x] Fixed bug of tags data that is not stored consistently on edit mode.
 - [x] Improved extension page. It is now possible to filter extensions by searching and/or paginating.
 - [x] Improved performance of files/folders listing by deferring the GUI creation.
 - [x] Renamed `before` and `after` key for field to `value-before` and `value-after`.
 - [x] Renamed `field/combo` type with `field/option`.
 - [x] The `$_['/']` data now will includes the `$url` as prefix.
 - [x] [@mecha-cms/mecha#96](https://github.com/mecha-cms/mecha/issues/96)

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
