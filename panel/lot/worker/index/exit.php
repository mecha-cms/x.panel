<?php

User::reset($__key = Cookie::get('panel.c.user.key'));

Message::success('user_exit');
Hook::fire('on.user.exit', [USER . DS . $__key . '.page', null]);
Guardian::kick($__state->path . '/::g::/enter' . HTTP::query());