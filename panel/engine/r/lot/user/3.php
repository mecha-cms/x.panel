<?php

$_ = _\lot\x\panel\_error_user_check($_);

if ('g' === $_['task'] && $user->path === $_['f']) {
    // Hide everything but `email`, `link` and `status`
    if (isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot'])) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot'] as $k => &$v) {
            if (
                'email' !== $k &&
                'link' !== $k &&
                'status' !== $k
            ) {
                $v['skip'] = true;
            }
        }
    }
}
