<?php

// `http://127.0.0.1/panel/::g::/layout/1`
$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';
if (1 === count($_['chops'])) {
    if (extension_loaded('zip')) {
        $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['import'] = [
            'description' => 'Replace layout files with the new ones.',
            'type' => 'Link',
            'icon' => 'M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z',
            'url' => $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['layout' => 'blob.layout', 'tab' => false]) . $url->hash,
            'stack' => 40
        ];
    }
    if (is_file($f = ($d = $_['f']) . DS . 'about.page')) {
        $page = new Page($f);
        // Hide some file(s) from the list
        foreach ([
            // About file
            $d . DS . 'about.page',
            // License file
            $d . DS . 'LICENSE'
        ] as $p) {
            $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$p]['hidden'] = true;
        }
        $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['info'] = [
            'title' => 'Info',
            'lot' => [
                0 => [
                    'title' => $page->title . ' <sup>' . $page->version . '</sup>',
                    'description' => _\lot\x\panel\h\w($page->description, 'a'),
                    'type' => 'Section',
                    'content' => $page->content,
                    'stack' => 10
                ]
            ],
            'stack' => 20
        ];
    }
    if (is_file($f = $d . DS . 'LICENSE')) {
        $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['license'] = [
            'lot' => [
                0 => [
                    'type' => 'Section',
                    'content' => '<pre class="is:text"><code class="txt">' . htmlspecialchars(file_get_contents($f)) . '</code></pre>',
                    'stack' => 10
                ]
            ],
            'stack' => 30
        ];
    }
}

return $lot;