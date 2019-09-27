<?php

// TODO
namespace _\lot\x\panel\task\get\page {
    function user($_, $lot) {
        if (\is_file($f = $_['f'])) {
            $title = '<code>@' . \Path::N($f) . '</code>';
            $alter = [
                'page-exist' => ['user-exist', $title],
                'page-update' => ['user-update', $title]
            ];
            foreach ($_['alert'] as $k => &$v) {
                foreach ($v as $kk => &$vv) {
                    if (\is_array($vv)) {
                        if (isset($alter[$vv[0]])) {
                            $vv = \array_replace($vv, $alter[$vv[0]]);
                        }
                    } else if (\is_string($vv)) {
                        $vv = $alter[$vv] ?? $vv;
                    }
                }
            }
        }
        return $_;
    }
    \Hook::set('do.page.user.get', __NAMESPACE__ . "\\user", 20);
}