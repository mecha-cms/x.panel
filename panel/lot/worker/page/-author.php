<?php

if (!$__page[0]) {
    return "";
}

$__authors = [];
$__user = User::get();

call_user_func(function() use(&$__authors, $__page, $__user) {
    if ($__user->status !== 1) {
        $__authors = ['@' . $__user->key => (new User($__user->key))->author];
    } else {
        foreach (g(USER, 'page') as $__v) {
            $__v = new User(Path::N($__v));
            $__k = $__v->key;
            $__authors['@' . $__k] = $__v->author;
        }
    }
});

$__s = $__page[0]->author;
return '<p>' . Form::select('author', $__user && $__user->status !== 1 && $__command !== 's' ? [$__s => $__page[1]->author] : $__authors, $__s, ['classes' => ['select', 'block'], 'id' => 'f-author']) . '</p>';