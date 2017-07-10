<?php

$__is_data = substr($url->path, -2) === '/+' || strpos($url->path, '/+/') !== false;

// `panel/::s::/page` → new page in `lot\page`
// `panel/::g::/page` → index view
// `panel/::s::/page/blog` → new child page for `lot\page\blog`
// `panel/::g::/page/blog` → edit page of `lot\page\blog`
Config::set([
    'is' => $__is_has_step ? 'pages' : 'page',
    'is_f' => $__is_has_step ? false : 'editor',
    'layout' => $__is_has_step || $__is_data ? 2 : 3,
    'panel' => [
        'm' => [
            't' => substr($__path, -2) === '/+' || strpos($__path, '/+/') !== false ? [
                'data' => [
                    'stack' => 10
                ]
            ] : array_merge([
                'page' => [
                    'stack' => 10
                ]
                ], Plugin::exist('art') ? [
                'css' => [
                    'title' => 'CSS',
                    'stack' => 20
                ],
                'js' => [
                    'title' => 'JavaScript',
                    'stack' => 30
                ]
            ] : [])
        ]
    ]
]);

Lot::set('__is_data', $__is_data);

Hook::set('__page.url', function($__content, $__lot) use($__state) {
    $__s = Path::F($__lot['path'], LOT);
    return rtrim($__state->path . '/::g::/' . ltrim(To::url($__s), '/'), '/');
});

if ($__f = File::exist(__DIR__ . DS . 'page' . DS . $__action . '.php')) require $__f;

if (!$__is_has_step && $__page[0]) {
    $__s = trim(To::url(Path::F($__path, 'page')), '/');
    Config::set('panel.f.page.options', [
        'as_' => [
            'title' => $language->panel->as_,
            'value' => $__s,
            'is' => [
                'active' => $site->path === $__s,
                '.' => $site->path === $__s
            ]
        ],
        'as_page' => Get::pages(LOT . DS . $__path, 'draft,page,archive') ? [
            'title' => $language->panel->as_page,
            'value' => 1,
            'is' => [
                'active' => file_exists(Path::F($__page[0]->path) . DS . $__page[0]->slug . '.' . $__page[0]->state)
            ]
        ] : null
    ]);
}