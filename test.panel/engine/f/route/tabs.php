<?php namespace x\panel\route\__test;

function tabs($_) {
    $_['title'] = 'Tabs';
    $lot = [];
    $lot['tabs-0'] = [
        'description' => 'Description goes here.',
        'lot' => [
            'tab-0' => [
                'content' => '<p>Content of the first tab.</p>',
                'stack' => 10,
                'title' => 'Tab 1'
            ],
            'tab-1' => [
                'content' => '<p>Content of the second tab.</p>',
                'stack' => 20,
                'title' => 'Tab 2'
            ],
            'tab-2' => [
                'content' => '<p>Content of the third tab.</p>',
                'description' => 'Description goes here.',
                'stack' => 30,
                'status' => 'Toggle',
                'title' => 'Tab 3',
                'toggle' => true
            ]
        ],
        'stack' => 10,
        'title' => 'Tabs',
        'type' => 'tabs'
    ];
    $lot['tabs-1'] = [
        'lot' => [
            'tab-0' => [
                'content' => '<p>Content of the first tab.</p>',
                'stack' => 10,
                'title' => 'Tab 1'
            ],
            'tab-1' => [
                'content' => '<p>Content of the second tab.</p>',
                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                'stack' => 20,
                'title' => 'Tab 2'
            ],
            'tab-2' => [
                'content' => '<p>Content of the third tab.</p>',
                'description' => 'Tab 3',
                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                'stack' => 30,
                'title' => false
            ],
            'tab-3' => [
                'content' => '<p>Content of the fourth tab.</p>',
                'icon' => ['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z', 'M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z'],
                'stack' => 40,
                'title' => 'Tab 4'
            ],
        ],
        'stack' => 20,
        'type' => 'tabs'
    ];
    $lot['tabs-2'] = [
        'lot' => [
            'tab-0' => [
                'content' => '<p>Content of the first tab.</p>',
                'description' => 'Default.',
                'stack' => 10,
                'title' => 'Tab 1'
            ],
            'tab-1' => [
                'active' => false,
                'content' => '<p>Content of the second tab.</p>',
                'description' => 'Disabled.',
                'stack' => 20,
                'title' => 'Tab 2'
            ],
            'tab-2' => [
                'content' => '<p>Content of the third tab.</p>',
                'description' => 'Linked.',
                'link' => 'https://example.com',
                'stack' => 30,
                'title' => 'Tab 3'
            ]
        ],
        'stack' => 30,
        'type' => 'tabs'
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'][0]['lot'] = $lot;
    return $_;
}