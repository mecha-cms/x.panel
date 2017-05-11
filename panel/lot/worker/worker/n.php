<?php

$__n_n = (array) Config::get('panel.n.n', []);

$__n = [];

if (!isset($__n_n['error']) || $__n_n['error'] !== false) {
    if ($__log = File::exist(ENGINE . DS . 'log' . DS . 'error.log')) {
        preg_match_all('#^\s*\[(.+?)\]#m', File::open($__log)->read(), $__errors);
        if (!empty($__errors[1])) {
            $__n['error'] = isset($__n_n['error']) && is_array($__n_n['error']) ? $__n_n['error'] : [
                'text' => $language->errors,
                'attributes' => [
                    'href' => $__state->path . '/::g::/error'
                ],
                'i' => count(array_unique($__errors[1]))
            ];
        }
    }
    unset($__n_n['error']);
}

if (!isset($__n_n['exit']) || $__n_n['exit'] !== false) {
    $__n['exit'] = isset($__n_n['exit']) && is_array($__n_n['exit']) ? $__n_n['exit'] : [
        'text' => $language->exit,
        'attributes' => [
            'href' => $__state->path . '/::g::/exit'
        ]
    ];
    unset($__n_n['exit']);
}

if (!isset($__n_n[""]) || $__n_n[""] !== false) {
    $__n[""] = isset($__n_n[""]) && is_array($__n_n[""]) ? $__n_n[""] : [
        'text' => $language->visit . ' ' . $language->site,
        'attributes' => [
            'href' => $url . "",
            'target' => '_blank'
        ]
    ];
    unset($__n_n[""]);
}

foreach ($__n_n as $__k => $__v) {
    if ($__v === false) continue;
    $__n[$__k] = is_array($__v) ? $__v : [
        'text' => $language->{$__k},
        'attributes' => [
            'href' => $__state->path . '/::g::/' . $__k
        ]
    ];
}

$__n_n = Anemon::eat($__n)->sort([1, 'text'], '?')->vomit(); // hold!

unset($__n);