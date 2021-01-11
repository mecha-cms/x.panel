<?php

$rules = require __DIR__ . DS . '2.php';

// Member user(s) cannot do anything but updating their user file
$rules['bar']['folder'] = false;
$rules['bar']['link'] = false;
$rules['bar']['search'] = false;
$rules['bar']['site'] = false;
foreach (['asset', 'block', 'comment', 'page'] as $m) {
    $rules['route'][$m] = false;
}

return $rules;
