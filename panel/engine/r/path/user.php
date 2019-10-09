<?php

if (count($_['chop']) > 1) {
    $status = $user['status'];
    if ($status !== 1) {
        // Hide add user link
        $GLOBALS['_']['lot']['bar']['lot'][0]['lot']['s']['hidden'] = true;
        // XSS Protection
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Prevent user(s) from adding a hidden form (or changing the `page[status]` field value) that
            // defines its `status` through developer tools and such by enforcing the `page[status]` value
            if (isset($_POST['page']['status']) && $_POST['page']['status'] !== $status) {
                Alert::error('You don&rsquo;t have permission to change the <code>status</code> value.');
            }
            $_POST['page']['status'] = $status;
            unset($_POST['data']['status']);
        }
        // Prevent user from editing another user file
        if ($_['f'] !== $user->path) {
            Alert::error('Permission denied for your current user status: <code>' . $user['status'] . '</code>.<br><small>' . $url->current . '</small>');
            Guard::kick(dirname($url->clean) . '/1' . $url->hash);
        }
    }
}