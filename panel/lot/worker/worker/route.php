<?php

// Set common path…
Route::set([$__state->path . '/::%s%::/%*%/%i%', $__state->path . '/::%s%::/%*%'], function($__command, $__path, $__step = 1) use($__state, $__user_enter, $__user_key, $__user_token) {
    extract(Lot::get(null, []));
    $__command = To::url($__command, true);
    $__path = To::url($__path, true);
    $__path_shield = PANEL . DS . 'lot' . DS . 'shield' . DS . $__state->shield;
    $__chops = explode('/', $__path);
    $__DIR = Path::D(__DIR__);
    $__s = $__DIR . DS . 'worker' . DS;
    $__task = File::exist($__DIR . DS . 'index' . DS . $__chops[0] . '.php');
    Lot::set([
        '__chops' => $__chops,
        '__command' => $__command,
        '__path' => $__path,
        '__path_shield' => $__path_shield,
        '__step' => $__step - 1
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
    Lot::set([
        '__token' => $__token,
        '__hash' => $__hash,
        '__user' => $__user,
        '__user_enter' => $__user_enter,
        '__user_key' => $__user_key,
        '__user_token' => $__user_token,
        '__message' => Message::get() ?: Lot::get('message', "")
    ]);
    // Custom file manager layout
    if ($__task) {
        require $__task;
    } else {
        Shield::abort(PANEL_404);
    }
    // Default to file manager
    $__l = Request::get('l', Config::get('panel.l', 'file'));
    require File::exist(
        $__DIR . DS . 'worker' . DS . $__l . '.php',
        $__DIR . DS . 'worker' . DS . 'file.php'
    );
    if (($__layout = Request::get('layout', "")) !== "") {
        Config::set('panel.layout', is_numeric($__layout) ? $__layout : 0);
    }
    Shield::attach(__DIR__ . DS . '..' . DS . Config::get('panel.layout', 0) . '.php');
}, 1);

// Set upload path for AJAX…
Route::set($__state->path . '/::u::/%s%', function($__s = "") {
    HTTP::mime('application/json');
    $__t = Request::get('token');
    if (!Request::is('post')) {
        // “Method Not Allowed”
        HTTP::status(405);
        echo json_encode(['x' => 1, 'v' => 0, 'status' => 405]);
        exit;
    } else if (!$__t || $__t !== Session::get(Guardian::$config['session']['token'])) {
        // “Non-Authoritative Information”
        HTTP::status(203);
        echo json_encode(['x' => 1, 'v' => 0, 'status' => 203]);
        exit;
    }
    if (!empty($__FILES)) {
        $__output = [];
        foreach ($__FILES as $__k => $__v) {
            if (!File::upload($__v, LOT . DS . $__s, function($__file) use($__url) {
                $__output[$__k] = json_encode(array_merge($__file, ['x' => 0, 'v' => 1]));
            })) {
                // “Not Acceptable”
                $__output[$__k] = ['x' => 1, 'v' => 0, 'status' => 406];
            }
        }
        echo json_encode($__output);
    } else {
        // “No Content”
        HTTP::status(204);
        echo json_encode(['x' => 1, 'v' => 0, 'status' => 204]);
    }
    exit;
}, 1);