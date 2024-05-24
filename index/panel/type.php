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
            // This prevents user from editing the base folder name
            $path = $_['path'] ?? "";
            $task = $_['task'] ?? "";
            if (false === strpos($path, '/') && 'get' === $task) {
                $_['lot']['desk']['lot']['alert']['icon'] = 'M12 2C17.5 2 22 6.5 22 12S17.5 22 12 22 2 17.5 2 12 6.5 2 12 2M12 4C10.1 4 8.4 4.6 7.1 5.7L18.3 16.9C19.3 15.5 20 13.8 20 12C20 7.6 16.4 4 12 4M16.9 18.3L5.7 7.1C4.6 8.4 4 10.1 4 12C4 16.4 7.6 20 12 20C13.9 20 15.6 19.4 16.9 18.3Z';
                $_['type'] = 'void';
            } else {
                $_['type'] = 'folder';
            }
        }
    }
}

// Set
lot('_', array_replace_recursive(lot('_'), $_));

foreach (array_reverse(step(strtr($_['type'] ?? 'void', '/', D), D)) as $v) {
    if (is_file($f = __DIR__ . D . 'type' . D . $v . '.php')) {
         (static function ($f) {
            extract(lot(), EXTR_SKIP);
            if ($_ = require $f) {
                lot('_', array_replace_recursive(lot('_'), (array) $_));
            }
        })($f);
    } else if (is_callable($f = "\\x\\panel\\type\\" . strtr($v, [
        '-' => '_',
        '.' => '__',
        D => "\\"
    ]))) {
        lot('_', array_replace_recursive(call_user_func($f, []), lot('_')));
    }
}

// Get
$_ = lot('_');

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