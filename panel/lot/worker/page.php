<?php

if (!$file = File::exist([
    LOT . DS . $path . '.draft',
    LOT . DS . $path . '.page',
    LOT . DS . $path . '.archive'
])) {
    Shield::abort();
}

Lot::set([
    'page' => new Page($file),
    'page_' => o(array_replace(a(Config::get('page', [])), Get::page($file), Page::apart(file_get_contents($file))))
]);