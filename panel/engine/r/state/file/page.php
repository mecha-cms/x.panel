<?php

// `http://127.0.0.1/panel/::g::/page/foo-bar.page`
$GLOBALS['_']['content'] = $_['content'] = [
    'archive' => 'page',
    'data' => 'data',
    'draft' => 'page',
    'page' => 'page'
][pathinfo($_['f'], PATHINFO_EXTENSION)] ?? $_['content'];

$lot = require __DIR__ . DS . '..' . DS . $_['content'] . '.php';

if ($_['content'] === 'page' && State::get('x.art') !== null) {
    $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['art'] = [
        'lot' => [
            'fields' => [
                'type' => 'Fields',
                'lot' => [
                    'css' => [
                        'title' => '<abbr title="Cascading Style Sheet">CSS</abbr>',
                        'type' => 'Source',
                        'name' => 'data[css]',
                        'alt' => $language->fieldAltCss,
                        'value' => $page['css'],
                        'width' => true,
                        'height' => true,
                        'stack' => 10
                    ],
                    'js' => [
                        'title' => '<abbr title="JavaScript">JS</abbr>',
                        'type' => 'Source',
                        'name' => 'data[js]',
                        'alt' => $language->fieldAltJs,
                        'value' => $page['js'],
                        'width' => true,
                        'height' => true,
                        'stack' => 20
                    ]
                ],
                'stack' => 10
            ]
        ],
        'stack' => 30
    ];
}

return $lot;