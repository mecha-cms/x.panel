GUI Extension for Mecha
=======================

![Panel](https://user-images.githubusercontent.com/1669261/70392346-c92a9a00-1a11-11ea-8c55-8d2a8abe5b08.png)

Release Notes
-------------

### 2.2.0

 - **TODO:** AJAX system that allows other JavaScript extensions to listen to the AJAX loading and loaded events.
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
