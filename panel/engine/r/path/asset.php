<?php

if (!is_dir($d = LOT . DS . 'asset' . DS . $user->user)) {
    mkdir($d, 0755, true);
    $_['alert']['success'][] = ['Created folder %s.', '<code>' . _\lot\x\panel\h\path($d) . '</code>'];
    $_SESSION['_']['folder'][$d] = 1;
    $_['kick'] = $url->current;
}

// You cannot edit or delete your own folder
if (count($_['chops']) < 3) {
    if ('g' === $_['task']) {
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$d]['tasks']['g']['url'] = false;
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$d]['tasks']['l']['url'] = false;
    }
}

if (1 !== $user['status']) {
    // Force to enter to the user file(s)
    if (1 === count($_['chops']) || $_['chops'][1] !== $user->user) {
        $_['alert']['error'][] = i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>';
        $_['kick'] = $url . $_['/'] . '::g::/asset/' . $user->user . '/1';
    }
    // Hide parent folder link
    if (count($_['chops']) < 3) {
        $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'][$_['f']]['hidden'] = true;
    }
}

$GLOBALS['_'] = $_;