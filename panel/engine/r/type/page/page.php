<?php

$_ = require __DIR__ . DS . '..' . DS . 'page.php';

if (null !== State::get('x.art')) {
    // Add custom CSS and JS field(s)
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['art'] = [
        'lot' => [
            'fields' => [
                'type' => 'fields',
                'lot' => [
                    'css' => [
                        'title' => 'CSS',
                        'type' => 'source',
                        'name' => 'data[css]',
                        'hint' => ['%s goes here...', 'CSS'],
                        'value' => $page['css'],
                        'width' => true,
                        'height' => true,
                        'stack' => 10
                    ],
                    'js' => [
                        'title' => 'JS',
                        'type' => 'source',
                        'name' => 'data[js]',
                        'hint' => ['%s goes here...', 'JavaScript'],
                        'value' => $page['js'],
                        'width' => true,
                        'height' => true,
                        'stack' => 20
                    ]
                ],
                'stack' => 10
            ]
        ],
        'stack' => 30
    ];
}

if (null !== State::get('x.tag') && (
    's' === $_['task'] && substr_count($_['path'], '/') > 0 ||
    'g' === $_['task'] && substr_count($_['path'], '/') > 1
)) {
    // Convert list of tag(s) slug into list of tag(s) ID
    Hook::set(['do.page.get', 'do.page.set'], function($_) use($user) {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        // Post request only
        if ('post' !== $_['form']['type']) {
            return $_;
        }
        // Delete `kind.data` file if `data[kind]` field is empty
        if (empty($_['form']['lot']['data']['kind']) && is_file($f = Path::F($_['f']) . DS . 'kind.data')) {
            unlink($f);
            return $_;
        }
        if (!is_dir($d = LOT . DS . 'tag')) {
            mkdir($d, 0775, true);
        }
        $any = map(Tags::from(LOT . DS . 'tag', 'archive,page')->sort([-1, 'id']), function($tag) {
            return $tag->id;
        })[0] ?? 0; // Get the highest tag ID
        $out = [];
        ++$any; // New ID must be unique
        foreach (preg_split('/\s*,+\s*/', $_['form']['lot']['data']['kind']) as $v) {
            if ("" === $v) {
                continue;
            }
            if ($id = From::tag($v = To::kebab($v))) {
                $out[] = $id;
            } else {
                $out[] = $any;
                if (!is_dir($dd = $d . DS . $v)) {
                    mkdir($dd, 0775, true);
                }
                file_put_contents($f = $dd . '.page', To::page([
                    'title' => To::title($v),
                    'author' => $user->user ?? null
                ]));
                !defined('DEBUG') || !DEBUG && chmod($f, 0600);
                file_put_contents($ff = $dd . DS . 'id.data', $any);
                !defined('DEBUG') || !DEBUG && chmod($ff, 0600);
                file_put_contents($ff = $dd . DS . 'time.data', date('Y-m-d H:i:s'));
                !defined('DEBUG') || !DEBUG && chmod($ff, 0600);
                $_['alert']['info'][] = ['%s %s successfully created.', ['Tag', '<code>' . x\panel\from\path($f) . '</code>']];
                ++$any;
            }
        }
        sort($out);
        file_put_contents($f = Path::F($_['f']) . DS . 'kind.data', json_encode($out));
        !defined('DEBUG') || !DEBUG && chmod($f, 0600);
        return $_;
    }, 11);
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page']['lot']['fields']['lot']['tags'] = [
        'type' => 'query',
        'state' => [
            'max' => 12,
            'x' => true
        ],
        'name' => 'data[kind]',
        'value' => (new Page($_['f']))->query,
        'width' => true,
        'stack' => 41
    ];
}

$_['lot']['bar']['lot'][0]['lot']['s']['url'] = strtr(dirname($url->clean), ['::g::' => '::s::']) . $url->query('&', [
    'tab' => false,
    'type' => 'page/page'
]) . $url->hash;

$_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['s']['title'] = 's' === $_['task'] ? 'Publish' : 'Update';

return $_;
