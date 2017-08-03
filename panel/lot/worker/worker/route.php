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
    require $__s . 'extend.php';
    require $__s . 'extend' . DS . 'plugin.php';
    require $__s . 'shield.php';
    require $__s . 'f.php';
    require $__s . 'n.php';
    require $__s . 'asset.php';
    require $__s . 'lot.php';
    $__token = Guardian::token();
    $__hash = Guardian::hash();
    $__user = User::get();
    // Restricted user
    if ($__user) {
        if ($__user->status === -1) {
            Shield::abort(PANEL_404);
        } else if ($__user->status !== 1) {
            if (
                (!isset($__chops[1]) || $__chops[1] !== $__user_key) &&
                Is::these(['language', 'state', 'user'])->has($__chops[0])
            ) {
                Shield::abort(PANEL_404);
            }
        }
    }
    // Default to file manager
    require $__s . 'file.php';
    if ($__f = File::exist($__DIR . DS . 'index' . DS . $__chops[0] . '.php')) {
        // Custom file manager layout
        require $__f;
    }
    Lot::set([
        '__token' => $__token,
        '__hash' => $__hash,
        '__user' => $__user,
        '__user_enter' => $__user_enter,
        '__user_key' => $__user_key,
        '__user_token' => $__user_token,
        '__message' => Message::get() ?: Lot::get('message', "")
    ]);
    if ($__user && $__action === 's' && Request::is('get')) {
        Request::save('post', 'user', '@' . $__user->key);
    }
    Shield::attach(__DIR__ . DS . '..' . DS . Config::get('panel.layout', 0) . '.php');
}, 1);