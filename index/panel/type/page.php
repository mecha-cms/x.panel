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

$folder_parent = $file->exist ? $file->parent->path . D . $file->name : false;
$has_folder = $file->exist ? is_dir($folder_parent) : false;
$page = $file->exist ? new Page($file->path) : new Page;
$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : null;

// Sanitize the form data
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    // Get current time
    $time = $_SERVER['REQUEST_TIME'] ?? time();
    // Set `time` data based on the current `time` data or use the `$time` value
    $_POST['data']['time'] = (string) new Time($_POST['data']['time'] ?? $time);
    // Remove all HTML tag(s) from the `author` data
    $_POST['page']['author'] = strip_tags($_POST['page']['author'] ?? "");
    if (isset($_POST['page']['id'])) {
        // Remove all HTML tag(s) from the `id` data if any
        $_POST['page']['id'] = strip_tags($_POST['page']['id']);
    }
    if (isset($_POST['page']['link'])) {
        // Remove all HTML tag(s) from the `link` data if any
        $_POST['page']['link'] = strip_tags($_POST['page']['link']);
    }
    if (isset($_POST['page']['description'])) {
        // Remove all block HTML tag(s) from the `description` data if any
        $_POST['page']['description'] = x\panel\to\w($_POST['page']['description'], ['a' => true]);
        // Limit `description` data value to 1275 character(s)
        $_POST['page']['description'] = To::description($_POST['page']['description'], 1275);
    }
    if (isset($_POST['page']['title'])) {
        // Remove all block HTML tag(s) from the `title` data if any
        $_POST['page']['title'] = x\panel\to\w($_POST['page']['title']);
        // Limit `title` data value to 255 character(s)
        $_POST['page']['title'] = To::description($_POST['page']['title'], 255);
    }
    // Make sure to have a file extension
    $_POST['page']['x'] = strip_tags($_POST['page']['x'] ?? 'page');
    // Make sure to have a file name
    if (empty($_POST['page']['name'])) {
        $name = To::kebab($_POST['page']['title'] ?? "");
        $_POST['page']['name'] = "" !== $name ? $name : date('Y-m-d-H-i-s', $time);
    }
    // Detect `time` pattern in the page’s file name and remove the `time` data if matched
    $n = $_POST['page']['name'];
    if (
        is_string($n) && (
            // `2017-04-21.page`
            2 === substr_count($n, '-') ||
            // `2017-04-21-14-25-00.page`
            5 === substr_count($n, '-')
        ) &&
        is_numeric(strtr($n, ['-' => ""])) &&
        preg_match('/^[1-9]\d{3,}-(0\d|1[0-2])-(0\d|[1-2]\d|3[0-1])(-([0-1]\d|2[0-4])(-([0-5]\d|60)){2})?$/', $n)
    ) {
        unset($_POST['data']['time']);
    }
}

$session = $_SESSION['_']['files'] ?? [];

Hook::set('_', function ($_) use ($page, $session, $trash, $url) {
    $apart = [];
    // Collect form name(s) from the hidden field(s)
    if (!empty($_['lot']['desk']['lot']['form']['values'])) {
        foreach ($_['lot']['desk']['lot']['form']['values'] as $k => $v) {
            if ('data' === $k && is_array($v)) {
                $apart = array_replace($apart, $v);
                continue;
            }
            if (0 === strpos($k, 'data[')) {
                $apart[substr(explode(']', $k, 2)[0], 5)] = 1;
            }
        }
    }
    // Collect form name(s) from the field(s) on each tab
    if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'])) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot'] as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (empty($vv['fields']['lot'])) {
                    continue;
                }
                foreach ($vv['fields']['lot'] as $kkk => $vvv) {
                    $vvvv = $vvv['name'] ?? $kkk;
                    if (0 === strpos($vvvv, 'data[')) {
                        $apart[substr(explode(']', $vvvv, 2)[0], 5)] = 1;
                    }
                }
            }
        }
        $count = 0;
        $files = [];
        $token = $_['token'] ?? null;
        if ($page->exist) {
            $apart = array_replace(From::page(file_get_contents($path = $page->path)), $apart);
            foreach (g(dirname($path) . D . pathinfo($path, PATHINFO_FILENAME), 'data') as $k => $v) {
                $p = strtr($k, [
                    LOT . D => "",
                    D => '/'
                ]);
                if (!$skip = isset($apart[basename($k, '.data')])) {
                    ++$count;
                }
                $files[$k] = [
                    'current' => !empty($session[$k]),
                    'description' => size(filesize($k)),
                    'path' => $k,
                    'skip' => $skip,
                    'tags' => [
                        'x:data' => true
                    ],
                    'tasks' => [
                        'get' => [
                            'description' => 'Edit',
                            'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                            'stack' => 10,
                            'title' => 'Edit',
                            'url' => [
                                'part' => 0,
                                'path' => $p,
                                'query' => x\panel\_query_set(),
                                'task' => 'get'
                            ]
                        ],
                        'let' => [
                            'title' => 'Delete',
                            'description' => 'Delete',
                            'stack' => 20,
                            'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                            'url' => [
                                'part' => 0,
                                'path' => $p,
                                'query' => x\panel\_query_set([
                                    'tab' => ['data'],
                                    'token' => $token,
                                    'trash' => $trash
                                ]),
                                'task' => 'let'
                            ]
                        ]
                    ],
                    'title' => S . ($n = basename($k)) . S,
                    'type' => 'file',
                    'url' => [
                        'part' => 0,
                        'path' => $p,
                        'query' => x\panel\_query_set(),
                        'task' => 'get'
                    ]
                ];
                if (isset($session[$k])) {
                    unset($_SESSION['_']['files'][$k]);
                }
            }
            ksort($files);
        }
        if ($count) {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['lot']['files']['lot'] = $files;
        } else {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['lot']['files']['skip'] = true;
        }
    }
    return $_;
}, 20);

$GLOBALS['page'] = $page;

$page_type = 'text/html' === $page->type ? 'HTML' : $page->type;

return x\panel\type\page(array_replace_recursive($_, [
    'lot' => [
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
                                        'page' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    // `fields`
                                                    'lot' => [
                                                        'title' => ['value' => $page['title']],
                                                        'name' => ['value' => $page->name],
                                                        'content' => [
                                                            'state' => $page_type ? [
                                                                'source' => ['type' => $page_type]
                                                            ] : null,
                                                            'value' => $page['content']
                                                        ],
                                                        'description' => ['value' => $page['description']],
                                                        'author' => ['value' => $page['author'] ?? $user->user],
                                                        'type' => [
                                                            'lot' => ['Markdown' => isset($state->x->markdown) ? 'Markdown' : null],
                                                            'value' => $page_type
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'data' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    // `fields`
                                                    'lot' => [
                                                        'link' => ['value' => $page['link']],
                                                        'time' => ['value' => $page['time']],
                                                        'files' => [
                                                            // `field`
                                                            'lot' => [
                                                                'tasks' => [
                                                                    // `tasks/link`
                                                                    'lot' => [
                                                                        'set' => [
                                                                            'active' => $has_folder,
                                                                            'description' => $has_folder ? ['New %s', 'Data'] : ($folder_parent ? ['Missing folder %s', x\panel\from\path($folder_parent)] : null),
                                                                            'url' => $has_folder ? [] : null
                                                                        ]
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'values' => ['trash' => $trash]
                ]
            ]
        ]
    ]
]));