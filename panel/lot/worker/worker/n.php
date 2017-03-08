<?php

$__n = (array) Config::get('panel.n', []);

$__o = [];

if (!isset($__n['error']) || $__n['error'] !== false) {
    if ($__log = File::exist(ENGINE . DS . 'log' . DS . 'error.log')) {
        preg_match_all('#^\s*\[(.+?)\]#m', File::open($__log)->read(), $__errors);
        if (!empty($__errors[1])) {
            $__o['error'] = isset($__n['error']) && is_array($__n['error']) ? $__n['error'] : [
                'text' => $language->errors,
                'attributes' => [
                    'href' => $__state->path . '/::g::/error'
                ],
                'i' => count(array_unique($__errors[1]))
            ];
        }
    }
    unset($__n['error']);
}

if (!isset($__n['exit']) || $__n['exit'] !== false) {
    $__o['exit'] = isset($__n['exit']) && is_array($__n['exit']) ? $__n['exit'] : [
        'text' => $language->exit,
        'attributes' => [
            'href' => $__state->path . '/::g::/exit'
        ]
    ];
    unset($__n['exit']);
}

if (!isset($__n[""]) || $__n[""] !== false) {
    $__o[""] = isset($__n[""]) && is_array($__n[""]) ? $__n[""] : [
        'text' => $language->visit . ' ' . $language->site . ' &#x2197;',
        'attributes' => [
            'href' => $url . "",
            'target' => '_blank'
        ]
    ];
    unset($__n[""]);
}

foreach ($__n as $k => $v) {
    if ($v === false) continue;
    $__o[$k] = is_array($v) ? $v : [
        'text' => $language->{$k},
        'attributes' => [
            'href' => $__state->path . '/::g::/' . $k
        ]
    ];
}

$__n = Anemon::eat($__o)->sort([1, 'text'], '?')->vomit(); // hold!

unset($__o);