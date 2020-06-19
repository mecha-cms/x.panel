GUI Extension for Mecha
=======================

![Panel](https://user-images.githubusercontent.com/1669261/70392346-c92a9a00-1a11-11ea-8c55-8d2a8abe5b08.png)

Release Notes
-------------

### 2.4.x

 - Removed `$lot` hook parameter and store the form data to `$_['form']` property, for easy form data manipulation during CRUD process.
 - Fixed bug where creating a new page does not populate the `time` data automatically.

### 2.3.2

 - Bug fixes and improvements for page properties with multi-dimensional array (#16)

### 2.3.1

 - Improved function naming convention. PHP functions should conform to the snake-case naming convention (e.g. `a_b__c\d`).
 - Updated [F3H](https://github.com/taufik-nurrohman/f3h) script to version `1.0.11`.

### 2.3.0

 - Moved from `Pjax` to `F3H`.
 - Changed field API to use lower-case for data types and to separate sub-types with `/` instead of `.`. So that every data type will look like a friendly MIME type format, just like the `type` attribute value alternative for every page file. Example: from `Text.DateTime` to `text/date-time`, from `Form.Post` to `form/post`.
 - Added ability to set custom panel route and GUI through static functions under `_\lot\x\panel\route` namespace.

### 2.2.1

 - Removed leading `/` in `$_['path']` and trailing `/` in `$_['/']` for consistency with `$_['i']` and global `$url` properties.

### 2.2.0

 - Added generic JavaScript hook system for external extensions that can be used to enhance the core control panel features.
 - Added AJAX system that allows other JavaScript extensions to listen to the AJAX loading and loaded events.
 - Fixed bug where users could not save the file content properly due to the automatic string evaluation by the `e` function.

### 2.1.6

 - Added `Blobs` field type as an alternative to `Blob` field type that can accept multiple file uploads.
 - Added ability to convert RGB color string into HEX color string for `Color` and `Colors` field type.
 - The generic file uploading interface now uses `Blobs` field type as the file picker so that you can now upload multiple files there.

### 2.1.5

 - Fixed broken recursive folder delete if `trash` parameter exists.
 - Added optional `title` and `description` attribute for `Fields`.
 - Added [`Set`](https://user-images.githubusercontent.com/1669261/73904817-dcea6380-48cf-11ea-9c66-25a61e2c1b8e.png) field type.

### 2.1.4

 - Fixed [#13](https://github.com/mecha-cms/x.panel/issues/13), somehow.

### 2.1.3

 - Added [notification tab](https://user-images.githubusercontent.com/1669261/72582860-ba8ba880-3916-11ea-90b7-c7c3322e8925.png) in the state editor to give a set of fields for author to store their contact details. By default, you will have an email address field there. Developers may add more contact details there to be used on certain extensions.
 - Added [error log notification](https://user-images.githubusercontent.com/1669261/72618638-24836c80-396e-11ea-8705-434506abe2d8.png) in the GUI when debug mode is enabled by the author.
 - **TODO:** Fix this issue: [#13](https://github.com/mecha-cms/x.panel/issues/13)

### 2.1.2

 - Refactor.
