<?php

$__authors = [];

call_user_func(function() use(&$__authors, $__page, $__user) {
    foreach (g(USER, 'page') as $__v) {
        $__v = new User(Path::N($__v));
        $__k = $__v->key;
        if ($__user->status !== 1 && $__k !== $__user->key) continue;
        $__authors[User::ID . $__k] = $__v->author;
    }
});

return '<p>' . Form::select('author', $__user->status !== 1 && $__action !== 's' ? [User::ID . $__page[0]->author => $__page[1]->author] : $__authors, $__page[0]->author, ['classes' => ['select', 'block'], 'id' => 'f-author']) . '</p>';