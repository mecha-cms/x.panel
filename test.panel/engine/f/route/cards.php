<?php namespace x\panel\route\__test;

function cards($_) {
    $_['title'] = 'Cards';
    $item = static function($title = null, $description = null, $type = 'card', $stack = 10) {
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
            'title' => $title,
            'type' => $type,
            'stack' => $stack,
            'url' => '/'
        ];
    };
    $lot = [];
    $lot['cards-0'] = [
        'lot' => [
            $item('Default Card Example', 'Card description goes here.', 'card', 10),
            $item('Default Card Example', 'Card description goes here.', 'card', 10),
            $item('Default Card Example', 'Card description goes here.', 'card', 10),
            \array_replace($item('Card Example with Image', 'Image placeholder provided by <a href="https://placekitten.com" rel="nofollow" target="_blank">Placekitten</a> service.', 'card', 10.1), [
                'image' => 'https://placekitten.com/264/264?image=3'
            ]),
            \array_replace($item('Disabled Card Example', 'Card description goes here.', 'card', 10.3), [
                'active' => false
            ]),
            \array_replace($item('Current Card Example', 'Card description goes here.', 'card', 20.1), [
                'current' => true
            ])
        ],
        'sort' => $_GET['sort'] ?? null,
        'stack' => 10,
        'type' => 'cards'
    ];
    $_['lot']['desk']['width'] = true;
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}