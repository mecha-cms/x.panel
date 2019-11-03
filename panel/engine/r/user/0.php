<?php

require __DIR__ . DS . '3.php';

if ('g' === $_['task'] && $_['f'] === $user->path) {
    // Hide navigation bar
    $GLOBALS['_']['lot']['bar']['hidden'] = true;
    $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][0]['title'] = $user['author'];
    $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][0]['description'] = 'Waiting for reviews.';
    $GLOBALS['_']['lot']['desk']['lot']['form']['lot'][0]['content'] = '<p>' . i('While waiting to be reviewed, you can update your user information at any time.') . '</p>';
}