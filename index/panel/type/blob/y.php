<?php

// Inherit to `.\x.php`
$_ = require __DIR__ . D . 'x.php';

$description = '<p>' . i('Make sure that the layout package you want to upload is structured like this:') . '</p>';
$description .= '<pre><code class="txt">' . i('layout') . '.zip
├── about.page
├── index.php
├── page.php
├── pages.php
└── …</code></pre>';

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['blob']['lot']['fields']['lot']['description']['lot']['content']['content'] = $description;

return $_;