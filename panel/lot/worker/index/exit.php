<?php

User::reset($__key = Cookie::get('panel.c.user.key'));

// Also, exit the default log in system from the `user` extension!
Session::reset('url.user');
Session::reset('url.pass');
Session::reset('url.token');

Message::success('user_exit');
Hook::fire('on.user.exit', [USER . DS . $__key . '.page', null]);
Guardian::kick($__state->path . '/::g::/enter' . HTTP::query());