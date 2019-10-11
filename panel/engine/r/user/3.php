<?php

require __DIR__ . DS . '2.php';

// Member user(s) cannot do anything but updating their user file
if ($_['task'] === 'g' && $_['f'] === $user->path) {
    // Hide everything but `link`
    if (isset($GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot'])) {
        foreach ($GLOBALS['_']['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot'] as $k => &$v) {
            if ($k !== 'link') {
                $v['hidden'] = true;
            }
        }
    }
    // Add exit button
    $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['exit'] = [
        'type' => 'Link',
        'title' => $language->doExit,
        'url' => $url . ($_['user']['guard']['path'] ?? $_['user']['path']) . '/' . $user->name . '?exit=' . $_['token'],
        'tags' => ['is:text'],
        'stack' => 20
    ];
} else {
    if ($url->clean !== $url . $_['/'] . '::g::' . $_['state']['path']) {
        Alert::error('Permission denied for your current user status: <code>' . $user['status'] . '</code>.<br><small>' . $url->current . '</small>');
    }
    Guard::kick($url . $_['/'] . '::g::/user/' . $user->name(true) . $url->query('&', [
        'content' => false,
        'tab' => false
    ]) . $url->hash);
}