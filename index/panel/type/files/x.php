<?php

$trash = !empty($state->x->panel->trash) ? date('Y-m-d-H-i-s') : null;

$_['lot']['bar']['lot'][0]['lot']['folder']['skip'] = true;
$_['lot']['bar']['lot'][0]['lot']['link']['icon'] = 'M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z';
$_['lot']['bar']['lot'][0]['lot']['link']['skip'] = false;
$_['lot']['bar']['lot'][0]['lot']['link']['url'] = [
    'part' => 1,
    'path' => dirname($_['path']),
    'query' => x\panel\_query_set(),
    'task' => 'get'
];

Hook::set('_', function ($_) use ($state, $url) {
    $bounds = [];
    foreach (g(LOT . D . 'x', 'page', 1) as $k => $v) {
        if ('about.page' !== basename($k)) {
            continue;
        }
        $p = new Page($k);
        $title = strip_tags((string) ($p->title ?? ""));
        $key = strtr(x\panel\from\path(dirname($k)), [
            "\\" => '/'
        ]);
        foreach ((array) ($p['use'] ?? []) as $kk => $vv) {
            $bounds[strtr($kk, [
                "\\" => '/'
            ])][$key] = $title;
        }
    }
    $bound = $bounds[x\panel\from\path(LOT . D . 'x' . D . strtok($_['path'], '/'))] ?? [];
    if (!empty($bound)) {
        asort($bound);
        // Disable delete button where possible
        $index = $_['folder'] . D . 'index.php';
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$index]['tasks']['let']['active'] = false;
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$index]['tasks']['let']['description'] = ['Required by %s', implode(', ', $bound)];
        unset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$index]['tasks']['let']['url']);
    }
    if (is_file($file = ($folder = $_['folder']) . D . 'about.page')) {
        $page = new Page($file);
        $content = $page->content;
        // Make URL example(s) in content become usable
        $content = strtr($content, [
            '://127.0.0.1/panel/' => ':' . explode(':', $_['base'], 2)[1] . '/',
            '://127.0.0.1' => ':' . explode(':', $url . "", 2)[1]
        ]);
        $image = $use = "";
        if (isset($page->images)) {
            $image .= '<figure class="siema" style="border: 1px solid var(--stroke);">';
            foreach ($page->images as $v) {
                $image .= '<div>';
                $image .= '<img alt="" class="image" loading="lazy" src="' . $v . '">';
                $image .= '</div>';
            }
            $image .= '</figure>';
        } else if (isset($page->image)) {
            $image .= '<figure class="siema" style="border: 1px solid var(--stroke);">';
            $image .= '<img alt="" class="image" loading="lazy" src="' . $page->image . '">';
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
                        'query' => x\panel\_query_set(['tab' => ['info']]),
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
        // Add alert(s) from `about.page` file if any
        if (isset($page->alert) && is_array($page->alert)) {
            foreach ($page->alert as $k => $v) {
                foreach ($v as $kk => $vv) {
                    if (!is_string($vv)) {
                        continue; // TODO
                    }
                    $_['alert'][$k][$kk] = Hook::fire('page.description', [$vv], $page);
                }
            }
        }
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
            'stack' => 20
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

return $_;