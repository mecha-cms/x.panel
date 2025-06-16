<?php

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $chunk = $_POST['data']['chunk'] ?? $_POST['page']['chunk'] ?? null;
    $x = basename(strip_tags($_POST['page']['x'] ?? 'page'));
    // Having chunk value less than `1` will not create a `chunk.data` file. Instead, it will create a placeholder page
    // to hide the pages.
    if (is_int($chunk) && $chunk < 1 && is_dir($folder)) {
        unset($_POST['data']['chunk'], $_POST['page']['chunk']);
        file_put_contents($ff = $folder . D . '.' . $x, "");
        chmod($ff, 0600);
    } else if (is_file($ff = $folder . D . '.' . $x)) {
        unlink($ff);
    }
}

$count = ($f = $_['file']) ? q(g(dirname($f) . D . pathinfo($f, PATHINFO_FILENAME), 'page')) : 0;
$folder = $f ? dirname($f) . D . pathinfo($f, PATHINFO_FILENAME) : P;

$layouts = [];
$layouts_active = !!array_filter(glob(LOT . D . 'y' . D . '*' . D . '{page,pages}', GLOB_BRACE | GLOB_ONLYDIR), function ($v) {
    return is_file(dirname($v) . D . 'index.php');
});

if ($layouts_active) {
    foreach (glob(LOT . D . 'y' . D . '*' . D . '{page,pages}' . D . '*.php', GLOB_BRACE | GLOB_NOSORT) as $v) {
        if (!is_file(($d = dirname($v, 2)) . D . 'index.php')) {
            continue;
        }
        $n = substr($v, strlen($d) + 1, -4);
        $layouts[$n] = $n;
    }
}

$chunk = $state->x->page->page->chunk ?? 5;
$deep = $state->x->page->page->deep ?? 0;
$sort = $state->x->page->page->sort ?? [1, 'path'];

$page_chunk = $page['chunk'] ?? null;
$page_deep = $page['deep'] ?? null;
$page_sort = $page['sort'] ?? null;
$page_state = (array) ($page['state'] ?? []);

Hook::set('_', function ($_) {
    // Hide the extension and layout option(s) if it is empty, unless there is a `skip` property that was explicitly set
    foreach (['x', 'y'] as $k) {
        if (!isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['state']['lot']['fields']['lot']['state.' . $k])) {
            continue;
        }
        if (!isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['state']['lot']['fields']['lot']['state.' . $k]['skip'])) {
            $default = empty($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['state']['lot']['fields']['lot']['state.' . $k]['lot']);
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['state']['lot']['fields']['lot']['state.' . $k]['skip'] = $default;
        }
    }
    return $_;
}, 20);

return x\panel\type\page\page(array_replace_recursive($_, [
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
                                                        'layout' => [
                                                            'lot' => $layouts,
                                                            'skip' => !$layouts_active,
                                                            'value' => $page['layout'] ?? ""
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'state' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    // `fields`
                                                    'lot' => [
                                                        'chunk' => [
                                                            'active' => $count > 0, // Disable this field if child page(s) count is `0`
                                                            'hint' => $chunk,
                                                            'value' => is_file($folder . D . '.archive') || is_file($folder . D . '.page') ? 0 : ($page_chunk === $chunk ? null : $page_chunk)
                                                        ],
                                                        'deep' => [
                                                            'active' => $count > 0,
                                                            'hint' => $deep,
                                                            'value' => $page_deep === $deep ? null : $page_deep,
                                                        ],
                                                        'sort' => [
                                                            'active' => $count > 0,
                                                            'value' => $page_sort === $sort ? null : json_encode($page_sort)
                                                        ],
                                                        'state.x' => [
                                                            'skip' => null,
                                                            'values' => $page_state['x'] ?? []
                                                        ],
                                                        'state.y' => [
                                                            'skip' => null,
                                                            'values' => $page_state['y'] ?? []
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
    ]
]));