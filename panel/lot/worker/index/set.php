<?php

if (Request::is('post') && !empty($__user_key)) {
    Request::reset('post', '_key');
    $data = [
        'author' => Request::post('_author', false),
        'type' => Request::post('_type', 'HTML'),
        'link' => Request::post('_link', false),
        'email' => Request::post('_email', false),
        'status' => Request::post('_status', 2),
        'content' => Request::post('_description', false)
    ];
    Page::open(ENGINE . DS . 'log' . DS . 'user' . DS . $__user_key . '.page')->data($data)->save(0600);
}

Hook::set('shield.path', function($__path) use($site) {
    $s = Path::N($__path);
    if ($s === $site->is) {
        return PANEL . DS . 'lot' . DS . 'worker' . DS . $s . DS . Path::B(__FILE__);
    }
    return $__path;
});