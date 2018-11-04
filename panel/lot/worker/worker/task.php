<?php

// `empty trash`
function _bf28477() {
    \File::open(LOT . DS . 'trash')->delete();
    \Message::success('The trash folder has been cleaned successfully.');
    $state = \Extend::state('panel');
    return ['kick' => $state['path'] . '/::g::/' . $state['$'] . '/1'];
}

// `set language`
function _c528a68c($file) {
    $f = STATE . DS . 'config.php';
    $config = \File::open($f)->import();
    $config['language'] = \Path::N($file);
    \File::export($config)->saveTo($f, 0600);
    $page = new \Page($file);
    \Message::success('The interface language has been successfully set to ' . $page->title . '.');
    return ['kick' => \Extend::state('panel', 'path') . '/::g::/language/1'];
}

// `rename`
function _d99d544e($from, $to) {
    \File::open($from)->renameTo($to);
}

// `rename then kick`
function _32a5a0db($from, $to, $id) {
    _d99d544e($from, $to);
    \Session::set('panel.file.active', dirname($from) . DS . 'about.page');
    return ['kick' => \Extend::state('panel', 'path') . '/::g::/' . $id . '/1'];
}

// `activate/deactivate extension`
function _2eca1f34($from, $to) {
    $page = new \Page(dirname($from) . DS . 'about.page');
    \Message::success('Extension ' . $page->title . ' has been ' . (\Path::X($from) === 'x' ? 'activated' : 'deactivated') . '.');
    return _32a5a0db($from, $to, 'extend');
}

// `activate/deactivate plugin`
function _787b240a($from, $to) {
    $page = new \Page(dirname($from) . DS . 'about.page');
    \Message::success('Plugin ' . $page->title . ' has been ' . (\Path::X($from) === 'x' ? 'activated' : 'deactivated') . '.');
    return _32a5a0db($from, $to, 'extend/plugin/lot/worker');
}

// `activate/deactivate shield`
function _8f86d176($file) {
    $f = STATE . DS . 'config.php';
    $config = \File::open($f)->import();
    $config['shield'] = basename(dirname($file));
    \File::export($config)->saveTo($f, 0600);
    $page = new \Page($file);
    \Message::success('Current theme has been successfully set to ' . $page->title . '.');
    return ['kick' => \Extend::state('panel', 'path') . '/::g::/shield/1'];
}

// `user exit`
function _950abfd9($file) {
    \File::open(\Path::F($file) . DS . 'token.data')->delete();
    $state = \Extend::state('user');
    \Message::success('user_exit');
    return ['kick' => $state['_path'] ?? $state['path']];
}

// `ajax count files`
function _fea4a865($file, $x = '*') {
    $count = count(glob($file . DS . '*.' . $x, GLOB_NOSORT));
    HTTP::type('text/plain')->header('Cache-Control', 'no-cache');
    echo $count ? $count : "";
    exit;
}