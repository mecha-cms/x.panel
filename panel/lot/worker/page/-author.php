<?php

if (!$__page[0]) {
    return "";
}

$__authors = [];

call_user_func(function() use(&$__authors, $__page, $__user_key, $__user_status) {
    if ($__user_status !== 1) {
        $__authors = ['@' . $__user_key => (new User($__user_key))->author];
    } else {
        foreach (g(USER, 'page') as $__v) {
            $__v = new User(Path::N($__v));
            $__k = $__v->key;
            $__authors['@' . $__k] = $__v->author;
        }
    }
});

$__s = $__page[0]->author;
return '<p>' . Form::select('author', $__user && $__user_status !== 1 && $__command !== 's' ? [$__s => $__page[1]->author] : $__authors, $__s, ['class[]' => ['select', 'width'], 'id' => 'f-author']) . '</p>';