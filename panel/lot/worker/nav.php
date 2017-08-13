<?php

$__s = require __DIR__ . DS . 'worker' . DS . '-n.php';
$__avatar = $__user ? '<span>' . HTML::img($__user->avatar($url->protocol . 'www.gravatar.com/avatar/' . md5($__user->email ?: $__user_key) . '?s=60&amp;d=monsterid')) . '<a href="' . $url . '/' . $__state->path . '/::g::/user/' . $__user_key . '" title="' . $language->edit . '">@' . $__user_key . '</a></span>' : "";

echo $__avatar ? str_replace('</nav>', $__avatar . '</nav>', $__s) : $__s;