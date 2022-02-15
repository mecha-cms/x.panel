<?php

if (isset($state->x->tag) && (
    'set' === $_['task'] && substr_count($_['path'], '/') > 0 ||
    'get' === $_['task'] && substr_count($_['path'], '/') > 1
)) {
    // Convert list of tag(s) slug into list of tag(s) ID
    Hook::set([
        'do.page.get',
        'do.page.set'
    ], function($_) use($user) {
        // Method not allowed!
        if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            return $_;
        }
        // Abort by previous hook’s return value if any
        if (isset($_['kick']) || !empty($_['alert']['error'])) {
            return $_;
        }
        // Abort if current page is not a file
        if (!is_file($file = $_['file'])) {
            return $_;
        }
        // Delete `kind.data` file if `data[kind]` field is empty
        if (empty($_POST['data']['kind']) && is_file($data = dirname($file) . D . pathinfo($file, PATHINFO_FILENAME) . D . 'kind.data')) {
            unlink($data);
            return $_;
        }
        if (!is_dir($d = LOT . D . 'tag')) {
            mkdir($d, 0775, true);
        }
        $any = map(Tags::from(LOT . D . 'tag', 'archive,page')->sort([-1, 'id']), static function($tag) {
            return $tag->id;
        })[0] ?? 0; // Get the highest tag ID
        $out = [];
        ++$any; // New ID must be unique
        foreach (preg_split('/\s*,+\s*/', $_POST['data']['kind']) as $v) {
            if ("" === $v) {
                continue;
            }
            if ($id = From::tag($v = To::kebab($v))) {
                $out[] = $id;
            } else {
                $out[] = $any;
                if (!is_dir($dd = $d . D . $v)) {
                    mkdir($dd, 0775, true);
                }
                file_put_contents($f = $dd . '.page', To::page([
                    'title' => To::title($v),
                    'author' => $user->user ?? null
                ]));
                chmod($f, 0600);
                file_put_contents($ff = $dd . D . 'id.data', $any);
                chmod($ff, 0600);
                file_put_contents($ff = $dd . D . 'time.data', date('Y-m-d H:i:s'));
                chmod($ff, 0600);
                $_['alert']['info'][$f] = ['%s %s successfully created.', ['Tag', '<code>' . x\panel\from\path($f) . '</code>']];
                ++$any;
            }
        }
        if ($out) {
            sort($out);
            file_put_contents($data, json_encode($out));
            chmod($data, 0600);
        }
        return $_;
    }, 11);
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page']['lot']['fields']['lot']['tags'] = [
        'name' => 'data[kind]',
        'stack' => 41,
        'state' => ['max' => 12],
        'type' => 'query',
        'value' => (new Page($_['file']))->query,
        'width' => true
    ];
}

$_['lot']['bar']['lot'][0]['lot']['set']['url'] = x\panel\to\link([
    'part' => 0,
    'query' => [
        'query' => null,
        'stack' => null,
        'tab' => null,
        'type' => 'page/page'
    ],
    'task' => 'set'
]);

$_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['set']['title'] = 'set' === $_['task'] ? 'Publish' : 'Update';

return $_;