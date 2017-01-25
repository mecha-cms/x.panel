<?php

$__kins = [[], []];
foreach (glob(LANGUAGE . DS . '*.page') as $v) {
    $__kins[0][] = new Page($v, [], '__language');
    $__kins[1][] = new Page($v, [], 'language');
}

Lot::set('__kins', $__kins);

if (!$__file = File::exist([
    LOT . DS . $__path . '.page',
    LANGUAGE . DS . $site->language . '.page'
])) {
    Shield::abort();
}

$__page = [
    new Page($__file, [], '__language'),
    new Page($__file, [], 'language')
];

Lot::set('__page', $__page);

if ($__sgr === 's') {
    Lot::set('__page', [
        new Page(null, [
            'type' => 'YAML',
            'content' => $__page[0]->content
        ], '__language'),
        $__page[1]
    ]);
}