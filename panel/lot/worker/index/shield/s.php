<?php

if (count($__chops) === 1) {
    // Upload…
    if (!empty($_FILES['file'])) {
        if (($x = Path::X($_FILES['file']['name'])) !== 'zip') {
            Message::error('file_x', '<em>' . $x . '</em>');
        }
    }
} else if (count($__chops) === 2) {
    if (!Request::get('token')) {
        Shield::abort(PANEL_404);
    }
    $__c = require STATE . DS . 'config.php';
    $__c['shield'] = Path::B($__path);
    if (!Message::$x) {
        File::export($__c)->saveTo(STATE . DS . 'config.php', 0600);
        Message::success(To::sentence($language->updateed));
        Guardian::kick(str_replace('::s::', '::g::', Path::D($url->current)));
    }
} else if (count($__chops) >= 2) {
    if ($__is_post && !Message::$x) {
        if (Request::post('xx') === -1) {
            Guardian::kick(str_replace('::g::', '::r::', $url->current . HTTP::query(['token' => Request::post('token')])));
        }
        $__n = Request::post('name');
        $__x = Path::X($__n, false);
        $__is_f = Request::post('xx') !== 0;
        if (!$__n) {
            Message::error('void_field', $language->name, true);
        } else if ($__is_f) {
            if ($__x === false) {
                Message::error('void_field', $language->extension, true);
            } else if (!Is::these(File::$config['extensions'])->has($__x)) {
                Message::error('file_x', '<em>' . $__x . '</em>');
            }
        }
        $__ff = SHIELD . DS . $__chops[1] . DS . call_user_func('To::' . ($__is_f ? 'file' : 'folder'), $__n);
        Hook::fire('on.shield.set', [$__ff]);
        if (!Message::$x) {
            if ($__is_f) {
                File::open($__f)->delete();
                File::write(Request::post('content'))->saveTo($__ff);
            } else {
                File::open($__f)->moveTo($__ff);
            }
            Message::success(To::sentence($language->updateed));
            Guardian::kick($__state->path . '/::g::/' . $__chops[0] . '/' . $__chops[1] . '/' . To::url($__n));
        } else {
            Request::save('post');
        }
    }
    $__a = [
        'name' => null,
        'type' => 'HTML'
    ];
    $__a = o($__a);
    Lot::set('__page', [$__a, $__a]);
}