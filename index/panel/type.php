<?php

// Set pre-defined panel type by path type
if (!array_key_exists('type', $_GET) && !isset($_['type'])) {
    if ($_['file']) {
        // `/path/to/file.txt`
        $_['type'] = 'file';
    } else if ($_['folder']) {
        if (!empty($_['part'])) {
            // `/path/to/folder/1`
            $_['type'] = 'files';
        } else {
            // `/path/to/folder`
            $_['type'] = 'folder';
        }
    }
}

// Set
$GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], $_);

foreach (array_reverse(step(strtr($_['type'] ?? 'void', '/', D), D)) as $v) {
    if (is_file($f = __DIR__ . D . 'type' . D . $v . '.php')) {
         (static function ($f) {
            extract($GLOBALS, EXTR_SKIP);
            if ($_ = require $f) {
                $GLOBALS['_'] = array_replace_recursive($GLOBALS['_'], (array) $_);
            }
        })($f);
    } else if (is_callable($f = "\\x\\panel\\type\\" . strtr($v, [
        '-' => '_',
        '.' => '__',
        D => "\\"
    ]))) {
        $GLOBALS['_'] = array_replace_recursive(call_user_func($f, []), $GLOBALS['_']);
    }
}

// Get
$_ = $GLOBALS['_'];

// Run the task(s) to `do.*.*` hook after panel type is set
$tasks = array_reverse(step(trim(strtr($_['task'] ?? 'get', '/', D), D), D));
$type = strtr(strtr($_['type'] ?? P, "\\", '/'), '/', D);
foreach ($tasks as $task) {
    $task = strtr($task, "\\", '/');
    foreach (array_reverse(step(strtr($task, '/', D) . D . $type, D)) as $v) {
        // Function-based task
        if (
            is_callable($f = "\\x\\panel\\task\\" . strtr($v, [
                '-' => '_',
                '.' => '__',
                D => "\\"
            ])) ||
            // If you have to use route(s) with number prefix, you can prefix the function name with a `_`.
            // For route `/panel/fire/123/foo/bar/1`, you can have function named `_123()` instead of `123()`.
            is_callable($f = strtr($f, ["\\task\\fire\\" => "\\task\\fire\\_"]))
        ) {
            Hook::set(strtr('do.' . $type . '.' . $task, D, '/'), $f, 10);
        }
    }
}