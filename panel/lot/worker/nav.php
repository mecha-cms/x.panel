<?php

$__s = require __DIR__ . DS . 'worker' . DS . '-n.php';
$__avatar = $__user ? '<span>' . HTML::img($__user->avatar($url->protocol . 'www.gravatar.com/avatar/' . md5($__user->email ?: $__user->key) . '?s=60&amp;d=monsterid')) . User::ID . $__user->key . '</span>' : "";

echo $__avatar ? str_replace('</nav>', $__avatar . '</nav>', $__s) : $__s;