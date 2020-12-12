<?php

Hook::set('do.blob.set', function($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    $error = !empty($_['alert']['error']);
    foreach ($_['form']['blob'] ?? [] as $k => $v) {
        if (!empty($v['error'])) {
            $error = 1;
            break;
        }
    }
    if (!$error) {
        foreach (g(LOT . DS . $_['chops'][0], null, true) as $k => $v) {
            1 === $v ? unlink($k) : rmdir($k);
        }
    }
    return $_;
}, 9.91); // Must come after `do.blob.set` hook in `blob.x.php` file

$lot = require __DIR__ . DS . 'blob.x.php';

$description = '<p>' . i('Please note that this action will first delete all of your current layout files before replacing it with the new ones.') . ' ' . i('You may want to <a href="%s" target="_blank" title="%s">save a copy</a> of your current layout files before doing this.', [$url . $_['/'] . '/::f::/de686795/' . $_['chops'][0] . $url->query('&amp;', [
    'd' => 0,
    // 'kick' => URL::short($url->current, false),
    'layout' => false,
    'token' => $_['token'],
    'trash' => false
]), 'Download current layout as a ZIP file.']) . '</p>';

$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description']['skip'] = false;
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description']['content'] = $description;

return $lot;
