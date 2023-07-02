<?php

$_ = x\panel\type\files\y(array_replace_recursive($_, [
    'lot' => [
        'bar' => [
            // `bar`
            'lot' => [
                0 => [
                    // `links`
                    'lot' => [
                        'link' => ['skip' => false]
                    ]
                ]
            ]
        ]
    ]
]));

if (!is_dir($folder = $_['folder'] ?? P)) {
    return $_;
}

if (is_file($file = $folder . D . 'about.page')) {
    $page = new Page($file);
    $page_alert = $page->alert;
    $page_content = $page->content;
    $page_description = $page->description;
    $page_image = $page->image;
    $page_images = (array) $page->images;
    $page_title = $page->title;
    $page_version = $page->version;
    // Add alert(s) from `about.page` file if any
    if ($page_alert) {
        if (is_string($page_alert)) {
            $page_alert = ['info' => $page_alert];
        }
        foreach ($page_alert as $k => $v) {
            foreach ((array) $v as $kk => $vv) {
                if (!is_string($vv) || "" === trim($vv)) {
                    continue;
                }
                $_['alert'][$k][$kk] = Hook::fire('page.description', [$vv], $page);
            }
        }
    }
    // Make URL example(s) in content usable
    $page_content = strtr($page_content ?? "", [
        '://127.0.0.1/panel/' => ':' . explode(':', $_['base'], 2)[1] . '/',
        '://127.0.0.1' => ':' . explode(':', $url . "", 2)[1]
    ]);
    $page_content_enter = $page_content_exit = "";
    if ($page_images) {
        $page_content_enter .= '<figure class="figure siema">';
        foreach ($page_images as $v) {
            $page_content_enter .= '<div>';
            $page_content_enter .= '<img alt="" class="image" src="' . eat($v) . '">';
            $page_content_enter .= '</div>';
        }
        $page_content_enter .= '</figure>';
    } else if ($page_image) {
        $page_content_enter .= '<figure class="figure siema">';
        $page_content_enter .= '<div>';
        $page_content_enter .= '<img alt="" src="' . eat($page_image) . '">';
        $page_content_enter .= '</div>';
        $page_content_enter .= '</figure>';
    }
    if (is_file($file = $folder . D . 'composer.json')) {
        $list = [];
        $meta = json_decode(file_get_contents($file), true);
        if (!empty($meta['require'])) {
            foreach ($meta['require'] as $k => $v) {
                $n = basename($k);
                if (0 === strpos($n, 'x.')) {
                    if (is_file($f = dirname($folder, 2) . D . 'x' . D . substr($n, 2) . D . 'index.php')) {
                        $list[0][$k] = '<li><a href="' . x\panel\to\link([
                            'part' => 1,
                            'path' => 'x/' . substr($n, 2),
                            'query' => x\panel\_query_set(['tab' => ['info']]),
                            'task' => 'get'
                        ]) . '" target="_blank">' . $k . '</a></li>';
                    } else {
                        $list[0][$k] = '<li><a href="https://packagist.org/packages/' . $k . '" target="_blank">' . $k . '</a> ' . i('Missing.') . '</li>';
                    }
                } else if (0 === strpos($n, 'y.')) {
                    if (is_file(dirname($folder) . D . substr($n, 2) . D . 'index.php')) {
                        $list[0][$k] = '<li><a href="' . x\panel\to\link([
                            'part' => 1,
                            'path' => 'y/' . substr($n, 2),
                            'query' => x\panel\_query_set(['tab' => ['info']]),
                            'task' => 'get'
                        ]) . '" target="_blank">' . $k . '</a></li>';
                    } else {
                        $list[0][$k] = '<li><a href="https://packagist.org/packages/' . $k . '" target="_blank">' . $k . '</a> ' . i('Missing.') . '</li>';
                    }
                }
            }
        }
        if (!empty($meta['suggest'])) {
            foreach ($meta['suggest'] as $k => $v) {
                if (isset($meta['require'][$k])) {
                    continue;
                }
                $n = basename($k);
                if (0 === strpos($n, 'x.')) {
                    if (is_file($f = dirname($folder, 2) . D . 'x' . D . substr($n, 2) . D . 'index.php')) {
                        continue;
                    }
                    $list[1][$k] = '<li><a href="https://packagist.org/packages/' . $k . '" target="_blank">' . $k . '</a> ' . i('Missing.') . '</li>';
                } else if (0 === strpos($n, 'y.')) {
                    if (is_file(dirname($folder) . D . substr($n, 2) . D . 'index.php')) {
                        continue;
                    }
                    $list[1][$k] = '<li><a href="https://packagist.org/packages/' . $k . '" target="_blank">' . $k . '</a> ' . i('Missing.') . '</li>';
                }
            }
        }
        $page_content_exit .= '<hr>';
        $page_content_exit .= x\panel\lot\type([
            'lot' => [
                'row' => [
                    'lot' => [
                        'columns' => [
                            'lot' => [
                                'require' => [
                                    'content' => !empty($list[0]) ? '<ul>' . implode("", $list[0]) . '</ul>' : '<p role="status">' . i('None.') . '</p>',
                                    'level' => 4,
                                    'stack' => 10,
                                    'title' => 'Requires',
                                    'type' => 'column'
                                ],
                                'suggest' => [
                                    'content' => !empty($list[1]) ? '<ul>' . implode("", $list[1]) . '</ul>' : '<p role="status">' . i('None.') . '</p>',
                                    'level' => 4,
                                    'stack' => 10,
                                    'title' => 'Suggests',
                                    'type' => 'column'
                                ]
                            ],
                            'stack' => 10,
                            'type' => 'columns'
                        ]
                    ],
                    'stack' => 10,
                    'type' => 'row'
                ]
            ],
            'tags' => ['p' => true],
            'type' => 'rows'
        ], 0);
    }
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['info']['lot'][0]['content'] = $page_content_enter . $page_content . $page_content_exit;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['info']['lot'][0]['description'] = $page_description;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['info']['lot'][0]['title'] = $page_title . ' <sup>' . $page_version . '</sup>';
    // Hide some file(s) from the list
    foreach ([
        // Parent folder
        $folder,
        // About file
        $folder . D . 'about.page',
        // License file
        $folder . D . 'LICENSE',
        // Custom stack data
        $folder . D . basename($folder)
    ] as $v) {
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$v]['skip'] = true;
    }
}

