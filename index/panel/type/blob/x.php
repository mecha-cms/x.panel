<?php

$description = ['It is not possible to upload the package due to the missing %s extension.', 'PHP <a href="https://www.php.net/manual/en/class.ziparchive.php" rel="nofollow" target="_blank"><code>zip</code></a>'];
$zip = extension_loaded('zip');

Hook::set('do.blob.set', function ($_) use ($description, $zip) {
    if ('POST' !== $_SERVER['REQUEST_METHOD']) {
        return $_;
    }
    $r = basename($folder = $_['folder'] ?? "");
    if (!$zip) {
        $_['alert']['error'][$folder] = $description;
    }
    if (isset($_POST['blobs']) && is_array($_POST['blobs'])) {
        foreach ($_POST['blobs'] as $k => $v) {
            if (!empty($v['status'])) {
                continue;
            }
            $name = pathinfo($v['name'], PATHINFO_FILENAME);
            $x = pathinfo($v['name'], PATHINFO_EXTENSION);
            // Match `x.foo-bar`, `x.foo-bar@main`, `x.foo-bar@v1.0.0` or <https://semver.org#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string>
            if (preg_match('/^' . x($r)  . '\.([^@]+)(?:[@](?:main|v(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?))?$/', $name, $m)) {
                $wrap = $m[1];
            } else {
                $wrap = strtok($name, '@');
            }
            $_POST['options'][$k]['folder'] = $wrap; // Wrap package in a folder
            $_POST['options'][$k]['zip']['extract'] = true; // Extract package
            $_POST['options'][$k]['zip']['keep'] = false; // Delete package
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

$description = '<p>' . i('Make sure that the extension package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . i('extension') . '.zip
├── about.page
├── index.php
└── …</code></pre>';

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