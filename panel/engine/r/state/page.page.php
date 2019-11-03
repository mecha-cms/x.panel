<?php

$lot = require __DIR__ . DS . 'page.php';

if (null !== State::get('x.art')) {
    // Add custom CSS and JS field(s)
    $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['art'] = [
        'lot' => [
            'fields' => [
                'type' => 'Fields',
                'lot' => [
                    'css' => [
                        'title' => 'CSS',
                        'type' => 'Source',
                        'name' => 'data[css]',
                        'alt' => ['%s goes here...', 'CSS'],
                        'value' => $page['css'],
                        'width' => true,
                        'height' => true,
                        'stack' => 10
                    ],
                    'js' => [
                        'title' => 'JS',
                        'type' => 'Source',
                        'name' => 'data[js]',
                        'alt' => ['%s goes here...', 'JavaScript'],
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

if (null !== State::get('x.tag') && substr_count($_['path'], '/') > 1) {
    // Convert list of tag(s) slug into list of tag(s) ID
    Hook::set(['do.page.set', 'do.page.get'], function($_, $lot) use($user) {
        // Abort by previous hook’s return value if any
        if (!empty($_['alert']['error'])) {
            return $_;
        }
        if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($lot['data']['kind'])) {
            $any = map(Tags::from(TAG, 'archive,page')->sort([-1, 'id']), function($tag) {
                return $tag->id;
            })[0] ?? 0; // Get the highest tag ID
            $out = [];
            ++$any; // New ID must be unique
            foreach (preg_split('/\s*,+\s*/', $lot['data']['kind']) as $v) {
                if ("" === $v) {
                    continue;
                }
                if ($id = From::tag($v = To::kebab($v))) {
                    $out[] = $id;
                } else {
                    $out[] = $any;
                    (new Tag($f = TAG . DS . $v . '.page'))->set([
                        'title' => To::title($v),
                        'author' => $user->user ?? null
                    ])->save(0600);
                    (new File(TAG . DS . $v . DS . 'id.data'))->set($any)->save(0600);
                    (new File(TAG . DS . $v . DS . 'time.data'))->set(date('Y-m-d H:i:s'))->save(0600);
                    $_['alert']['info'][] = ['%s %s successfully created.', ['Tag', '<code>' . _\lot\x\panel\h\path($f) . '</code>']];
                    ++$any;
                }
            }
            (new File(Path::F($_['f']) . DS . 'kind.data'))->set(json_encode($out))->save(0600);
        }
        return $_;
    }, 11);
    $lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page']['lot']['fields']['lot']['tags'] = [
        'type' => 'Query',
        'state' => ['max' => 10],
        'name' => 'data[kind]',
        'value' => (new Page($_['f']))->query,
        'width' => true,
        'stack' => 41
    ];
}

$lot['bar']['lot'][0]['lot']['s']['url'] = str_replace('::g::', '::s::', dirname($url->clean)) . $url->query('&', ['layout' => 'page.page', 'tab' => false]) . $url->hash;
$lot['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['s']['title'] = 's' === $_['task'] ? 'Publish' : 'Update';

return $lot;