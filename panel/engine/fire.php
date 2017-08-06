<?php

$__user_enter = $__user_key = $__user_token = null;

if ($__user_key = Cookie::get('panel.c.user.key')) {
    if ($__user_token = File::open(USER . DS . $__user_key . DS . 'token.data')->get(0)) {
        if (Cookie::get('panel.c.user.token') === $__user_token) {
            $__user_enter = true;
        }
    }
}

$__f = PANEL . DS . 'lot' . DS . 'worker' . DS;

if (Request::is('get') && !$__user_enter && g(USER, 'page', "", false) && $url->path === $__state->path . '/::s::/user') {
    Guardian::kick("");
}

if (
    (
        $url->path === $__state->path ||
        strpos($url->path . '/', $__state->path . '/') === 0
    ) &&
    (
        $__user_enter ||
        $url->path === $__state->path . '/::s::/user' ||
        $url->path === $__state->path . '/::g::/enter'
    )
) {
    if (
        $url->path === $__state->path . '/::g::/enter' &&
        file_exists($__f . 'index' . DS . 'user.php') &&
        !g(USER, 'page')
    ) {
        Message::info('void', $language->users);
        Guardian::kick($__state->path . '/::s::/user');
    }
    Hook::set('on.panel.ready', function() use($language) {
        foreach ((array) $language->o_type as $k => $v) {
            Config::set('panel.o.page.type.' . $k, $v);
        }
        foreach ((array) $language->o_editor as $k => $v) {
            Config::set('panel.o.page.editor.' . $k, $v);
        }
    }, 1);
    require $__f . 'index.php';
    if ($__ff = File::exist(__DIR__ . DS . '..' . DS . 'lot' . DS . 'shield' . DS . $__state->shield . DS . 'index.php')) {
        require $__ff;
    }
    require $__f . 'worker' . DS . 'route.php';
}