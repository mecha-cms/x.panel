<?php namespace _\lot\x\panel\route\__test;

function title($_) {
    $_['title'] = 'Title';
    $lot = [
        'title-0' => [
            'type' => 'title',
            'level' => 0,
            'content' => 'Title 0',
            'stack' => 2
        ],
        'title-0-0' => [
            'type' => 'title',
            'level' => 0,
            'content' => 'Title 0',
            'icon' => 'M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z',
            'stack' => 2.1
        ],
        'title-0-1' => [
            'type' => 'title',
            'level' => 0,
            'content' => 'Title 0',
            'icon' => ['M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z', 'M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z'],
            'stack' => 2.1
        ],
        'title-0-2' => [
            'type' => 'title',
            'level' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.',
            'icon' => ['M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z', 'M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z'],
            'stack' => 2.1
        ],
        'title-1' => [
            'type' => 'title',
            'level' => 1,
            'content' => 'Title 1',
            'stack' => 3
        ],
        'title-2' => [
            'type' => 'title',
            'level' => 2,
            'content' => 'Title 2',
            'stack' => 3
        ],
        'title-3' => [
            'type' => 'title',
            'level' => 3,
            'content' => 'Title 3',
            'stack' => 4
        ],
        'title-4' => [
            'type' => 'title',
            'level' => 4,
            'content' => 'Title 4',
            'stack' => 5
        ],
        'title-5' => [
            'type' => 'title',
            'level' => 5,
            'content' => 'Title 5',
            'stack' => 6
        ],
        'title-6' => [
            'type' => 'title',
            'level' => 6,
            'content' => 'Title 6',
            'stack' => 7
        ]
    ];
    $_['lot']['desk']['lot']['form']['lot'][1]['lot'] = $lot;
    return $_;
}
