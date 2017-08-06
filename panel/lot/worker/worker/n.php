<?php

// `text`
// `url`
// `h`
// `i`
// `attributes`
// `is`
//    `active`
//    `hidden`
// `hidden`
// `+`
// `stack`

$__n['+'] = [
    'text' => '&#x22EE;',
    'url' => "",
    'stack' => 0
];

$__n['+']['site'] = [
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
        $__n['+']['error'] = [
            'text' => $language->errors,
            'url' => $__state->path . '/::g::/error',
            'h' => count($__errors[1]),
            'stack' => 20
        ];
    }
}

$__n['+']['exit'] = [
    'text' => $language->exit,
    'url' => $__state->path . '/::g::/exit',
    'stack' => 30
];

Config::set('panel.n', $__n); // hold!