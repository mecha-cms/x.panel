<?php

$description = ['It is not possible to upload the package due to the missing %s extension.', 'PHP <code>zip</code>'];
$zip = extension_loaded('zip');

Hook::set('do.blob.set', function($_) use($description, $zip) {
    $f = $_['f'];
    if (!$zip) {
        $_['alert']['error'][$f] = $description;
    }
    if ('post' !== $_['form']['type']) {
        return $_;
    }
    if (isset($_['form']['lot']['blob']) && is_array($_['form']['lot']['blob'])) {
        foreach ($_['form']['lot']['blob'] as $blob) {
            if (!empty($blob['error'])) {
                continue;
            }
            $x = pathinfo($blob['name'], PATHINFO_EXTENSION);
            // Allow ZIP archive(s) only
            if ('zip' !== $x) {
                $_['alert']['error'][$f] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
            }
        }
    }
    return $_;
}, 9.9);

$_ = require __DIR__ . DS . '..' . DS . 'blob.php';

// Disable multiple file upload
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['type'] = 'blob';
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['name'] = 'blob[0]';

// Disable file uploader if it is not possible to extract package with the current environment
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['active'] = $zip;
if (!$zip) {
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['description'] = $description;
}

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['options']['skip'] = true;

// Force delete package
$_['lot']['desk']['lot']['form']['data']['options']['let'] = 1;

// Force extract package
$_['lot']['desk']['lot']['form']['data']['options']['extract'] = 1;

$description = '<p>' . i('Make sure that the package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . To::kebab(i('extension')) . '.zip&#xA;&#x2514;&#x2500;&#x2500;&#x20;' . To::kebab(i('extension')) . '&#x5C;&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;about.page&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;index.php&#xA;&#x20;&#x20;&#x20;&#x20;&#x2514;&#x2500;&#x2500;&#x20;&#x2026;</code></pre>';

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description'] = [
    'title' => "",
    'type' => 'field',
    'lot' => [
        'content' => [
            'type' => 'content',
            'content' => $description,
            'stack' => 10
        ]
    ],
    'stack' => 20
];

return $_;
