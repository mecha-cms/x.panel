<?php

Config::set('panel.+.svg', require __DIR__ . DS . '..' . DS . 'lot' . DS . 'state' . DS . 'svg.php');

$query = HTTP::get('q');
$svg = fn\panel\svg();

// No `nav` key in URL query or has `nav` key in URL query with value of boolean `true`
if (!HTTP::is('get', 'nav') || HTTP::get('nav')) {

    Hook::set('on.ready', function() use($svg, $query) {

        extract(Lot::get());

        $item_view = Config::get('panel.+.form.editor');

        $c = $panel->c;
        $id = $panel->id;
        $chops = $panel->chops;
        $path = trim($id . '/' . $panel->path, '/');
        $folders = glob(LOT . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT);

        sort($folders);

        if (!$item_view) {
            $i = 0;
            $links = [];
            foreach ($folders as $v) {
                $n = basename($v);
                if (strpos('._', $n[0]) !== false) {
                    continue; // Skip hidden folder(s)
                }
                $links[$n] = [
                    'icon' => [[isset($svg[$n]) ? (isset($svg[$n]['$']) ? $svg[$n]['$'] : $svg[$n]) : $svg['folder']]],
                    'path' => $n,
                    'stack' => 10 + $i
                ];
                $i += .1;
            }
        }

        Config::set('panel.nav.lot', [
            'title' => false,
            'description' => $language->{$item_view ? 'back' : 'folders'},
            'icon' => [[$item_view ? 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z' : 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z']],
            'path' => $item_view ? ($c === 's' ? $path : dirname($path)) . '/1' : null,
            '+' => $item_view ? null : $links,
            'kind' => ['inverse'],
            'stack' => 10
        ]);

        Config::set('panel.nav.search', [
            'content' => fn\panel\nav_li_search([
                'title' => $language->{str_replace('.', "\\.", $id)},
                'path' => ($item_view ? dirname($path) : $path) . '/1'
            ], $id),
            'stack' => 10.1
        ]);

        $path_user = basename(USER);
        $active = strpos($path . '/', $path_user . '/') === 0;
        $package_feature = Extend::exist('package');
        Config::set('panel.nav.site', [
            '+' => [
                'state/config' => [
                    'title' => $language->config,
                    'path' => 'state/config.php',
                    'icon' => [[$svg['config']]],
                    'stack' => 10
                ],
                'user' => [
                    'icon' => [['M12,19.2C9.5,19.2 7.29,17.92 6,16C6.03,14 10,12.9 12,12.9C14,12.9 17.97,14 18,16C16.71,17.92 14.5,19.2 12,19.2M12,5A3,3 0 0,1 15,8A3,3 0 0,1 12,11A3,3 0 0,1 9,8A3,3 0 0,1 12,5M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z']],
                    'path' => $path_user,
                    '+' => [
                        'g' => [
                            'title' => $language->edit,
                            'icon' => [['M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z']],
                            'path' => $path_user . '/' . $user->slug . '.page',
                            'stack' => 10
                        ],
                        'exit' => [
                            'icon' => [['M19,21V19H15V17H19V15L22,18L19,21M10,4A4,4 0 0,1 14,8A4,4 0 0,1 10,12A4,4 0 0,1 6,8A4,4 0 0,1 10,4M10,14C11.15,14 12.25,14.12 13.24,14.34C12.46,15.35 12,16.62 12,18C12,18.7 12.12,19.37 12.34,20H2V18C2,15.79 5.58,14 10,14Z']],
                            'active' => false,
                            'path' => basename(USER) . '/' . $user->slug . '.page',
                            'task' => '950abfd9',
                            'stack' => 10.1
                        ]
                    ],
                    'stack' => 10.2
                ],
                'view' => [
                    'title' => $language->view_site,
                    'url' => "",
                    'icon' => [['M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z']],
                    'active' => false,
                    'target' => '_blank',
                    'stack' => 10.2
                ],
                '_0' => $package_feature ? [
                    'type' => '|',
                    'stack' => 20
                ] : null,
                'package' => $package_feature ? [
                    'title' => $language->do_backup,
                    'icon' => [['M12,3A9,9 0 0,0 3,12H0L4,16L8,12H5A7,7 0 0,1 12,5A7,7 0 0,1 19,12A7,7 0 0,1 12,19C10.5,19 9.09,18.5 7.94,17.7L6.5,19.14C8.04,20.3 9.94,21 12,21A9,9 0 0,0 21,12A9,9 0 0,0 12,3M14,12A2,2 0 0,0 12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12Z']],
                    'active' => false,
                    'task' => ['421d9546', [1]],
                    'stack' => 20.1
                ] : null
            ],
            'stack' => 20
        ]);

        $messages = glob(LOT . DS . '.message' . DS . '*.page', GLOB_NOSORT);
        Config::set('panel.nav.message', [
            'x' => $user->status !== 1,
            'title' => false,
            'i' => ($i = $user->status !== 1 ? 0 : count($messages)),
            'description' => $i . ' ' . $language->{'message' . ($i === 1 ? "" : 's')},
            'icon' => [[$svg['bell'][$user->_mute ? 2 : ($i > 0 ? 1 : 0)] ?? ""]],
            'active' => $i > 0,
            'path' => '.message',
            'kind' => ['right'],
            'stack' => 10.2
        ]);

        if ($item_view && $c === 'g') {
            Config::set('panel.nav.s', [
                'title' => false,
                'description' => $language->new__($language->file, true),
                'icon' => [['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z']],
                'path' => dirname($path),
                'c' => 's',
                'query' => [
                    'tab' => ['file'],
                    'tabs' => ['false']
                ],
                'stack' => 10.09
            ]);
        }

        if ($query) {
            Config::set('panel.nav.lot', [
                'description' => $language->clear,
                'icon' => [['M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z']],
                'path' => $path . '/1',
                '+' => null,
                'query' => [
                    'q' => false,
                    'view' => HTTP::get('view', false)
                ]
            ]);
        }

    }, 0);

}

