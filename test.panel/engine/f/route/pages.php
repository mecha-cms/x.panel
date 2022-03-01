<?php namespace x\panel\route\__test;

function pages($_) {
    $_['title'] = 'Pages';
    $item = static function($title = null, $description = null, $type = 'page', $stack = 10) {
        return [
            'description' => $description,
            'tasks' => [
                0 => [
                    'description' => 'Task 1.',
                    'icon' => 'M5,3C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19H5V5H12V3H5M17.78,4C17.61,4 17.43,4.07 17.3,4.2L16.08,5.41L18.58,7.91L19.8,6.7C20.06,6.44 20.06,6 19.8,5.75L18.25,4.2C18.12,4.07 17.95,4 17.78,4M15.37,6.12L8,13.5V16H10.5L17.87,8.62L15.37,6.12Z',
                    'stack' => 10,
                    'title' => 'Task 1',
                    'url' => '/'
                ],
                1 => [
                    'description' => 'Task 2.',
                    'icon' => 'M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z',
                    'stack' => 20,
                    'title' => 'Task 2',
                    'url' => '/'
                ]
            ],
            'stack' => $stack,
            'title' => $title,
            'type' => $type,
            'url' => '/'
        ];
    };
    $lot = [];
    $lot['pages-0'] = [
        'lot' => [
            $item('Default Page Example', 'Page description goes here.', 'page', 10),
            \array_replace($item('Image Page Example', 'Image placeholder provided by <a href="https://placekitten.com" rel="nofollow" target="_blank">Placekitten</a> service.', 'page', 10.1), [
                'image' => 'https://placekitten.com/100/100?image=3'
            ]),
            \array_replace($item('Hidden Icon/Image View', 'Disabling the thumbnail view.', 'page', 10.2), [
                'image' => false
            ]),
            \array_replace($item('Icon Page Example', 'Icon provided by <a href="https://materialdesignicons.com" rel="nofollow" target="_blank">Material Design Icons</a> library.', 'page', 10.3), [
                'icon' => 'M12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16M18.7,12.4C18.42,12.24 18.13,12.11 17.84,12C18.13,11.89 18.42,11.76 18.7,11.6C20.62,10.5 21.69,8.5 21.7,6.41C19.91,5.38 17.63,5.3 15.7,6.41C15.42,6.57 15.16,6.76 14.92,6.95C14.97,6.64 15,6.32 15,6C15,3.78 13.79,1.85 12,0.81C10.21,1.85 9,3.78 9,6C9,6.32 9.03,6.64 9.08,6.95C8.84,6.75 8.58,6.56 8.3,6.4C6.38,5.29 4.1,5.37 2.3,6.4C2.3,8.47 3.37,10.5 5.3,11.59C5.58,11.75 5.87,11.88 6.16,12C5.87,12.1 5.58,12.23 5.3,12.39C3.38,13.5 2.31,15.5 2.3,17.58C4.09,18.61 6.37,18.69 8.3,17.58C8.58,17.42 8.84,17.23 9.08,17.04C9.03,17.36 9,17.68 9,18C9,20.22 10.21,22.15 12,23.19C13.79,22.15 15,20.22 15,18C15,17.68 14.97,17.36 14.92,17.05C15.16,17.25 15.42,17.43 15.7,17.59C17.62,18.7 19.9,18.62 21.7,17.59C21.69,15.5 20.62,13.5 18.7,12.4Z'
            ]),
            \array_replace($item('Disabled Page Example', 'Page description goes here.', 'page', 10.4), [
                'active' => false
            ]),
            \array_replace($item('Current Page Example', 'Page description goes here.', 'page', 20.1), [
                'current' => true
            ])
        ],
        'sort' => $_GET['sort'] ?? null,
        'stack' => 10,
        'type' => 'pages'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}