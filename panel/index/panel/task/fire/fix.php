<?php

if (is_file($log = ENGINE . D . 'log' . D . 'error')) {
    unlink($log);
}

// Redirect away!
kick($_REQUEST['kick'] ?? x\panel\to\link(['query' => null]));