if ($query) {
    Message::info('search', To::text($query));
    Lot::set('message', Message::get(null, false));
}

Config::set('panel.+.data.tool', [
    'g' => [
        'title' => false,
        'description' => $language->edit,
        'icon' => [['M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z']],
        'c' => 'g',
        'stack' => 10
    ],
    // Only user with status `1` that has delete access
    'r' => $user->status === 1 ? [
        'title' => false,
        'description' => $language->delete,
        'icon' => [['M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z']],
        'c' => 'r',
        'query' => [
            'a' => -2,
            'token' => $user->token,
        ],
        'stack' => 10.1
    ] : null
]);

Config::set('panel.+.file.tool', [
    'g' => [
        'title' => false,
        'description' => $language->edit,
        'icon' => [['M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z']],
        'c' => 'g',
        'stack' => 10
    ],
    // Only user with status `1` that has delete access
    'r' => $user->status === 1 ? [
        'title' => false,
        'description' => $language->delete,
        'icon' => [['M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z']],
        'c' => 'r',
        'query' => [
            'a' => -2,
            'token' => $user->token,
        ],
        'stack' => 10.1
    ] : null
]);

Config::set('panel.+.page.tool', [
    'enter' => [
        'if' => function($file): array {
            $f = Path::F($file);
            return [
                'hidden' => !glob($f . DS . '*{draft,page,archive}', GLOB_BRACE | GLOB_NOSORT),
                'path' => Path::R($f, LOT, '/') . '/1'
            ];
        },
        'title' => false,
        'description' => $language->enter . ': ' . $language->pages,
        'icon' => [['M15.5,2C13,2 11,4 11,6.5C11,9 13,11 15.5,11C16.4,11 17.2,10.7 17.9,10.3L21,13.4L22.4,12L19.3,8.9C19.7,8.2 20,7.4 20,6.5C20,4 18,2 15.5,2M4,4A2,2 0 0,0 2,6V20A2,2 0 0,0 4,22H18A2,2 0 0,0 20,20V15L18,13V20H4V6H9.03C9.09,5.3 9.26,4.65 9.5,4H4M15.5,4C16.9,4 18,5.1 18,6.5C18,7.9 16.9,9 15.5,9C14.1,9 13,7.9 13,6.5C13,5.1 14.1,4 15.5,4Z']],
        'c' => 'g',
        'stack' => 9.9
    ],
    's' => [
        'if' => function($file): array {
            $f = Path::F($file);
            return [
                'hidden' => !!glob($f . DS . '*{draft,page,archive}', GLOB_BRACE | GLOB_NOSORT),
                'path' => Path::R($f, LOT, '/')
            ];
        },
        'title' => false,
        'description' => $language->add . ': ' . $language->pages,
        'icon' => [['M19,19V5H5V19H19M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V5C3,3.89 3.9,3 5,3H19M11,7H13V11H17V13H13V17H11V13H7V11H11V7Z']],
        'c' => 's',
        'stack' => 9.9
    ],
    'g' => [
        'title' => false,
        'description' => $language->edit,
        'icon' => [['M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z']],
        'c' => 'g',
        'stack' => 10
    ],
    // Only user with status `1` that has delete access
    'r' => $user->status === 1 ? [
        'title' => false,
        'description' => $language->delete,
        'icon' => [['M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z']],
        'c' => 'r',
        'query' => [
            'a' => -2,
            'token' => $user->token,
        ],
        'stack' => 10.1
    ] : null
]);