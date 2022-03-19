<?php

$description = ['It is not possible to upload the package due to the missing %s extension.', 'PHP <a href="https://www.php.net/manual/en/class.ziparchive.php" rel="nofollow" target="_blank"><code>zip</code></a>'];
$zip = extension_loaded('zip');

Hook::set('do.blob.set', function($_) use($description, $zip) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    $folder = $_['folder'];
    if (!$zip) {
        $_['alert']['error'][$folder] = $description;
    }
    if (isset($_POST['blobs']) && is_array($_POST['blobs'])) {
        foreach ($_POST['blobs'] as $v) {
            if (!empty($v['status'])) {
                continue;
            }
            $x = pathinfo($v['name'], PATHINFO_EXTENSION);
            // Allow ZIP archive(s) only
            if ('zip' !== $x) {
                $_['alert']['error'][$folder] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
            }
        }
    }
    return $_;
}, 9.9);

// Disable multiple file upload
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['name'] = 'blobs[0]';
$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['type'] = 'blob';

// Disable file uploader if it is not possible to extract package with the current environment
if (!$zip) {
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['active'] = false;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['blob']['description'] = $description;
}

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['options']['skip'] = true;

// Force delete package
$_['lot']['desk']['lot']['form']['values']['options']['let'] = 1;

// Force extract package
$_['lot']['desk']['lot']['form']['values']['options']['extract'] = 1;

$description = '<p>' . i('Make sure that the package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . To::kebab(i('extension')) . '.zip&#xA;&#x2514;&#x2500;&#x2500;&#x20;' . To::kebab(i('extension')) . '&#x5C;&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;about.page&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;index.php&#xA;&#x20;&#x20;&#x20;&#x20;&#x2514;&#x2500;&#x2500;&#x20;&#x2026;</code></pre>';

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description'] = [
    'lot' => [
        'content' => [
            'content' => $description,
            'stack' => 10,
            'type' => 'content'
        ]
    ],
    'stack' => 20,
    'title' => "",
    'type' => 'field'
];

return $_;