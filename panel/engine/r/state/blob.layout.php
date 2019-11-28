<?php

// TODO

$lot = require __DIR__ . DS . 'blob.x.php';

$description = '<p>' . i('Make sure that the package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . i('layout') . '.zip&#xA;&#x2514;&#x2500;&#x2500;&#x20;' . i('layout') . '&#x5C;&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;404.php&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;about.page&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;page.php&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;pages.php&#xA;&#x20;&#x20;&#x20;&#x20;&#x2514;&#x2500;&#x2500;&#x20;&#x2026;</code></pre>';
$description .= '<p>' . i('Please note that this action will first remove all of your current layout files before replacing it with the new ones.') . ' ' . i('You may want to <a href="%s">create a backup data</a> of your current layout files before doing so.', '#') . '</p>';

$lot['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description']['content'] = $description;

return $lot;