<?php

$status = $user['status'] ?? -1;
if (is_file($f = __DIR__ . DS . '..' . DS . '..' . DS . 'state' . DS . 'user' . DS . $status . '.php')) {
    State::set('x.panel.guard.status.' . $status, (array) require $f);
}
