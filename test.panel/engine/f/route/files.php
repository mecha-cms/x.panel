<?php namespace x\panel\route\__test;

function files($_) {
    $_['title'] = 'Files';
    $item = static function($title = null, $description = null, $type = 'file', $stack = 10) {
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
    $lot['files-0'] = [
        'lot' => [
            $item('folder-1', null, 'folder', 10),
            $item('folder-2', null, 'folder', 10.1),
            $item('folder-3', null, 'folder', 10.2),
            $item('file-1', '1 KB', 'file', 20),
            $item('file-2', '2.4 KB', 'file', 20.1),
            $item('file-3', '0 KB', 'file', 20.2),
            \array_replace($item('file-4', '100 KB', 'file', 20.3), [
                'current' => true
            ])
        ],
        'sort' => $_GET['sort'] ?? null,
        'stack' => 10,
        'type' => 'files'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}