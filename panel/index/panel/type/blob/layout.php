<?php

Hook::set('do.blob.set', function($_) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    $error = !empty($_['alert']['error']);
    if (isset($_POST['blobs']) && is_array($_POST['blobs'])) {
        foreach ($_POST['blobs'] as $v) {
            if (!empty($v['status'])) {
                $error = 1;
                break;
            }
        }
    }
    if (!$error) {
        foreach (g(LOT . D . strtok($_['path'], '/'), null, true) as $k => $v) {
            1 === $v ? unlink($k) : rmdir($k);
        }
    }
    return $_;
}, 9.91); // Must come after `do.blob.set` hook in `x.php` file

// Inherit to `.\x.php`
$_ = require __DIR__ . D . 'x.php';

$description = '<p>' . i('Make sure that the package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . To::kebab(i('layout')) . '.zip&#xA;&#x251C;&#x2500;&#x2500;&#x20;about.page&#xA;&#x251C;&#x2500;&#x2500;&#x20;error.php&#xA;&#x251C;&#x2500;&#x2500;&#x20;page.php&#xA;&#x251C;&#x2500;&#x2500;&#x20;pages.php&#xA;&#x2514;&#x2500;&#x2500;&#x20;&#x2026;</code></pre>';
$description .= '<p>' . i('Please note that this action will first delete all of your current layout files before replacing it with the new ones.') . ' ' . i('You may want to <a href="%s" target="_blank" title="%s">save a copy</a> of your current layout files before doing this.', [x\panel\to\link([
    'path' => strtok($_['path'], '/'),
    'query' => [
        'chunk' => null,
        'deep' => null,
        'folder' => 'false',
        'stack' => null,
        'tab' => null,
        'token' => $_['token'],
        'trash' => null,
        'type' => null,
        'x' => null
    ],
    'task' => 'fire/zip'
]), 'Download current layout as a ZIP file.']) . '</p>';

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description']['lot']['content']['content'] = $description;

return $_;