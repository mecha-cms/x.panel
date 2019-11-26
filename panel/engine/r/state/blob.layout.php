<?php

// TODO

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_POST['blob']) && is_array($_POST['blob'])) {
        foreach ($_POST['blob'] as $blob) {
            $x = pathinfo($blob['name'] ?? "", PATHINFO_EXTENSION);
            // Allow ZIP archive(s) only
            if ('zip' !== $x) {
                $GLOBALS['_']['alert']['error'][] = ['File extension %s is not allowed.', '<code>' . $x . '</code>'];
                break;
            }
        }
    }
}

$lot = require __DIR__ . DS . 'blob.php';

$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['o']['hidden'] = true;
$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['o:extract'] = [
    'type' => 'Hidden',
    'name' => 'o[extract]',
    'value' => 1
];

$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['o:let'] = [
    'type' => 'Hidden',
    'name' => 'o[let]',
    'value' => 1
];

return $lot;