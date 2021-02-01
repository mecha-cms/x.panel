<?php

$rules = require __DIR__ . DS . '2.php';

// Member user(s) cannot do anything but updating their user file
$rules['bar'][0]['folder'] = false;
$rules['bar'][0]['link'] = false;
$rules['bar'][0]['search'] = false;
$rules['bar'][1]['site'] = false;
foreach (['asset', 'block', 'comment', 'page'] as $m) {
    $rules['route'][$m] = false;
}

return $rules;
