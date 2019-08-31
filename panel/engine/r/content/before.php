<!DOCTYPE html>
<html dir="ltr" class>
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width" name="viewport">
    <meta content="noindex" name="robots">
    <title><?php echo w($t->reverse); ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body>

<?php echo _\lot\x\panel([
    'type' => 'Bar',
    'lot' => [
        0 => [
            'type' => 'List',
            'lot' => [
                0 => [
                    'icon' => 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z',
                    'caret' => false,
                    'title' => false,
                    '/' => '/',
                    'lot' => [
                        'asset' => [
                            'title' => 'Asset',
                            '/' => 'asset'
                        ],
                        'block' => [
                            'title' => 'Block',
                            '/' => 'block'
                        ],
                        'cache' => [
                            'title' => 'Cache',
                            '/' => 'cache'
                        ]
                    ],
                    'tags' => ['main']
                ],
                1 => [
                    0 => false,
                    1 => '<form><p class="field"><label>Search</label><span><input class="input" placeholder="Test" type="text"></span></p></form>'
                ]
            ],
            'stack' => 10
        ],
        1 => [
            'type' => 'List',
            'lot' => [
                0 => [
                    'title' => 'Site',
                    '/' => '/',
                    'lot' => [
                        0 => ['title' => '2019'],
                        1 => [
                            'title' => '2018',
                            'lot' => [
                                0 => ['title' => 'January'],
                                1 => ['title' => 'February'],
                                2 => ['title' => 'March'],
                                3 => ['title' => 'April']
                            ]
                        ],
                        2 => ['title' => '2017']
                    ]
                ]
            ],
            'stack' => 20
        ],
        2 => [
            'type' => 'List',
            'lot' => [
                0 => [
                    'icon' => 'M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21',
                    'caret' => false,
                    '/' => '/'
                ]
            ],
            'stack' => 30
        ]
    ]
], 0);