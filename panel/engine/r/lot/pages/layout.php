<?php

// `http://127.0.0.1/panel/::g::/layout/1`

$_ = require __DIR__ . DS . '..' . DS . 'index.php';

$zip = extension_loaded('zip');

if (1 === count($_['chop'])) {
    $_['lot']['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['import'] = [
        'active' => $zip,
        'title' => 'Replace',
        'description' => 'Replace layout files with the new ones.',
        'type' => 'link',
        'icon' => 'M14,3L12,1H4A2,2 0 0,0 2,3V15A2,2 0 0,0 4,17H11V19L15,16L11,13V15H4V3H14M21,10V21A2,2 0 0,1 19,23H8A2,2 0 0,1 6,21V19H8V21H19V12H14V7H8V13H6V7A2,2 0 0,1 8,5H16L21,10Z',
        'url' => $zip ? $_['/'] . '/::s::/' . $_['path'] . $url->query('&', [
            'tab' => false,
            'type' => 'blob/layout'
        ]) . $url->hash : null,
        'stack' => 10.1
    ];
    Hook::set('_', function($_) {
        extract($GLOBALS, EXTR_SKIP);
        if (is_file($f = ($d = $_['f']) . DS . 'about.page')) {
            $page = new Page($f);
            $content = $page->content;
            // Make URL example(s) in content become usable
            $content = strtr($content, [
                '://127.0.0.1/panel/' => ':' . explode(':', $_['/'], 2)[1] . '/',
                '://127.0.0.1' => ':' . explode(':', $url . "", 2)[1]
            ]);
            $use = "";
            if (isset($page['use'])) {
                $uses = [];
                foreach ((array) $page['use'] as $k => $v) {
                    $p = is_file($kk = x\panel\to\path($k) . DS . 'about.page') ? new Page($kk) : null;
                    $uses[$k] = [$v, $p ? ($p->title ?? $k) : $k, is_file(dirname($kk) . DS . 'index.php')];
                }
                $links = [];
                foreach ($uses as $k => $v) {
                    if (!empty($v[2])) {
                        $links[$v[1]] = '<li><a href="' . $_['/'] . '/::g::/' . strtr($k, [
                            ".\\lot\\" => "",
                            "\\" => '/'
                        ]) . '/1?tab[0]=info">' . $v[1] . '</a>' . (0 === $v[0] ? ' (' . i('optional') . ')' : "") . '</li>';
                    } else {
                        $links[$v[1]] = '<li><s title="' . i('Missing %s extension.', $v[1]) . '">' . $v[1] . '</s></li>';
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
                $d . DS . 'about.page',
                // License file
                $d . DS . 'LICENSE'
            ] as $p) {
                $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$p]['skip'] = true;
            }
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['info'] = [
                'title' => 'Info',
                'lot' => [
                    0 => [
                        'title' => $page->title . ' <sup>' . $page->version . '</sup>',
                        'description' => $page->description,
                        'type' => 'content',
                        'content' => $content . $use,
                        'stack' => 10
                    ]
                ],
                'stack' => 20
            ];
        }
        if (is_file($f = $d . DS . 'LICENSE')) {
            $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['license'] = [
                'icon' => false === strpos(file_get_contents($f), '://fsf.org') ? 'M9 10A3.04 3.04 0 0 1 12 7A3.04 3.04 0 0 1 15 10A3.04 3.04 0 0 1 12 13A3.04 3.04 0 0 1 9 10M12 19L16 20V16.92A7.54 7.54 0 0 1 12 18A7.54 7.54 0 0 1 8 16.92V20M12 4A5.78 5.78 0 0 0 7.76 5.74A5.78 5.78 0 0 0 6 10A5.78 5.78 0 0 0 7.76 14.23A5.78 5.78 0 0 0 12 16A5.78 5.78 0 0 0 16.24 14.23A5.78 5.78 0 0 0 18 10A5.78 5.78 0 0 0 16.24 5.74A5.78 5.78 0 0 0 12 4M20 10A8.04 8.04 0 0 1 19.43 12.8A7.84 7.84 0 0 1 18 15.28V23L12 21L6 23V15.28A7.9 7.9 0 0 1 4 10A7.68 7.68 0 0 1 6.33 4.36A7.73 7.73 0 0 1 12 2A7.73 7.73 0 0 1 17.67 4.36A7.68 7.68 0 0 1 20 10Z' : null,
                'lot' => [
                    0 => [
                        'type' => 'content',
                        'content' => '<pre class="is:text"><code class="txt">' . htmlspecialchars(file_get_contents($f)) . '</code></pre>',
                        'stack' => 10
                    ]
                ],
                'stack' => 30
            ];
        }
        $git = $page->git ?? 'mecha-cms/' . ($n = 'layout.' . To::kebab($page->title));
        $version_current = explode('.', $page->version);
        $version_next = explode('.', Cache::live($n, function() use($git) {
            return fetch('https://mecha-cms.com/git/version/' . $git, [
                'user-agent' => 'Mecha/' . VERSION
            ]);
        }, '1 day'));
        $version = implode('.', $version_next);
        // Check for major update
        if (isset($version_current[0]) && isset($version_next[0]) && (int) $version_current[0] < (int) $version_next[0]) {
            $_['alert']['info'][$d] = ['%s has been released. You have to update it manually. This version may not work properly with your current core version.', [$page->title . ' ' . $version]];
        // Check for minor update
        } else if (isset($version_current[1]) && isset($version_next[1]) && (int) $version_current[1] < (int) $version_next[1]) {
            $_['alert']['info'][$d] = ['%s has been released.', [$page->title . ' ' . $version]];
        // Check for patch update
        } else if (isset($version_current[2]) && isset($version_next[2]) && (int) $version_current[2] < (int) $version_next[2]) {
            $_['alert']['info'][$d] = ['%s has been released. Should be safe to update now.', [$page->title . ' ' . $version]];
        }
        return $_;
    }, 10.1);
}

return $_;