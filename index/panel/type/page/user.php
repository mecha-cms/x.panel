<?php

// Sanitize the form data
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    // Generate author name if author field is empty
    if (empty($_POST['page']['author'])) {
        $_POST['page']['author'] = To::title($_POST['page']['name'] ?? i('User') . ' ' . (q(g(LOT . D . 'user', 'page'))) + 1);
    }
    // Remove all possible HTML tag(s) from the `email` data if any
    if (isset($_POST['page']['email'])) {
        $_POST['page']['email'] = strip_tags($_POST['page']['email']);
    }
    // Encrypt the `pass` data if any
    if (isset($_POST['data']['pass'])) {
        $name = $_POST['data']['name'] ?? $_POST['page']['name'] ?? uniqid();
        $_POST['data']['pass'] = P . password_hash($_POST['data']['pass'] . '@' . $name, PASSWORD_DEFAULT);
    }
}

unset($_['lot']['bar']['lot'][0]['lot']['set']);
unset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page']['lot']['fields']['lot']['name']);
unset($_['lot']['desk']['lot']['form']['lot'][2]['lot']['fields']['lot'][0]['lot']['tasks']['lot']['archive']);

if (isset($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page']['lot']['fields']['lot']['author'])) {
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page']['lot']['fields']['lot']['author'] = [
        'value' => $page['author']
    ];
}

$task = $_['task'] ?? 'set';

$_ = x\panel\type\page\user(array_replace_recursive($_, [
    'lot' => [
        'desk' => [
            // `desk`
            'lot' => [
                'form' => [
                    // `form/post`
                    'lot' => [
                        1 => [
                            // `section`
                            'lot' => [
                                'tabs' => [
                                    // `tabs`
                                    'lot' => [
                                        'data' => [
                                            // `tab`
                                            'lot' => [
                                                'fields' => [
                                                    // `fields`
                                                    'lot' => [
                                                        'email' => ['value' => $page['email']],
                                                        'status' => 'set' !== $task ? ['value' => $page['status']] : []
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        2 => [
                            // `section`
                            'lot' => [
                                'fields' => [
                                    // `fields`
                                    'lot' => [
                                        0 => [
                                            // `field`
                                            'lot' => [
                                                'tasks' => [
                                                    // `tasks`
                                                    'lot' => [
                                                        'draft' => ['skip' => 'set' !== $task && $page->name === $user->name],
                                                        'let' => ['skip' => 'set' === $task || $page->name === $user->name]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'values' => [
                        'page' => ['name' => 'set' !== $task ? $page->name : null]
                    ]
                ]
            ]
        ]
    ]
]));

if (1 !== ($status = $user->status)) {
    if (is_array($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['status']['lot'] ?? 0)) {
        foreach ($_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['status']['lot'] as $k => &$v) {
            if ($status === ($v['value'] ?? $k)) {
                $_['lot']['desk']['lot']['form']['values']['page']['status'] = $status;
                $v['active'] = false;
            } else {
                $v['skip'] = true;
            }
        }
        unset($v);
    }
    $_['lot']['bar']['lot'][0]['lot']['link']['active'] = false;
    $_['lot']['bar']['lot'][0]['lot']['search']['skip'] = true;
    $_['lot']['bar']['lot'][0]['lot']['set']['skip'] = true;
    $_['lot']['bar']['lot'][1]['lot']['site']['lot']['state']['skip'] = true;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['data']['lot']['fields']['lot']['files']['skip'] = true;
}

return $_;