if (is_file($file = $folder . D . 'LICENSE')) {
    $content = file_get_contents($file);
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['license']['icon'] = false === strpos($content, '://fsf.org') ? 'M9 10A3.04 3.04 0 0 1 12 7A3.04 3.04 0 0 1 15 10A3.04 3.04 0 0 1 12 13A3.04 3.04 0 0 1 9 10M12 19L16 20V16.92A7.54 7.54 0 0 1 12 18A7.54 7.54 0 0 1 8 16.92V20M12 4A5.78 5.78 0 0 0 7.76 5.74A5.78 5.78 0 0 0 6 10A5.78 5.78 0 0 0 7.76 14.23A5.78 5.78 0 0 0 12 16A5.78 5.78 0 0 0 16.24 14.23A5.78 5.78 0 0 0 18 10A5.78 5.78 0 0 0 16.24 5.74A5.78 5.78 0 0 0 12 4M20 10A8.04 8.04 0 0 1 19.43 12.8A7.84 7.84 0 0 1 18 15.28V23L12 21L6 23V15.28A7.9 7.9 0 0 1 4 10A7.68 7.68 0 0 1 6.33 4.36A7.73 7.73 0 0 1 12 2A7.73 7.73 0 0 1 17.67 4.36A7.68 7.68 0 0 1 20 10Z' : null;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['license']['lot'][0]['content'] = '<pre class="is:text"><code class="txt">' . preg_replace('/&lt;(https?:\/\/\S+?)&gt;/', '&lt;<a href="$1" rel="nofollow" target="_blank">$1</a>&gt;', htmlspecialchars($content)) . '</code></pre>';
}

return $_;