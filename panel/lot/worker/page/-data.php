<?php

$__a = ',' . Config::get('panel.x.s.data') . ',author,content,description,email,link,status,title,type' . ',';
$__aparts = Page::apart($__command === 'g' ? file_get_contents($__page[0]->path) : "");

call_user_func(function() use(&$__aparts, $__a) {
    foreach ($__aparts as $__k => $__v) {
        if (strpos($__a, ',' . $__k . ',') !== false) {
            unset($__aparts[$__k]);
            continue;
        }
        $__aparts[$__k] = is_array($__v) ? json_encode($__v) : s($__v);
    }
});

return '<p>' . Form::textarea('__datas', To::yaml($__aparts), $language->f_yaml, ['classes' => ['textarea', 'block', 'code']]) . '</p>';