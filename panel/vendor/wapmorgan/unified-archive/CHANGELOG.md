# Change Log

### 0.1.2 - ***

**BC-breaking changes**:
- PclZip-interface getter renamed to `getPclZipInterface()`.
- Make `addFiles()` return number of **added files** instead of total files number.

Other changes:
- Make `addFiles()` / `deleteFiles()` / `archiveFiles()` throw `\Exception`s when any error occurred (and even when
files list is empty).
- Fixed usage of `/` always as directory separator in `addFiles()` and `archiveFiles()`.

Format-specific changes:
- Divided format-specific code into separate components.
- **zip**: 
    - Excluded directories from files list (`getFileNames()`).
    - Fixed retrieving new list of files after `addFiles()` usage.
    - Fixed invalid "/" archive entry after `archiveFiles()` usage.
- **tar** (`TarArchive` adapter):
    - Fixed number of added files of `addFiles()`.
    - Fixed list of files after `deleteFiles()` usage.
    - Added checks for compressed tar's support in `canOpenArchive()` and `canOpenType()`.
- **tar** (`PharData` adapter):
    - Fixed list of files after `addFiles()`/`deleteFiles()` usage and path generation of archive in `archiveFiles()`.
    - Fixed path of files in `getFileNames()` to use UNIX path separator ("/").
- **iso**:
    - Excluded directories from files list (`getFileNames()`).
- **7zip**:
    - Fixed result of `deleteFiles()` and `archiveFiles()` in-archive paths.
    - Fixed calculation of compressed file size in `getFileData()`.
    - Set infinite timeout of `7z` system call (useful for big archives).

### 0.1.1 - Sep 21, 2018
API changes:
* **Changed algorithm of files list generation in `archiveFiles()` and `addFiles()`**:
    ```php
    // 1. one file
    $archive->archiveFiles('/var/www/site/abc.log', 'archive.zip'); // => stored as 'abc.log'
    // 2. directory
    $archive->archiveFiles('/var/www/site/runtime/logs', 'archive.zip'); // => directory content stored in archive root
    // 3. list
    $archive->archiveFiles([
          '/var/www/site/abc.log' => 'abc.log', // stored as 'abc.log'
          '/var/www/site/abc.log', // stored as '/var/www/site/abc.log'
          '/var/www/site/runtime/logs' => 'logs', // directory content stored in 'logs' dir
          '/var/www/site/runtime/logs', // stored as '/var/www/site/runtime/logs'
    ], 'archive.zip');
    ```
* **Disabled paths expanding in `extractFiles()` and `deleteFiles()` by default**.

    If you need to expand `src/` path to all files within this directory in archive, set second argument `$expandFilesList` argument to `true`.
    ```php
    $archive->extractFiles(__DIR__, 'src/', true);
    $archive->deleteFiles('tests/', true);
    ```

* Added new element in `archiveFiles()` result in emulation mode. Now it returns an archive with 4 elements: new `type` element with archive type.

Fixes:
* Fixed **LZW-stream** (.tar.Z) wrapper (before it didn't work).
* Fixed **ISO** archives reading (before archive size could be calculated wrong).
* Fixed **CAB** archives extraction in `getFileContent($file)` (before it didn't work).
* Improved extraction in `getFileContent($file)` for **RAR** archives by using streams (before it did extract file in temporarily folder, read it and then delete it).

Improvements:
* Added `isFileExists($file): bool` method for checking if archive has a file with specific name.
* Added `getFileResource($file): resource` method for getting a file descriptor for reading all file content without full extraction in memory.
* Added `canOpenArchive($archiveFileName): bool` and `canOpenType($archiveFormat): bool` static methods to check if specific archive or format can be opened.
* Added `detectArchiveType($fileName): string|false` static method to detect (by filename or content) archive type.
* Added `addFile($file, $inArchiveName = null)` / `addDirectory($directory, $inArchivePath = null)` to add one file or one directory, `archiveFile($file, $archiveName)` / `archiveDirectory($directory, $archiveName)` to archive one file or directory.

Miscellaneous:
* Added simple tests.
* Added `phar` distribution.

### 0.1.0 - Apr 11, 2018
API changes:
* Renamed methods `extractNode()` → `extractFiles()`, `archiveNodes()` → `archiveFiles()`. Original method are still available with `@deprecated` status.
* `getFileData()` now returns `ArchiveEntry` instance instead of `stdClass`. Original object fields are still available with `@deprecated` status.
* `addFiles()` and `deleteFiles()` now return false when archive is not editable.

Improvements:
* Added checks of archive opening status in constructor: now an `Exception` will be throwed if archive file is not readable.
* Some changes in `archiveNodes()` about handling directory names.
* Fixed archive rescan in `addFiles()` and `deleteFiles()`.

Miscellaneous:
* Removed example scripts (`examples/`).
* Code changes: added comments.

### 0.0.11 - Mar 21, 2018
* Cleaned up some old code. 
* Added `ext-phar` adapter for tar archives (if `pear/archive_tar` is not installed).

### 0.0.10 - Aug 7, 2017
* Remove `docopt` from requirements. Now it's a suggestion.

### 0.0.9 - Jul 20, 2017
* Added `cam` (Console Archive Manager) script.

### 0.0.8 - Jan 24, 2017
* Added initial support for `CAB` archives without extracting. 
* Added handling of short names of tar archives (.tgz/.tbz2/...). 
* Removed external repository declaration. 
* Removed `die()` in source code.

### 0.0.7 - Jan 14, 2017
* Fixed usage of `ereg` function for PHP >7.

### 0.0.6 - Jan 9, 2017	
* Added functionality for adding files in archive. 
* Added functionality for deleting files from archive. 
* Fixed discovering `7z` archive number of files and creating new archive.

### 0.0.5 - Jan 8, 2017	
* Added support for `7z` (by 7zip-cli) archives.

### 0.0.4 - Jan 7, 2017	
* Added support for single-file `bz2` (bzip2) and `xz` (lzma2) archives.

### 0.0.3 - Aug 18, 2015	
* Removed `archive_tar` from required packages.

### 0.0.2 - May 27, 2014
* Released under the MIT license

### 0.0.1 - May 26, 2014
First version.
