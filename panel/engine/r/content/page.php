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

<?php

echo _\lot\x\panel(['lot' => [
    'nav' => [
        'type' => 'nav',
        'lot' => [
            'header' => [
                'type' => 'nav.ul',
                'lot' => [
                    0 => [
                        'icon' => 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z',
                        'caret' => false,
                        'title' => false,
                        'path' => '/',
                        'lot' => ['Asset', 'Block', 'Cache', 'Comment', 'Content', 'Page', 'Extension'],
                        'tags' => ['main']
                    ],
                    1 => [
                        0 => false,
                        1 => '<form><p class="field"><label>Search</label><span><input class="input" placeholder="Search" type="text"></span></p></form>'
                    ]
                ],
                'stack' => 10
            ],
            'body' => [
                'type' => 'nav.ul',
                'lot' => ['Home', 'About', [
                    'title' => 'Archive',
                    'path' => '/',
                    'lot' => ['2019', [
                        'title' => '2018',
                        'lot' => ['January', 'February', 'March', 'April']
                    ], '2017']
                ], 'Contact'],
                'stack' => 20
            ],
            'footer' => [
                'type' => 'nav.ul',
                'lot' => [
                    0 => [
                        'icon' => 'M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21',
                        'caret' => false,
                        'path' => '/'
                    ]
                ],
                'stack' => 30
            ]
        ]
    ],
    'desk' => [
        'type' => 'desk',
        'lot' => [
            'header' => [
                'type' => 'desk.header',
                'content' => 'Header goes here.',
                'stack' => 10
            ],
            'body' => [
                'type' => 'desk.body',
                'lot' => [
                    'form' => [
                        'type' => 'form.post',
                        'path' => '/foo/bar',
                        'lot' => [
                            'tab' => [
                                'type' => 'tab',
                                'lot' => [
                                    'page' => [
                                        'title' => 'Page',
                                        'content' => 'Content for <em>Page</em> tab.'
                                    ],
                                    'data' => [
                                        'title' => 'Data',
                                        'content' => 'Content for <em>Data</em> tab.'
                                    ],
                                    'any' => [
                                        'title' => 'Others',
                                        'content' => 'Content for <em>Others</em> tab.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'stack' => 20
            ],
            'footer' => [
                'type' => 'desk.footer',
                'content' => 'Footer goes here.',
                'stack' => 30
            ]
        ]
    ]
]], 0, '#');

?>

  </body>
</html>