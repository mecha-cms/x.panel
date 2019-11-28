<?php

// `http://127.0.0.1/panel/::g::/layout/1`
$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';
if (1 === count($_['chops'])) {
    if (extension_loaded('zip')) {
        $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['import'] = [
            'title' => 'Import',
            'description' => 'Replace layout files with the new ones.',
            'type' => 'Link',
            'icon' => 'M11,4H13V16L18.5,10.5L19.92,11.92L12,19.84L4.08,11.92L5.5,10.5L11,16V4Z',
            'url' => $url . $_['/'] . '::s::' . $_['path'] . $url->query('&', ['layout' => 'blob.layout', 'tab' => false]) . $url->hash,
            'stack' => 40
        ];
        $lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['export'] = [
            'title' => 'Export',
            'description' => 'Download current layout files as a ZIP file.',
            'type' => 'Link',
            'icon' => 'M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z',
            'stack' => 50
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
        $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['legal'] = [
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