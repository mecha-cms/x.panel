<?php

// Inherit to `.\x.php`
$_ = require __DIR__ . D . 'x.php';

if (!isset($_with_hooks) || $_with_hooks) {
    Hook::set('do.blob.set', function ($_) use ($description, $zip) {
        if ('POST' !== $_SERVER['REQUEST_METHOD']) {
            return $_;
        }
        if (!empty($_['alert']['error']) || $_['status'] >= 400) {
            return $_;
        }
        $error = false;
        if (isset($_POST['blobs']) && is_array($_POST['blobs'])) {
            foreach ($_POST['blobs'] as $k => $v) {
                if (!empty($v['status'])) {
                    $error = true;
                    break;
                }
            }
        }
        if (!$error && $_['folder']) {
            // Disable other layout(s)
            foreach (glob($_['folder'] . D . '*' . D . 'index.php', GLOB_NOSORT) as $v) {
                if (!rename($v, $vv = dirname($v) . D . '.index.php')) {
                    $_['alert']['error'][] = ['Could not rename %s to %s.', [x\panel\from\path($v), x\panel\from\path($vv)]];
                }
            }
        }
        return $_;
    }, 9.99);
}

$description = '<p>' . i('Make sure that the layout package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . i('layout') . '.zip
├── about.page
├── index.php
├── page.php
├── pages.php
└── …</code></pre>';

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description']['lot']['content']['content'] = $description;

return $_;