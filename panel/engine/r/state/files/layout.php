<?php

// `http://127.0.0.1/panel/::g::/layout/1`
$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';
if (1 === count($_['chops'])) {
    if (extension_loaded('zip')) {
        $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['import'] = [
            'title' => 'Replace',
            'description' => 'Replace layout files with the new ones.',
            'type' => 'Link',
            'icon' => 'M14,3L12,1H4A2,2 0 0,0 2,3V15A2,2 0 0,0 4,17H11V19L15,16L11,13V15H4V3H14M21,10V21A2,2 0 0,1 19,23H8A2,2 0 0,1 6,21V19H8V21H19V12H14V7H8V13H6V7A2,2 0 0,1 8,5H16L21,10Z',
            'url' => $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['layout' => 'blob.layout', 'tab' => false]) . $url->hash,
            'stack' => 10.1
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
