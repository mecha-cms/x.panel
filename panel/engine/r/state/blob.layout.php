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

$description = '<p>' . i('Make sure that the package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . To::kebab(i('layout')) . '.zip&#xA;&#x251C;&#x2500;&#x2500;&#x20;404.php&#xA;&#x251C;&#x2500;&#x2500;&#x20;about.page&#xA;&#x251C;&#x2500;&#x2500;&#x20;page.php&#xA;&#x251C;&#x2500;&#x2500;&#x20;pages.php&#xA;&#x2514;&#x2500;&#x2500;&#x20;&#x2026;</code></pre>';
$description .= '<p>' . i('Please note that this action will first delete all of your current layout files before replacing it with the new ones.') . ' ' . i('You may want to <a href="%s" target="_blank" title="%s">save a copy</a> of your current layout files before doing this action.', [$url . $_['/'] . '/::f::/de686795/' . $_['chops'][0] . $url->query('&amp;', [
    'd' => 0,
    // 'kick' => URL::short($url->current, false),
    'layout' => false,
    'token' => $_['token'],
    'trash' => false
]), 'Download current layout as a ZIP file.']) . '</p>';

$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description']['content'] = $description;

return $lot;
