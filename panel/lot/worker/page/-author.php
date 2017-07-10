<?php

$__authors = [];
$__select = $__page[0]->author;
foreach (g(USER, 'page') as $__v) {
    $__v = new User(Path::N($__v));
    $__k = $__v->key;
    if ($__user->status !== 1 && $__k !== $__user->key) continue;
    $__authors[User::ID . $__k] = $__v->author;
}

echo __panel_s__('author', [
    'content' => '<p>' . Form::select('author', $__user->status !== 1 && $__action !== 's' ? [User::ID . $__page[0]->author => $__page[1]->author] : $__authors, $__select, ['classes' => ['select', 'block'], 'id' => 'f-author']) . '</p>'
]);