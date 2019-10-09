<?php

if ($user['status'] !== 1) {
    if (count($_['chop']) === 1) {
        if (!is_dir($d = LOT . DS . 'asset' . DS . $user->user)) {
            mkdir($d, 0755, true);
        }
    }
    // Force to enter to the user file(s)
    if (count($_['chop']) === 1 || $_['chop'][1] !== $user->user) {
        Alert::error('Permission denied for your current user status: <code>' . $user['status'] . '</code>.<br><small>' . $url->current . '</small>');
        Guard::kick($url . $_['/'] . '::g::/asset/' . $user->user . '/1');
    }
}

// Hide parent folder link
$GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$_['f']]['hidden'] = true;