<?php

$__user_enter = $__user_key = $__user_token = null;

// Validate the author!
if ($__user_key = Cookie::get('panel.c.user.key')) {
    if ($__user_token = File::open(USER . DS . $__user_key . DS . 'token.data')->get(0)) {
        if (Cookie::get('panel.c.user.token') === $__user_token) {
            $__user_enter = true;
        }
    }
}

// Inherit log in author from `user` extension…
if (!$__user_enter) {
    if ($__key = Session::get('url.user')) {
        $__key = substr($__key, 1);
        Cookie::set('panel.c.user.key', $__key);
    }
    if ($__token = Session::get('url.token')) {
        Cookie::set('panel.c.user.token', $__token);
        File::write($__token)->saveTo(USER . DS . $__key . DS . 'token.data', 0600);
    }
    if ($__key && $__token) {
        Guardian::kick($__state->path . '/::g::/' . $__state->kick('page'));
    }
}

$__f = PANEL . DS . 'lot' . DS . 'worker' . DS;
$__g = g(USER, 'page');

$path_ = $url->path;
$_path = $__state->path;

// A visitor is trying to access the user creator!
if (Request::is('get') && !$__user_enter && $__g && $path_ === $_path . '/::s::/user') {
    Guardian::kick(""); // redirect to the home page
}

// Add a hook to the default log in system from the `user` extension that will
// redirect the user to the `Extend::state('panel', 'kick')` after logged in!
if ($url->path === Extend::state('user', 'path')) {
    Hook::set('on.user.enter', function() use($__state) {
        Guardian::kick($__state->path . '/::g::/' . $__state->kick('page'));
    });
}

// Add some tool(s) that will be visible if user is logged in…
if ($__user_enter) {
    /*
    if (Extend::exist('comment')) {
        Hook::set('page.a.comment', function($__a, $__v) use($language, $url, $_path) {
            $__s = str_replace([LOT . DS, DS], ["", '/'], Path::F($__v->path));
            $__a = $__a + [
                'get' => HTML::a($language->edit, $_path . '/::g::/' . $__s, false, ['class[]' => ['comment-a', 'comment-a:get']]),
                'reset' => HTML::a($language->delete, $_path . '/::r::/' . $__s . HTTP::query([
                    'token' => Guardian::token()
                ]), false, ['class[]' => ['comment-a', 'comment-a:reset']])
            ];
            return $__a;
        });
    }
    */
}

if (
    (
        // is in `panel`…
        $path_ === $_path ||
        // or in `panel/*`…
        strpos($path_ . '/', $_path . '/') === 0
    ) && (
        // and is logged in…
        $__user_enter ||
        // or in the log in page…
        $path_ === $_path . '/::g::/enter'
    )
) {
    // If no user(s), add a message!
    if (!$__g) {
        Message::info('void', $language->users);
    }
    // Passed!
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