<?php

if ($c === 'g' && !$panel->file || HTTP::get('view') === 'file') {
    return;
}

require __DIR__ . DS . 'page.php';

// Hide the `art` and `image` tab
Config::set('panel.desk.body.tab.art.hidden', true);
Config::set('panel.desk.body.tab.image.hidden', true);

// Modify default page field(s)
$parent = $c === 's' ? HTTP::get('f.data.parent', false) : null;
$author = $page->author->key ?? $page->author ?? null;

$source = "";
if ($parent) {
    $comment = new Comment($file . DS . $parent . '.page');
    $link = Path::R($comment->path, LOT, '/');
    $source .= '<h3>' . $comment->author . '</h3>';
    $source .= '<div class="p">' . $comment->content . fn\panel\links([
        'g' => [
            'x' => $user->status !== 1 && !Is::user($comment['author']),
            'title' => $language->edit,
            'path' => $link,
            'stack' => 10
        ],
        'r' => $user->status === 1 ? [
            'title' => $language->delete,
            'path' => $link,
            'c' => 'r',
            'query' => [
                'a' => -2,
                'f' => false,
                'token' => $user->token
            ],
            'stack' => 10.1
        ] : null
    ]) . '</div>';
}

Config::set('panel.desk.body.tab', [
    'file' => [
        'field' => [
            'page[title]' => null,
            'page[description]' => null,
            'slug' => null,
            'reply' => $parent ? [
                'title' => false,
                // 'expand' => true,
                'type' => 'content',
                'value' => $source,
                'stack' => 9.9
            ] : null,
            'page[author]' => [
                'key' => 'name',
                'type' => $parent ? 'hidden' : 'text',
                'value' => $c === 's' ? $user->key : $author,
                'width' => true,
                'stack' => 10
            ],
            'page[email]' => $user->key === $author ? null : [
                'key' => 'email',
                'type' => $parent ? 'hidden' : 'email',
                'value' => $page->email,
                'width' => true,
                'stack' => 10.3
            ],
            'page[content]' => [
                'key' => $parent ? 'reply' : 'comment',
                'stack' => 10.4
            ],
            'page[type]' => ['stack' => 10.5],
            'page[status]' => [
                'type' => 'hidden',
                'value' => 1 // User that can reply from control panel is always administrator
            ],
            'data[parent]' => [
                'type' => 'hidden',
                'value' => $page->parent
            ],
            'tags' => ['hidden' => true]
        ]
    ]
]);