<?php

$_ = x\panel\type\files($_);

if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'])) {
    return $_;
}

if (!empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['skip'])) {
    return $_;
}

if (!isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type'])) {
    return $_;
}

if (0 !== strpos($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['type'] . '/', 'files/')) {
    return $_;
}

if (!is_dir($folder = $_['folder'] ?? P)) {
    return $_;
}

$chunk = $_['chunk'] ?? 20;
$count = 0;
$deep = $_['deep'] ?? 0;
$part = $_['part'] ?? 1;
$query = strtolower(s($_['query']['query'] ?? ""));
$token = $_['token'] ?? null;
$x = $_['x'] ?? null;

$files = [[], []];
foreach ($query ? k($folder, $x, $deep, preg_split('/\s+/', $query)) : g($folder, $x, $deep) as $k => $v) {
    $n = basename($k);
    if ('.' === $n[0] || isset($files[$v][$k])) {
        continue; // Skip hidden file(s) and folder(s)
    }
    $files[$v][$k] = $v;
    ++$count;
}

if ("" === $query) {
    uksort($files[0], 'strnatcmp');
    uksort($files[1], 'strnatcmp');
}

$files = array_chunk(array_merge($files[0], $files[1]), $chunk, true)[$part - 1] ?? [];
$prefix = strlen($p = $_['path'] ?? "") + 1;
$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : false;
foreach ($files as $k => $v) {
    $path = strtr($k, [
        LOT . D => "",
        D => '/'
    ]);
    $n = basename($k);
    $title = substr($path, $prefix);
    $files[$k] = [
        'current' => !empty($_SESSION['_'][0 === $v ? 'folders' : 'files'][$k]),
        'description' => 0 === $v ? ['Open %s', 'Folder'] : S . size(filesize($k)) . S,
        'link' => 1 === $v ? To::URL($k) : null,
        'tags' => ['x:' . pathinfo($n, PATHINFO_EXTENSION) => 1 === $v],
        'tasks' => [
            'get' => [
                'description' => 'Edit',
                'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                'stack' => 10,
                'title' => 'Edit',
                'url' => [
                    'part' => 0,
                    'path' => $path,
                    'query' => x\panel\_query_set([
                        'type' => '0' !== ($n[0] ?? '0') && strspn($n, '0123456789') === strlen($n) ? (0 === $v ? 'folder' : 'file') : null
                    ]),
                    'task' => 'get'
                ]
            ],
            'let' => [
                'description' => 'Delete',
                'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                'stack' => 20,
                'title' => 'Delete',
                'url' => [
                    'part' => 0,
                    'path' => $path,
                    'query' => x\panel\_query_set([
                        'token' => $token,
                        'trash' => $trash,
                        'type' => '0' !== ($n[0] ?? '0') && strspn($n, '0123456789') === strlen($n) ? (0 === $v ? 'folder' : 'file') : null
                    ]),
                    'task' => 'let'
                ]
            ]
        ],
        'title' => S . ("" !== $query ? preg_replace('/' . x($query) . '/i', '<mark>$0</mark>', strip_tags($title)) : $title) . S,
        'type' => 0 === $v ? 'folder' : 'file',
        'url' => 0 === $v ? [
            'part' => 1,
            'path' => $path,
            'query' => x\panel\_query_set(),
            'task' => 'get'
        ] : null
    ];
    if (isset($_SESSION['_'][0 === $v ? 'folders' : 'files'][$k])) {
        unset($_SESSION['_'][0 === $v ? 'folders' : 'files'][$k]);
    }
}

if (substr_count($p, '/') > 0 && $part <= 1) {
    $files = [$folder => [
        'description' => ['Go to %s', 'Parent'],
        'tasks' => [
            'get' => [
                'description' => 'Edit Parent Folder',
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
                'description' => 'Delete Parent Folder',
                'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                'stack' => 20,
                'title' => 'Delete',
                'url' => [
                    'part' => 0,
                    'path' => $p,
                    'query' => x\panel\_query_set([
                        'token' => $token,
                        'trash' => $trash
                    ]),
                    'task' => 'let'
                ]
            ]
        ],
        'title' => S . '..' . S,
        'type' => 'folder',
        'url' => [
            'part' => 1,
            'path' => dirname($p),
            'query' => x\panel\_query_set(),
            'task' => 'get'
        ]
    ]] + $files;
}

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] = $files;
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['pager']['chunk'] = $_['chunk'] = $chunk;
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['pager']['count'] = $_['count'] = $count;

return $_;