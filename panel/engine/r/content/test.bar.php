<?php

$folders = [];

$i = .9;
foreach (g(LOT) as $k => $v) {
    if ($v === 0) {
        $folders[$n = basename($k)] = [
            'icon' => ['M10,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V8C22,6.89 21.1,6 20,6H12L10,4Z'],
            'title' => $language->{$n === 'x' ? 'extension' : $n},
            '/' => '/' . $n, // TODO
            'stack' => $i + .1
        ];
    }
}

return [
    'bar' => [
        // type: Bar
        'lot' => [
            0 => [
                // type: List
                'lot' => [
                    0 => [
                        // tags: [main]
                        'lot' => $folders
                    ]
                ]
            ],
            1 => [
                // type: List
                'lot' => [
                    'site' => [
                        'title' => $language->site,
                        '/' => $url . "",
                        'lot' => [
                            0 => [
                                'title' => $language->config,
                                '/' => '/', // TODO
                                'stack' => 10
                            ],
                            1 => [
                                'title' => $language->doVisit,
                                'link' => $url . "",
                                'stack' => 20
                            ]
                        ],
                        'stack' => 10
                    ]
                ]
            ],
            2 => [
                // type: List
                'lot' => [
                    'alert' => [
                        'title' => false,
                        'icon' => ['M21,19V20H3V19L5,17V11C5,7.9 7.03,5.17 10,4.29C10,4.19 10,4.1 10,4A2,2 0 0,1 12,2A2,2 0 0,1 14,4C14,4.1 14,4.19 14,4.29C16.97,5.17 19,7.9 19,11V17L21,19M14,21A2,2 0 0,1 12,23A2,2 0 0,1 10,21'],
                        '/' => '/' // TODO
                    ]
                ]
            ]
        ]
    ]
];