<?php

if (!$file = File::exist(LOT . DS . $path . '.page')) {
    $file = LANGUAGE . DS . $site->language . '.page';
}

if (!file_exists($file)) {
    Shield::abort();
}

$pages = [[], []];
foreach (glob(LANGUAGE . DS . '*.page') as $v) {
    $pages[0][] = new Page($v, [], '::' . $sgr . '::language');
    $pages[1][] = new Page($v, [], 'language');
}

Lot::set([
    'pages' => $pages,
    'page' => [
        new Page($file, [], '::' . $sgr . '::language'),
        new Page($file, [], 'language')
    ]
]);