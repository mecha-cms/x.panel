<?php

if (is_dir(($file = $_['file'] ?? $_['folder']) ?? P) && 'get' === $_['task']) {
    $_['alert']['error'][$file] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($file) . '</code>', 'file']];
    $_['kick'] = x\panel\to\link([
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => [
            'query' => null,
            'stack' => null,
            'tab' => null,
            'type' => null
        ],
        'task' => 'get'
    ]);
    return $_;
}

$trash = !empty($state->x->panel->guard->trash) ? date('Y-m-d-H-i-s') : null;

$fields = [];

if (is_file($file ?? P)) {
    $i = 10;
    foreach ((array) require x\panel\_cache_let($file) as $k => $v) {
        // Pre-defined field type
        $field = [
            'stack' => $i,
            'type' => 'text',
            'value' => is_array($v) ? json_encode($v) : s($v),
            'width' => true
        ];
        if (false === $v || true === $v) {
            $field['type'] = 'toggle';
            unset($field['width']);
        } else if (is_float($v) || is_int($v)) {
            $field['type'] = 'number';
            $field['step'] = is_float($v) ? '.1' : '1';
            unset($field['width']);
        } else if (is_string($v)) {
            $count = strlen($v);
            // `#ffffff`
            if ((4 === $count || 7 === $count) && '#' === $v[0] && ctype_xdigit(substr($v, 1))) {
                $field['type'] = 'color';
                unset($field['width']);
            // `00:00` or `00:00:00`
            } else if ((5 === $count || 8 === $count) && is_numeric($v[0]) && 2 === strpos($v, ':') && preg_match('/^([0-1]\d|2[0-4])(:([0-5]\d|60)){1,2}$/', $v)) {
                $field['type'] = 'time';
                unset($field['width']);
            // `0000-00-00`
            } else if ($count >= 10 && is_numeric($v[0]) && preg_match('/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])$/', $v)) {
                $field['type'] = 'date';
                unset($field['width']);
            // `0000-00-00 00:00:00`
            } else if ($count >= 19 && is_numeric($v[0]) && preg_match('/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])[ ]([0-1]\d|2[0-4])(:([0-5]\d|60)){2}$/', $v)) {
                $field['type'] = 'date-time';
                unset($field['width']);
            }
        }
        $fields[$k] = $field;
        $i += 10;
    }
}

$back = trim(dirname($_['path']), '.');

$bar = [
    // `bar`
    'lot' => [
        // `links`
        0 => [
            'lot' => [
                'link' => [
                    'skip' => false,
                    'url' => x\panel\to\link([
                        'part' => "" !== $back ? 1 : 0,
                        'path' => 'get' === $_['task'] ? trim("" !== $back ? $back : ($state->x->panel->route ?? 'page/1'), '/') : $_['path'],
                        'query' => [
                            'query' => null,
                            'stack' => null,
                            'tab' => null,
                            'type' => null
                        ]
                    ])
                ],
                'folder' => ['skip' => true]
            ]
        ]
    ]
];

$desk = [
    // `desk`
    'lot' => [
        'form' => [
            // `form/post`
            'data' => [
                'file' => ['seal' => '0600'],
                'token' => $_['token'],
                'trash' => $trash,
                'type' => $_['type']
            ],
            'lot' => [
                1 => [
                    // `section`
                    'lot' => [
                        'tabs' => [
                            // `tabs`
                            'gap' => false,
                            'lot' => [
                                'file' => [
                                    'lot' => [
                                        'fields' => [
                                            'lot' => $fields,
                                            'stack' => 10,
                                            'type' => 'fields'
                                        ]
                                    ],
                                    'stack' => 10
                                ]
                            ]
                        ]
                    ]
                ],
                2 => [
                    // `section`
                    'lot' => [
                        'fields' => [
                            'lot' => [
                                0 => [
                                    'lot' => [
                                        'tasks' => [
                                            'lot' => [
                                                'set' => [
                                                    'name' => false,
                                                    'stack' => 10,
                                                    'title' => 'get' === $_['task'] ? 'Update' : 'Create',
                                                    'type' => 'submit'
                                                ],
                                                'let' => ['skip' => true]
                                            ],
                                            'type' => 'tasks/button'
                                        ]
                                    ],
                                    'title' => "",
                                    'type' => 'field'
                                ]
                            ],
                            'stack' => 10,
                            'type' => 'fields'
                        ]
                    ]
                ]
            ]
        ]
    ]
];

return ($_ = array_replace_recursive($_, [
    'lot' => [
        'bar' => $bar,
        'desk' => $desk
    ]
]));