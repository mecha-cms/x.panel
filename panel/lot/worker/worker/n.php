<?php

// `text`
// `url`
// `i`
// `attributes`
// `is`
//    `active`
// `stack`

$__n = [];

$__n["-"] = [
    'text' => $language->visit . ' ' . $language->site,
    'url' => $url . "",
    'attributes' => [
        'target' => '_blank'
    ],
    'stack' => 10
];

if ($__log = File::open(ENGINE . DS . 'log' . DS . 'error.log')->read()) {
    preg_match_all('#^\s*\[(.+?)\].*\s*$#m', $__log, $__errors);
    if (!empty($__errors[1])) {
        $__errors[1] = array_unique($__errors[1], SORT_STRING);
        foreach ($__errors[1] as $__k => $__v) {
            $__vv = trim(explode(']', $__errors[0][$__k])[1]);
            if (!trim(explode(':', $__vv . ':')[1])) {
                unset($__errors[1][$__k]);
            }
        }
        $__n['error'] = isset($__n_n['error']) && is_array($__n_n['error']) ? $__n_n['error'] : [
            'text' => $language->errors,
            'url' => $__state->path . '/::g::/error',
            'i' => count($__errors[1]),
            'stack' => 20
        ];
    }
}

$__n['exit'] = isset($__n_n['exit']) && is_array($__n_n['exit']) ? $__n_n['exit'] : [
    'text' => $language->exit,
    'url' => $__state->path . '/::g::/exit',
    'stack' => 30
];

Config::set('panel.n.n', $__n = Anemon::eat($__n)->sort([1, 'stack'], "")->vomit()); // hold!