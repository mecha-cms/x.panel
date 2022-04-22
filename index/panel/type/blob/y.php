<?php

// Inherit to `.\x.php`
$_ = require __DIR__ . D . 'x.php';

$description = '<p>' . i('Make sure that the layout package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . To::kebab(i('layout')) . '.zip&#xA;&#x2514;&#x2500;&#x2500;&#x20;' . To::kebab(i('layout')) . '&#x5C;&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;about.page&#xA;&#x20;&#x20;&#x20;&#x20;&#x251C;&#x2500;&#x2500;&#x20;index.php&#xA;&#x20;&#x20;&#x20;&#x20;&#x2514;&#x2500;&#x2500;&#x20;&#x2026;</code></pre>';

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description']['lot']['content']['content'] = $description;

return $_;