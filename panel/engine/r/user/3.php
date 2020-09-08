<?php

require __DIR__ . DS . '2.php';

// Member user(s) cannot do anything but updating their user file
if ('g' === $_['task'] && $user->path === $_['f']) {
    // Hide everything but `link`
    if (isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot'])) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot'] as $k => &$v) {
            if ('link' !== $k) {
                $v['hidden'] = true;
            }
        }
    }
    // Add exit button
    $_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['exit'] = [
        '2' => [
            'target' => '_top' // Needed to disable the AJAX link
        ],
        'title' => 'Exit',
        'type' => 'link',
        'url' => $url . ($_['user']['guard']['path'] ?? $_['user']['path']) . '/' . $user->name . '?exit=' . $_['token'],
        'tags' => ['is:text'],
        'stack' => 20
    ];
} else {
    if ($url->clean !== $url . $_['/'] . '/::g::' . $_['state']['path']) {
        Alert::error(i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>');
    }
    Guard::kick($url . $_['/'] . '/::g::/user/' . $user->name(true) . $url->query('&', [
        'layout' => false,
        'tab' => false
    ]) . $url->hash);
}
