<?php

if (!is_int($status = $user->status)) {
    $_['lot']['desk']['lot']['alert']['content'] = i('The active user does not have a valid %s value.', ['<code>status</code>']);
    $_['lot']['desk']['lot']['alert']['icon'] = 'M10 4A4 4 0 0 0 6 8A4 4 0 0 0 10 12A4 4 0 0 0 14 8A4 4 0 0 0 10 4M17.5 13C15 13 13 15 13 17.5C13 20 15 22 17.5 22C20 22 22 20 22 17.5C22 15 20 13 17.5 13M10 14C5.58 14 2 15.79 2 18V20H11.5A6.5 6.5 0 0 1 11 17.5A6.5 6.5 0 0 1 11.95 14.14C11.32 14.06 10.68 14 10 14M17.5 14.5C19.16 14.5 20.5 15.84 20.5 17.5C20.5 18.06 20.35 18.58 20.08 19L16 14.92C16.42 14.65 16.94 14.5 17.5 14.5M14.92 16L19 20.08C18.58 20.35 18.06 20.5 17.5 20.5C15.84 20.5 14.5 19.16 14.5 17.5C14.5 16.94 14.65 16.42 14.92 16Z';
    $_['status'] = 405;
} else if (1 === $status) {
    // Full access!
} else if (2 === $status) {
    // Partial access!
    if (0 === strpos($_['path'] . '/', 'asset/') && 0 !== strpos($_['path'] . '/', 'asset/' . $user->user . '/')) {
        if (!is_dir($d = LOT . D . 'asset' . D . $user->user)) {
            mkdir($d, 0775, true);
        }
        $_['kick'] = ['path' => 'asset/' . $user->user];
    }
    // TODO
} else if (3 === $status) {
    if ($user->path !== $_['file'] && dirname($user->path) . D . $user->name !== $_['folder']) {
        $_['lot']['desk']['lot']['alert']['content'] = i('You don\'t have permission to access this page.');
        $_['lot']['desk']['lot']['alert']['icon'] = 'M10 4A4 4 0 0 0 6 8A4 4 0 0 0 10 12A4 4 0 0 0 14 8A4 4 0 0 0 10 4M17.5 13C15 13 13 15 13 17.5C13 20 15 22 17.5 22C20 22 22 20 22 17.5C22 15 20 13 17.5 13M10 14C5.58 14 2 15.79 2 18V20H11.5A6.5 6.5 0 0 1 11 17.5A6.5 6.5 0 0 1 11.95 14.14C11.32 14.06 10.68 14 10 14M17.5 14.5C19.16 14.5 20.5 15.84 20.5 17.5C20.5 18.06 20.35 18.58 20.08 19L16 14.92C16.42 14.65 16.94 14.5 17.5 14.5M14.92 16L19 20.08C18.58 20.35 18.06 20.5 17.5 20.5C15.84 20.5 14.5 19.16 14.5 17.5C14.5 16.94 14.65 16.42 14.92 16Z';
        $_['status'] = 405;
    } else {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $status_new = $_POST['data']['status'] ?? $_POST['page']['status'] ?? "";
            if ($status_new !== $status) {
                $_['alert']['error'][$user->path] = ['You don\'t have permission to change the %s value.', ['<code>status</code>']];
                unset($_POST['data']['status'], $_POST['page']['status']);
            }
        }
    }
} else if (0 === $status) {
    if ($user->path !== $_['file'] && dirname($user->path) . D . $user->name !== $_['folder']) {
        $_['lot']['desk']['lot']['alert']['content'] = i('You don\'t have permission to access this page.');
        $_['lot']['desk']['lot']['alert']['icon'] = 'M10 4A4 4 0 0 0 6 8A4 4 0 0 0 10 12A4 4 0 0 0 14 8A4 4 0 0 0 10 4M17.5 13C15 13 13 15 13 17.5C13 20 15 22 17.5 22C20 22 22 20 22 17.5C22 15 20 13 17.5 13M10 14C5.58 14 2 15.79 2 18V20H11.5A6.5 6.5 0 0 1 11 17.5A6.5 6.5 0 0 1 11.95 14.14C11.32 14.06 10.68 14 10 14M17.5 14.5C19.16 14.5 20.5 15.84 20.5 17.5C20.5 18.06 20.35 18.58 20.08 19L16 14.92C16.42 14.65 16.94 14.5 17.5 14.5M14.92 16L19 20.08C18.58 20.35 18.06 20.5 17.5 20.5C15.84 20.5 14.5 19.16 14.5 17.5C14.5 16.94 14.65 16.42 14.92 16Z';
        $_['status'] = 405;
    } else {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $status_new = $_POST['data']['status'] ?? $_POST['page']['status'] ?? "";
            if ($status_new !== $status) {
                $_['alert']['error'][$user->path] = ['You don\'t have permission to change the %s value.', ['<code>status</code>']];
                unset($_POST['data']['status'], $_POST['page']['status']);
            }
        }
    }
} else if ($status < 0) {
    $_['lot']['desk']['lot']['alert']['content'] = i('The active user is currently using a reserved %s value.', ['<code>status</code>']);
    $_['lot']['desk']['lot']['alert']['icon'] = 'M10 4A4 4 0 0 0 6 8A4 4 0 0 0 10 12A4 4 0 0 0 14 8A4 4 0 0 0 10 4M17.5 13C15 13 13 15 13 17.5C13 20 15 22 17.5 22C20 22 22 20 22 17.5C22 15 20 13 17.5 13M10 14C5.58 14 2 15.79 2 18V20H11.5A6.5 6.5 0 0 1 11 17.5A6.5 6.5 0 0 1 11.95 14.14C11.32 14.06 10.68 14 10 14M17.5 14.5C19.16 14.5 20.5 15.84 20.5 17.5C20.5 18.06 20.35 18.58 20.08 19L16 14.92C16.42 14.65 16.94 14.5 17.5 14.5M14.92 16L19 20.08C18.58 20.35 18.06 20.5 17.5 20.5C15.84 20.5 14.5 19.16 14.5 17.5C14.5 16.94 14.65 16.42 14.92 16Z';
    $_['status'] = 405;
}

$GLOBALS['_'] = $_;