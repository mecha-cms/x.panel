<?php

$zip = extension_loaded('zip');

Hook::set('do.blob.set', function($_) use($zip) {
    if (!$zip) {
        $_['alert']['error'][] = ['It is not possible to upload the package due to the missing %s extension.', 'PHP <code>zip</code>'];
    }
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    if (isset($_['form']['blob']) && is_array($_['form']['blob'])) {
        foreach ($_['form']['blob'] as $blob) {
            if (!empty($blob['error'])) {
                continue;
            }
            $x = pathinfo($blob['name'], PATHINFO_EXTENSION);
            // Allow ZIP archive(s) only
            if ('zip' !== $x) {
                $_['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
            }
        }
    }
    return $_;
}, 9.9);

$lot = require __DIR__ . DS . 'blob.php';

// Disable multiple file upload
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['type'] = 'blob';
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['name'] = 'blob[0]';

// Disable file uploader if it is not possible to extract package with the current environment
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['active'] = $zip;

// Force extract package
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['o']['hidden'] = true;
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['o:extract'] = [
    'type' => 'hidden',
    'name' => 'o[extract]',
    'value' => 1
];

// Force delete package
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['o:let'] = [
    'type' => 'hidden',
    'name' => 'o[let]',
    'value' => 1
];

$description = '<p>' . i('Make sure that the package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . To::kebab(i('extension')) . '.zip&#xA;&#x2514;&#x2500;&#x2500;&#x20;' . To::kebab(i('extension')) . '&#x5C;&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;about.page&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;index.php&#xA;&#x20;&#x20;&#x20;&#x20;&#x2514;&#x2500;&#x2500;&#x20;&#x2026;</code></pre>';

$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description'] = [
    'type' => 'field',
    'title' => "",
    'content' => $description,
    'stack' => 20
];

return $lot;
