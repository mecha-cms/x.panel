<?php

$lot = require __DIR__ . DS . 'trash.php';

if (!empty($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'])) {
    foreach ($lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['files']['lot']['files']['lot'] as $k => &$v) {
        unset($v['tasks']['restore']);
    }
}

$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['blob']['hidden'] = true;
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['file']['hidden'] = true;
$lot['desk']['lot']['form']['lot'][0]['lot']['tasks']['lot']['folder']['hidden'] = true;

return $lot;