<?php

Hook::set('message.set.success', function($__s) use($language, $__action, $__chops, $__path) {
    if ($__action === 'r') {
        return $__s;
    }
    $__p = new Page(LOT . DS . ($__action === 's' ? $__path . DS . Request::post('slug') : $__path) . '.' . Request::post('x'), [], $__chops[0]);
    return $__s . ' ' . HTML::a($language->view, $__p->url, true, ['classes' => ['right']]);
});

require __DIR__ . DS . '..' . DS . 'worker' . DS . 'page.php';

// `panel/::s::/page` → new page in `lot\page`
// `panel/::g::/page` → index view
// `panel/::s::/page/blog` → new child page for `lot\page\blog`
// `panel/::g::/page/blog` → edit page of `lot\page\blog`
Config::set('panel.m.t', substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false ? [
    'data' => [
        'title' => $language->editor,
        'stack' => 10
    ]
] : (Plugin::exist('art') ? [
    'css' => [
        'title' => 'CSS',
        'stack' => 20
    ],
    'js' => [
        'title' => 'JavaScript',
        'stack' => 30
    ]
] : []));