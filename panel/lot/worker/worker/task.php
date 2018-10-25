<?php namespace fn\task;

function rename($a, $b) {
    \File::open($a)->renameTo($b);
}

function rename_extend($a, $b) {
    rename($a, $b);
    \Session::set('panel.file.active', dirname($a) . DS . 'about.page');
    return ['kick' => \Extend::state('panel', 'path') . '/::g::/extend/1'];
}