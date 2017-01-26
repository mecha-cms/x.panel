<?php

$__user = Cookie::get('Mecha\Panel.user');

Cookie::reset('Mecha\Panel.user');
Cookie::reset('Mecha\Panel.token');

$f = ENGINE . DS . 'log' . DS . 'user' . DS . $__user . DS;

File::open($f . 'user.data')->delete();
File::open($f . 'token.data')->delete();

// Delete trash…
foreach (File::explore(PAGE, true, true) as $k => $v) {
    if ($v === 0) continue;
    if (Path::X($k) === 'trash') {
        File::open($k)->delete();
        $s = Path::F($k);
        if (Is::D($s)) {
            File::open($s)->delete();
        }
    }
}

// --ditto
foreach (g(LANGUAGE, 'trash') as $v) {
    File::open($v)->delete();
}

Message::success('user_exit');

Guardian::kick($__state['path'] . '/::g::/enter');