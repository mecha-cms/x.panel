<?php

if (is_dir(($file = $_['file'] ?? $_['folder']) ?? P) && 'get' === $_['task']) {
    $_['alert']['error'][$file] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($file) . '</code>', 'file']];
    $_['kick'] = [
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}

$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : null;

$fields = [];

if (is_file($file ?? P)) {
    $i = 10;
    foreach ((array) require x\panel\_cache_let($file) as $k => $v) {
        // Field type auto-detection
        $field = [
            'name' => 'state[' . $k . ']',
            'stack' => $i,
            'type' => 'text',
            'value' => is_array($v) ? json_encode($v) : s($v),
            'width' => true
        ];
        if (null === $v) {
            $field['hint'] = 'NULL';
            unset($field['value']);
        } else if (false === $v || true === $v) {
            $field['type'] = 'item';
            $field['lot'] = [
                'false' => 'No',
                'true' => 'Yes'
            ];
            unset($field['width']);
        } else if (is_float($v) || is_int($v)) {
            $field['type'] = 'number';
            $field['step'] = is_float($v) ? '0.1' : '1';
            unset($field['width']);
        } else if (is_string($v)) {
            $count = strlen($v);
            // `#ffffff`
            if ((4 === $count || 7 === $count) && '#' === $v[0] && (function_exists('ctype_xdigit') && ctype_xdigit(substr($v, 1)) || preg_match('/^#[a-f\d]+$/i', $v))) {
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
            // `1.0.0` <https://semver.org#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string>
            } else if ($count >= 5 && preg_match('/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/', $v)) {
                $field['type'] = 'version';
                unset($field['width']);
            }
        }
        $fields[$k] = $field;
        $i += 10;
    }
}

$back = trim(dirname($_['path']), '.');
$kick = $state->x->panel->kick ?? 'get/asset/1';

// Check if redirect target is relative to the panel base URL
if (0 !== strpos($kick, '/')) {
    // Remove task part from redirect target path
    $kick = preg_replace('/^(?:[gls]et|fire\/[^\/]+)\//', '/', $kick);
// Redirect target is a full URL
} else if (false !== strpos($kick, '://')) {
    // Set to default back link
    $kick = 'asset/1';
}

$end = array_slice(explode('/', "" !== $back ? $back : $kick), -1)[0];
$has_part = is_numeric($end) && '0' !== $end && '-' !== $end[0];

$bar = [
    // `bar`
    'lot' => [
        // `links`
        0 => [
            'lot' => [
                'link' => [
                    'skip' => false,
                    'url' => [
                        'part' => $has_part ? 0 : 1,
                        'path' => 'get' === $_['task'] ? trim("" !== $back ? $back : $kick, '/') : $_['path'],
                        'query' => x\panel\_query_set()
                    ]
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
            ],
            'values' => [
                'file' => [
                    'name' => is_file($file) ? basename($file) : null,
                    'seal' => '0600'
                ],
                'kick' => $_GET['kick'] ?? null,
                'token' => $_['token'],
                'trash' => $trash,
                'type' => $_['type']
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