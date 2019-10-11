<?php

$lot = require __DIR__ . DS . 'page.php';

if (State::get('x.art') !== null) {
    // Add custom CSS and JS field(s)
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

$lot['bar']['lot'][0]['lot']['s']['url'] = str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['content' => 'page.page', 'tab' => false]) . $url->hash;
$lot['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['s']['title'] = $language->{'do' . ($_['task'] === 's' ? 'Publish' : 'Update')};

return $lot;