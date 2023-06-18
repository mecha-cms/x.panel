<?php

if (!$file->exist && 'get' === $_['task']) {
    $_['alert']['error'][] = ['Path %s is not a %s.', ['<code>' . x\panel\from\path($file->path ?? $folder->path ?? P) . '</code>', 'file']];
    $_['kick'] = [
        'part' => 1,
        'path' => dirname($_['path']),
        'query' => x\panel\_query_set(),
        'task' => 'get'
    ];
    return $_;
}

if ($file->exist && 'set' === $_['task']) {
    $_['kick'] = ['task' => 'get'];
    return $_;
}

$fields = [];
$stack = 10;
$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : null;

// TODO: Sanitize the form data
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    // test($_POST['state']); exit;
}

if ($file->exist) {
    foreach ((array) require x\panel\_cache_let($file->path) as $k => $v) {
        // Auto-detect field type(s)
        $field = [
            'name' => 'state[' . $k . ']',
            'stack' => $stack,
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
        } else if (is_array($v)) {
            // TODO
        } else if (is_float($v) || is_int($v)) {
            $field['type'] = 'number';
            $field['step'] = is_float($v) ? '0.1' : '1';
            unset($field['width']);
        } else if (is_string($v)) {
            $count = strlen($v);
            // `http://example.com`
            if (0 === strpos($v, 'http://') || 0 === strpos($v, 'https://')) {
                $field['type'] = 'link';
            // `/foo/bar/baz`
            } else if (0 === strpos($v, '/') && preg_match('/^(\/[._]?[a-z\d]+(-[a-z\d]+)*)+$/', $v)) {
                $field['type'] = 'route';
            // `#ffffff`
            } else if ((4 === $count || 7 === $count) && '#' === $v[0] && (function_exists('ctype_xdigit') && ctype_xdigit(substr($v, 1)) || preg_match('/^#[a-f\d]+$/i', $v))) {
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
        $stack += 10;
    }
}

$back = trim(dirname($_['path'] ?? ""), '.');
$kick = $state->x->panel->kick ?? 'get/asset/1';

// Check if the redirect target is relative to the panel base URL
if (0 !== strpos($kick, '/')) {
    // Remove task part from redirect target path
    $kick = preg_replace('/^(?:[gls]et|fire\/[^\/]+)\//', '/', $kick);
// The redirect target is a full URL
} else if (false !== strpos($kick, '://')) {
    // Set to default
    $kick = 'asset/1';
}

$end = array_slice(explode('/', "" !== $back ? $back : $kick), -1)[0];
$has_part = is_numeric($end) && '0' !== $end && '-' !== $end[0];

return x\panel\type\state(array_replace_recursive($_, [
    'lot' => [
        'bar' => [
            // `bar`
            'lot' => [
                0 => [
                    // `links`
                    'lot' => [
                        'link' => [
                            'url' => [
                                'part' => $has_part ? 0 : 1,
                                'path' => 'get' === $_['task'] ? trim("" !== $back ? $back : $kick, '/') : $_['path']
                            ]
                        ],
                        'folder' => ['skip' => true]
                    ]
                ]
            ]
        ],
        'desk' => [
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
                                    'lot' => [
                                        'file' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => ['lot' => $fields]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'values' => [
                        'file' => ['name' => $file->name(true)]
                    ]
                ]
            ]
        ]
    ]
]));