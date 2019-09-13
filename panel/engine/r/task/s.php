<?php namespace _\lot\x\panel\task\set;

function blob($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($form['token']) || $form['token'] !== $_['token']) {
            $_['alert']['error'][] = 'Invalid token.';
            return $_;
        }
        // ...
    }
    return $_;
}

function file($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($form['token']) || $form['token'] !== $_['token']) {
            $_['alert']['error'][] = 'Invalid token.';
            return $_;
        }
        $name = \basename(\To::file($form['file']['name'] ?? ""));
        if ($name === "") {
            $_['alert']['error'][] = 'Please fill out the <em>Name</em> field.';
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = 'Path <code>' . $f . '</code> already exists.';
        } else {
            \file_put_contents($f, $form['file']['content'] ?? "");
            \chmod($f, \octdec($form['file']['seal'] ?? '0777'));
            $_['alert']['success'][] = 'Path <code>' . $f . '</code> created.';
            $_['kick'] = $url . $_['//'] . '/::g::' . $_['path'] . '/1' . $e;
            $_SESSION['_']['file'][$f] = 1;
        }
    }
    return $_;
}

function folder($_, $form) {
    extract($GLOBALS, \EXTR_SKIP);
    $e = $url->query('&', [
        'content' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($form['token']) || $form['token'] !== $_['token']) {
            $_['alert']['error'][] = 'Invalid token.';
            return $_;
        }
        $name = \To::folder($form['folder']['name'] ?? "");
        if ($name === "") {
            $_['alert']['error'][] = 'Please fill out the <em>Name</em> field.';
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = 'Path <code>' . $f . '</code> already exists.';
        } else {
            \mkdir($f, \octdec($form['folder']['seal'] ?? '0755'), true);
            $_['alert']['success'][] = 'Path <code>' . $f . '</code> created.';
            if (!empty($form['folder']['kick'])) {
                $_['kick'] = $url . $_['//'] . '/::g::' . \strtr($f, [
                    \LOT => "",
                    \DS => '/'
                ]) . '/1' . $e;
            } else {
                $_['kick'] = $url . $_['//'] . '/::g::' . $_['path'] . '/1' . $e;
            }
            foreach (\step($f, \DS) as $v) {
                $_SESSION['_']['folder'][$v] = 1;
            }
        }
    }
    return $_;
}

foreach (['blob', 'file', 'folder'] as $v) {
    \Hook::set('on.' . $v . '.set', __NAMESPACE__ . "\\" . $v, 10);
}