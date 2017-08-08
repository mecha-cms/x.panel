<?php

if ($__command !== 'r') {
    Shield::abort(PANEL_404);
}

if (!$__t = Request::get('token')) {
    Shield::abort(PANEL_404);
} else if ($__t !== Session::get(Guardian::$config['session']['token'])) {
    Shield::abort(PANEL_404);
}

Session::reset('panel');
Hook::fire('on.session.reset', [null, null]);
Message::success(To::sentence($language->cleared));
Guardian::kick($__state->path . '/::g::/' . $__state->kick('page') . HTTP::query(['token' => false]));