<?php

if ($__user && $__roles = (array) a(Config::get('panel.v.user', []))) {
    if (isset($__roles[$__user->status])) {
        $__role = $__roles[$__user->status];
        if ($__role === false) {
            Shield::abort(PANEL_ERROR, [403]);
        } else if (is_array($__role)) {
            foreach ((array) a(Config::get('panel.v.n', [])) as $__k => $__v) {
                if (!isset($__role[$__k]) || !(is_callable($__role[$__k]) ? $__role[$__k]($__user) : $__role[$__k])) {
                    Config::set('panel.v.n.' . $__k, false);
                }
            }
            if (isset($__role[$__chops[0]])) {
                $__if = is_callable($__role[$__chops[0]]) ? $__role[$__chops[0]]($__user) : $__role[$__chops[0]];
                if (!$__if) {
                    Shield::abort(PANEL_ERROR, [403]);
                }
            } else if ($__chops[0] !== 'enter' && $__chops[0] !== 'exit') {
                Shield::abort(PANEL_ERROR, [403]);
            }
        } else if ($__role === true) {
            // Do nothing.
        }
    }
}