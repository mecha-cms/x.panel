<?php

$__errors = [];

if ($__log = File::open(ENGINE . DS . 'log' . DS . 'error.log')->read()) {
    preg_match_all('#^\s*\[(.+?)\].*\s*$#m', $__log, $__e);
    if (!empty($__e[1])) {
        call_user_func(function() use(&$__errors, $__e) {
            foreach (array_unique($__e[1], SORT_STRING) as $__k => $__v) {
                $__vv = trim(explode(']', $__e[0][$__k])[1]);
                if (!trim(explode(':', $__vv . ':')[1])) {
                    continue;
                }
                $__errors[] = '<th>' . str_replace(' ', '&nbsp;', (new Date($__v))->F2) . '</th><td>' . $__vv . '</td>';
            }
        });
        return '<p>' . $language->showing . ' ' . count($__errors) . ' ' . l($language->of) . ' ' . count($__e[1]) . '.</p><table class="table"><tbody><tr>' . implode('</tr><tr>', $__errors) . '</tr></tbody></table>';
    }
}

return "";