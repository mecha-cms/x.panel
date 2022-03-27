<?php

$zip = extension_loaded('zip');

if (false === strpos($_['path'], '/')) {
    $_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['import'] = [
        'active' => $zip,
        'description' => 'Replace layout files with the new ones.',
        'icon' => 'M14,3L12,1H4A2,2 0 0,0 2,3V15A2,2 0 0,0 4,17H11V19L15,16L11,13V15H4V3H14M21,10V21A2,2 0 0,1 19,23H8A2,2 0 0,1 6,21V19H8V21H19V12H14V7H8V13H6V7A2,2 0 0,1 8,5H16L21,10Z',
        'stack' => 10.1,
        'title' => 'Replace',
        'type' => 'link',
        'url' => $zip ? [
            'part' => 0,
            'query' => [
                'chunk' => null,
                'deep' => null,
                'query' => null,
                'stack' => null,
                'tab' => null,
                'type' => 'blob/layout',
                'x' => null
            ],
            'task' => 'set'
        ] : null
    ];
    Hook::set('_', function($_) use($state, $url) {
        if (is_file($file = ($folder = $_['folder']) . D . 'about.page')) {
            $page = new Page($file);
            $content = $page->content;
            // Make URL example(s) in content become usable
            $content = strtr($content, [
                '://127.0.0.1/panel/' => ':' . explode(':', $_['base'], 2)[1] . '/',
                '://127.0.0.1' => ':' . explode(':', $url . "", 2)[1]
            ]);
            $image = $use = "";
            if (isset($page['images'])) {
                // TODO
            } else if (isset($page['image'])) {
                $image .= '<figure>';
                $image .= '<img alt="" class="image" loading="lazy" src="' . htmlspecialchars($page->image) . '">';
                $image .= '</figure>';
            }
            if (isset($page['use'])) {
                $uses = [];
                foreach ((array) $page['use'] as $k => $v) {
                    $p = is_file($kk = x\panel\to\path($k) . D . 'about.page') ? new Page($kk) : null;
                    $uses[$k] = [$v, $p ? ($p->title ?? $k) : $k, is_file(dirname($kk) . D . 'index.php')];
                }
                $links = [];
                foreach ($uses as $k => $v) {
                    if (!empty($v[2])) {
                        $links[strip_tags($v[1])] = '<li><a href="' . x\panel\to\link([
                            'part' => 1,
                            'path' => strtr($k, [
                                ".\\lot\\" => "",
                                "\\" => '/'
                            ]),
                            'query' => [
                                'chunk' => null,
                                'deep' => null,
                                'query' => null,
                                'stack' => null,
                                'tab' => ['info'],
                                'type' => null,
                                'x' => null
                            ],
                            'task' => 'get'
                        ]) . '">' . $v[1] . '</a>' . (0 === $v[0] ? ' (' . i('optional') . ')' : "") . '</li>';
                    } else {
                        $links[$v[1]] = '<li><s title="' . i('Missing %s extension.', $v[1]) . '">' . $v[1] . '</s>' . (0 === $v[0] ? ' (' . i('optional') . ')' : "") . '</li>';
                    }
                }
                ksort($links);
                $use .= '<details><summary><strong>' . i('Dependenc' . (1 === ($i = count($uses)) ? 'y' : 'ies')) . '</strong> (' . $i . ')</summary><ul>';
                $use .= implode("", $links);
                $use .= '</ul></details>';
            }
            // Hide some file(s) from the list
            foreach ([
                // About file
                $folder . D . 'about.page',
                // License file
                $folder . D . 'LICENSE'
            ] as $v) {
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$v]['skip'] = true;
            }
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['info'] = [
                'lot' => [
                    0 => [
                        'content' => $image . $content . $use,
                        'description' => $page->description,
                        'stack' => 10,
                        'title' => $page->title . ' <sup>' . $page->version . '</sup>',
                        'type' => 'content'
                    ]
                ],
                'stack' => 20,
                'title' => 'Info'
            ];
        }
        if (is_file($file = $folder . D . 'LICENSE')) {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['license'] = [
                'icon' => false === strpos(file_get_contents($file), '://fsf.org') ? 'M9 10A3.04 3.04 0 0 1 12 7A3.04 3.04 0 0 1 15 10A3.04 3.04 0 0 1 12 13A3.04 3.04 0 0 1 9 10M12 19L16 20V16.92A7.54 7.54 0 0 1 12 18A7.54 7.54 0 0 1 8 16.92V20M12 4A5.78 5.78 0 0 0 7.76 5.74A5.78 5.78 0 0 0 6 10A5.78 5.78 0 0 0 7.76 14.23A5.78 5.78 0 0 0 12 16A5.78 5.78 0 0 0 16.24 14.23A5.78 5.78 0 0 0 18 10A5.78 5.78 0 0 0 16.24 5.74A5.78 5.78 0 0 0 12 4M20 10A8.04 8.04 0 0 1 19.43 12.8A7.84 7.84 0 0 1 18 15.28V23L12 21L6 23V15.28A7.9 7.9 0 0 1 4 10A7.68 7.68 0 0 1 6.33 4.36A7.73 7.73 0 0 1 12 2A7.73 7.73 0 0 1 17.67 4.36A7.68 7.68 0 0 1 20 10Z' : null,
                'lot' => [
                    0 => [
                        'content' => '<pre class="is:text"><code class="txt">' . htmlspecialchars(file_get_contents($file)) . '</code></pre>',
                        'stack' => 10,
                        'type' => 'content'
                    ]
                ],
                'stack' => 30
            ];
        }
        return $_;
    }, 10.1);
}

return $_;