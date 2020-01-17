<?php

// Force layout to `state`
$GLOBALS['_']['layout'] = $_['layout'] = 'state';

// Sanitize form data
Hook::set('do.state.get', function($_, $lot) {
    if ('POST' !== $_SERVER['REQUEST_METHOD'] || !isset($lot['state'])) {
        return $_;
    }
    extract($GLOBALS, EXTR_SKIP);
    $lot['state']['title'] = _\lot\x\panel\h\w($lot['state']['title'] ?? "");
    $lot['state']['description'] = _\lot\x\panel\h\w($lot['state']['description'] ?? "");
    $lot['state']['email'] = _\lot\x\panel\h\w($lot['state']['email'] ?? "");
    $lot['state']['charset'] = strip_tags($lot['state']['charset'] ?? 'utf-8');
    $lot['state']['language'] = strip_tags($lot['state']['language'] ?? 'en');
    $user_state = require LOT . DS . 'x' . DS . 'user' . DS . 'state.php';
    $panel_state = require LOT . DS . 'x' . DS . 'panel' . DS . 'state.php';
    $core_state = require ROOT . DS . 'state.php';
    $default = $user_state['guard']['path'] ?? $panel_state['guard']['path'] ?? $core_state['x']['user']['guard']['path'] ?? $core_state['x']['panel']['guard']['path'] ?? "";
    $default = '/' . trim($default, '/') . '/';
    if (!empty($lot['state']['x']['user']['guard']['path'])) {
        if ($secret = To::kebab(trim($lot['state']['x']['user']['guard']['path'], '/'))) {
            $lot['state']['x']['user']['guard']['path'] = '/' . $secret;
            $default = '/' . $secret . '/';
        } else {
            unset($lot['state']['x']['user']['guard']['path']);
        }
    }
    if ($_['/'] !== $default) {
        $_['/'] = $default;
        if ($default === $panel_state['guard']['path'] . '/') {
            $_['alert']['info'][] = ['Your log-in URL has been restored to %s', '<code>' . $url . $user_state['path'] . '</code>'];
        } else {
            $_['alert']['info'][] = ['Your log-in URL has been changed to %s', '<code>' . $url . substr($default, 0, -1) . '</code>'];
        }
    }
    $_POST = $lot; // Update data
    return $_;
}, 9.9);

if (1 !== $user['status'] || 'g' !== $_['task']) {
    if (Is::user()) {
        $_['alert']['error'][] = i('Permission denied for your current user status: %s', '<code>' . $user['status'] . '</code>') . '<br><small>' . $url->current . '</small>';
        $_['kick'] = $url . $_['/'] . '::g::' . $_['state']['path'] . '/1' . $url->query('&', ['layout' => false, 'tab' => false]) . $url->hash;
    } else {
        $_['kick'] = "";
    }
}
