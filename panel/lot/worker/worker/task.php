<?php

// `empty cache`
function _4f9d54dd() {
    if (!defined('CACHE')) return;
    File::open(CACHE)->delete();
    Folder::create(CACHE, 0755);
    Message::success('empty_cache');
    global $panel;
    return ['kick' => $panel->r . '/::g::/cache/1'];
}

// `empty trash`
function _bf28477() {
    File::open(LOT . DS . 'trash')->delete();
    Message::success('empty_trash');
    global $panel;
    return ['kick' => $panel->r . '/::g::/' . $panel->state->{'$'} . '/1'];
}

// `set language`
function _c528a68c($file) {
    $f = STATE . DS . 'config.php';
    $config = File::open($f)->import();
    $config['language'] = Path::N($file);
    File::export($config)->saveTo($f, 0600);
    $page = new Page($file);
    Message::success('language_set', ['<em>' . $page->title . '</em>'], true);
    global $panel;
    return ['kick' => $panel->r . '/::g::/language/1'];
}

// `rename`
function _d99d544e($from, $to) {
    File::open($from)->renameTo($to);
}

// `rename then kick`
function _32a5a0db($from, $to, $id) {
    _d99d544e($from, $to);
    Session::set('panel.file.active', dirname($from) . DS . 'about.page');
    global $panel;
    return ['kick' => $panel->r . '/::g::/' . $id . '/1'];
}

// `activate/deactivate extension`
function _2eca1f34($from, $to) {
    global $language;
    $page = new Page(dirname($from) . DS . 'about.page');
    Message::success('extend_do', ['<em>' . $page->title . '</em>', l($language->{Path::X($from) === 'x' ? 'attached' : 'ejected'})], true);
    return _32a5a0db($from, $to, 'extend');
}

// `activate/deactivate plugin`
function _787b240a($from, $to) {
    global $language;
    $page = new Page(dirname($from) . DS . 'about.page');
    Message::success('plugin_do', ['<em>' . $page->title . '</em>', l($language->{Path::X($from) === 'x' ? 'attached' : 'ejected'})], true);
    return _32a5a0db($from, $to, 'extend/plugin/lot/worker');
}

// `activate/deactivate shield`
function _8f86d176($file) {
    $f = STATE . DS . 'config.php';
    $config = File::open($f)->import();
    $config['shield'] = basename(dirname($file));
    File::export($config)->saveTo($f, 0600);
    $page = new Page($file);
    Message::success('shield_set', ['<em>' . $page->title . '</em>'], true);
    global $panel;
    return ['kick' => $panel->r . '/::g::/shield/1'];
}

// `user exit`
function _950abfd9($file) {
    $user = Lot::get('user');
    File::open(Path::F($file) . DS . 'token.data')->delete();
    $state = Extend::state('user');
    Message::success('user_exit');
    return ['kick' => $state['_path'] ?? $state['path']];
}

// `user exit force`
function _d4e798fd($file) {
    _950abfd9($file);
    return;
}

// `ajax count files`
function _fea4a865($file, $x = '*') {
    $count = count(glob($file . DS . '*.' . $x, GLOB_NOSORT));
    HTTP::type('text/plain')->header('Cache-Control', 'no-cache');
    echo $count ? $count : "";
    exit;
}

// `zip`
function _421d9546($file, $alt = null) {
    extract(Lot::get());
    $public = 'poll,share,view';
    $name = To::slug($config->title);
    if ($alt === 1) {
        $file = ROOT;
    } else if ($alt === 2) {
        // TODO
    } else if ($alt === -2) {
        // TODO
    } else {
        $name .= '.' . $panel->id;
    }
    $package = ASSET . DS . 'zip' . DS . $user->token . DS . $name . '.' . date('Y-m-d') . '.zip';
    Package::from($file)->packTo($package);
    return ['kick' => Path::R($package, ROOT, '/')];
}