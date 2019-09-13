<?php namespace _\lot\x\panel\task;

function get($_, $var) {
    global $url;
    $e = $url->query('&', [
        'tab' => false,
        'token' => false
    ]) . $url->hash;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($var['token']) || $var['token'] !== $_['token']) {
            $_['alert']['error'][] = 'Invalid token.';
            return $_;
        }
        $name = \basename(\To::file($var['file']['name'] ?? ""));
        if ($name === "") {
            $_['alert']['error'][] = 'Please fill out the <em>Name</em> field.';
        } else if (\stream_resolve_include_path($f = \dirname($_['f']) . \DS . $name) && $name !== \basename($_['f'])) {
            $_['alert']['error'][] = 'Path <code>' . $f . '</code> already exists.';
        } else {
            \file_put_contents($f, $var['file']['content']);
            if ($name !== \basename($_['f'])) {
                \unlink($_['f']);
            }
            if (isset($var['file']['seal'])) {
                \chmod($f, \octdec($var['file']['seal']));
            }
            $_['alert']['success'][] = 'Path <code>' . $_['f'] . '</code> updated.';
            $_['kick'] = $url . $_['//'] . '/::g::' . \dirname($_['path']) . '/' . $name . $e;
            $_SESSION['_']['file'][\dirname($f) . \DS . $name] = 1;
        }
    }
    return $_;
}

\Hook::set('on.file.get', __NAMESPACE__ . "\\get", 10);