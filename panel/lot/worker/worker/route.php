<?php

Route::set([$__state->path . '/::%s%::/%*%/%i%', $__state->path . '/::%s%::/%*%'], function($__action, $__path, $__step = 1) use($__state, $__user_enter, $__user_key, $__user_token) {
    extract(Lot::get(null, []));
    $__action = To::url($__action, true);
    $__path = To::url($__path, true);
    $__path_shield = PANEL . DS . 'lot' . DS . 'shield' . DS . $__state->shield;
    $__chops = explode('/', $__path);
    $__DIR = Path::D(__DIR__);
    $__s = $__DIR . DS . 'worker' . DS;
    Lot::set([
        '__action' => $__action,
        '__path' => $__path,
        '__path_shield' => $__path_shield,
        '__step' => $__step - 1,
        '__chops' => $__chops
    ]);
    $__task = File::exist($__DIR . DS . 'index' . DS . $__chops[0] . '.php');
    $site->is = 'page'; // default is `page`
    require $__s . 'extend.php';
    require $__s . 'extend' . DS . 'plugin.php';
    require $__s . DS . 'shield.php';
    require $__s . DS . 'f.php';
    require $__s . DS . 'n.php';
    require $__s . DS . 'asset.php';
    require $__s . DS . 'lot.php';
    if (!$__task) {
        Shield::abort(PANEL_404);
    }
    $__token = Guardian::token();
    $__hash = Guardian::hash();
    $__user = User::current();
    // Restricted user
    if ($__user && $__user->status !== 1 && Is::these(['language', 'state'])->has($__chops[0])) {
        Shield::abort(PANEL_404);
    }
    require $__task;
    Lot::set([
        '__token' => $__token,
        '__hash' => $__hash,
        '__user' => $__user,
        '__user_enter' => $__user_enter,
        '__user_key' => $__user_key,
        '__user_token' => $__user_token,
        '__message' => Message::get() ?: Lot::get('message', ""),
        '__n_n' => $__n_n
    ]);
    if ($__user && $__action === 's' && Request::is('get')) {
        Request::save('post', 'user', User::ID . $__user->key);
    }
    Shield::attach(__DIR__ . DS . '..' . DS . $site->layout(0) . '.php');
}, 1);