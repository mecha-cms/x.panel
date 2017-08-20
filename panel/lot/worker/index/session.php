<?php

if ($__command !== 'r') {
    Shield::abort(404);
}

if (!$__t = Request::get('token')) {
    Shield::abort(404);
} else if (!Guardian::check($__t)) {
    Shield::abort(404);
}

Session::reset('panel');
Hook::fire('on.session.reset', [null, null]);
Message::success(To::sentence($language->cleared));
Guardian::kick($__state->path . '/::g::/' . $__state->kick('page') . HTTP::query(['token' => false]));