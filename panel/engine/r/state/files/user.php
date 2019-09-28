<?php

// `http://127.0.0.1/panel/::g::/user/1`
$GLOBALS['_']['content'] = 'page';

Hook::set('page.title', function($title) {
    return strpos($this->path, USER . DS) === 0 ? ($this['author'] ?? '@' . S . $this->name) : $title;
}, 0);

Hook::set('page.description', function($description) {
    return strpos($this->path, USER . DS) === 0 ? '@' . S . $this->name : $description;
}, 0);

$prefix = $url . State::get('x.user.path') . '/';
Hook::set('page.url', function($url) use($prefix) {
    return strpos($this->path, USER . DS) === 0 ? $prefix . $this->name : $url;
}, 0);

return array_replace_recursive(require __DIR__ . DS . '..' . DS . $_['content'] . 's.php', [
    'desk' => [
        // type: Desk
        'lot' => [
            'form' => [
                // type: Form.Post
                'lot' => [
                    0 => [
                        // type: Section
                        'lot' => [
                            'tasks' => [
                                // type: Tasks.Button
                                'lot' => [
                                    'page' => [
                                        'title' => $language->user,
                                        'url' => $url . $_['/'] . '/::s::' . $_['path'] . $url->query('&', ['content' => 'page.user', 'tab' => false]) . $url->hash
                                    ]
                                ]
                            ]
                        ]
                    ],
                    1 => [
                        // type: Section
                        'lot' => [
                            'tabs' => [
                                // type: Tabs
                                'lot' => [
                                    'pages' => [
                                        'lot' => [
                                            'pages' => [
                                                // type: Pages
                                                'child' => false // Disallow to create child page(s) here
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        ]
    ]
]);