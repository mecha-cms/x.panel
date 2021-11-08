<?php

$_ = x\panel\_error_user_check();

if ('g' === $_['task'] && $user->path === $_['f']) {
    $_['lot']['desk']['lot']['form']['lot'][0]['title'] = $user['author'];
    $_['lot']['desk']['lot']['form']['lot'][0]['description'] = 'Waiting for reviews.';
    $_['lot']['desk']['lot']['form']['lot'][0]['content'] = '<p>' . i('While waiting to be reviewed, you can update your user information at any time.') . '</p>';
    // Hide everything but `link` and `status`
    if (isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot'])) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot'] as $k => &$v) {
            if (
                'link' !== $k &&
                'status' !== $k
            ) {
                $v['skip'] = true;
            }
        }
    }
}