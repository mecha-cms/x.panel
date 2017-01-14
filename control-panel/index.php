<?php

Route::set(['panel/page/%i%/%*%/%i%', 'panel/page/%i%/%*%'], function($state, $path, $step = 1) {
    Asset::reset();
    $states = ['draft', 'page', 'archive'];
    if (!isset($states[$state])) {
        Shield::abort();
    }
    $file = PAGE . DS . ltrim($path, '/') . '.' . $states[$state];
    Lot::set([
        'page' => new Page($file),
        '__page' => o(array_replace([
            'path' => To::path($file),
            'slug' => Path::N($file),
            'state' => $states[$state]
        ], Page::apart(file_get_contents($file))))
    ]);
    Shield::attach(__DIR__ . DS . 'lot' . DS . 'shield' . DS . 'mecha' . DS . 'page.php');
}, 1);