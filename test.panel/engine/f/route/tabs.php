<?php namespace _\lot\x\panel\route\__test;

function tabs($_) {
    $_['title'] = 'Tabs';
    $tabs_0 = [
        'title' => 'Tabs',
        'description' => 'Description goes here.',
        'type' => 'tabs',
        'lot' => [
            'tab-0' => [
                'title' => 'Tab 1',
                'content' => '<p>Content of the first tab.</p>',
                'stack' => 10
            ],
            'tab-1' => [
                'title' => 'Tab 2',
                'content' => '<p>Content of the second tab.</p>',
                'stack' => 20
            ],
            'tab-2' => [
                'title' => 'Tab 3',
                'description' => 'Description goes here.',
                'content' => '<p>Content of the third tab.</p>',
                'stack' => 30
            ]
        ],
        'stack' => 10
    ];
    $tabs_1 = [
        'type' => 'tabs',
        'lot' => [
            'tab-0' => [
                'title' => 'Tab 1',
                'content' => '<p>Content of the first tab.</p>',
                'stack' => 10
            ],
            'tab-1' => [
                'title' => 'Tab 2',
                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                'content' => '<p>Content of the second tab.</p>',
                'stack' => 20
            ],
            'tab-2' => [
                'title' => false,
                'description' => 'Tab 3',
                'icon' => 'M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z',
                'content' => '<p>Content of the third tab.</p>',
                'stack' => 30
            ],
            'tab-3' => [
                'title' => 'Tab 4',
                'icon' => ['M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z', 'M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A1.5,1.5 0 0,0 13.5,19.5C13.5,19.11 13.35,18.76 13.11,18.5C12.88,18.23 12.73,17.88 12.73,17.5A1.5,1.5 0 0,1 14.23,16H16A5,5 0 0,0 21,11C21,6.58 16.97,3 12,3Z'],
                'content' => '<p>Content of the fourth tab.</p>',
                'stack' => 40
            ],
        ],
        'stack' => 20
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'][0] = [
        'type' => 'section',
        'lot' => [
            'tabs-0' => $tabs_0,
            'tabs-1' => $tabs_1,
        ]
    ];
    return $_;
}
