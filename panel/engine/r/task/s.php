<?php namespace _\lot\x\panel\task;

function set($_, $var) {
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
        } else if (\stream_resolve_include_path($f = $_['f'] . \DS . $name)) {
            $_['alert']['error'][] = 'Path <code>' . $f . '</code> already exists.';
        } else {
            \file_put_contents($f, $var['file']['content']);
            if (isset($var['file']['seal'])) {
                \chmod($f, \octdec($var['file']['seal']));
            }
            $_['alert']['success'][] = 'Path <code>' . $f . '</code> created.';
            $_['kick'] = $url . $_['//'] . '/::g::' . $_['path'] . '/1' . $e;
            $_SESSION['_']['file'][$f] = 1;
        }
    }
    return $_;
}

\Hook::set('on.file.set', __NAMESPACE__ . "\\set", 10);