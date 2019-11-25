<?php

$status = $user['status'];
if (count($_['chops']) > 1) {
    if (1 !== $status) {
        // Hide add user link
        $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['s']['hidden'] = true;
        // XSS Protection
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            // Prevent user(s) from adding a hidden form (or changing the `page[status]` field value) that
            // defines its `status` through developer tools and such by enforcing the `page[status]` value
            if (isset($_POST['page']['status']) && $_POST['page']['status'] !== $status) {
                Alert::error('You don\'t have permission to change the <code>status</code> value.');
            }
            $_POST['page']['status'] = $status;
            unset($_POST['data']['status']);
        }
        // Prevent user from editing another user file
        if ('g' === $_['task'] && $_['f'] !== $user->path) {
            Alert::error(i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>');
            Guard::kick(dirname($url->clean) . '/1' . $url->hash);
        }
    }
// Prevent user(s) from creating new user
} else if ('s' === $_['task'] && 1 !== $status) {
    Alert::error(i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>');
    Guard::kick($url . $_['/'] . '::g::/user/' . $user->name(true) . $url->query('&', [
        'layout' => false,
        'tab' => false
    ]) . $url->hash);
}