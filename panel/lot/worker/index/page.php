<?php

Hook::set('message.set.success', function($__s) use($language, $__command, $__chops, $__path) {
    $__x = Request::post('x');
    if ($__command === 'r' || !$__x || strpos('/' . $__path . '/', '/+/') !== false) {
        return $__s;
    }
    if ($__x !== 'page') {
        return $__s;
    }
    $__p = new Page(LOT . DS . $__path . DS . Request::post('slug') . '.' . $__x, [], $__chops[0]);
    return $__s . ' ' . HTML::a($language->view, $__p->url, true, ['classes' => ['right']]);
});

// Set custom panel view
Config::set('panel.view', 'page');

// `panel/::s::/page` → new page in `lot\page`
// `panel/::g::/page` → index view
// `panel/::s::/page/blog` → new child page for `lot\page\blog`
// `panel/::g::/page/blog` → edit page of `lot\page\blog`
if (!$__is_has_step) {
    Config::set('panel.m.t', substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false ? [
        'data' => [
            'title' => $language->editor,
            'stack' => 10
        ]
    ] : (Plugin::exist('art') ? [
        'css' => [
            'title' => '<abbr title="Cascading Style Sheet">CSS</abbr>',
            'stack' => 20
        ],
        'js' => [
            'title' => '<abbr title="JavaScript">JS</abbr>',
            'stack' => 30
        ]
    ] : []));
}