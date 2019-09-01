<!DOCTYPE html>
<html dir="ltr" class>
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width" name="viewport">
    <meta content="noindex" name="robots">
    <title><?php echo w($t->reverse); ?></title>
    <link href="<?php echo $url; ?>/favicon.ico" rel="shortcut icon">
  </head>
  <body>

<?php

$over = defined('DEBUG') && DEBUG && isset($_GET['test']) && is_file($f = __DIR__ . DS . 'test.' . urlencode($_GET['test']) . '.php') ? require $f : [];
$content = _\lot\x\panel\lot(['lot' => array_replace_recursive([
    'bar' => [
        'type' => 'Bar',
        'lot' => [
            0 => [
                'type' => 'List',
                'lot' => [
                    0 => [
                        'icon' => 'M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z',
                        'caret' => false,
                        'title' => false,
                        'link' => '#',
                        'tags' => ['main'],
                        'stack' => 10
                    ],
                    1 => [
                        'type' => 'Form.Get',
                        '/' => $url->path . $url->query,
                        'name' => 'search',
                        'lot' => [
                            'field' => [
                                'type' => 'Fields',
                                'lot' => [
                                    'q' => [
                                        'type' => 'Text',
                                        'title' => $language->doSearch,
                                        'placeholder' => $language->doSearch
                                    ]
                                ]
                            ]
                        ],
                        'stack' => 20
                    ]
                ],
                'stack' => 10
            ],
            1 => [
                'type' => 'List',
                'lot' => [],
                'stack' => 20
            ],
            2 => [
                'type' => 'List',
                'lot' => [],
                'stack' => 30
            ]
        ],
        'stack' => 10
    ],
    'desk' => [
        'type' => 'Desk',
        'lot' => [
            'form' => [
                'type' => 'Form.Post',
                'name' => 'editor',
                'lot' => [
                    0 => [
                        'type' => 'Section',
                        'lot' => [],
                        'hidden' => true,
                        'stack' => 10
                    ],
                    1 => [
                        'type' => 'Section',
                        'lot' => [
                            'tab' => [
                                'type' => 'Tabs',
                                'name' => 0,
                                'lot' => []
                            ]
                        ],
                        'stack' => 20,
                        'hidden' => true
                    ],
                    2 => [
                        'type' => 'Section',
                        'lot' => [],
                        'stack' => 30,
                        'hidden' => true
                    ]
                ],
                'stack' => 10
            ]
        ],
        'stack' => 20
    ]
], $over)], 0);

if (!empty($GLOBALS['SVG'])) {
    $icons = '<svg xmlns="http://www.w3.org/2000/svg" display="none">';
    foreach ($GLOBALS['SVG'] as $k => $v) {
        $icons .= '<symbol id="i:' . $k . '" viewBox="0 0 24 24">';
        $icons .= strpos($v, '<') === 0 ? $v : '<path d="' . $v . '"></path>';
        $icons .= '</symbol>';
    }
    $icons .= '</svg>';
    echo $icons;
}

echo $content;

?>

  </body>
</html>