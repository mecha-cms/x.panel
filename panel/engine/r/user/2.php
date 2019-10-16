<?php

// Prevent user(s) from modifying the `content` type
if ($_['task'] === 'g' && isset($_GET['content'])) {
    Alert::error(i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>');
    Guard::kick($url->clean . $url->query('&', ['content' => false]) . $url->hash);
}

// Items page (has page offset in URL)
if (isset($_['i'])) {
    // Change asset menu link to jump to the user file(s)
    $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['folder']['lot']['asset']['url'] = $url . $_['/'] . '::g::/asset/' . $user->user . '/1';
    // Hide these menu(s)
    foreach (['block', 'cache', 'content', 'trash', 'user', 'x'] as $n) {
        $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['folder']['lot'][$n]['hidden'] = true;
    }
}

// Hide main state link
$GLOBALS['_']['lot']['bar']['lot'][1]['lot']['site']['lot']['state']['hidden'] = true;