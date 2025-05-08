<?php namespace x\panel;

$path = \trim($url->path ?? "", '/');
$query = \From::query($url->query ?? "");

$r = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$route = \trim($state->x->panel->route ?? $state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/');

if (0 === \strpos($path, $v = $route . '/fire/')) {
    $exist = \substr(\strstr($v = \substr($path, \strlen($v)), '/') ?: "", 1);
    $task = 'fire/' . \strstr($v, '/', true);
} else if (0 === \strpos($path, $v = $route . '/get/')) {
    $exist = \substr($path, \strlen($v));
    $task = 'get';
} else if (0 === \strpos($path, $v = $route . '/let/')) {
    $exist = \substr($path, \strlen($v));
    $task = 'let';
} else if (0 === \strpos($path, $v = $route . '/set/')) {
    $exist = \substr($path, \strlen($v));
    $task = 'set';
} else {
    $exist = $task = null;
}

$f = $part = 0;
if ($exist) {
    $part = \x\page\n($exist);
    if (!$f = \stream_resolve_include_path(\LOT . \D . $exist)) {
        if (null !== $part) {
            $f = \stream_resolve_include_path(\LOT . \D . \substr($exist, \strlen('/' . $part)));
        }
    }
}

foreach ([
    '%s goes here...' => "%s goes here\u{2026}",
    'Content goes here...' => "Content goes here\u{2026}",
    'Description goes here...' => "Description goes here\u{2026}"
] as $k => $v) {
    \lot('I')[$k] = \lot('I')[$k] ?? $v;
}

\lot('_', $_ = \array_replace_recursive([
    '0' => null,
    '1' => null,
    '2' => [],
    'alerts' => [],
    'are' => (array) ($state->are ?? []), // Inherit to the front-end state(s)
    'as' => [],
    'assets' => [],
    'author' => $user->user ?? null,
    'base' => $url . '/' . $route,
    'can' => (array) ($state->can ?? []), // Inherit to the front-end state(s)
    'chunk' => $query['chunk'] ?? 20,
    'content' => null,
    'count' => 0,
    'deep' => $query['deep'] ?? 0,
    'description' => null,
    'file' => $f && \is_file($f) ? $f : null,
    'folder' => $f && \is_dir($f) ? $f : null,
    'has' => (array) ($state->has ?? []), // Inherit to the front-end state(s)
    'hash' => $url['hash'],
    'icons' => [],
    'is' => \array_replace((array) ($state->is ?? []), ['error' => false]), // Inherit to the front-end state(s)
    'kick' => null,
    'lot' => [],
    'not' => (array) ($state->not ?? []), // Inherit to the front-end state(s)
    'of' => [],
    'part' => (int) $part,
    'path' => $exist,
    'query' => $query,
    'sort' => $query['sort'] ?? null, // Default is `[1, 'path']`
    'status' => $f ? 200 : 404,
    'task' => \lot('_' . $r)['task'] ?? $task,
    'title' => null,
    'token' => $user->token ?? null,
    'type' => \lot('_' . $r)['type'] ?? null,
    'with' => [],
    'x' => $query['x'] ?? null
], \lot('_') ?? []));

// Modify default log-in redirection to the panel page if it is not set
if ('GET' === $r && !\array_key_exists('kick', $_GET)) {
    if (!\is_dir(\LOT . \D . 'user') || $path === \trim($state->x->user->guard->route ?? $state->x->user->route ?? 'user', '/')) {
        $kick = \trim($state->x->panel->kick ?? 'get/asset/1', '/');
        // Redirect target without `/` prefix will be resolved relative to the panel base URL
        if (0 !== \strpos($kick, '/') && false === \strpos($kick, '://')) {
            $kick = '/' . $route . '/' . $kick;
        }
        $_GET['kick'] = $kick;
    }
}

function get() {
    // Capture all asset(s) data previously added by the extension(s) and layout you use, then mark them as ignored
    // asset(s) so you can preserve the asset(s) data but won’t make it load into the panel interface unless you
    // explicitly change the `skip` property value to `false`.
    $_['assets'] = [];
    foreach (\Asset::get() as $k => $v) {
        foreach ($v as $kk => $vv) {
            $_['assets'][$kk] = [
                '0' => null,
                '1' => null,
                '2' => (array) ($vv[2] ?? []),
                'link' => $vv['link'] ?? null,
                'path' => $vv['path'] ?? null,
                'skip' => true,
                'stack' => $vv['stack'],
                'url' => $vv['url'] ?? null
            ];
        }
    }
    $folder = \stream_resolve_include_path(__DIR__);
    $z = \defined("\\TEST") && \TEST ? '.' : '.min.';
    $_['assets'][$folder . \D . 'index' . $z . 'css'] = ['stack' => 20];
    $_['assets'][$folder . \D . 'index' . $z . 'js'] = ['stack' => 20];
    // Remove front-end asset(s)
    \Asset::let();
    // Extend current asset(s) data with user-defined asset(s) data
    if (!empty($_['assets'])) {
        foreach ((new \Anemone((array) $_['assets']))->sort([1, 'stack', 10], true)->get() as $k => $v) {
            if (false === $v || null === $v || !empty($v['skip'])) {
                continue;
            }
            $path = (string) ($v['path'] ?? $v['link'] ?? $v['url'] ?? $k);
            $stack = (float) ($v['stack'] ?? 10);
            if (!\is_numeric($k) && (!empty($v['link']) || !empty($v['path']) || !empty($v['url']))) {
                $v[2]['id'] = $k;
            }
            if (isset($v['id'])) {
                $v[2]['id'] = $v['id'];
            }
            \Asset::set($path, $stack, (array) ($v[2] ?? []));
        }
    }
}

function route() {
    $content = "";
    $type = \trim('panel/type/' . ($_GET['type'] ?? 'blank'), '/' . "\\");
    foreach (\step(\f2c($type), "\\") as $c) {
        try {
            $c = (new \ReflectionClass($c))->newInstance([
                'lot' => [
                    'field' => ['type' => 'field', 'content' => 'a field', 'stack' => 10],
                    'fields' => ['type' => 'fields', 'content' => 'a fields', 'stack' => 11],
                ]
            ], 0);
            if (\is_string($v = $c->type ?? 0)) {
                \status(200);
                \type($v);
            }
            $content = $c;
            break;
        } catch (\Throwable $e) {
            $content = $e;
        }
    }
    return (string) $content;
}

\Hook::set('get', __NAMESPACE__ . "\\get", 0);
\Hook::set('route', __NAMESPACE__ . "\\route", 0);