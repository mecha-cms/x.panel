<?php

$pages = [[], []];
foreach (glob(LANGUAGE . DS . '*.page') as $v) {
    $pages[0][] = new Page($v, [], '::' . $sgr . '::language');
    $pages[1][] = new Page($v, [], 'language');
}

Lot::set('pages', $pages);

if (!$file = File::exist([
    LOT . DS . $path . '.page',
    LANGUAGE . DS . $site->language . '.page'
])) {
    Shield::abort();
}

$page = [
    new Page($file, [], '::' . $sgr . '::language'),
    new Page($file, [], 'language')
];

Lot::set('page', $page);

if ($sgr === 's') {
    Lot::set('page', [
        new Page(null, [
            'type' => 'YAML',
            'content' => $page[0]->content
        ], '::' . $sgr . '::'),
        $page[1]
    ]);
}