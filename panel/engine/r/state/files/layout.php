<?php

// `http://127.0.0.1/panel/::g::/layout/1`
$lot = require __DIR__ . DS . '..' . DS . $_['layout'] . 's.php';
if (1 === count($_['chops'])) {
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