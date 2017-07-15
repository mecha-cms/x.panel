<?php

$__authors = [];
$__user = User::get();

call_user_func(function() use(&$__authors, $__page, $__user) {
    foreach (g(USER, 'page') as $__v) {
        $__v = new User(Path::N($__v));
        $__k = $__v->key;
        if ($__user->status !== 1 && $__k !== $__user->key) continue;
        $__authors['@' . $__k] = $__v->author;
    }
});

$__s = $__page[0]->author;
return '<p>' . Form::select('author', $__user->status !== 1 && $__action !== 's' ? ['@' . $__s => $__page[1]->author] : $__authors, $__s, ['classes' => ['select', 'block'], 'id' => 'f-author']) . '</p>';