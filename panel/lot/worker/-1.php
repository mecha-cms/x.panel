<?php

// Force log out for banned user(s)
Message::reset();
Message::error('user_x', ['<code>' . $user->key . '</code>']);
File::open(USER . DS . $user->slug . DS . 'token.data')->delete();
// Set random `pass` value so that banned user(s) will not be able to log-in in the future
File::put(X . Guardian::hash(uniqid()))->saveTo(USER . DS . $user->slug . DS . 'pass.data', 0600);
// Redirect to home page
Guardian::kick("");